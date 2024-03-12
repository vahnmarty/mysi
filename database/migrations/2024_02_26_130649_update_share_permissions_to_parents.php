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
        Schema::table('parents', function (Blueprint $table) {
            $table->boolean('share_personal_email')->nullable();
            $table->boolean('share_mobile_phone')->nullable();
            $table->boolean('share_full_address')->nullable();
        });

        Schema::table('children', function (Blueprint $table) {
            $table->boolean('share_personal_email')->nullable();
            $table->boolean('share_mobile_phone')->nullable();
            $table->boolean('share_full_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parents', function (Blueprint $table) {
            $table->dropColumn('share_personal_email');
            $table->dropColumn('share_mobile_phone');
            $table->dropColumn('share_full_address');
        });

        Schema::table('children', function (Blueprint $table) {
            $table->dropColumn('share_personal_email');
            $table->dropColumn('share_mobile_phone');
            $table->dropColumn('share_full_address');
        });
    }
};
