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
        Schema::create('family_directories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->string('si_family', 200)->nullable();
            $table->string('full_name', 200);
            $table->string('contact_type', 15);
            $table->year('graduation_year')->nullable();
            $table->string('personal_email', 100)->nullable();
            $table->string('mobile_phone', 11)->nullable();
            $table->string('home_address', 200)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_directories');
    }
};
