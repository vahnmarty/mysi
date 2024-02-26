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
        Schema::create('app_variables', function (Blueprint $table) {
            $table->id();
            $table->string('config', 100);
            $table->string('value', 1028);
            $table->string('display_value')->nullable();
            $table->string('format_style')->nullable();
            $table->string('title', 150)->nullable();
            $table->string('description', 300)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_variables');
    }
};
