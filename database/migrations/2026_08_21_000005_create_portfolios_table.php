<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * `portfolios` is created here for schema completeness only. It
     * intentionally has no Filament Resource and no public route in this
     * work unit — the "Proyectos" section is deferred to a later PR.
     */
    public function up(): void
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->string('company_name', 120)->unique();
            $table->string('image', 64);
            $table->string('website_url_es', 100)->nullable();
            $table->string('website_url_en', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};
