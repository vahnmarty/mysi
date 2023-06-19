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
            $table->string('salesforce_id')->nullable();
            $table->string('record_type_id')->nullable();

            $table->string('activity_name')->nullable();
            $table->string('number_of_years')->nullable();
            $table->string('hours_per_week')->nullable();
            $table->longtext('activity_information')->nullable();
            $table->longtext('most_passionate_activity')->nullable();
            $table->longtext('new_extracurricular_activities')->nullable();

            $table->timestamps();
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
