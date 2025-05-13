<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Plotname')->unique();
            $table->string('Plotarea');
            $table->string('Plotcode')->unique();
            $table->string('Plotaddr');
            $table->string('Plotdesc');
            $table->enum('Waterbill',['Monthly','None'])->default('None');
            $table->enum('Deposit',['Once','None'])->default('None');
            $table->enum('Waterdeposit',['Once','None'])->default('None');
            $table->enum('Outsourced',['Yes','None'])->default('None');
            $table->enum('Garbage',['Monthly','None'])->default('None');
            $table->enum('Kplcdeposit',['Once','None'])->default('None');
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
        Schema::dropIfExists('properties');
    }
}
