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
        Schema::create('family_dynamics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained();
            $table->string('model_type');
            $table->string('model_id');
            $table->string('related_type');
            $table->string('related_id');
            $table->string('relationship')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_dynamics');
    }
};
