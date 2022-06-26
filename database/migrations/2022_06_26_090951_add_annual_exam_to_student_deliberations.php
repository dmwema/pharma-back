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
        Schema::table('student_deliberations', function (Blueprint $table) {
            $table->integer('exam')->nullable();
            $table->integer('annual')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_deliberations', function (Blueprint $table) {
            $table->dropColumn('exam');
            $table->dropColumn('annual');
        });
    }
};
