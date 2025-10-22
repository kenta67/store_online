<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $table = 'compra';
    protected $primaryKey = 'idcompra';

    public $timestamps = false;

    protected $fillable = [
        'idcliente',
        'iddireccion',
        'idmetodo',
        'totalproducto',
        'montototal',
        'enviado',
        'fecharegistro',
    ];

    protected $casts = [
        'montototal' => 'decimal:2',
        'enviado' => 'boolean',
        'fecharegistro' => 'datetime',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idcliente', 'idcliente');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleCompra::class, 'idcompra', 'idcompra');
    }

    public function direccion()
    {
        return $this->belongsTo(Direccion::class, 'iddireccion', 'iddireccion');
    }

    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class, 'idmetodo', 'idmetodo');
    }
}

