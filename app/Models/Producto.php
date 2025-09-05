<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'idUser','descripcion','unidadMedida','stock','precio','estado','imagen'
    ];

    // Relación: un producto pertenece a un usuario (vendedor)
    public function vendedor()
    {
        return $this->belongsTo(User::class, 'idUser', 'id');
    }

    // Relación: un producto puede estar en muchos detalles de venta
    public function detalles()
    {
        return $this->hasMany(Detalle::class, 'idProducto', 'id');
    }
}
