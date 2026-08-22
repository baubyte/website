<?php

namespace Tests\Feature\Auth;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * PR4 introduces a single native Laravel auth guard (email + password on the
 * existing `users` table) with no roles/permissions layer. This locks that
 * decision down at the schema level: no group/permission table from the
 * legacy `myth/auth` package (auth_groups, auth_permissions, and their
 * pivot/attempt/token siblings) — nor any generic roles/permissions
 * package table — may be reintroduced by a later PR without this test
 * failing first.
 */
class NoRoleOrPermissionTablesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh', ['--force' => true]);
    }

    public function test_schema_has_no_role_or_permission_tables(): void
    {
        $tableNames = collect(Schema::getTables())->pluck('name');

        $forbidden = [
            'auth_groups',
            'auth_permissions',
            'auth_groups_permissions',
            'auth_groups_users',
            'auth_users_permissions',
            'auth_activation_attempts',
            'auth_reset_attempts',
            'auth_tokens',
            'auth_logins',
            'roles',
            'permissions',
            'role_user',
            'permission_role',
            'model_has_roles',
            'model_has_permissions',
        ];

        foreach ($forbidden as $table) {
            $this->assertFalse(
                $tableNames->contains($table),
                "Table '{$table}' must not exist — this migration uses a single guard with no roles/permissions layer."
            );
        }
    }

    public function test_schema_only_contains_the_expected_application_tables(): void
    {
        $tableNames = collect(Schema::getTables())->pluck('name')->sort()->values();

        $expected = collect([
            'migrations',
            'users',
            'password_reset_tokens',
            'sessions',
            'cache',
            'cache_locks',
            'jobs',
            'job_batches',
            'failed_jobs',
            'profiles',
            'skills',
            'experiences',
            'studies',
            'portfolios',
        ])->sort()->values();

        $this->assertSame($expected->all(), $tableNames->all());
    }
}
