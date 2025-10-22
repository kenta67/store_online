<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'producto';
    protected $primaryKey = 'idproducto';

    public $timestamps = false;

    protected $fillable = [
        'idmarca',
        'idcategoria',
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'imagen_url',
        'estado',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'stock' => 'integer',
        'estado' => 'boolean',
    ];

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'idmarca', 'idmarca');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'idcategoria', 'idcategoria');
    }

    public function carritos()
    {
        return $this->hasMany(Carrito::class, 'idproducto', 'idproducto');
    }

    /**
     * Obtener la URL de la imagen
     */
    public function getImagenUrlAttribute($value)
    {
        if ($value && !str_starts_with($value, 'http')) {
            return asset('storage/' . $value);
        }
        return $value ?: 'https://dummyimage.com/450x300/dee2e6/6c757d.jpg';
    }




    



    /**
     * Scope para productos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 1);
    }

    /**
     * Scope para productos con stock
     */
    public function scopeConStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Scope para filtrar por marca
     */
    public function scopePorMarca($query, $marcaId)
    {
        if ($marcaId) {
            return $query->where('idmarca', $marcaId);
        }
        return $query;
    }

    /**
     * Scope para filtrar por categoría
     */
    public function scopePorCategoria($query, $categoriaId)
    {
        if ($categoriaId) {
            return $query->where('idcategoria', $categoriaId);
        }
        return $query;
    }

    /**
     * Scope para búsqueda
     */
    public function scopeBuscar($query, $termino)
    {
        if ($termino) {
            return $query->where(function($q) use ($termino) {
                $q->where('nombre', 'LIKE', "%{$termino}%")
                  ->orWhere('descripcion', 'LIKE', "%{$termino}%");
            });
        }
        return $query;
    }
}

