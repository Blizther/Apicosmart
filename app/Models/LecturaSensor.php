<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LecturaSensor extends Model
{
    protected $table = 'lecturas_sensores';
    protected $primaryKey = 'id';
    public $timestamps = true;
    
    protected $fillable = [
        'dispositivo_id', 'ts', 'humedad', 'peso', 'temperatura',
    ];

    protected $casts = [
        'ts' => 'datetime',
    ];

    public function dispositivo()
    {
        return $this->belongsTo(Dispositivo::class, 'dispositivo_id', 'id');
    }
}
