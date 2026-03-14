<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Bautismo extends BaseModel
{
    use SoftDeletes;

    protected $table = 'bautismos';

    protected $fillable = [
        'iglesia_id',
        'fecha_bautismo',
        'encargado_id',
        'bautizado_id',
        'padre_id',
        'madre_id',
        'padrino_id',
        'madrina_id',
        'libro_bautismo',
        'folio',
        'partida_numero',
        'observaciones',
        'nota_marginal',
        'lugar_nacimiento',
        'lugar_expedicion',
        'fecha_expedicion',
    ];

    protected $casts = [
        'fecha_bautismo'   => 'date',
        'fecha_expedicion' => 'date',
    ];

    public function iglesia()
    {
        return $this->belongsTo(TenantIglesia::class, 'iglesia_id');
    }

    public function encargado()
    {
        return $this->belongsTo(Encargado::class, 'encargado_id');
    }

    public function bautizado()
    {
        return $this->belongsTo(Feligres::class, 'bautizado_id');
    }

    public function padre()
    {
        return $this->belongsTo(Feligres::class, 'padre_id');
    }

    public function madre()
    {
        return $this->belongsTo(Feligres::class, 'madre_id');
    }

    public function padrino()
    {
        return $this->belongsTo(Feligres::class, 'padrino_id');
    }

    public function madrina()
    {
        return $this->belongsTo(Feligres::class, 'madrina_id');
    }
}
