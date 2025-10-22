<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categoria';
    protected $primaryKey = 'idcategoria';

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
        return $this->hasMany(Producto::class, 'idcategoria', 'idcategoria');
    }

    /**
     * Scope para categorías activas
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', 1);
    }

    /**
     * Obtener categorías con conteo de productos
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