<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHousesOccupiedTotals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE VIEW houses_occupied_totals AS
            SELECT
                COUNT(*) AS Occupied
            FROM
                `houses`
            WHERE
            STATUS= 'Occupied';
            -- Add any additional join or filtering logic
        ");
        // Schema::create('houses_occupied_totals', function (Blueprint $table) {
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
        Schema::dropIfExists('houses_occupied_totals');
    }
}
