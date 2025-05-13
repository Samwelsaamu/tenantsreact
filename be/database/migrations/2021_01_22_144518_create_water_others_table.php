<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWaterOthersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('water_others', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('DateTrans');
            $table->string('Description')->unique();
            $table->string('Month');
            $table->string('Previous')->default('0.00');
            $table->string('Current')->default('0.00');
            $table->string('Cost')->default('0.00');
            $table->string('Units')->default('0.00');
            $table->string('Total')->default('0.00');
            $table->string('Total_OS')->default('0.00');
            $table->string('Tenant');
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
        Schema::dropIfExists('water_others');
    }
}
