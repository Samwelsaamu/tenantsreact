<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHousesVacant extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE VIEW houses_vacant AS
            SELECT
                id,Housename,Plot
            FROM
            houses
            WHERE
                Status='Vacant'
            ORDER BY
                id DESC;
            -- Add any additional join or filtering logic
        ");
        // Schema::create('houses_vacant', function (Blueprint $table) {
        //     $table->id();
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
        Schema::dropIfExists('houses_vacant');
    }
}
