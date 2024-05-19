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
            $table->string('name', 200);
            $table->string('type', 15);
            $table->boolean('share_email')->nullable();
            $table->string('email', 100)->nullable();
            $table->boolean('share_phone')->nullable();
            $table->string('phone', 11)->nullable();
            $table->boolean('share_full_address',)->nullable();
            $table->string('address', 200)->nullable();
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
