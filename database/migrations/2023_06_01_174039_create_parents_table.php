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
            $table->string('salutation');
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('suffix')->nullable();
            $table->string('relationship_type');
            $table->string('address_location');
            $table->string('email');
            $table->string('alt_email')->nullable();
            $table->string('mobile_phone');
            $table->string('employer');
            $table->string('job_title');
            $table->string('work_email')->nullable();
            $table->string('work_phone')->nullable();
            $table->string('work_phone_ext')->nullable();

            $table->longtext('schools_attended')->nullable();
            $table->string('living_situation')->nullable();
            $table->boolean('deceased_flag')->nullable();
            $table->string('communication_preferences')->nullable();

            $table->string('salesforce_id')->nullable();
            $table->string('record_type_id')->nullable();

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
