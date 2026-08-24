<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Stores an Iconify icon id in `"{prefix}:{name}"` form (e.g.
     * `"devicon:laravel"`), resolved against the vendored
     * `resources/icons/devicon.json` catalog by `App\Support\Icons\
     * IconCatalog`. Nullable: `null` means "fall back to legacy name
     * matching" (see `resources/js/lib/skillIcons.js`), so existing skills
     * keep rendering with no manual assignment required.
     */
    public function up(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->string('icon', 64)->nullable()->after('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
    }
};
