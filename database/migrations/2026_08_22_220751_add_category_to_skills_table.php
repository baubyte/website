<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * `category` is free text, editable from Filament — deliberately NOT an
     * enum. The owner wants to group skills however they see fit later
     * ("después cambio y voy agrupando por stack o no sé como se me
     * ocurra") without needing a code change or a new migration each time.
     */
    public function up(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->string('category')->nullable()->after('percentage');
        });

        // Existing real skills (all languages today) get a sane default so
        // the public site's grouping doesn't regress to "Otros" for data
        // that already has an obvious category.
        DB::table('skills')->whereNull('category')->update(['category' => 'Lenguajes']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
