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
        'rol',
        'idusuario'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Devuelve el ID del "dueño" lógico.
     * - Si es colaborador: el id del usuario que lo creó (idusuario).
     * - Si es usuario o admin: su propio id.
     */
    public function ownerId()
    {
        if ($this->rol === 'colaborador' && $this->idusuario) {
            return $this->idusuario;
        }

        return $this->id;
    }

    // Relación: un usuario tiene muchos productos
    public function productos()
    {
        if (auth()->check() && auth()->user()->rol === 'colaborador') {
            return $this->hasMany(Producto::class, 'idUser', 'idusuario');
        } else {
            return $this->hasMany(Producto::class, 'idUser', 'id');
        }
    }

    // Relación: un usuario tiene muchas ventas
    public function ventas()
    {
        if (auth()->check() && auth()->user()->rol === 'colaborador') {
            return $this->hasMany(Venta::class, 'idUser', 'idusuario');
        } else {
            return $this->hasMany(Venta::class, 'idUser', 'id');
        }
    }

    // Relación: un usuario tiene muchos apiarios
    public function apiarios()
    {
        if (auth()->check() && auth()->user()->rol === 'colaborador') {
            return $this->hasMany(Apiario::class, 'creadoPor', 'idusuario');
        } else {
            return $this->hasMany(Apiario::class, 'creadoPor', 'id');
        }
    }

    // Colmenas activas (vía apiarios)
    public function colmenasActivas()
    {
        if (auth()->check() && auth()->user()->rol === 'colaborador') {
            return $this->hasManyThrough(
                Colmena::class,
                Apiario::class,
                'creadoPor', // Foreign key en Apiario
                'idApiario', // Foreign key en Colmena
                'idusuario', // Local key en User (dueño)
                'idApiario'  // Local key en Apiario
            )->where('colmena.estado', 'activo');
        } else {
            return $this->hasManyThrough(
                Colmena::class,
                Apiario::class,
                'creadoPor',
                'idApiario',
                'id',
                'idApiario'
            )->where('colmena.estado', 'activo');
        }
    }

    // lista de colmenas con estado activo y nombre del apiario
    public function colmenasApiario()
    {
        // Usamos mismo criterio que en colmenasActivas
        if ($this->rol === 'colaborador' && $this->idusuario) {
            return $this->hasManyThrough(
                Colmena::class,
                Apiario::class,
                'creadoPor',
                'idApiario',
                'idusuario',
                'idApiario'
            )
            ->where('colmena.estado', 'activo')
            ->where('apiario.estado', 'activo');
        }

        return $this->hasManyThrough(
            Colmena::class,
            Apiario::class,
            'creadoPor',
            'idApiario',
            'id',
            'idApiario'
        )
        ->where('colmena.estado', 'activo')
        ->where('apiario.estado', 'activo');
    }

    // Inspecciones
    public function inspecciones()
    {
        // Queremos que el colaborador vea las inspecciones del dueño
        if ($this->rol === 'colaborador' && $this->idusuario) {
            return $this->hasMany(InspeccionColmena::class, 'idUser', 'idusuario');
        }

        return $this->hasMany(InspeccionColmena::class, 'idUser', 'id');
    }

    public function cantidadInspecciones()
    {
        return $this->inspecciones()->count();
    }

    // Última inspección (del dueño, si es colaborador)
    public function ultimaInspeccion()
    {
        // Si es colaborador, miramos la última inspección del usuario dueño
        if ($this->rol === 'colaborador' && $this->idusuario) {
            return $this->hasOne(InspeccionColmena::class, 'idUser', 'idusuario')
                        ->latestOfMany();
        }

        // Si es usuario o admin, la última inspección propia
        return $this->hasOne(InspeccionColmena::class, 'idUser', 'id')
                    ->latestOfMany();
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
        if (auth()->check() && auth()->user()->rol === 'colaborador') {
            return $this->hasMany(TareaPendiente::class, 'creadoPor', 'idusuario');
        } else {
            return $this->hasMany(TareaPendiente::class, 'creadoPor', 'id');
        }
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

    // Relación: un usuario tiene muchos dispositivos
    public function dispositivos()
    {
        if (auth()->check() && auth()->user()->rol === 'colaborador') {
            return $this->hasMany(Dispositivo::class, 'idUser', 'idusuario');
        } else {
            return $this->hasMany(Dispositivo::class, 'idUser', 'id');
        }
    }

    //relación: un usuario tiene muchas ventas realizadas
    public function ventasRealizadas()
    {
        if (auth()->check() && auth()->user()->rol === 'colaborador') {
            return $this->hasMany(Venta::class, 'idUser', 'idusuario');
        } else {
            return $this->hasMany(Venta::class, 'idUser', 'id');
        }
    }
}
