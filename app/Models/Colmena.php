<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Colmena extends Model
{
    use HasFactory;
     protected $table = 'colmena';
    protected $primaryKey = 'idColmena';
    protected $fillable = ['idColmena', 'codigo', 'estado','creadoPor', 'idReina','cantidadMarco','modelo', 'idApiario'];

    public $timestamps =false;
    protected $casts=[
        'fechaInstalacionFisica'=> 'timestamp', 
        'fechaCreacion'=> 'timestamp', 
        'fechaModificacion'=> 'timestamp', 
    ];

     // Relación: una colmena pertenece a un apiario
    public function apiario()
    {
        return $this->belongsTo(Apiario::class, 'idApiario', 'idApiario');
    }
}
