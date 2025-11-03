<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspeccionColmena extends Model
{
    use HasFactory;

     protected $table = 'inspeccioncolmena';
<<<<<<< HEAD
    protected $primaryKey = 'id';
    protected $fillable = ['idColmena','idUser','estadoOperativo', 'temperamento', 'intensidadImportacion','estadoReyna','pobalcion', 'celdasReales','patronPostura', 'enfermedadPlaga','reservaPolen','reservaMiel','notas'];
=======
    protected $primaryKey = 'idColmena';
    protected $fillable = ['idColmena','idUser','estadoOperativo', 'temperamento', 'intensidadImportacion','estadoReyna','poblacion', 'celdasReales','patronPostura', 'enfermedadPlaga','reservaPolen','reservaMiel','notas'];
>>>>>>> origin/pablo

    public $timestamps =false;
    protected $casts=[
        'fechaCreacion'=> 'datetime', 
        'fechaActualizar'=> 'datetime',  
    ];
<<<<<<< HEAD
    public function colmena()
{
    return $this->belongsTo(Colmena::class, 'idColmena', 'idColmena');
}

=======
    // Relación: una inspección pertenece a una colmena
    public function colmena()
    {
        return $this->belongsTo(Colmena::class, 'idColmena', 'idColmena');
    }
>>>>>>> origin/pablo
}
