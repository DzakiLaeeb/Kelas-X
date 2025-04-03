<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pelanggan extends Authenticatable
{
    use HasFactory;

    protected $table = 'pelanggans';
    protected $primaryKey = 'idpelanggan';
    
    protected $fillable = [
        'pelanggan',
        'jeniskelamin',
        'alamat',
        'telp',
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Tambahkan relasi dengan orders
    public function orders()
    {
        return $this->hasMany(Order::class, 'idpelanggan', 'idpelanggan');
    }
}




