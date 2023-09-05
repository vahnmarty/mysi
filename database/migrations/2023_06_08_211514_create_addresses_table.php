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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->string('sf_account_id', 18)->nullable();
            $table->string('sf_residence_id', 18)->nullable();
            $table->string('record_type_id', 18)->nullable();
            $table->string('address_type', 20)->nullable();
            $table->string('address', 75)->nullable();
            $table->string('city', 75)->nullable();
            $table->string('state', 75)->nullable();
            $table->string('zip_code', 5)->nullable();
            $table->string('phone_number', 10)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
