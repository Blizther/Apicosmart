<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DispositivoFabricado extends Model
{
    protected $table = 'dispositivos_fabricados';
    protected $fillable = ['serial','api_key_hash','estado'];

    public function vinculo()
    {
        return $this->hasOne(Dispositivo::class, 'dispositivo_fabricado_id');
    }
}

