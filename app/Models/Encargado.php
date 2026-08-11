<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class Encargado extends BaseModel
{

    use HasFactory, SoftDeletes;

    protected $table = 'encargado'; 

    
    protected $fillable = [
        'id_feligres',
        'path_firma_principal',
        'estado',
    ];

    public function feligres()
    {
        return $this->belongsTo(Feligres::class, 'id_feligres')->withTrashed();
    }

    public static function activoParaIglesia(?int $iglesiaId): ?self
    {
        return self::query()
            ->with('feligres.persona')
            ->where('estado', 'Activo')
            ->when($iglesiaId, fn ($q) => $q->whereHas('feligres', fn ($f) => $f->where('id_iglesia', $iglesiaId)))
            ->orderByDesc('id')
            ->first();
    }
}
