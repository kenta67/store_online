<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'cliente';
    protected $primaryKey = 'idcliente';

    // Deshabilitar timestamps de Laravel
    public $timestamps = false;

    protected $fillable = ['idusuario'];

    public function user()
    {
        return $this->belongsTo(User::class, 'idusuario', 'idusuario');
    }

    public function direcciones()
    {
        return $this->hasMany(Direccion::class, 'idcliente', 'idcliente');
    }

    public function compras()
    {
        return $this->hasMany(Compra::class, 'idcliente', 'idcliente');
    }
}

