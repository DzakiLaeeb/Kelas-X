<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'nama_produk',
        'harga',
        'stok',
        'gambar',
        'deskripsi',
        'category_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}


