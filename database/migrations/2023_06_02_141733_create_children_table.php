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
            $table->string('salesforce_id', 18)->nullable();
            $table->string('record_type_id', 18)->nullable();

            $table->string('first_name', 40)->nullable();
            $table->string('middle_name', 40)->nullable();
            $table->string('last_name', 80)->nullable();
            $table->string('suffix', 5)->nullable();
            $table->string('preferred_first_name', 40)->nullable();
            $table->string('personal_email', 255)->nullable();
            $table->string('mobile_phone', 10)->nullable();


            $table->string('relationship_type', 20)->nullable();
            $table->string('living_situation', 30)->nullable();
            $table->string('address_location', 20)->nullable();

            $table->boolean('si_alumni_flag')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('gender', 10)->nullable();
            $table->string('race', 255)->nullable();
            $table->string('ethnicity', 255)->nullable();
            $table->string('current_grade', 50)->nullable();
            $table->string('current_school', 100)->nullable();
            $table->string('current_school_not_listed', 100)->nullable();
            $table->string('religion', 15)->nullable();
            $table->string('religion_other', 50)->nullable();
            $table->string('religious_community', 100)->nullable();
            $table->string('religious_community_location', 255)->nullable();
            $table->year('baptism_year')->nullable();
            $table->year('confirmation_year')->nullable();
            $table->string('si_email', 255)->nullable();
            $table->string('si_email_password', 255)->nullable();
            $table->string('powerschool_id', 6)->nullable();
            $table->string('t_shirt_size', 20)->nullable();
            $table->boolean('performing_arts_flag')->nullable();
            $table->string('performing_arts_programs', 255)->nullable();
            $table->string('performing_arts_other', 255)->nullable();
            $table->string('medication_information', 750)->nullable();
            $table->string('allergies_information', 750)->nullable();
            $table->string('health_information', 750)->nullable();
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
