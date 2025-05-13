<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWaterMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('water_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('house');
            $table->integer('user')->nullable();
            $table->string('To');
            $table->string('Status');
            $table->string('Cost');
            $table->string('Code');
            $table->string('MessageId')->unique();
            $table->string('PatchNo');
            $table->timestamp('DateSent');
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
        Schema::dropIfExists('water_messages');
    }
}
