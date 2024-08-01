<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRekamLukaBakar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rekam_luka_bakar', function (Blueprint $table) {
            $table->id();
            $table->string('no_rekam');
            $table->string('tgl_rekam');
            $table->integer('pasien_id')->unsigned();
            $table->string('berat_badan');
            $table->string('persen_luka_bakar');
            $table->string('cairan')->nullable();
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('rekam_luka_bakar');
    }
}
