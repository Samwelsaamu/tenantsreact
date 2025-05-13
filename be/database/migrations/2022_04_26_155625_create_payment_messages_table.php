<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('payment_messages', function (Blueprint $table) {
        //     $table->bigIncrements('id');
        //     $table->bigInteger('plot')->nullable();
        //     $table->bigInteger('house');
        //     $table->bigInteger('tenant')->nullable();
        //     $table->integer('user')->nullable();
        //     $table->string('Month');
        //     $table->string('Message');
        //     $table->string('To');
        //     $table->string('Status');
        //     $table->string('Cost');
        //     $table->string('Code');
        //     $table->string('MessageId')->unique();
        //     $table->string('PatchNo')->nullable();
        //     $table->string('msgtype')->nullable();
        //     $table->timestamp('DateSent');
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
        Schema::dropIfExists('payment_messages');
    }
}
