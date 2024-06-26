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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id');
            $table->string('sf_activity_id', 18)->nullable();
            $table->string('sf_application_id', 18)->nullable();
            $table->string('activity_name', 50)->nullable();
            $table->string('number_of_years', 3)->nullable();
            $table->string('hours_per_week', 10)->nullable();
            $table->string('activity_information', 750)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
