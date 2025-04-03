<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id('idmenu');
            $table->foreignId('kategori_id')
                  ->constrained('kategoris')
                  ->onDelete('cascade');
            $table->string('nama');
            $table->string('deskripsi');
            $table->decimal('harga', 10, 2);
            $table->string('gambar');
            $table->string('badge')->nullable();
            $table->string('badge_type')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};

