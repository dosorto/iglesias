<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Persona extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'dni',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'fecha_nacimiento',
        'sexo',
        'telefono',
        'email',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    /**
     * Get the persona's full name.
     */
    public function getNombreCompletoAttribute(): string
    {
        return trim(
            "{$this->primer_nombre} {$this->segundo_nombre} {$this->primer_apellido} {$this->segundo_apellido}"
        );
    }

    public function estudiante(): HasOne
    {
        return $this->hasOne(Estudiante::class, 'persona_id');
    }
}
