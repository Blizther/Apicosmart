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
}
