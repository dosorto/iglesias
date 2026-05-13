<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Confirmacion extends BaseModel
{
    use SoftDeletes;

    protected $table = 'confirmaciones';

    protected $fillable = [
        'iglesia_id',
        'fecha_confirmacion',
        'lugar_confirmacion',
        'encargado_id',
        'feligres_id',
        'padre_id',
        'madre_id',
        'padrino_id',
        'madrina_id',
        'ministro_id',
        'libro_confirmacion',
        'folio',
        'partida_numero',
        'observaciones',
        'nota_marginal',
        'lugar_nacimiento',
        'lugar_expedicion',
        'fecha_expedicion',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'fecha_confirmacion' => 'date',
        'fecha_expedicion'   => 'date',
    ];

    public function iglesia()
    {
        return $this->belongsTo(TenantIglesia::class, 'iglesia_id');
    }

    public function encargado()
    {
        return $this->belongsTo(Encargado::class, 'encargado_id')->withTrashed();
    }

    public function feligres()
    {
        return $this->belongsTo(Feligres::class, 'feligres_id')->withTrashed();
    }

    public function padre()
    {
        return $this->belongsTo(Feligres::class, 'padre_id')->withTrashed();
    }

    public function madre()
    {
        return $this->belongsTo(Feligres::class, 'madre_id')->withTrashed();
    }

    public function padrino()
    {
        return $this->belongsTo(Feligres::class, 'padrino_id')->withTrashed();
    }

    public function madrina()
    {
        return $this->belongsTo(Feligres::class, 'madrina_id')->withTrashed();
    }

    public function ministro()
    {
        return $this->belongsTo(Feligres::class, 'ministro_id')->withTrashed();
    }
}