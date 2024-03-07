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
        Schema::table('accounts', function (Blueprint $table) {
            $table->after('record_type_id', function(Blueprint $table){
                $table->string('primary_language_spoken', 500)->nullable();
                $table->string('other_primary_language_spoken', 200)->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn('primary_language_spoken');
            $table->dropColumn('other_primary_language_spoken');
        });
    }
};
