<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';
    // timestamps = true por defecto, tu tabla los tiene
    protected $fillable = ['idUser', 'fecha', 'total', 'estado'];

    // Relación: una venta pertenece a un usuario (vendedor)
     public function detalles()
    {
        // detalles.idVenta → ventas.id
        return $this->hasMany(Detalle::class, 'idVenta');
    }

    public function usuario()
    {
        // ventas.idUser → users.id
        return $this->belongsTo(User::class, 'idUser');
    }
    
}
