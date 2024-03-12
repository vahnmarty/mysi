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
        Schema::table('emergency_contacts', function (Blueprint $table) {
            $table->renameColumn('first_name', 'full_name');
            $table->renameColumn('work_phone_extension', 'work_phone_ext');
            $table->dropColumn('last_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emergency_contacts', function (Blueprint $table) {
            $table->renameColumn('full_name', 'first_name');
            $table->renameColumn('work_phone_ext', 'work_phone_extension');
            $table->string('last_name');
        });
    }
};
