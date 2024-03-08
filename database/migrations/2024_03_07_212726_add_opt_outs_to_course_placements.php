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
            $table->boolean('english_placement_opt_out')->nullable()->after('english_placement');
            $table->boolean('math_placement_opt_out')->nullable()->after('math_placement');
            $table->boolean('biology_placement_opt_out')->nullable()->after('biology_placement');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_placements', function (Blueprint $table) {
            $table->dropColumn('english_placement_opt_out');
            $table->dropColumn('math_placement_opt_out');
            $table->dropColumn('biology_placement_opt_out');
        });
    }
};
