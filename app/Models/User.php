<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuario';
    protected $primaryKey = 'idusuario';

    // Deshabilitar timestamps de Laravel
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellido_p',
        'apellido_m',
        'telefono',
        'documento',
        'correo',
        'clave',
        'estado',
    ];

    protected $hidden = [
        'clave',
    ];

    // Especificar el campo de fecha personalizado
    const CREATED_AT = 'fecharegistro';
    const UPDATED_AT = null; // No tenemos campo de actualización

    public function getAuthPassword()
    {
        return $this->clave;
    }

    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'idusuario', 'idusuario');
    }

    public function empleado()
    {
        return $this->hasOne(Empleado::class, 'idusuario', 'idusuario');
    }

    /**
     * Get the user's full name
     */
    public function getNombreCompletoAttribute()
    {
        return $this->nombre . ' ' . $this->apellido_p . ($this->apellido_m ? ' ' . $this->apellido_m : '');
    }

    /**
     * Boot method para establecer fecharegistro automáticamente
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->fecharegistro = $model->fecharegistro ?: now();
        });
    }
}