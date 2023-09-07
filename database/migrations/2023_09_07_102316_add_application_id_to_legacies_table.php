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
        Schema::table('legacies', function (Blueprint $table) {
            $table->unsignedBigInteger('application_id')->nullbale()->after('account_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('legacies', function (Blueprint $table) {
            $table->dropColumn('application_id');
        });
    }
};
