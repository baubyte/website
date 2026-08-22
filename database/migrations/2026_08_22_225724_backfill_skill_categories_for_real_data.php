<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * One-time backfill for the real skills brought in by re-importing a
     * fresh production dump: most landed with `category = NULL` (the
     * legacy schema has no such column, so a brand-new row from the import
     * never gets one) and fell into the "Otros" fallback bucket, reading
     * as broken grouping (10 skills, 8 dumped into one catch-all group).
     * These are sensible starting categories, not a fixed taxonomy — the
     * owner can re-group any of them from Filament (`category` is a free
     * text field on purpose, see `add_category_to_skills_table`).
     */
    private const CATEGORIES = [
        // These two already carried a stale 'Lenguajes' value from the
        // earlier add_category_to_skills_table backfill (which only knew
        // about the old single-word language skills). Overridden
        // unconditionally so they land in the same "Backend" bucket as
        // Node.js & NestJS instead of splitting into two inconsistent
        // taxonomies. Name is 'PYTHON' (legacy casing preserved verbatim,
        // never mutated) not 'Python'.
        'PHP & Laravel' => 'Backend',
        'PYTHON' => 'Backend',
        'Node.js & NestJS' => 'Backend',
        'JavaScript / TypeScript' => 'Frontend',
        'Astro & Alpine.js' => 'Frontend',
        'SQL & Base de Datos (MySQL, Oracle, PG)' => 'Bases de Datos',
        'IA, RAG & Vector Stores' => 'IA',
        'Automatización & n8n' => 'Automatización',
        'Docker & Linux SysAdmin' => 'DevOps',
        'Git & CI/CD Workflows' => 'DevOps',
    ];

    private const OVERRIDE_EVEN_IF_SET = ['PHP & Laravel', 'PYTHON'];

    public function up(): void
    {
        foreach (self::CATEGORIES as $name => $category) {
            $query = DB::table('skills')->where('name', $name);

            if (! in_array($name, self::OVERRIDE_EVEN_IF_SET, true)) {
                $query->whereNull('category');
            }

            $query->update(['category' => $category]);
        }
    }

    public function down(): void
    {
        DB::table('skills')
            ->whereIn('name', array_keys(self::CATEGORIES))
            ->update(['category' => null]);
    }
};
