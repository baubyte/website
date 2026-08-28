<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->foreignId('skill_category_id')->nullable()->constrained('skill_categories')->nullOnDelete();
        });

        // Migrate existing categories
        $categories = DB::table('skills')->whereNotNull('category')->distinct()->pluck('category');
        foreach ($categories as $categoryName) {
            $id = DB::table('skill_categories')->insertGetId([
                'name_es' => $categoryName,
                'name_en' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::table('skills')->where('category', $categoryName)->update(['skill_category_id' => $id]);
        }

        Schema::table('skills', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->string('category', 60)->nullable();
        });

        // Restore category names
        $skills = DB::table('skills')->whereNotNull('skill_category_id')->get();
        foreach ($skills as $skill) {
            $cat = DB::table('skill_categories')->where('id', $skill->skill_category_id)->first();
            if ($cat) {
                DB::table('skills')->where('id', $skill->id)->update(['category' => $cat->name_es]);
            }
        }

        Schema::table('skills', function (Blueprint $table) {
            $table->dropForeign(['skill_category_id']);
            $table->dropColumn('skill_category_id');
        });
    }
};
