<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'personas';

    protected $primaryKey = 'persona_id';
    protected $appends = ['nombre_completo'];

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

    protected function getNombreCompletoAttribute(): string
    {
        return trim($this->nombre . ' ' . $this->ap_paterno . ' ' . $this->ap_materno);
    }
}
