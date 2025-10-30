<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispositivo extends Model
{
    protected $table = 'dispositivos';
    protected $fillable = ['idUser', 'dispositivo_fabricado_id', 'nombre', 'estado', 'idColmena'];

    public function fabricado()
    {
        return $this->belongsTo(DispositivoFabricado::class, 'dispositivo_fabricado_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'idUser');
    }
    public function lecturas()
    {
        return $this->hasMany(LecturaSensor::class, 'dispositivo_id');
    }
    public function colmena()
    {
        return $this->belongsTo(Colmena::class, 'idColmena', 'idColmena');
    }
    
}
