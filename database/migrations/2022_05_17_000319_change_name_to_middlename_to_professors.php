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
        Schema::table('middlename_to_professors', function (Blueprint $table) {
            $table->renameColumn('name', 'middlename');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('middlename_to_professors', function (Blueprint $table) {
            $table->renameColumn('middlename', 'name');
        });
    }
};
