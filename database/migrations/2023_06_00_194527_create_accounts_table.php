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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_name', 255);
            $table->string('phone', 10)->nullable();
            $table->string('sf_account_id', 18)->nullable();
            $table->string('record_type_id', 18)->nullable();
            $table->timestamps();
        });

        Schema::table('users', function(Blueprint $table){
            $table->unsignedBigInteger('account_id')->nullable()->after('id');
            $table->boolean('is_primary')->nullable()->after('account_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');

        Schema::table('users', function(Blueprint $table){
            $table->dropColumn('account_id');
            $table->dropColumn('is_primary');
        });
    }
};
