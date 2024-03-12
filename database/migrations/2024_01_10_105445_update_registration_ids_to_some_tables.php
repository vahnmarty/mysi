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
            $table->unsignedBigInteger('registration_id')->nullable()->after('account_id');
            $table->string('sf_healthcare_id', 18)->nullable()->after('registration_id');
        });

        Schema::table('emergency_contacts', function (Blueprint $table) {
            $table->unsignedBigInteger('registration_id')->nullable()->after('account_id');
            $table->string('sf_contact_id', 18)->nullable()->after('registration_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('healthcares', function (Blueprint $table) {
            $table->dropColumn('registration_id');
            $table->dropColumn('sf_healthcare_id', 18);
        });

        Schema::table('emergency_contacts', function (Blueprint $table) {
            $table->dropColumn('registration_id');
            $table->dropColumn('sf_contact_id', 18);
        });
    }
};
