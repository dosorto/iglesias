<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Session;

class Iglesias extends Model
{
    use HasFactory;

    protected $table = 'iglesias';

    /**
     * Always query Iglesias from the central (landlord) database,
     * even when the tenant middleware has switched the default connection.
     */
    public function getConnectionName(): string
    {
        return config('tenancy.central_connection', config('database.default'));
    }

    public static function currentIdFromSession(): ?int
    {
        if (! app()->bound('session')) {
            return null;
        }

        $iglesiaId = Session::get('tenant.id_iglesia');

        return $iglesiaId ? (int) $iglesiaId : null;
    }

    public static function currentFromSession(): ?self
    {
        $iglesiaId = static::currentIdFromSession();

        return $iglesiaId ? static::query()->find($iglesiaId) : null;
    }

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
        'db_connection',
        'db_host',
        'db_port',
        'db_database',
        'db_username',
        'db_password',
    ];

    protected $appends = ['logo_url', 'logo_derecha_url', 'certificado_bautismo_url'];

    // URL pública del logo (null si no tiene)
    protected function logoUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->path_logo
                ? asset('storage/' . $this->path_logo)
                : null,
        );
    }

    // URL pública del formato de certificado de bautismo
    protected function certificadoBautismoUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->path_certificado_bautismo
                ? asset('storage/' . $this->path_certificado_bautismo)
                : null,
        );
    }

    protected function logoDerechaUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->path_logo_derecha
                ? asset('storage/' . $this->path_logo_derecha)
                : null,
        );
    }


    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'id_iglesia');
    }
    public function religion()
{
    return $this->belongsTo(Religion::class, 'id_religion');
}
}