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
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->string('config');
            $table->string('value')->nullable();
            $table->string('title')->nullable();
            $table->string('content')->nullable();
            $table->string('form_type')->nullable();
            $table->boolean('email')->nullable();
            $table->boolean('system')->nullable();
            $table->boolean('sms')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};
