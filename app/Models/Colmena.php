<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colmena extends Model
{
    use HasFactory;
     protected $table = 'colmena';
    protected $primaryKey = 'idColmena';
    protected $fillable = ['idColmena', 'codigo', 'estado','creadoPor', 'idReina','cantidadMarco'];

    public $timestamps =false;
    protected $casts=[
        'fechaInstalacion'=> 'timestamp', 
        'fechaFabricacion'=> 'timestamp', 
        'fechaActualizar'=> 'timestamp', 
    ];

     // Relación: una colmena pertenece a un apiario
    public function apiario()
    {
        return $this->belongsTo(Apiario::class, 'idApiario', 'idApiario');
    }
}
