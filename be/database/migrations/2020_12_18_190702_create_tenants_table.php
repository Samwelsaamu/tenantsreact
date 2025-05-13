<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Fname');
            $table->string('Oname')->nullable();
            $table->enum('Gender',['Male','Female','Other'])->default('Other');
            $table->string('IDno')->unique();
            $table->string('HudumaNo')->nullabe();
            $table->integer('Phone');
            $table->string('Email')->nullabe();
            $table->enum('Status',['Assigned','New','Vacated','Reassigned','Transferred','Other'])->defult('New');
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
        Schema::dropIfExists('tenants');
    }
}
