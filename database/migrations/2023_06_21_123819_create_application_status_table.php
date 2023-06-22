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
        Schema::create('application_status', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id');
            $table->boolean('application_started')->nullable();
            $table->boolean('application_submitted')->nullable();
            $table->timestamp('application_start_date')->nullable();
            $table->timestamp('application_submit_date')->nullable();
            $table->string('application_status')->nullable();
            $table->boolean('notification_read')->nullable();
            $table->timestamp('notification_read_date')->nullable();
            $table->boolean('candidate_decision')->nullable();
            $table->timestamp('candidate_decision_date')->nullable();
            $table->boolean('survey_submitted')->nullable();
            $table->timestamp('survey_submit_date')->nullable();
            $table->boolean('registration_started')->nullable();
            $table->boolean('registration_completed')->nullable();
            $table->timestamp('registration_start_date')->nullable();
            $table->timestamp('registration_complete_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_status');
    }
};
