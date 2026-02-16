<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Estudiante extends BaseModel
{
    protected $fillable = [
        'cuenta',
        'persona_id',
    ];

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }
}
