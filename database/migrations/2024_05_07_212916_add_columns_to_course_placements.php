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
            $table->unsignedBigInteger('child_id')->nullable();
            $table->string('child_name', 100)->nullable();
            $table->string('academic_year', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_placements', function (Blueprint $table) {
            $table->dropColumn('child_id');
            $table->dropColumn('child_name');
            $table->dropColumn('academic_year');
        });
    }
};
