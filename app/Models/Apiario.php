<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apiario extends Model
{
    use HasFactory;

    protected $table = 'apiario';
    protected $primaryKey = 'idApiario';
    protected $fillable = [
        'idApiario',
        'nombre',
        'latitud',
        'longitud',
        'vegetacion',
        'altitud',
        'urlImagen',
        'estado',
        'creadoPor'
    ];

    public $timestamps = false;

    protected $casts = [
        'fechaCreacion'  => 'timestamp',
        'fechaActualizar'=> 'timestamp',
    ];

    // Relación: un apiario tiene muchas colmenas
    public function colmenas()
    {
        return $this->hasMany(Colmena::class, 'idApiario', 'idApiario');
    }

    // NUEVO: relación solo con colmenas activas (no rompe nada existente)
    public function colmenasActivas()
    {
        return $this->hasMany(Colmena::class, 'idApiario', 'idApiario')
            ->where('estado', 'activo');
    }

    // NUEVO: scope para consultar solo apiarios activos
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    public function cantidadColmnenasActivas()
    {
        return $this->colmenas()->where('estado', 'activo')->count();
    }

    // cantidad de colmenas con estado operativo enferma
    public function cantidadColmenasEnfermas()
    {
        return $this->colmenas()
            ->where('estadoOperativo', 'enferma')
            ->where('estado', 'activo')
            ->count();
    }

    // cantidad de colmenas con estado operativo activa y estado activo
    public function cantidadColmenasOperativaActiva()
    {
        return $this->colmenas()
            ->where('estadoOperativo', 'activa')
            ->where('estado', 'activo')
            ->count();
    }
}
