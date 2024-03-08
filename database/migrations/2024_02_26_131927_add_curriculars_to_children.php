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
        Schema::table('children', function (Blueprint $table) {
            $table->boolean('is_interested_club')->nullable();
            $table->string('clubs', 1028)->nullable();
            $table->boolean('is_interested_sports')->nullable();
            $table->string('sports', 1028)->nullable();
            $table->string('instruments', 1028)->nullable();
            $table->string('interest1', 100)->nullable();
            $table->string('interest2', 100)->nullable();
            $table->string('interest3', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('children', function (Blueprint $table) {
            $table->dropColumn('is_interested_club');
            $table->dropColumn('clubs');
            $table->dropColumn('is_interested_sports');
            $table->dropColumn('sports');
            $table->dropColumn('instruments');
            $table->dropColumn('interest1');
            $table->dropColumn('interest2');
            $table->dropColumn('interest3');
        });
    }
};
