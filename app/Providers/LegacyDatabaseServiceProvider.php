<?php

namespace App\Providers;

use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use RuntimeException;

/**
 * Guards the `legacy` database connection (the CodeIgniter application's
 * database) against writes from application code.
 *
 * This is an application-level guard only: it intercepts every query on the
 * `legacy` connection before it reaches the database driver and rejects
 * anything that is not a read (SELECT/SHOW/DESCRIBE/EXPLAIN). It does not
 * replace database-level read-only grants, which are out of scope for this
 * work unit.
 */
class LegacyDatabaseServiceProvider extends ServiceProvider
{
    /**
     * Statement keywords that mutate data or schema and must never run
     * against the legacy connection.
     */
    private const WRITE_KEYWORDS = [
        'insert', 'update', 'delete', 'replace',
        'truncate', 'alter', 'drop', 'create',
        'grant', 'revoke', 'lock',
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        DB::connection('legacy')->beforeExecuting(
            function (string $query, array $bindings, Connection $connection): void {
                $firstWord = strtolower(strtok(ltrim($query), " \t\n\r("));

                if (in_array($firstWord, self::WRITE_KEYWORDS, true)) {
                    throw new RuntimeException(
                        "The [legacy] database connection is read-only; refused to run: {$query}"
                    );
                }
            }
        );
    }
}
