<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id('idmenu'); // Primary key dengan auto increment
            $table->integer('idkategori'); // Kolom kategori
            $table->string('menu'); // Nama menu
            $table->string('gambar'); // Path atau URL gambar
            $table->string('deskripsi'); // Deskripsi menu
            $table->float('harga'); // Kolom harga tanpa primary key
            $table->timestamps(); // Kolom created_at dan updated_at
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
