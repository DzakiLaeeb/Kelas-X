<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';
    protected $primaryKey = 'idmenu';
    
    protected $fillable = [
        'nama',
        'kategori_id',
        'deskripsi',
        'harga',
        'gambar',
        'badge',
        'badge_type'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function getGambarAttribute($value)
    {
        if (!$value) {
            return null;
        }
        
        // Jika path tidak dimulai dengan 'http' atau 'https', tambahkan storage path
        if (!str_starts_with($value, 'http')) {
            return 'storage/' . $value;
        }
        
        return $value;
    }
}




