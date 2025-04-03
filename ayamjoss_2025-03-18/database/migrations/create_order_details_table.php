<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->string('idorder');
            $table->unsignedBigInteger('idmenu');
            $table->integer('jumlah');
            $table->decimal('harga', 10, 2);
            $table->timestamps();

            $table->foreign('idorder')
                  ->references('idorder')
                  ->on('orders')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_details');
    }
};