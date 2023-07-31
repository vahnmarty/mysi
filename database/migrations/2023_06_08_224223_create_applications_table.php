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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->uuid('uuid');
            $table->unsignedBigInteger('child_id');
            $table->string('salesforce_id', 18)->nullable();
            $table->string('record_type_id', 18)->nullable();

            $table->string('other_high_school_1', 100)->nullable();
            $table->string('other_high_school_2', 100)->nullable();
            $table->string('other_high_school_3', 100)->nullable();
            $table->string('other_high_school_4', 100)->nullable();
            $table->string('impact_to_community', 750)->nullable();
            $table->string('describe_family_spirituality', 75)->nullable();
            $table->string('describe_family_spirituality_in_detail', 750)->nullable();
            $table->boolean('religious_studies_classes')->nullable();
            $table->string('religious_studies_classes_explanation', 500)->nullable();
            $table->boolean('school_liturgies')->nullable();
            $table->string('school_liturgies_explanation', 500)->nullable();
            $table->boolean('retreats')->nullable();
            $table->string('retreats_explanation', 500)->nullable();
            $table->string('community_service')->nullable();
            $table->string('community_service_explanation', 500)->nullable();
            $table->string('religious_statement_by', 150)->nullable();
            $table->string('religious_relationship_to_student', 20)->nullable();
            $table->string('why_si_for_your_child', 750)->nullable();
            $table->string('childs_quality_and_area_of_growth', 750)->nullable();
            $table->string('something_about_child', 750)->nullable();
            $table->string('parent_statement_by', 150)->nullable();
            $table->string('parent_relationship_to_student', 20)->nullable();

            $table->string('why_did_you_apply', 750)->nullable();
            $table->string('greatest_challenge', 750)->nullable();
            $table->string('religious_activity_participation', 750)->nullable();
            $table->string('favorite_and_difficult_subjects', 750)->nullable();
            $table->string('writing_sample_essay', 2250)->nullable();
            $table->string('writing_sample_essay_acknowledgement')->nullable();
            $table->string('writing_sample_essay_by', 150)->nullable();
            $table->boolean('agree_to_release_record')->nullable();
            $table->boolean('agree_academic_record_is_true')->nullable();
            $table->string('entrance_exam_reservation', 100)->nullable();
            $table->string('other_catholic_school_name', 100)->nullable();
            $table->string('other_catholic_school_location', 255)->nullable();
            $table->date('other_catholic_school_date')->nullable();

            $table->boolean('has_learning_disability')->nullable();
            $table->json('file_learning_documentation')->nullable();

            $table->string('most_passionate_activity', 750)->nullable();
            $table->string('new_extracurricular_activities', 750)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
