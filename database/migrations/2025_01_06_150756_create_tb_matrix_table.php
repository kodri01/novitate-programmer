<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbMatrixTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_matrix', function (Blueprint $table) {
            $table->id();
            $table->integer('panjang'); 
            $table->integer('tinggi'); 
            $table->timestamps();

            $table->unique(['panjang', 'tinggi']); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_matrix');
    }
}
