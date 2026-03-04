<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class PrimeraComunion extends BaseModel
{
    use SoftDeletes;

    protected $table = 'primeras_comuniones';

    protected $fillable = [
        'id_iglesia',
        'fecha_primera_comunion',
        'id_feligres',
        'id_catequista',
        'id_ministro',
        'id_parroco',
        'libro_comunion',
        'folio',
        'partida_numero',
        'observaciones',
    ];

    protected $casts = [
        'fecha_primera_comunion' => 'date',
    ];

    public function iglesia()
    {
        return $this->belongsTo(Iglesias::class, 'id_iglesia');
    }

    public function feligres()
    {
        return $this->belongsTo(Feligres::class, 'id_feligres');
    }

    public function catequista()
    {
        return $this->belongsTo(Feligres::class, 'id_catequista');
    }

    public function ministro()
    {
        return $this->belongsTo(Feligres::class, 'id_ministro');
    }

    public function parroco()
    {
        return $this->belongsTo(Feligres::class, 'id_parroco');
    }
}