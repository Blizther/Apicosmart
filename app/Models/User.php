<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'nombre',
        'primerApellido',
        'segundoApellido',
        'email',
        'telefono',
        'nombreUsuario',
        'password',
        'rol'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relación: un usuario tiene muchos productos
    public function productos()
    {
        return $this->hasMany(Producto::class, 'idUser', 'id');
    }

    // Relación: un usuario tiene muchas ventas
    public function ventas()
    {
        return $this->hasMany(Venta::class, 'idUser', 'id');
    }
    // Relación: un usuario tiene muchos apiarios
    public function apiarios()
    {
        return $this->hasMany(Apiario::class, 'creadoPor', 'id');
    }
    public function colmenasActivas()
    {
        return $this->hasManyThrough(
            Colmena::class,
            Apiario::class,
            'creadoPor', // Foreign key on Apiario table
            'idApiario', // Foreign key on Colmena table
            'id',        // Local key on User table
            'idApiario'  // Local key on Apiario table
        )->where('colmena.estado', 'activo');
    }
    public function inspecciones()
    {
        return $this->hasMany(InspeccionColmena::class, 'idUser', 'id');
    }
    public function cantidadInspecciones()
    {
        return $this->inspecciones()->count();
    }
}
