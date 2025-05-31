<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'menu';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_menu';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'menu',
        'id_kategori',
        'gambar',
        'harga',
        'deskripsi',
        'tersedia'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'harga' => 'decimal:2',
        'tersedia' => 'boolean',
    ];

    /**
     * Get the category that owns the menu.
     */
    public function kategori()
    {
        return $this->belongsTo(Category::class, 'id_kategori', 'id_kategori');
    }

    /**
     * Get the full URL for the menu image.
     *
     * @return string|null
     */
    public function getGambarUrlAttribute()
    {
        if (!$this->gambar) {
            return null;
        }
        
        return url('uploads/' . $this->gambar);
    }

    /**
     * Get the attributes that should be included in arrays.
     *
     * @return array
     */
    protected $appends = ['gambar_url'];
}
