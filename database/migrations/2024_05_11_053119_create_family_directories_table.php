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
        Schema::create('family_directories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->string('name');
            $table->string('type');
            $table->boolean('share_email')->nullable();
            $table->string('email')->nullable();
            $table->boolean('share_phone')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('share_full_address')->nullable();
            $table->boolean('address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_directories');
    }
};
