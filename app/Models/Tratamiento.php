<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Tratamiento extends Model
{
    use HasFactory;
    protected $table = 'tratamiento';
    protected $primaryKey = 'idTratamiento';
    protected $fillable = ['idTratamiento', 'problemaTratado', 'descripcion', 'fechaAdministracion','idUsuario', 'idColmena'];
    public $timestamps =false;
    protected $casts=[
        'fechaCreacion'=> 'timestamp', 
    ];
    // Relación: un tratamiento pertenece a una colmena
    public function colmena()
    {
        return $this->belongsTo(Colmena::class, 'idColmena', 'idColmena');
    }
    // Relación: un tratamiento pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUsuario', 'id');    
    }
}
