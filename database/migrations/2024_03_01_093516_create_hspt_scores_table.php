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
        Schema::create('hspt_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained();
            $table->unsignedBigInteger('child_id')->nullable();
            $table->string('name', 100)->nullable();
            $table->string('school', 150)->nullable();
            $table->string('grade', 50)->nullable();
            $table->string('academic_year', 10)->nullable();
            $table->integer('composite')->nullable();
            $table->integer('quantitative')->nullable();
            $table->integer('math')->nullable();
            $table->integer('verbal')->nullable();
            $table->integer('reading')->nullable();
            $table->integer('language')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hspt_scores');
    }
};
