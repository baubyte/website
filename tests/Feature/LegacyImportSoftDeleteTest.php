<?php

namespace Tests\Feature;

use App\Console\Commands\LegacyImportCommand;
use App\Models\User;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Tests\TestCase;

/**
 * No row in the real `legacy` database is soft-deleted today, so this test
 * fabricates the scenario with an in-memory fixture row instead of writing
 * to the real (read-only) `legacy` database, exactly as the task brief asks.
 */
class LegacyImportSoftDeleteTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh', ['--force' => true]);
    }

    public function test_soft_deleted_legacy_user_is_imported_as_soft_deleted(): void
    {
        $command = new class extends LegacyImportCommand
        {
            protected function fetchLegacyRows(string $table): Collection
            {
                if ($table !== 'users') {
                    return collect();
                }

                return collect([(object) [
                    'id' => 999002,
                    'email' => 'soft-deleted@example.test',
                    'password_hash' => password_hash('irrelevant', PASSWORD_BCRYPT),
                    'created_at' => now()->subYear(),
                    'updated_at' => now()->subMonth(),
                    'deleted_at' => now()->subDay(),
                ]]);
            }
        };

        $command->setLaravel($this->app);
        $exitCode = $command->run(new ArrayInput(['--only' => 'users']), new NullOutput);

        $this->assertSame(0, $exitCode);

        $this->assertNull(User::find(999002), 'A soft-deleted user must not appear in normal queries.');

        $trashed = User::withTrashed()->find(999002);
        $this->assertNotNull($trashed, 'The soft-deleted user must still exist in the database.');
        $this->assertTrue($trashed->trashed());
        $this->assertNotNull($trashed->deleted_at);
    }
}
