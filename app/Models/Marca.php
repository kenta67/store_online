<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $table = 'marca';
    protected $primaryKey = 'idmarca';

    public $timestamps = false;

    protected $fillable = [
        'descripcion',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class, 'idmarca', 'idmarca');
    }

    /**
     * Scope para marcas activas
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', 1);
    }

    /**
     * Obtener marcas con conteo de productos
     */
    public static function conConteoProductos()
    {
        return self::activas()
            ->withCount(['productos as total_productos' => function($query) {
                $query->where('estado', 1);
            }])
            ->orderBy('descripcion')
            ->get();
    }
}