<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = [
        'idorder',
        'idmenu',
        'kategori_id',
        'nama',
        'deskripsi',
        'jumlah',
        'harga',
        'gambar',
        'badge',
        'badge_type'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'idorder', 'idorder');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'idmenu', 'idmenu');
    }
}








