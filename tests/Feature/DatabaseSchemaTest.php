<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Schema assertions for the 5 tables migrated from the legacy CodeIgniter
 * database in this work unit (profiles, skills, experiences, studies,
 * portfolios). These tests run against the application's default database
 * connection ("mysql" / DDEV's "db" schema), never the read-only "legacy"
 * connection.
 */
class DatabaseSchemaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh');
    }

    public function test_profiles_table_has_expected_columns(): void
    {
        $this->assertTrue(
            Schema::hasColumns('profiles', [
                'id', 'name', 'surname', 'avatar', 'email_contact',
                'description_es', 'description_en',
                'specialty_es', 'specialty_en',
                'language_es', 'language_en',
                'github_url', 'linkedin_url', 'instagram_url',
                'created_at', 'updated_at', 'deleted_at',
            ])
        );
    }

    public function test_profiles_name_and_surname_have_no_unique_constraint(): void
    {
        $uniqueColumns = collect(Schema::getIndexes('profiles'))
            ->filter(fn (array $index) => $index['unique'])
            ->flatMap(fn (array $index) => $index['columns'])
            ->all();

        $this->assertNotContains('name', $uniqueColumns);
        $this->assertNotContains('surname', $uniqueColumns);
    }

    public function test_skills_table_has_expected_columns(): void
    {
        $this->assertTrue(
            Schema::hasColumns('skills', [
                'id', 'name', 'percentage', 'created_at', 'updated_at', 'deleted_at',
            ])
        );
    }

    public function test_experiences_table_has_start_date_and_end_date_columns(): void
    {
        $this->assertTrue(Schema::hasColumn('experiences', 'start_date'));
        $this->assertTrue(Schema::hasColumn('experiences', 'end_date'));
        $this->assertFalse(Schema::hasColumn('experiences', 'start'));
        $this->assertFalse(Schema::hasColumn('experiences', 'end'));
    }

    public function test_experiences_has_index_on_start_date(): void
    {
        $indexedColumns = collect(Schema::getIndexes('experiences'))
            ->flatMap(fn (array $index) => $index['columns'])
            ->all();

        $this->assertContains('start_date', $indexedColumns);
    }

    public function test_studies_table_has_start_date_and_end_date_columns(): void
    {
        $this->assertTrue(Schema::hasColumn('studies', 'start_date'));
        $this->assertTrue(Schema::hasColumn('studies', 'end_date'));
        $this->assertFalse(Schema::hasColumn('studies', 'start'));
        $this->assertFalse(Schema::hasColumn('studies', 'end'));
    }

    public function test_studies_has_index_on_start_date(): void
    {
        $indexedColumns = collect(Schema::getIndexes('studies'))
            ->flatMap(fn (array $index) => $index['columns'])
            ->all();

        $this->assertContains('start_date', $indexedColumns);
    }

    public function test_portfolios_table_has_expected_columns(): void
    {
        $this->assertTrue(
            Schema::hasColumns('portfolios', [
                'id', 'company_name', 'image', 'website_url_es', 'website_url_en',
                'created_at', 'updated_at', 'deleted_at',
            ])
        );
    }

    public function test_portfolios_company_name_is_unique(): void
    {
        $uniqueColumns = collect(Schema::getIndexes('portfolios'))
            ->filter(fn (array $index) => $index['unique'])
            ->flatMap(fn (array $index) => $index['columns'])
            ->all();

        $this->assertContains('company_name', $uniqueColumns);
    }
}
