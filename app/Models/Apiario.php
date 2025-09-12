<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apiario extends Model
{
    use HasFactory;

    protected $table = 'apiario';
    protected $primaryKey = 'idApiario';
    protected $fillable = ['idApiario', 'nombre', 'latitud', 'longitud', 'departamento', 'municipio', 'estado','creadoPor'];

    public $timestamps =false;
    protected $casts=[
        'fechaCreacion'=> 'timestamp', 
        'fechaActualizar'=> 'timestamp', 
    ];

     // Relación: un apiario tiene muchas colmenas
    // App\Models\Apiario.php
    public function colmenas()
    {
        return $this->hasMany(Colmena::class, 'idApiario', 'idApiario');
    }

}
