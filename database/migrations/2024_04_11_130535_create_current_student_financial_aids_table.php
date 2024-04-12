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
        Schema::create('current_student_financial_aids', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('child_id');
            $table->string('financial_aid')->nullable();
            $table->float('annual_financial_aid_amount')->nullable();
            $table->float('total_financial_aid_amount')->nullable();
            $table->json('fa_contents')->nullable();
            $table->timestamp('fa_acknowledged_at')->nullable();
            $table->timestamp('notification_sent_at')->nullable();
            $table->timestamps();
        });

        Schema::table('re_registrations', function (Blueprint $table) {
            $table->dropColumn('financial_aid')->nullable();
            $table->dropColumn('annual_financial_aid_amount')->nullable();
            $table->dropColumn('total_financial_aid_amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('re_registrations', function (Blueprint $table) {
            $table->string('financial_aid')->nullable();
            $table->float('annual_financial_aid_amount')->nullable();
            $table->float('total_financial_aid_amount')->nullable();
        });

        Schema::dropIfExists('current_student_financial_aids');
    }
};
