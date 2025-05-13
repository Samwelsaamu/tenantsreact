<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('House');
            $table->integer('Tenant');
            $table->integer('Excess');
            $table->integer('Arrears');
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
            $table->integer('PaidUploaded');
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
        Schema::dropIfExists('payments');
    }
}
