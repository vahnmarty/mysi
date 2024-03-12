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
        Schema::table('healthcares', function (Blueprint $table) {
            $table->renameColumn('insurance_company', 'medical_insurance_company');
            $table->renameColumn('policy_number', 'medical_policy_number');
            $table->renameColumn('first_name', 'physician_name');
            $table->dropColumn('last_name');
            $table->renameColumn('phone', 'physician_phone');
            $table->renameColumn('phone_extension', 'physician_phone_ext')->after('phone');

            $table->after('phone', function(Blueprint $table){
                $table->string('prescribed_medications', 1024)->nullable();
                $table->string('allergies', 1024)->nullable();
                $table->string('other_issues', 1024)->nullable();
            });
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('healthcares', function (Blueprint $table) {
            // Invert the column renaming
            $table->renameColumn('medical_insurance_company', 'insurance_company');
            $table->renameColumn('medical_policy_number', 'policy_number');
            $table->renameColumn('physician_name', 'first_name');
            $table->string('last_name')->nullable();
            $table->renameColumn('physician_phone', 'phone');
            $table->renameColumn('physician_phone_ext', 'phone_extension');

            // Invert the column dropping
            $table->dropColumn('prescribed_medications');
            $table->dropColumn('allergies');
            $table->dropColumn('other_issues');
        });
    }
};
