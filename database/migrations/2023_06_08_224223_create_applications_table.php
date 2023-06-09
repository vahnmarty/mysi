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
            $table->string('status')->nullable();
            $table->string('salesforce_id')->nullable();
            $table->string('record_type_id')->nullable();

            $table->string('other_high_school_1')->nullable();
            $table->string('other_high_school_2')->nullable();
            $table->string('other_high_school_3')->nullable();
            $table->string('other_high_school_4')->nullable();
            $table->longtext('impact_to_community')->nullable();
            $table->string('describe_family_spirituality')->nullable();
            $table->string('describe_family_spirituality_in_detail')->nullable();
            $table->boolean('religious_studies_classes')->nullable();
            $table->string('religious_studies_classes_explanation')->nullable();
            $table->boolean('school_liturgies')->nullable();
            $table->string('school_liturgies_explanation')->nullable();
            $table->boolean('retreats')->nullable();
            $table->string('retreats_explanation')->nullable();

            
            $table->string('community_service')->nullable();
            $table->string('community_service_explanation')->nullable();
            $table->string('religious_statement_by')->nullable();
            $table->string('religious_relationship_to_student')->nullable();
            $table->string('why_si_for_your_child')->nullable();
            $table->string('childs_quality_and_area_of_growth')->nullable();
            $table->string('something_about_child')->nullable();
            $table->string('parent_statement_by')->nullable();
            $table->string('parent_relationship_to_student')->nullable();

            $table->string('why_did_you_apply')->nullable();
            $table->string('greatest_challenge')->nullable();
            $table->string('religious_activity_participation')->nullable();
            $table->string('favorite_and_difficult_subjects')->nullable();
            $table->string('writing_sample_essay')->nullable();
            $table->string('writing_sample_essay_acknowledgement')->nullable();
            $table->string('writing_sample_essay_by')->nullable();
            $table->string('agree_to_release_record')->nullable();
            $table->string('agree_academic_record_is_true')->nullable();
            $table->string('entrance_exam_reservation')->nullable();
            $table->string('other_catholic_school_name')->nullable();
            $table->string('other_catholic_school_location')->nullable();
            $table->string('other_catholic_school_date')->nullable();

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
