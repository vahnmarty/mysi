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
        Schema::table('applications', function (Blueprint $table) {
            $table->after('new_extracurricular_activities', function(Blueprint $table){
                $table->boolean('honors_math')->nullable();
                $table->boolean('honors_english')->nullable();
                $table->boolean('honors_bio')->nullable();
                $table->boolean('with_financial_aid')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('honors_math');
            $table->dropColumn('honors_english');
            $table->dropColumn('honors_bio');
            $table->dropColumn('with_financial_aid');
        });
    }
};
