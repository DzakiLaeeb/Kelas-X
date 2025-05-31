<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_kategori';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'kategori',
        'keterangan'
    ];

    /**
     * Get the menu items for the category.
     */
    public function menus()
    {
        return $this->hasMany(Menu::class, 'idkategori', 'id_kategori');
    }
}