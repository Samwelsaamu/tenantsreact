<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('Plot');
            $table->string('Month');
            $table->integer('Rent');
            $table->integer('Garbage');
            $table->integer('KPLC');
            $table->integer('HseDeposit');
            $table->integer('Water');
            $table->integer('Lease');
            $table->integer('Waterbill');
            $table->integer('Equity');
            $table->integer('Cooperative');
            $table->integer('Others');
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
        Schema::dropIfExists('stats');
    }
}
