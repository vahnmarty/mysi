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
        Schema::table('hspt_scores', function (Blueprint $table) {
            $table->string('hspt_id', 50)->after('id')->nullable();
            $table->string('school_code', 100)->after('school')->nullable();
            $table->after('language', function(Blueprint $table){
                $table->string('classroom', 200)->nullable();
                $table->string('day', 50)->nullable();
                $table->string('time', 50)->nullable();
            });
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hspt_scores', function (Blueprint $table) {
            $table->dropColumn('hspt_id', 50);
            $table->dropColumn('school_code', 100);
            $table->dropColumn('classroom', 200);
            $table->dropColumn('day', 50);
            $table->dropColumn('time', 50);
        });
    }
};
