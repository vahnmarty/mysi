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
            $table->string('english_class', 50)->after('honors_english')->nullable();
            $table->string('math_class', 50)->after('honors_math')->nullable();
            $table->string('bio_class', 50)->after('honors_bio')->nullable();
            $table->string('candidate_decision_status')->nullable()->after('candidate_decision');

            $table->after('notification_read_date', function(Blueprint $table){
                $table->float('deposit_amount')->nullable();
                $table->string('financial_aid', 5)->nullable();
                $table->float('annual_financial_aid_amount')->nullable();
                $table->float('total_financial_aid_amount')->nullable();
            });
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_status', function (Blueprint $table) {
            $table->dropColumn('english_class');
            $table->dropColumn('math_class');
            $table->dropColumn('bio_class');
            $table->dropColumn('candidate_decision_status');
            $table->dropColumn('deposit_amount');
            $table->dropColumn('financial_aid');
            $table->dropColumn('annual_financial_aid_amount');
            $table->dropColumn('total_financial_aid_amount');
        });
    }
};
