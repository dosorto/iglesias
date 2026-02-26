<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feligres extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_persona',
        'id_iglesia',
        'fecha_ingreso',
        'estado',
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }

    public function iglesia()
    {
        return $this->belongsTo(Iglesias::class, 'id_iglesia');
    }

    public function encargado()
    {
        return $this->hasOne(Encargado::class, 'id_feligres');
    }
}
