<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alimentacion extends Model
{
    use HasFactory;
        protected $table = 'alimentacion';
    protected $primaryKey = 'idAlimentacion';
    protected $fillable = ['idAlimentacion', 'tipoAlimento', 'cantidad', 'unidadMedida', 'motivo', 'fechaSuministracion', 'descripcion', 'idColmena', 'idUsuario'];
    public $timestamps =false;
    protected $casts=[
        'fechaSuministracion'=> 'timestamp', 
    ];
    // Relación: una alimentación pertenece a una colmena
    public function colmena()
    {
        return $this->belongsTo(Colmena::class, 'idColmena', 'idColmena');
    }
}
