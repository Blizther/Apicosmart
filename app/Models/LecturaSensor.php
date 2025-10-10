<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LecturaSensor extends Model
{
    protected $table = 'lecturas_sensores';
    protected $fillable = ['dispositivo_id', 'ts', 'humedad', 'peso', 'temperatura'];
    public function dispositivo()
    {
        return $this->belongsTo(Dispositivo::class, 'dispositivo_id');
    }
}
