<?php

namespace Tests\Feature;

use App\Console\Commands\LegacyImportCommand;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Skill;
use App\Models\Study;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Tests\TestCase;

/**
 * Exercises `legacy:import` against the real `legacy` connection imported in
 * PR1 (a small, stable dataset: 1 active user, 1 profile, 9 skills,
 * 5 experiences, 4 studies, 0 portfolios). These counts are read from the
 * database in each test rather than hardcoded, so the suite stays correct
 * if the legacy dataset ever changes.
 */
class LegacyImportCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh', ['--force' => true]);
    }

    public function test_dry_run_writes_nothing(): void
    {
        $exitCode = Artisan::call('legacy:import', ['--dry-run' => true]);

        $this->assertSame(0, $exitCode);
        $this->assertSame(0, User::count());
        $this->assertSame(0, Profile::count());
        $this->assertSame(0, Skill::count());
        $this->assertSame(0, Experience::count());
        $this->assertSame(0, Study::count());
    }

    public function test_import_is_idempotent_across_repeated_runs(): void
    {
        // withTrashed() counts are compared against raw legacy row counts
        // (not filtered by soft-delete) because at least one real legacy
        // `experiences` row is itself soft-deleted, and a correct import
        // preserves that row rather than dropping it (see the dedicated
        // soft-delete test). This test's purpose is duplicate detection
        // across repeated runs, independent of soft-delete state.
        $legacyActiveUsers = DB::connection('legacy')->table('users')->where('active', 1)->count();
        $legacyProfiles = DB::connection('legacy')->table('profiles')->count();
        $legacySkills = DB::connection('legacy')->table('skills')->count();
        $legacyExperiences = DB::connection('legacy')->table('experiences')->count();
        $legacyStudies = DB::connection('legacy')->table('studies')->count();

        $firstExitCode = Artisan::call('legacy:import');

        $this->assertSame(0, $firstExitCode);
        $this->assertSame($legacyActiveUsers, User::withTrashed()->count());
        $this->assertSame($legacyProfiles, Profile::withTrashed()->count());
        $this->assertSame($legacySkills, Skill::withTrashed()->count());
        $this->assertSame($legacyExperiences, Experience::withTrashed()->count());
        $this->assertSame($legacyStudies, Study::withTrashed()->count());

        $secondExitCode = Artisan::call('legacy:import');

        $this->assertSame(0, $secondExitCode);
        $this->assertSame($legacyActiveUsers, User::withTrashed()->count(), 'Re-running the import must not duplicate users.');
        $this->assertSame($legacyProfiles, Profile::withTrashed()->count(), 'Re-running the import must not duplicate profiles.');
        $this->assertSame($legacySkills, Skill::withTrashed()->count(), 'Re-running the import must not duplicate skills.');
        $this->assertSame($legacyExperiences, Experience::withTrashed()->count(), 'Re-running the import must not duplicate experiences.');
        $this->assertSame($legacyStudies, Study::withTrashed()->count(), 'Re-running the import must not duplicate studies.');
    }

    public function test_only_option_limits_import_to_requested_tables(): void
    {
        $exitCode = Artisan::call('legacy:import', ['--only' => 'skills']);

        $this->assertSame(0, $exitCode);
        $this->assertGreaterThan(0, Skill::count());
        $this->assertSame(0, User::count());
        $this->assertSame(0, Profile::count());
    }

    public function test_only_option_rejects_unknown_table_names(): void
    {
        $exitCode = Artisan::call('legacy:import', ['--only' => 'skills,not_a_real_table']);

        $this->assertSame(1, $exitCode);
        $this->assertSame(0, Skill::count(), 'Nothing should be imported when --only contains an invalid table.');
    }

    public function test_verify_reports_matching_counts_after_import(): void
    {
        Artisan::call('legacy:import');

        $exitCode = Artisan::call('legacy:import', ['--verify' => true]);

        $this->assertSame(0, $exitCode);
    }

    public function test_verify_reports_drift_before_import(): void
    {
        $exitCode = Artisan::call('legacy:import', ['--verify' => true]);

        $this->assertSame(1, $exitCode);
    }

    public function test_migrated_user_can_authenticate_with_legacy_password_hash(): void
    {
        $plaintext = 'legacy-plaintext-secret';
        $hash = password_hash($plaintext, PASSWORD_BCRYPT);

        $command = new class($hash) extends LegacyImportCommand
        {
            public function __construct(private readonly string $fixtureHash)
            {
                parent::__construct();
            }

            protected function fetchLegacyRows(string $table): Collection
            {
                if ($table !== 'users') {
                    return collect();
                }

                return collect([(object) [
                    'id' => 999001,
                    'email' => 'fixture-login@example.test',
                    'password_hash' => $this->fixtureHash,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'deleted_at' => null,
                ]]);
            }
        };

        $command->setLaravel($this->app);
        $command->run(new ArrayInput(['--only' => 'users']), new NullOutput);

        $this->assertTrue(Auth::attempt([
            'email' => 'fixture-login@example.test',
            'password' => $plaintext,
        ]), 'The migrated user must authenticate with their legacy plaintext password, unchanged.');
    }

    public function test_profile_avatar_file_is_copied_into_public_storage(): void
    {
        Storage::fake('public');

        Artisan::call('legacy:import', ['--only' => 'profiles']);

        $avatar = DB::connection('legacy')->table('profiles')->value('avatar');

        Storage::disk('public')->assertExists('profiles/'.$avatar);
    }

    public function test_auto_increment_is_synced_after_import(): void
    {
        Artisan::call('legacy:import', ['--only' => 'skills']);

        $maxId = (int) DB::table('skills')->max('id');
        $newSkill = Skill::create(['name' => 'New Skill After Import', 'percentage' => 42]);

        $this->assertGreaterThan($maxId, $newSkill->id);
    }
}
