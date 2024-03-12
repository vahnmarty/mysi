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
        Schema::create('magis_programs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('registration_id')->nullable();
            $table->string('sf_magis_id', 18)->nullable();
            $table->string('salesforce_id', 18)->nullable();
            $table->string('record_type_id', 18)->nullable();
            $table->boolean('first_gen')->nullable();
            $table->boolean('is_interested')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('magis_programs');
    }
};
