<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    protected $table = 'asignaciones'; // Nombre de la tabla en PostgreSQL

    protected $primaryKey = 'asignacion_id';

    protected $fillable = [
        'chofer_id',
        'vehiculo_id',
        'ruta_id'
    ];
}
