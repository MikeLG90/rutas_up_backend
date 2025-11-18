<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    protected $table = 'rutas'; // Nombre de la tabla en PostgreSQL

    protected $primaryKey = 'ruta_id';

    protected $fillable = [
        'nombre_ruta',
        'puntos_geograficos',
        'distancia'
    ];
}
