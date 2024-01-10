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
        Schema::create('course_placements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('registration_id')->nullable();
            $table->string('sf_placement_id', 18)->nullable();
            $table->string('salesforce_id', 18)->nullable();
            $table->string('english_placement', 100)->nullable();
            $table->string('math_placement', 100)->nullable();
            $table->string('math_challenge', 100)->nullable();
            $table->string('language1', 100)->nullable();
            $table->string('language2', 100)->nullable();
            $table->string('language3', 100)->nullable();
            $table->string('language4', 100)->nullable();
            $table->string('language1_skill', 300)->nullable();
            $table->boolean('language_challenge_flag', 1)->nullable();
            $table->string('language_challenge_choice', 255)->nullable();
            $table->string('about_choice', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('password', 255)->nullable();
            $table->string('final_language', 255)->nullable();
            $table->string('final_math', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_placements');
    }
};
