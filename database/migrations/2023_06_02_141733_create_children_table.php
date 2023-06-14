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
        Schema::create('children', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->string('salesforce_id')->nullable();
            $table->string('record_type_id')->nullable();

            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('suffix')->nullable();
            $table->string('preferred_first_name')->nullable();
            $table->string('personal_email')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->boolean('si_alumni_flag')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('gender')->nullable();
            $table->string('race')->nullable();
            $table->string('ethnicity')->nullable();
            $table->string('current_grade')->nullable();
            $table->string('current_school')->nullable();
            $table->string('current_school_not_listed')->nullable();
            $table->string('religion')->nullable();
            $table->string('religion_other')->nullable();
            $table->string('religious_community')->nullable();
            $table->string('religious_community_location')->nullable();
            $table->year('baptism_year')->nullable();
            $table->year('confirmation_year')->nullable();
            $table->string('si_email')->nullable();
            $table->string('si_email_password')->nullable();
            $table->string('powerschool_id')->nullable();
            $table->string('t_shirt_size')->nullable();
            $table->string('performing_arts_flag')->nullable();
            $table->string('performing_arts_programs')->nullable();
            $table->string('performing_arts_other')->nullable();
            $table->longtext('medication_information')->nullable();
            $table->longtext('allergies_information')->nullable();
            $table->longtext('health_information')->nullable();
            $table->boolean('multi_racial_flag')->nullable();
            $table->year('expected_graduation_year')->nullable();
            $table->year('expected_enrollment_year')->nullable();
            $table->boolean('graduated_hs_flag')->nullable();
                       
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children');
    }
};
