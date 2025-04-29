<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisposisiRmTable extends Migration
{
    public function up()
    {
        Schema::create('disposisi_rm', function (Blueprint $table) {
            $table->id();
            $table->string('disposisi');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('disposisi_rm');
    }
}
