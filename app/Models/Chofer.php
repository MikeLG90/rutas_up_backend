<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chofer extends Model
{
    protected $table = 'choferes';

    protected $fillable = [
        'persona_id',
        'usuario_id',
        'licencia_conducir',
        'fecha_expiracion_licencia',
        'vehiculo_id',
        'estado',
        'fecha_ingreso',
        'observaciones'
    ];

    protected $casts = [
        'fecha_expiracion_licencia' => 'date',
        'fecha_ingreso' => 'date',
    ];
}
