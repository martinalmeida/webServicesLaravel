<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alquiler extends Model
{
    use HasFactory;

    protected $table = 'registros_alquiler';

    protected $fillable = [
        'id', 'idMaquinaria', 'idAlquiler'
    ];
}