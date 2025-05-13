<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('payment_dates', function (Blueprint $table) {
        //     $table->bigIncrements('id');
        //     $table->bigInteger('Plot')->nullable();
        //     $table->bigInteger('House');
        //     $table->bigInteger('Tenant')->nullable();
        //     $table->float('Amount',10,2)->default('0.00');
        //     $table->string('Month');
        //     $table->date('Date_Transacted');
        //     $table->string('Through')->default('Uploaded');
        //     $table->string('PId');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_dates');
    }
}
