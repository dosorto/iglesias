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

    /**
     * Mutator: Capitalize primer_nombre
     */
    public function setPrimerNombreAttribute($value): void
    {
        $this->attributes['primer_nombre'] = Str::title($value);
    }

    /**
     * Mutator: Capitalize segundo_nombre
     */
    public function setSegundoNombreAttribute($value): void
    {
        $this->attributes['segundo_nombre'] = $value ? Str::title($value) : null;
    }

    /**
     * Mutator: Capitalize primer_apellido
     */
    public function setPrimerApellidoAttribute($value): void
    {
        $this->attributes['primer_apellido'] = Str::title($value);
    }

    /**
     * Mutator: Capitalize segundo_apellido
     */
    public function setSegundoApellidoAttribute($value): void
    {
        $this->attributes['segundo_apellido'] = $value ? Str::title($value) : null;
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
