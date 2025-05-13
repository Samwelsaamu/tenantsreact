<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Bank');
            $table->date('Trans_Date');
            $table->date('Value_Date');
            $table->string('Bank_Ref_No');
            $table->string('Customer_Ref_No');
            $table->string('Description');
            $table->integer('Debit');
            $table->integer('Credit');
            $table->integer('House');
            $table->string('UniqTrans')->unique();
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
        Schema::dropIfExists('statements');
    }
}
