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
        Schema::create('annual_work_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('annual_work_id')->constrained();
            $table->foreignId('student_id')->constrained();
            $table->decimal('cote')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('annual_work_student');
    }
};
