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
        Schema::create('re_registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->uuid('uuid');
            $table->unsignedBigInteger('child_id');
            $table->string('sf_reregistration_id', 18)->nullable();
            $table->string('sf_contact_id', 18)->nullable();
            $table->string('record_type_id', 18)->nullable();
            $table->boolean('attending_si');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('healthcares', function(Blueprint $table){
            $table->unsignedBigInteger('re_registration_id')->nullable()->after('registration_id');
        });

        Schema::table('emergency_contacts', function(Blueprint $table){
            $table->unsignedBigInteger('re_registration_id')->nullable()->after('registration_id');
        });

        Schema::table('accommodations', function(Blueprint $table){
            $table->unsignedBigInteger('re_registration_id')->nullable()->after('registration_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('healthcares', function(Blueprint $table){
            $table->dropColumn('re_registration_id');
        });

        Schema::table('emergency_contacts', function(Blueprint $table){
            $table->dropColumn('re_registration_id');
        });

        Schema::table('accommodations', function(Blueprint $table){
            $table->dropColumn('re_registration_id');
        });

        Schema::dropIfExists('re_registrations');
    }
};
