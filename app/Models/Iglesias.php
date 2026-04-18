<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\SoftDeletes;

class Iglesias extends Model
{
    use HasFactory, SoftDeletes;

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
        'header_diocesis',
        'header_lugar',
        'parroco_nombre',
        'telefono',
        'email',
        'subdomain',
        'estado',
        'id_religion',
        'path_logo',
        'path_logo_derecha',
        'path_certificado_bautismo',
        'path_certificado_confirmacion',
        'path_certificado_primera_comunion',
        'path_certificado_matrimonio',
        'path_certificado_curso',
        'orientacion_certificado',
        'orientacion_certificado_bautismo',
        'orientacion_certificado_confirmacion',
        'orientacion_certificado_primera_comunion',
        'orientacion_certificado_matrimonio',
        'orientacion_certificado_curso',
        'paper_size_certificado',
        'paper_size_certificado_bautismo',
        'paper_size_certificado_confirmacion',
        'paper_size_certificado_primera_comunion',
        'paper_size_certificado_matrimonio',
        'paper_size_certificado_curso',
        'db_connection',
        'db_host',
        'db_port',
        'db_database',
        'db_username',
        'db_password',
    ];

    protected $appends = [
        'logo_url',
        'logo_derecha_url',
        'certificado_bautismo_url',
        'certificado_confirmacion_url',
        'certificado_primera_comunion_url',
        'certificado_matrimonio_url',
        'certificado_curso_url',
    ];

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

    protected function certificadoConfirmacionUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->path_certificado_confirmacion
                ? asset('storage/' . $this->path_certificado_confirmacion)
                : null,
        );
    }

    protected function certificadoPrimeraComunionUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->path_certificado_primera_comunion
                ? asset('storage/' . $this->path_certificado_primera_comunion)
                : null,
        );
    }

    protected function certificadoMatrimonioUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->path_certificado_matrimonio
                ? asset('storage/' . $this->path_certificado_matrimonio)
                : null,
        );
    }

    protected function certificadoCursoUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->path_certificado_curso
                ? asset('storage/' . $this->path_certificado_curso)
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
        return $this->belongsTo(Religion::class, 'id_religion')->withTrashed();
}
}