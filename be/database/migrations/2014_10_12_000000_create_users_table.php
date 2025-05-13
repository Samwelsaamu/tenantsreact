<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Fullname');
            $table->string('Username')->unique();
            $table->string('email')->unique();
            $table->unsignedInteger('Phone');
            $table->unsignedInteger('Idno');
            $table->enum('Status',['Active','Reset','Disabled','New'])->defualt('New');
            $table->string('Userrole');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('last_login_ip')->nullable();
            $table->string('user_online')->default('0');
            $table->timestamp('two_factor_verified')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('current_login_at')->nullable();
            $table->timestamp('current_activity_at')->nullable();
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
        Schema::dropIfExists('users');
    }
}
