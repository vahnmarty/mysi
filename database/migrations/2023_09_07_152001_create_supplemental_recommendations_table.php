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
        Schema::create('supplemental_recommendations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('child_id');
            $table->unsignedBigInteger('application_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('sf_recommendation_id')->nullable();
            $table->string('requester_name')->nullable();
            $table->string('requester_email')->nullable();
            $table->string('recommender_first_name')->nullable();
            $table->string('recommender_last_name')->nullable();
            $table->string('recommender_email')->nullable();
            $table->string('message', 2250)->nullable();
            $table->string('relationship_to_student')->nullable();
            $table->string('years_known_student')->nullable();
            $table->string('recommendation', 2250)->nullable();
            $table->boolean('active')->default(true)->nullable();
            $table->date('date_requested')->nullable();
            $table->date('date_received')->nullable();
            $table->boolean('status')->nullable()->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplemental_recommendations');
    }
};
