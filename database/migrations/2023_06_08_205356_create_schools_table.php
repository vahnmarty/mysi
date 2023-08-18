<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('cds_code', 50)->nullable();
            $table->string('county', 255)->nullable();
            $table->string('city', 255)->nullable();
            $table->string('district', 200)->nullable();
            $table->string('name', 300);
            $table->boolean('status_flag')->default(1);
            $table->string('funding_type', 50)->nullable();
            $table->string('educational_program_type', 100)->nullable();
            $table->string('entity_type', 100)->nullable();
            $table->string('education_level', 200)->nullable();
            $table->string('low_grade', 50)->nullable();
            $table->string('high_grade', 50)->nullable();
            $table->string('charter_flag', 10)->nullable();
            $table->string('magnet_flag', 10)->nullable();
            $table->string('public_flag', 10)->nullable();
            $table->string('catholic_flag', 10)->nullable();
            $table->string('independent_flag', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
