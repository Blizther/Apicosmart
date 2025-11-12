<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspeccionColmena extends Model
{
    use HasFactory;

    protected $table = 'inspeccioncolmena';
    // Si tu PK es 'id' puedes omitir esta línea porque es el default:
    // protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'idColmena',
        'idUser',
        'estadoOperativo',
        'temperamento',
        'intensidadImportacion',
        'estadoReyna',
        'poblacion',         // corregido (antes había 'pobalcion')
        'celdasReales',
        'patronPostura',
        'enfermedadPlaga',
        'reservaPolen',
        'reservaMiel',
        'notas',
    ];

    protected $casts = [
        'fechaCreacion'   => 'datetime',
        'fechaActualizar' => 'datetime',
    ];

    // Relación: una inspección pertenece a una colmena
    public function colmena()
    {
        return $this->belongsTo(Colmena::class, 'idColmena', 'idColmena');
    }
}
