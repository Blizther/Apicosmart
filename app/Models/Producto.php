<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos'; // Definir el mombre de la tabla
    protected $fillable = ['idUser','descripcion', 'unidadMedida', 'stock','precio','imagen'];
    // Campos permitidos para inserción masiva

    // Mutador para convertir 'nombre' a mayúsculas antes de guardar
    public function setDescripcionAttribute($value)
    {
        $this->attributes['descripcion'] = strtoupper($value);
    }
}
