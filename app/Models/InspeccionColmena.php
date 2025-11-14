<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspeccionColmena extends Model
{
    use HasFactory;

    protected $table = 'inspeccioncolmena';
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'idColmena',
        'idUser',

        // ENUM oficiales
        'temperamento',
        'intensidadImportacion',
        'poblacion',
        'estadoReina',
        'celdasReales',
        'reservaMiel',
        'reservaPolen',
        'patronPostura',
        'enfermedadPlaga',
        'estadoOperativo',

        // TEXT
        'acciones_tomadas',
        'notas',

        // Foto (varchar 255)
        'evidencia_foto',

        // Fechas
        'fechaInspeccion',
        'horaInspeccion',
        'fechaCreacion',
        'fechaActualizacion',
    ];

    protected $casts = [
        'fechaInspeccion'   => 'date',
        'fechaCreacion'     => 'datetime',
        'fechaActualizacion'=> 'datetime',
    ];

    // Relación: una inspección pertenece a una colmena
    public function colmena()
    {
        return $this->belongsTo(Colmena::class, 'idColmena', 'idColmena');
    }

    // Relación: usuario que creó la inspección
    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUser', 'id');
    }
}
