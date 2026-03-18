<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Matrimonio extends BaseModel
{
    use SoftDeletes;

    protected $table = 'matrimonios';

    protected $fillable = [
        'iglesia_id',
        'fecha_matrimonio',
        'encargado_id',
        'esposo_id',
        'esposa_id',
        'testigo1_id',
        'testigo2_id',
        'libro_matrimonio',
        'folio',
        'partida_numero',
        'observaciones',
        'nota_marginal',
        'lugar_expedicion',
        'fecha_expedicion',
    ];

    protected $casts = [
        'fecha_matrimonio'  => 'date',
        'fecha_expedicion'  => 'date',
    ];

    public function iglesia()
    {
        return $this->belongsTo(TenantIglesia::class, 'iglesia_id');
    }

    public function encargado()
    {
        return $this->belongsTo(Encargado::class, 'encargado_id');
    }

    public function esposo()
    {
        return $this->belongsTo(Feligres::class, 'esposo_id');
    }

    public function esposa()
    {
        return $this->belongsTo(Feligres::class, 'esposa_id');
    }

    public function testigo1()
    {
        return $this->belongsTo(Feligres::class, 'testigo1_id');
    }

    public function testigo2()
    {
        return $this->belongsTo(Feligres::class, 'testigo2_id');
    }
}
