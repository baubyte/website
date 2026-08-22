<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * `profiles` is a singleton table (one profile, in practice one row).
     * `name`/`surname` intentionally carry no unique constraint: the legacy
     * migration marked them unique, which is a defect — a single-profile
     * site gains nothing from that constraint.
     */
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('surname', 120);
            $table->string('avatar', 64);
            $table->string('email_contact', 100)->nullable();
            $table->text('description_es')->nullable();
            $table->text('description_en')->nullable();
            $table->string('specialty_es', 100);
            $table->string('specialty_en', 100)->nullable();
            $table->string('language_es', 100)->nullable();
            $table->string('language_en', 100)->nullable();
            $table->string('github_url', 100)->nullable();
            $table->string('linkedin_url', 100)->nullable();
            $table->string('instagram_url', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
