<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispositivo extends Model
{
    protected $table = 'dispositivos';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'idUser', 'serial', 'api_key', 'nombre', 'estado',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUser', 'id');
    }

    public function lecturas()
    {
        return $this->hasMany(LecturaSensor::class, 'dispositivo_id', 'id');
    }
}
