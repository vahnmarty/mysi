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
        Schema::create('contact_directories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('url', 200)->nullable();
            $table->integer('sort')->nullable();
            $table->string('representative_name', 200)->nullable();
            $table->string('representative_email', 200)->nullable();
            $table->string('representative_phone', 200)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_directories');
    }
};
