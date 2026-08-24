<?php

namespace App\Console\Commands;

use App\Models\Experience;
use App\Models\Portfolio;
use App\Models\Profile;
use App\Models\Skill;
use App\Models\Study;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

/**
 * Imports data from the read-only `legacy` connection (the CodeIgniter
 * application, see App\Providers\LegacyDatabaseServiceProvider) into this
 * application's own tables.
 *
 * Design decisions (see the change's design doc):
 * - Import order is `users → profiles → skills → experiences → studies →
 *   portfolios`. There are no foreign keys between these tables; `users`
 *   goes first so login keeps working even if a later table fails.
 * - Legacy primary keys are preserved via an id-based upsert, so re-running
 *   the command updates existing rows instead of duplicating them.
 * - One DB transaction per table: a failure rolls back only that table and
 *   the command continues with the next one, unless `--strict` is passed.
 */
class LegacyImportCommand extends Command
{
    protected $signature = 'legacy:import
        {--only= : Comma-separated list of tables to import (users,profiles,skills,experiences,studies,portfolios)}
        {--dry-run : Read and report counts only, write nothing}
        {--strict : Abort the whole import on the first table failure}
        {--verify : Compare legacy vs. local row counts and report drift, write nothing}';

    protected $description = 'Import data from the legacy CodeIgniter database into the new schema';

    /**
     * Canonical import order. No foreign keys exist between these tables;
     * `users` is first so authentication keeps working even if a later
     * table fails to import.
     */
    private const TABLES = ['users', 'profiles', 'skills', 'experiences', 'studies', 'portfolios'];

    /**
     * Read-only bind mount of the legacy repo's uploaded files, configured
     * in `.ddev/docker-compose.legacy-uploads.yaml`. The legacy repo lives
     * outside this project, so its filesystem is not reachable otherwise
     * from inside the DDEV web container.
     */
    private const LEGACY_AVATAR_PATH = '/legacy-uploads/profile/images/';

    public function handle(): int
    {
        $tables = self::TABLES;

        if ($this->option('only')) {
            $requested = array_values(array_filter(array_map('trim', explode(',', (string) $this->option('only')))));
            $invalid = array_diff($requested, self::TABLES);

            if ($invalid !== []) {
                $this->error('Unknown table(s) in --only: '.implode(', ', $invalid));

                return self::FAILURE;
            }

            $tables = array_values(array_intersect(self::TABLES, $requested));
        }

        if ($this->option('verify')) {
            return $this->runVerify($tables);
        }

        $dryRun = (bool) $this->option('dry-run');
        $strict = (bool) $this->option('strict');

        $summary = [];
        $hadFailure = false;

        foreach ($tables as $table) {
            $result = $this->processTable($table, $dryRun);
            $summary[$table] = $result;

            if ($result['failed'] > 0) {
                $hadFailure = true;

                if ($strict) {
                    $this->printSummary($summary, $dryRun);
                    $this->error("Aborting: --strict is set and table [{$table}] failed.");

                    return self::FAILURE;
                }
            }
        }

        $this->printSummary($summary, $dryRun);

        return $hadFailure ? self::FAILURE : self::SUCCESS;
    }

    /**
     * Reads all legacy rows for one table. `users` only pulls active
     * accounts, per the change's exact contract.
     *
     * Declared `protected` (not `private`) so tests can override it to
     * inject fixture rows without ever writing to the real, read-only
     * `legacy` database.
     */
    protected function fetchLegacyRows(string $table): Collection
    {
        $query = DB::connection('legacy')->table($table)->orderBy('id');

        if ($table === 'users') {
            $query->where('active', 1);
        }

        return $query->get();
    }

    /**
     * Runs one table's import inside its own transaction. Any exception
     * rolls back that table only; the summary reports it as failed and the
     * caller decides whether to continue (default) or abort (--strict).
     */
    private function processTable(string $table, bool $dryRun): array
    {
        $rows = $this->fetchLegacyRows($table);
        $read = $rows->count();

        if ($dryRun) {
            return ['read' => $read, 'written' => 0, 'skipped' => $read, 'failed' => 0, 'error' => null, 'legacy_id' => null];
        }

        $written = 0;
        $error = null;
        $failedLegacyId = null;

        DB::beginTransaction();

        try {
            foreach ($rows as $row) {
                $failedLegacyId = $row->id;
                $this->importRow($table, $row);
                $written++;
            }

            DB::commit();
            $this->syncAutoIncrement($table);
            $failedLegacyId = null;
        } catch (Throwable $exception) {
            DB::rollBack();
            $error = $exception->getMessage();
            Log::error('legacy:import: table failed, rolled back', [
                'table' => $table,
                'legacy_id' => $failedLegacyId,
                'message' => $error,
            ]);
            $written = 0;
        }

        return [
            'read' => $read,
            'written' => $written,
            'skipped' => 0,
            'failed' => $error === null ? 0 : 1,
            'error' => $error,
            'legacy_id' => $failedLegacyId,
        ];
    }

    private function importRow(string $table, object $row): void
    {
        match ($table) {
            'users' => $this->importUserRow($row),
            'profiles' => $this->importProfileRow($row),
            'skills' => $this->importSkillRow($row),
            'experiences' => $this->importExperienceRow($row),
            'studies' => $this->importStudyRow($row),
            'portfolios' => $this->importPortfolioRow($row),
        };
    }

    /**
     * `name` = `profiles.name` + `profiles.surname` for the legacy profile
     * whose `email_contact` matches this user's email (case-insensitive),
     * since legacy has no direct `profiles.user_id` foreign key. Falls back
     * to the local part of the email when no profile matches.
     */
    protected function importUserRow(object $row): void
    {
        $attributes = [
            'name' => $this->resolveUserName($row->email),
            'email' => $row->email,
        ];

        // Only seed `password` the very first time this user is imported.
        // Real bug the owner hit twice: re-running `legacy:import` after
        // re-syncing a fresh production dump silently reverted the admin's
        // Laravel-set password back to the old CodeIgniter hash on every
        // run, locking them out of a password they'd since changed from
        // Filament. Once a user row exists, its password belongs to
        // Laravel's own auth — the legacy hash never overwrites it again.
        if (! User::withTrashed()->find((int) $row->id)) {
            $attributes['password'] = $row->password_hash;
        }

        $this->upsertPreservingTimestamps(User::class, (int) $row->id, $attributes, $row);
    }

    protected function importProfileRow(object $row): void
    {
        $this->copyAvatarIfPresent($row->avatar);

        $this->upsertPreservingTimestamps(Profile::class, (int) $row->id, [
            'name' => $row->name,
            'surname' => $row->surname,
            // Legacy CI4 stores the bare filename (its own upload path was
            // implied/fixed); `copyAvatarIfPresent()` below always copies
            // into `storage/app/public/profiles/`, matching where
            // Filament's own `FileUpload::directory('profiles')` puts a
            // manually-uploaded avatar too. Store the SAME `profiles/`
            // prefix here so both paths resolve identically via
            // `/storage/{avatar}` — without this, a re-import silently
            // points the avatar at a file that doesn't exist at that URL.
            'avatar' => $row->avatar ? 'profiles/'.$row->avatar : $row->avatar,
            'email_contact' => $row->email_contact,
            'description_es' => $row->description_es,
            'description_en' => $row->description_en,
            'specialty_es' => $row->specialty_es,
            'specialty_en' => $row->specialty_en,
            'language_es' => $row->language_es,
            'language_en' => $row->language_en,
            'github_url' => $row->github_url,
            'linkedin_url' => $row->linkedin_url,
            'instagram_url' => $row->instagram_url,
        ], $row);
    }

    protected function importSkillRow(object $row): void
    {
        $this->upsertPreservingTimestamps(Skill::class, (int) $row->id, [
            'name' => $row->name,
            'percentage' => $row->percentage,
        ], $row);
    }

    protected function importExperienceRow(object $row): void
    {
        $this->upsertPreservingTimestamps(Experience::class, (int) $row->id, [
            'company' => $row->company,
            'specialty_es' => $row->specialty_es,
            'specialty_en' => $row->specialty_en,
            'description_es' => $row->description_es,
            'description_en' => $row->description_en,
            'start_date' => $this->sanitizeLegacyDatetime($row->start),
            'end_date' => $this->sanitizeLegacyDatetime($row->end),
        ], $row);
    }

    protected function importStudyRow(object $row): void
    {
        $this->upsertPreservingTimestamps(Study::class, (int) $row->id, [
            'entity' => $row->entity,
            'title_es' => $row->title_es,
            'title_en' => $row->title_en,
            'description_es' => $row->description_es,
            'description_en' => $row->description_en,
            'start_date' => $this->sanitizeLegacyDatetime($row->start),
            'end_date' => $this->sanitizeLegacyDatetime($row->end),
        ], $row);
    }

    protected function importPortfolioRow(object $row): void
    {
        $this->upsertPreservingTimestamps(Portfolio::class, (int) $row->id, [
            'company_name' => $row->company_name,
            'image' => $row->image,
            'website_url_es' => $row->website_url_es,
            'website_url_en' => $row->website_url_en,
        ], $row);
    }

    /**
     * Idempotent id-based upsert that preserves the legacy primary key and
     * the legacy `created_at`/`updated_at`/`deleted_at` values exactly.
     *
     * Functionally equivalent to `Model::updateOrCreate(['id' => $id], ...)`,
     * but writes through `setRawAttributes()` instead of `fill()`/`save()`'s
     * normal cast pipeline. Two things make the literal `updateOrCreate()`
     * call unusable here:
     * - `created_at`/`updated_at`/`deleted_at` sit outside every model's
     *   `$fillable`, and `save()` would silently overwrite them with "now"
     *   unless timestamp management is disabled for this instance.
     * - `User::password` uses Laravel's `hashed` cast. On `fill()`, that
     *   cast calls `Hash::verifyConfiguration()` and throws when the
     *   legacy bcrypt hash's cost factor doesn't match the app's
     *   currently configured `BCRYPT_ROUNDS` (it never will, since legacy
     *   hashes were produced by a different, older application with its
     *   own cost setting). `setRawAttributes()` writes the value as-is,
     *   preserving the exact legacy hash so `Hash::check()` still
     *   authenticates it correctly.
     */
    private function upsertPreservingTimestamps(string $modelClass, int $legacyId, array $attributes, object $legacyRow): Model
    {
        /** @var Model $instance */
        $instance = $modelClass::withTrashed()->find($legacyId) ?? new $modelClass;

        $instance->setRawAttributes(array_merge($instance->getAttributes(), $attributes, [
            'id' => $legacyId,
            'created_at' => $this->sanitizeLegacyDatetime($legacyRow->created_at),
            'updated_at' => $this->sanitizeLegacyDatetime($legacyRow->updated_at),
            'deleted_at' => $this->sanitizeLegacyDatetime($legacyRow->deleted_at ?? null),
        ]));

        $instance->timestamps = false;
        $instance->save();

        return $instance;
    }

    /**
     * Some legacy rows carry MySQL zero-dates (`0000-00-00 00:00:00`), which
     * the strict-mode connection used by this application refuses to insert.
     * Treated as "no real value" and mapped to `null` rather than aborting
     * the whole row.
     */
    private function sanitizeLegacyDatetime(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return str_starts_with($value, '0000-00-00') ? null : $value;
    }

    private function resolveUserName(string $email): string
    {
        $profile = DB::connection('legacy')->table('profiles')
            ->whereRaw('LOWER(email_contact) = ?', [strtolower($email)])
            ->first();

        if ($profile) {
            return trim($profile->name.' '.$profile->surname);
        }

        return Str::before($email, '@');
    }

    private function copyAvatarIfPresent(?string $avatar): void
    {
        if (! $avatar) {
            return;
        }

        $source = self::LEGACY_AVATAR_PATH.$avatar;

        if (! is_file($source)) {
            $this->warn("legacy:import: avatar file not found, skipping copy: {$source}");

            return;
        }

        Storage::disk('public')->put('profiles/'.$avatar, file_get_contents($source));
    }

    private function syncAutoIncrement(string $table): void
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            return;
        }

        $max = (int) DB::table($table)->max('id');
        DB::statement("ALTER TABLE `{$table}` AUTO_INCREMENT = ".($max + 1));
    }

    private function runVerify(array $tables): int
    {
        $rows = [];
        $drift = false;

        foreach ($tables as $table) {
            $legacyCount = $table === 'users'
                ? DB::connection('legacy')->table('users')->where('active', 1)->count()
                : DB::connection('legacy')->table($table)->count();

            $localCount = DB::table($table)->count();
            $match = $legacyCount === $localCount;
            $drift = $drift || ! $match;

            $rows[] = [$table, $legacyCount, $localCount, $match ? 'OK' : 'DRIFT'];
        }

        $this->table(['Table', 'Legacy count', 'Local count', 'Match'], $rows);

        return $drift ? self::FAILURE : self::SUCCESS;
    }

    private function printSummary(array $summary, bool $dryRun): void
    {
        $label = $dryRun ? ' (dry-run, nothing written)' : '';
        $this->info("Import summary{$label}:");

        $rows = [];
        foreach ($summary as $table => $result) {
            $rows[] = [$table, $result['read'], $result['written'], $result['skipped'], $result['failed']];
        }

        $this->table(['Table', 'Read', 'Written', 'Skipped', 'Failed'], $rows);

        foreach ($summary as $table => $result) {
            if (! empty($result['error'])) {
                $this->error("  [{$table}] legacy id {$result['legacy_id']}: {$result['error']}");
            }
        }
    }
}
