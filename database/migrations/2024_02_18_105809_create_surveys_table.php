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
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id');
            $table->uuid('uuid');
            $table->string('type');
            $table->string('other_school')->nullable();
            $table->string('most_important_reason')->nullable();
            $table->string('most_important_reason_comment')->nullable();
            $table->string('second_important_reason')->nullable();
            $table->string('second_important_reason_comment')->nullable();
            $table->string('third_important_reason')->nullable();
            $table->string('third_important_reason_comment')->nullable();
            $table->longtext('experience')->nullable();
            $table->longtext('si_admission_process')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('survey_schools', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('survey_id');
            $table->string('school_name')->nullable();
            $table->string('school_decision')->nullable();
            $table->string('applied_for_aid')->nullable();
            $table->string('amount_aid')->nullable();
            $table->string('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_schools');
        Schema::dropIfExists('surveys');
    }
};
