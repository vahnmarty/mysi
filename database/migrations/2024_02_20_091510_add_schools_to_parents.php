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
            $table->string('high_school', 100)->nullable();
            $table->string('high_school_city', 75)->nullable();
            $table->string('high_school_state', 75)->nullable();
            $table->string('undergraduate_school', 100)->nullable();
            $table->string('undergraduate_school_city', 75)->nullable();
            $table->string('undergraduate_school_state', 75)->nullable();
            $table->string('graduate_school', 100)->nullable();
            $table->string('graduate_school_city', 75)->nullable();
            $table->string('graduate_school_state', 75)->nullable();

            $table->boolean('is_primary_contact')->nullable();
            $table->boolean('has_legal_custody')->nullable();
            $table->boolean('is_pickup_allowed')->nullable();
            $table->string('marital_status', 75)->after('relationship_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parents', function (Blueprint $table) {
            $table->dropColumn('high_school');
            $table->dropColumn('high_school_city');
            $table->dropColumn('high_school_state');
            $table->dropColumn('undergraduate_school');
            $table->dropColumn('undergraduate_school_city');
            $table->dropColumn('undergraduate_school_state');
            $table->dropColumn('graduate_school');
            $table->dropColumn('graduate_school_city');
            $table->dropColumn('graduate_school_state');
            $table->dropColumn('is_primary_contact');
            $table->dropColumn('has_legal_custody');
            $table->dropColumn('is_pickup_allowed');
            $table->dropColumn('marital_status');
        });
    }
};
