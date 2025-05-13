<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsOthersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments_others', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('Tenant');
            $table->integer('Excess');
            $table->integer('Arrears');
            $table->string('Month');
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
        Schema::dropIfExists('payments_others');
    }
}
