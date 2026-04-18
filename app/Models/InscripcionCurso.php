<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InscripcionCurso extends BaseModel
{
    use SoftDeletes;

    protected $table = 'inscripciones_curso';

    protected $fillable = [
        'curso_id',
        'feligres_id',
        'fecha_inscripcion',
        'aprobado',
        'certificado_emitido',
        'fecha_certificado',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'fecha_inscripcion'   => 'date',
        'fecha_certificado'   => 'date',
        'aprobado'            => 'boolean',
        'certificado_emitido' => 'boolean',
    ];

    /* =========================
        RELACIONES PRINCIPALES
    ==========================*/

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class, 'curso_id')->withTrashed();
    }

    public function feligres(): BelongsTo
    {
        return $this->belongsTo(Feligres::class, 'feligres_id')->withTrashed();
    }

    /*
    Acceso directo a persona desde inscripción
    Inscripcion → Feligres → Persona
    */

    public function persona()
    {
        return $this->feligres?->persona();
    }

    /* =========================
        RELACIONES DE AUDITORÍA
    ==========================*/

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by')->withTrashed();
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by')->withTrashed();
    }
}