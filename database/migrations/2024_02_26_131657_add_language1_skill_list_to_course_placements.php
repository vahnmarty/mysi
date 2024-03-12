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
        Schema::table('course_placements', function (Blueprint $table) {
            $table->string('language1_skill_list', 1028)->nullable()->after('language1_skill');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_placements', function (Blueprint $table) {
            $table->dropColumn('language1_skill_list');
        });
    }
};
