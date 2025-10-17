<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspeccionColmena extends Model
{
    use HasFactory;

     protected $table = 'inspeccioncolmena';
    protected $primaryKey = 'idColmena';
    protected $fillable = ['idColmena','idUser','estadoOperativo', 'temperamento', 'intensidadImportacion','estadoReyna', 'celdasReales','patronPostura', 'enfermedadPlaga','reservaPolen','reservaMiel','notas'];

    public $timestamps =false;
    protected $casts=[
        'fechaCreacion'=> 'timestamp', 
        'fechaActualizar'=> 'timestamp',  
    ];
}
