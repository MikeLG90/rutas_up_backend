<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'observaciones',
        'estatus'
    ];

    protected $casts = [
        'fecha_expiracion_licencia' => 'date',
        'fecha_ingreso' => 'date',
    ];

    public function documentos(): MorphMany
    {
        return $this->morphMany(Documento::class, 'documentable');
    }
    public function usuario(): BelongsTo 
    {
     
        return $this->belongsTo(User::class, 'usuario_id', 'usuario_id');
    }
    
}
