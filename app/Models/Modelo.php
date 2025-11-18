<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    protected $table = 'modelos'; // Nombre de la tabla en PostgreSQL

    protected $primaryKey = 'modelo_id';

    protected $fillable = [
        'modelo',
        'marca_id'
    ];

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }
}
