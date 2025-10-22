<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleado';
    protected $primaryKey = 'idempleado';

    // Deshabilitar timestamps de Laravel
    public $timestamps = false;

    protected $fillable = ['idusuario', 'idrol'];

    public function user()
    {
        return $this->belongsTo(User::class, 'idusuario', 'idusuario');
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'idrol', 'idrol');
    }
}