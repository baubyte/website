<?php

namespace Tests\Feature;

use App\Console\Commands\LegacyImportCommand;
use App\Models\Experience;
use App\Models\Skill;
use App\Models\Study;
use App\Models\User;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Tests\TestCase;

/**
 * `legacy:import` runs one transaction per table. A row-level failure in one
 * table must roll back only that table and let the command continue with the
 * remaining tables — unless `--strict` is passed, in which case the command
 * aborts immediately.
 *
 * The failure is injected by overriding a single row-import method in an
 * anonymous subclass (never by mutating the real read-only `legacy`
 * database, which the application-level guard from PR1 rejects anyway).
 */
class LegacyImportPartialFailureTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh', ['--force' => true]);
    }

    private function commandWithFailingSkillRow(): LegacyImportCommand
    {
        return new class extends LegacyImportCommand
        {
            protected function importSkillRow(object $row): void
            {
                throw new \RuntimeException('Simulated invalid skill value for legacy id '.$row->id);
            }
        };
    }

    public function test_a_failing_table_rolls_back_without_aborting_other_tables(): void
    {
        $command = $this->commandWithFailingSkillRow();
        $command->setLaravel($this->app);

        $exitCode = $command->run(new ArrayInput([]), new NullOutput);

        $this->assertSame(1, $exitCode, 'Exit code must be 1 when any table failed.');
        $this->assertSame(0, Skill::count(), 'The failing table must roll back completely.');
        $this->assertGreaterThan(0, User::count(), 'Tables before the failing one must still import.');
        $this->assertGreaterThan(0, Experience::count(), 'Tables after the failing one must still import.');
        $this->assertGreaterThan(0, Study::count(), 'Tables after the failing one must still import.');
    }

    public function test_strict_flag_aborts_remaining_tables_on_first_failure(): void
    {
        $command = $this->commandWithFailingSkillRow();
        $command->setLaravel($this->app);

        $exitCode = $command->run(new ArrayInput(['--strict' => true]), new NullOutput);

        $this->assertSame(1, $exitCode);
        $this->assertSame(0, Skill::count());
        $this->assertSame(0, Experience::count(), '--strict must stop before importing tables after the failure.');
        $this->assertSame(0, Study::count(), '--strict must stop before importing tables after the failure.');
        $this->assertGreaterThan(0, User::count(), 'Tables imported before the failure keep their committed data even in --strict mode.');
    }
}
