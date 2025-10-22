<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    use HasFactory;

    protected $table = 'detallecompra';
    protected $primaryKey = 'iddetallecompra';

    public $timestamps = false;

    protected $fillable = [
        'idcompra',
        'idproducto',
        'cantidad',
        'total',
        'fecharegistro',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'fecharegistro' => 'datetime',
    ];

    public function compra()
    {
        return $this->belongsTo(Compra::class, 'idcompra', 'idcompra');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'idproducto', 'idproducto');
    }
}

