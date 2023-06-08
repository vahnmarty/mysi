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
            $table->string('salesforce_id')->nullable();
            $table->string('record_type_id')->nullable();

            $table->string('salutation')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('suffix')->nullable()->nullable();
            $table->string('preferred_first_name')->nullable();
            $table->string('personal_email')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->boolean('si_alumni_flag')->nullable();
            $table->string('relationship_type')->nullable();
            $table->string('address_location')->nullable();
            $table->string('alternate_email')->nullable()->nullable();
            $table->string('employer')->nullable();
            $table->string('job_title')->nullable();
            $table->string('work_email')->nullable();
            $table->string('work_phone')->nullable();
            $table->string('work_phone_ext')->nullable();
            $table->longtext('schools_attended')->nullable();
            $table->string('living_situation')->nullable();
            $table->boolean('deceased_flag')->nullable();
            $table->string('communication_preferences')->nullable();

            

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
