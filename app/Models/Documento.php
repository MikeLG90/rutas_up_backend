<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Documento extends Model
{
    protected $table = 'documentos';

    protected $fillable = [
        'nombre',
        'fecha_expiracion',
        'ruta_archivo',
        'documentable_id',
        'documentable_type',
    ];

    protected $casts = [
        'fecha_expiracion' => 'date',
    ];

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }
}