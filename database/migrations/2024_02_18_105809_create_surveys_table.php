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
            $table->string('other_school', 150)->nullable();
            $table->string('most_important_reason', 150)->nullable();
            $table->string('most_important_reason_comment', 300)->nullable();
            $table->string('second_important_reason', 150)->nullable();
            $table->string('second_important_reason_comment', 300)->nullable();
            $table->string('third_important_reason', 150)->nullable();
            $table->string('third_important_reason_comment', 300)->nullable();
            $table->longtext('experience')->nullable();
            $table->longtext('si_admission_process')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('survey_schools', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('survey_id');
            $table->string('school_name', 150)->nullable();
            $table->string('school_decision', 50)->nullable();
            $table->string('applied_for_aid', 50)->nullable();
            $table->float('amount_aid')->nullable();
            $table->string('comment', 150)->nullable();
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
