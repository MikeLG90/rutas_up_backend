<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'personas'; // Nombre de la tabla en PostgreSQL

    protected $primaryKey = 'persona_id';

    protected $fillable = [
        'nombre',
        'ap_paterno',
        'ap_materno',
        'sexo',
        'fecha_nacimiento',
    ];

    public function usuario()
    {
        return $this->hasOne(User::class. 'persona_id');
    }
}
