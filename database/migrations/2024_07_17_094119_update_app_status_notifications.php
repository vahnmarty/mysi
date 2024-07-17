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
        Schema::table('application_status', function (Blueprint $table) {
            $table->after('fa_acknowledged_at', function(Blueprint $table){
                $table->string('claver_award')->nullable();
                $table->timestamp('claver_award_acknowledged_at')->nullable();
                $table->string('product_design')->nullable();
                $table->timestamp('product_design_acknowledged_at')->nullable();
            });

            $table->string('candidate_decline_school', 250)->after('candidate_decision_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_status', function (Blueprint $table) {
            $table->dropColumn('claver_award');
            $table->dropColumn('claver_award_acknowledged_at');
            $table->dropColumn('product_design');
            $table->dropColumn('product_design_acknowledged_at');
            $table->dropColumn('candidate_decline_school');
        });
    }
};
