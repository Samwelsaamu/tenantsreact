<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('houses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Housename')->unique();
            $table->string('PrevName')->nullable();
            $table->integer('plot');
            $table->integer('Rent')->default('0.00');
            $table->integer('Deposit')->default('0.00');
            $table->integer('Kplc')->default('0.00');
            $table->integer('Water')->default('0.00');
            $table->integer('Lease')->default('0.00');
            $table->integer('Garbage')->default('0.00');
            $table->string('Info');
            $table->enum('Status',['Occupied','Vacant','Maintainance','Caretaker'])->default('Vacant');
            $table->integer('DueDay')->default('1');
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
        Schema::dropIfExists('houses');
    }
}
