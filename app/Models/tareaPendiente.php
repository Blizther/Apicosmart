<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class TareaPendiente extends Model
{
    use HasFactory;
     protected $table = 'tareapendiente';
    protected $primaryKey = 'idTareaPendiente';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaActualizacion'; // si no tienes campo de actualización
    protected $fillable = ['idTareaPendiente', 'descripcion', 'estado','fechaCreacion', 'fechaVencimiento', 'creadoPor'];

    public $timestamps =false;
    protected $casts=[
        'fechaCreacion'=> 'datetime', 
        'fechaVencimiento'=> 'datetime', 
    ];
    // Relación: una tarea pendiente pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'creadoPor', 'id');
    }
}