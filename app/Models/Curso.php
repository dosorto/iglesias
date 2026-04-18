<?php

namespace App\Models;

use App\Models\InscripcionCurso;
use Illuminate\Database\Eloquent\SoftDeletes;

class Curso extends BaseModel
{
    use SoftDeletes;

    protected $table = 'cursos';

    protected $fillable = [
        'iglesia_id',
        'encargado_id',
        'tipo_curso_id',
        'instructor_id',
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function encargado()
    {
        return $this->belongsTo(Encargado::class, 'encargado_id')->withTrashed();
    }

    public function tipoCurso()
    {
        return $this->belongsTo(TipoCurso::class, 'tipo_curso_id')->withTrashed();
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id')->withTrashed();
    }

    public function inscripcionesCurso()
    {
        return $this->hasMany(InscripcionCurso::class, 'curso_id');
    }
}