<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use RuntimeException;
use Tests\TestCase;

class LegacyDatabaseReadOnlyTest extends TestCase
{
    /**
     * The write guard must intercept the query before it ever reaches the
     * database connection, so these assertions do not require the legacy
     * schema/data to be imported yet.
     */
    public function test_legacy_connection_rejects_insert_statements(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('read-only');

        DB::connection('legacy')->table('profiles')->insert([
            'id' => 999999,
        ]);
    }

    public function test_legacy_connection_rejects_update_statements(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('read-only');

        DB::connection('legacy')->table('profiles')->where('id', 1)->update([
            'id' => 1,
        ]);
    }

    public function test_legacy_connection_rejects_delete_statements(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('read-only');

        DB::connection('legacy')->table('profiles')->where('id', 999999)->delete();
    }

    public function test_legacy_connection_rejects_raw_write_statements(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('read-only');

        DB::connection('legacy')->statement('DELETE FROM profiles WHERE id = 999999');
    }

    /**
     * This assertion does require the legacy database to have been imported
     * (see docs/legacy-db-import.md / apply-progress for the exact command).
     */
    public function test_legacy_connection_allows_select_statements(): void
    {
        $count = DB::connection('legacy')->table('profiles')->count();

        $this->assertIsInt($count);
        $this->assertGreaterThan(0, $count);
    }
}
