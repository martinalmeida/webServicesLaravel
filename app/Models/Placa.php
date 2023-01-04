<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Placa extends Model
{
    use HasFactory;

    protected $table = 'maquinarias';

    protected $fillable = [
        'id', 'placa', 'nit', 'status'
    ];
}
