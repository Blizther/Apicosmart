<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspeccionColmena extends Model
{
    use HasFactory;

     protected $table = 'inspeccioncolmena';
    protected $primaryKey = 'idColmena';
    protected $fillable = ['idColmena', 'temperamento', 'intensidadImportacion','estadoReyna', 'seldasReales','patronPostura', 'enfermedadPlaga','reservaPolen','notas'];

    public $timestamps =false;
    protected $casts=[
        'fechaCreacion'=> 'timestamp', 
        'fechaActualizar'=> 'timestamp',  
    ];
}
