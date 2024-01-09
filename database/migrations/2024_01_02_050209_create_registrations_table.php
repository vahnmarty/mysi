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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->uuid('uuid');
            $table->unsignedBigInteger('child_id');
            $table->unsignedBigInteger('application_id');
            $table->string('sf_application_id', 18)->nullable();
            $table->string('sf_contact_id', 18)->nullable();
            $table->string('record_type_id', 18)->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
