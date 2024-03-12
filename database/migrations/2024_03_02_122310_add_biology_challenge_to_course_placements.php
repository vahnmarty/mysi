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
            $table->string('biology_placement')->after('math_challenge')->nullable();
            $table->string('biology_challenge')->after('biology_placement')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_placements', function (Blueprint $table) {
            $table->dropColumn('biology_placement');
            $table->dropColumn('biology_challenge');
        });
    }
};
