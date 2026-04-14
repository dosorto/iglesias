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
        return $this->belongsTo(TenantIglesia::class, 'id_iglesia');
    }

    public function encargado()
    {
        return $this->hasOne(Encargado::class, 'id_feligres');
    }

    public function instructor()
    {
        return $this->hasOne(Instructor::class, 'feligres_id');
    }

    public function inscripcionesCurso()
    {
        return $this->hasMany(InscripcionCurso::class, 'feligres_id');
    }

    public function bautismos()
    {
        return $this->hasMany(Bautismo::class, 'bautizado_id');
    }

    public function confirmaciones()
    {
        return $this->hasMany(Confirmacion::class, 'feligres_id');
    }

    public function primerasComuniones()
    {
        return $this->hasMany(PrimeraComunion::class, 'id_feligres');
    }

    public function matrimoniosEsposo()
    {
        return $this->hasMany(Matrimonio::class, 'esposo_id');
    }

    public function matrimoniosEsposa()
    {
        return $this->hasMany(Matrimonio::class, 'esposa_id');
    }

}
