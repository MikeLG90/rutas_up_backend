<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles'; // Nombre de la tabla en PostgreSQL

    protected $primaryKey = 'rol_id';

    protected $fillable = [
        'rol'
    ];
}
