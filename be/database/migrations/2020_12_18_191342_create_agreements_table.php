<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgreementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agreements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('Plot');
            $table->integer('House');
            $table->integer('Tenant');
            $table->date('DateAssigned');
            $table->date('DateTo')->nullable();
            $table->date('DateVacated')->nullable();
            $table->string('Month');
            $table->integer('Deposit');
            $table->integer('Arrears');
            $table->integer('Damages');
            $table->integer('Transaction');
            $table->integer('Refund');
            $table->integer('Phone');
            $table->string('UniqueID')->unique();
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
        Schema::dropIfExists('agreements');
    }
}
