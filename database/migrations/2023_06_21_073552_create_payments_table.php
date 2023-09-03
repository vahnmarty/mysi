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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id');
            $table->unsignedBigInteger('user_id');
            $table->string('sf_application_id', 18)->nullable();
            $table->string('name_on_card',100)->nullable();
            $table->string('payment_type', 20)->nullable();
            $table->string('transaction_id', 20)->nullable();
            $table->string('auth_id', 10)->nullable();
            $table->float('initial_amount', 16, 2)->nullable();
            $table->string('promo_code', 100)->nullable();
            $table->float('promo_amount', 16, 2)->nullable();
            $table->float('final_amount', 16, 2)->nullable();
            $table->integer('quantity')->nullable();
            $table->float('total_amount', 16, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
