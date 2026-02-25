<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class Iglesias extends BaseModel
{
   use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'email',
        'parroco_nombre',
        'estado',
    ];
}
