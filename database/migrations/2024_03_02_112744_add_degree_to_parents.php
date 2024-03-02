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
            $table->string('undergraduate_degree', 75)->after('undergraduate_school')->nullable();
            $table->string('undergraduate_major', 100)->after('undergraduate_degree')->nullable();
            $table->string('graduate_degree', 75)->after('graduate_school')->nullable();
            $table->string('graduate_major', 100)->after('graduate_degree')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parents', function (Blueprint $table) {
            $table->dropColumn('undergraduate_degree');
            $table->dropColumn('undergraduate_major');
            $table->dropColumn('graduate_degree');
            $table->dropColumn('graduate_major');
        });
    }
};
