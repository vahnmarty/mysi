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
        Schema::create('legacies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->string('sf_legacy_id', 18)->nullable();
            $table->string('sf_application_id', 18)->nullable();
            $table->string('first_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->string('relationship_type', 20)->nullable();
            $table->year('graduation_year')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legacies');
    }
};
