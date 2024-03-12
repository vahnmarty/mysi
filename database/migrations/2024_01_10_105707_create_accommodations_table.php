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
        Schema::create('accommodations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('registration_id')->nullable();
            $table->string('sf_accommodation_id', 18)->nullable();
            $table->string('salesforce_id', 18)->nullable();
            $table->string('record_type_id', 18)->nullable();
            $table->boolean('formal')->nullable();
            $table->boolean('informal')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accommodations');
    }
};
