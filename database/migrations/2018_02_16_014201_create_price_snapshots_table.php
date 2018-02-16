<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceSnapshotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_snapshots', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('coin_id')->index();
            $table->float('btc_price')->nullable()->index();
            $table->float('usd_price', 20, 2)->nullable()->index();
            $table->float('percent_change_btc')->nullable()->index();
            $table->float('percent_change_usd')->nullable()->index();
            $table->float('market_cap_btc', 20, 8)->nullable();
            $table->float('market_cap_usd', 20, 8)->nullable();
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
        Schema::dropIfExists('price_snapshots');
    }
}
