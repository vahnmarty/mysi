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
        Schema::create('prep_shops', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->date('date');
            $table->string('morning_schedule', 100)->nullable();
            $table->string('afternoon_schedule', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prep_shops');
    }
};
