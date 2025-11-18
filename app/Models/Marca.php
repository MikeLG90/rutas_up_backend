<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $table = 'marcas'; // Nombre de la tabla en PostgreSQL

    protected $primaryKey = 'marca_id';

    protected $fillable = [
        'marca'
    ];
}
