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
        Schema::create('notification_messages', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('application_id')->nullable();
            $table->json('content')->nullable();
            $table->json('financial_aid_content')->nullable();
            $table->json('faq_content')->nullable();
            $table->boolean('with_fa')->nullable();
            $table->timestamp('fa_acknowledged_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_messages');
    }
};
