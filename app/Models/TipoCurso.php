<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;


class TipoCurso extends Model
{
    use SoftDeletes;
    protected $table = 'tipo_curso';
    protected $fillable = [
        "nombre_curso",
        "descripcion_curso",
        "estado_curso"
    ];
}
