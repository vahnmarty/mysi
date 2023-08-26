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
        Schema::create('parents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->string('sf_contact_id', 18)->nullable();
            $table->string('sf_account_id', 18)->nullable();
            $table->string('record_type_id', 18)->nullable();

            $table->boolean('is_primary')->nullable();
            $table->string('salutation', 5)->nullable();
            $table->string('first_name', 40)->nullable();
            $table->string('middle_name', 40)->nullable();
            $table->string('last_name', 80)->nullable();
            $table->string('suffix', 5)->nullable()->nullable();
            $table->string('preferred_first_name', 40)->nullable();
            $table->string('personal_email', 255)->nullable();
            $table->string('mobile_phone', 10)->nullable();
            $table->boolean('si_alumni_flag')->nullable();
            $table->string('relationship_type', 20)->nullable();
            $table->string('address_location', 20)->nullable();
            $table->string('alternate_email', 255)->nullable();
            $table->string('employment_status', 50)->nullable();
            $table->string('employer', 100)->nullable();
            $table->string('job_title', 128)->nullable();
            $table->string('work_email', 255)->nullable();
            $table->string('work_phone', 10)->nullable();
            $table->string('work_phone_ext', 20)->nullable();
            $table->string('schools_attended', 1000)->nullable();
            $table->string('living_situation', 30)->nullable();
            $table->string('deceased_flag', 3)->nullable();
            $table->string('communication_preferences', 255)->nullable();
            $table->int('graduation_year')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parents');
    }
};
