<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('other_high_school_1_not_listed')->after('other_high_school_1')->nullable();
            $table->string('other_high_school_2_not_listed')->after('other_high_school_2')->nullable();
            $table->string('other_high_school_3_not_listed')->after('other_high_school_3')->nullable();
            $table->string('other_high_school_4_not_listed')->after('other_high_school_4')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('other_high_school_1_not_listed');
            $table->dropColumn('other_high_school_2_not_listed');
            $table->dropColumn('other_high_school_3_not_listed');
            $table->dropColumn('other_high_school_4_not_listed');
        });
    }
};
