<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colmena extends Model
{
    use HasFactory;

    protected $table = 'colmena';
    protected $primaryKey = 'idColmena';
    protected $fillable = [
        'idColmena',
        'codigo',
        'estado',
        'creadoPor',
        'idReina',
        'cantidadMarco',
        'modelo',
        'idApiario'
    ];

    public $timestamps = false;

    protected $casts = [
        'fechaInstalacionFisica' => 'timestamp',
        'fechaCreacion'          => 'timestamp',
        'fechaModificacion'      => 'timestamp',
    ];

    // Relación: una colmena pertenece a un apiario
    public function apiario()
    {
        return $this->belongsTo(Apiario::class, 'idApiario', 'idApiario');
    }

    // NUEVO: scope para colmenas activas
    public function scopeActivas($query)
    {
        return $query->where('estado', 'activo');
    }

    // NUEVO: colmenas activas cuyo apiario también está activo
    public function scopeActivasConApiarioActivo($query)
    {
        return $query->where('estado', 'activo')
            ->whereHas('apiario', function ($q) {
                $q->where('estado', 'activo');
            });
    }

    // Relación: una colmena tiene muchas inspecciones
    public function inspecciones()
    {
        return $this->hasMany(InspeccionColmena::class, 'idColmena', 'idColmena');
    }

    // última inspección
    public function ultimaInspeccion()
    {
        return $this->hasOne(InspeccionColmena::class, 'idColmena', 'idColmena')->latestOfMany();
    }

    // Relación: una colmena tiene muchos tratamientos
    public function tratamientos()
    {
        return $this->hasMany(Tratamiento::class, 'idColmena', 'idColmena');
    }

    // Relación: una colmena tiene muchas alimentaciones
    public function alimentaciones()
    {
        return $this->hasMany(Alimentacion::class, 'idColmena', 'idColmena');
    }

    // Relación: una colmena tiene muchos dispositivos
    public function dispositivos()
    {
        return $this->hasMany(Dispositivo::class, 'idColmena', 'idColmena');
    }

    // Relación: una colmena tiene muchas cosechas
    public function cosechas()
    {
        return $this->hasMany(Cosecha::class, 'idColmena', 'idColmena');
    }
}
