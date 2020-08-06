<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSewaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sewa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->index('user_id_foreign');
            $table->integer('penyewa_id')->index('penyewa_id_foreign');
            $table->integer('barang_id')->index('barang_id_foreign');
            $table->string('tanggal_sewa', 100);
            $table->string('tanggal_pengembalian', 100);
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
        Schema::dropIfExists('sewa');
    }
}
