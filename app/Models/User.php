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
    //ultima inspección realizada por el usuario
    public function ultimaInspeccion()
    {
        return $this->hasOne(InspeccionColmena::class, 'idUser', 'id')->latestOfMany();
    }
    public function getUltimaInspeccionFechaAttribute()
    {
        return $this->ultimaInspeccion?->fechaCreacion?->format('d/m/Y');
    }
    public function cantidadProductosActivos()
    {
        return $this->productos()->where('estado', 1)->count();
    }
    // Relación: un usuario tiene muchas tareas pendientes
    public function tareasPendientes()
    {
        return $this->hasMany(TareaPendiente::class, 'creadoPor', 'id');
    }
    //lista de tareas pendientes sin completar
    public function tareasPendientesSinCompletar()
    {
        return $this->tareasPendientes()->where('estado', 'pendiente')->get();
    }
    //lista de todas las tareas pendientes
    public function todasTareasPendientes()
    {
        return $this->tareasPendientes()->get();    
    }
}
