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
        Schema::table('re_registrations', function (Blueprint $table) {
            $table->string('transfer_school')->nullable()->after('attending_si');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('re_registrations', function (Blueprint $table) {
            $table->dropColumn('transfer_school');
        });
    }
};
