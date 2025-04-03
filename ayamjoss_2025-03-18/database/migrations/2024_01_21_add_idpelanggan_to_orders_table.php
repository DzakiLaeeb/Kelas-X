<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedInteger('idpelanggan')->after('idorder');
            
            $table->foreign('idpelanggan')
                  ->references('idpelanggan')
                  ->on('pelanggans')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['idpelanggan']);
            $table->dropColumn('idpelanggan');
        });
    }
};
