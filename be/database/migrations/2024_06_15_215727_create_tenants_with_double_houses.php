<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsWithDoubleHouses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE VIEW tenants_with_double_houses AS
            SELECT
                tenants.id,
                COUNT(agreements.Tenant) AS Doubles
            FROM
                tenants,
                agreements
            WHERE
                tenants.id = agreements.Tenant && agreements.Month = 0
            GROUP BY
                tenants.id, agreements.Tenant
            HAVING 
                Doubles >1
            ORDER BY
                tenants.id DESC;
            -- Add any additional join or filtering logic
        ");

        // Schema::create('tenants_with_double_houses', function (Blueprint $table) {
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
        Schema::dropIfExists('tenants_with_double_houses');
    }
}
