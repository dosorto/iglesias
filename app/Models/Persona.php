<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

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

    public function getDniAttribute($value): string
    {
        return $value ?? '';
    }

    protected function normalizarNombre(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value !== '' ? mb_strtoupper($value, 'UTF-8') : null;
    }

    /**
     * Mutator: Uppercase primer_nombre
     */
    public function setPrimerNombreAttribute($value): void
    {
        $this->attributes['primer_nombre'] = $this->normalizarNombre($value);
    }

    /**
     * Mutator: Uppercase segundo_nombre
     */
    public function setSegundoNombreAttribute($value): void
    {
        $this->attributes['segundo_nombre'] = $this->normalizarNombre($value);
    }

    /**
     * Mutator: Uppercase primer_apellido
     */
    public function setPrimerApellidoAttribute($value): void
    {
        $this->attributes['primer_apellido'] = $this->normalizarNombre($value);
    }

    /**
     * Mutator: Uppercase segundo_apellido
     */
    public function setSegundoApellidoAttribute($value): void
    {
        $this->attributes['segundo_apellido'] = $this->normalizarNombre($value);
    }

    /**
     * Mutator: Normalize email to lowercase (Issue #9)
     */
    public function setEmailAttribute($value): void
    {
        $this->attributes['email'] = $value ? Str::lower($value) : null;
    }

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

    public function feligres(): HasOne
    {
        return $this->hasOne(\App\Models\Feligres::class, 'id_persona');
    }
}
