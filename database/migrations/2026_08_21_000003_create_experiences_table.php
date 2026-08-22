<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Renamed from the legacy `start`/`end` columns to `start_date`/
     * `end_date`: `END` is a reserved word in MySQL.
     */
    public function up(): void
    {
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->string('company', 120);
            $table->string('specialty_es', 120);
            $table->string('specialty_en', 120);
            $table->text('description_es')->nullable();
            $table->text('description_en')->nullable();
            $table->date('start_date')->index();
            $table->date('end_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};
