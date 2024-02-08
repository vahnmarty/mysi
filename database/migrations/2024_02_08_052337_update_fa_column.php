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
        Schema::table('notification_messages', function (Blueprint $table) {
            $table->dropColumn('fa_acknowledged_at');
            $table->dropColumn('with_fa');
        });

        Schema::table('application_status', function (Blueprint $table) {
            $table->timestamp('fa_acknowledged_at')->after('financial_aid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notification_messages', function (Blueprint $table) {
            $table->timestamp('fa_acknowledged_at')->nullable();
            $table->boolean('with_fa')->nullable();
        });

        Schema::table('application_status', function (Blueprint $table) {
            $table->dropColumn('fa_acknowledged_at');
        });
    }
};
