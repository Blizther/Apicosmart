<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TareaPendiente extends Model
{
    use HasFactory;
    //ingresar el nombre de la tabla

    protected $table = 'tareapendiente';
    //definir la llave primaria
    protected $primaryKey = 'idTareaPendiente';
    //definir los campos que se pueden asignar de forma masiva
    protected $fillable = [
        'idTareaPendiente',
        'idUser',
        'idColmena',
        'titulo',
        'descripcion',
        'prioridad',
        'estado',
        'fechaInicio',
        'fechaFin',
        'creadoPor',
        'tipo',
        'eliminado',
    ];
    public $timestamps = false;
    protected $casts = [
        'updated_at' => 'timestamp',
        'created_at' => 'timestamp',
        'fechaRecordatorio' => 'timestamp',
    ];

     // Scope para solo tareas "activas" (no eliminadas)
    public function scopeActivas($query)
    {
        return $query->where('eliminado', 'activo');
    }

    // Relación con usuario (ajusta el modelo si el tuyo no se llama Usuario)
    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUser');
    }


    // Relación con colmena (ajusta el modelo si el tuyo no se llama Colmena)
    public function colmena()
    {
        return $this->belongsTo(Colmena::class, 'idColmena');
    }
    public function apiario()
    {
        return $this->belongsTo(Apiario::class, 'idApiario');
    }


}
