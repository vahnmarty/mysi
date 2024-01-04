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
        // Delete columns
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('honors_math');
            $table->dropColumn('honors_english');
            $table->dropColumn('honors_bio');
        });

        // Create Columns
        Schema::table('application_status', function (Blueprint $table) {
            $table->after('application_status', function(Blueprint $table){
                $table->boolean('honors_math')->nullable();
                $table->boolean('honors_english')->nullable();
                $table->boolean('honors_bio')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add Columns
        Schema::table('applications', function (Blueprint $table) {
            $table->boolean('honors_math')->nullable();
            $table->boolean('honors_english')->nullable();
            $table->boolean('honors_bio')->nullable();
        });

        // Delete Columns
        Schema::table('application_status', function (Blueprint $table) {
            $table->dropColumn('honors_math');
            $table->dropColumn('honors_english');
            $table->dropColumn('honors_bio');
        });
    }
};
