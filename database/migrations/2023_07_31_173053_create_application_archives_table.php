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
        Schema::create('application_archives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('application_id');
            $table->timestamps();

            $table->json('application')->nullable();
            $table->json('student')->nullable();
            $table->json('addresses')->nullable();
            $table->json('parents')->nullable();
            $table->json('parents_matrix')->nullable();
            $table->json('siblings')->nullable();
            $table->json('siblings_matrix')->nullable();
            $table->json('legacy')->nullable();
            $table->json('activities')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_archives');
    }
};
