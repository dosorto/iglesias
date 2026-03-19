<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class TenantIglesia extends Model
{
    use HasFactory;

    protected $table = 'iglesias';

    protected $fillable = [
        'nombre',
        'direccion',
        'parroco_nombre',
        'telefono',
        'email',
        'estado',
        'id_religion',
        'path_logo',
        'path_logo_derecha',
        'path_certificado_bautismo',
        'orientacion_certificado',
    ];

    protected $appends = ['logo_url', 'logo_derecha_url', 'certificado_bautismo_url'];

    protected function logoUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->path_logo
                ? asset('storage/' . ltrim($this->path_logo, '/'))
                : null,
        );
    }

    protected function certificadoBautismoUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->path_certificado_bautismo
                ? asset('storage/' . ltrim($this->path_certificado_bautismo, '/'))
                : null,
        );
    }

    protected function logoDerechaUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->path_logo_derecha
                ? asset('storage/' . ltrim($this->path_logo_derecha, '/'))
                : null,
        );
    }

    public static function currentId(): ?int
    {
        $iglesiaId = static::query()->value('id');

        return $iglesiaId ? (int) $iglesiaId : null;
    }

    public static function current(): ?self
    {
        return static::query()->first();
    }
}