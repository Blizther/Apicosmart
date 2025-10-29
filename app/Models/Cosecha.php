<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cosecha extends Model
{
    use HasFactory;
    //ingresar el nombre de la tabla
    protected $table = 'cosechamiel';
    //definir la llave primaria
    protected $primaryKey = 'idCosecha';
    //definir los campos que se pueden asignar de forma masiva
    protected $fillable = [
        'idCosecha',
        'idUsuario',
        'peso',
        'estadoMiel',
        'fechaCosecha',
        'idColmena',
        'observaciones'
    ];

    //relacion una cosecha pertenece a una colmena
    public function colmena()
    {
        return $this->belongsTo(Colmena::class, 'idColmena', 'idColmena');  

    }
    //relacion una cosecha pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUsuario', 'id');    
    }
}