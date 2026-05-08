<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;

class TenantIglesia extends Model
{
    use HasFactory;

    protected $table = 'iglesias';

    protected $fillable = [
        'nombre',
        'direccion',
        'header_diocesis',
        'header_lugar',
        'parroco_nombre',
        'telefono',
        'email',
        'estado',
        'id_religion',
        'path_logo',
        'path_logo_derecha',
        'path_login_background',
        'path_certificado_bautismo',
        'path_certificado_bautismo_portrait',
        'path_certificado_bautismo_landscape',
        'path_certificado_confirmacion',
        'path_certificado_confirmacion_portrait',
        'path_certificado_confirmacion_landscape',
        'path_certificado_primera_comunion',
        'path_certificado_primera_comunion_portrait',
        'path_certificado_primera_comunion_landscape',
        'path_certificado_matrimonio',
        'path_certificado_matrimonio_portrait',
        'path_certificado_matrimonio_landscape',
        'path_certificado_curso',
        'path_certificado_curso_portrait',
        'path_certificado_curso_landscape',
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
    ];

    protected $appends = [
        'logo_url',
        'logo_derecha_url',
        'login_background_url',
        'certificado_bautismo_url',
        'certificado_confirmacion_url',
        'certificado_primera_comunion_url',
        'certificado_matrimonio_url',
        'certificado_curso_url',
    ];

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

    protected function certificadoConfirmacionUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->path_certificado_confirmacion
                ? asset('storage/' . ltrim($this->path_certificado_confirmacion, '/'))
                : null,
        );
    }

    protected function certificadoPrimeraComunionUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->path_certificado_primera_comunion
                ? asset('storage/' . ltrim($this->path_certificado_primera_comunion, '/'))
                : null,
        );
    }

    protected function certificadoMatrimonioUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->path_certificado_matrimonio
                ? asset('storage/' . ltrim($this->path_certificado_matrimonio, '/'))
                : null,
        );
    }

    protected function certificadoCursoUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->path_certificado_curso
                ? asset('storage/' . ltrim($this->path_certificado_curso, '/'))
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

    protected function loginBackgroundUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->path_login_background
                ? asset('storage/' . ltrim($this->path_login_background, '/'))
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

    public static function currentFromCentral(): ?self
    {
        $iglesia = Iglesias::whereNotNull('db_database')->first();

        if (! $iglesia?->db_database) {
            return null;
        }

        $tenantConn  = config('tenancy.tenant_connection', 'tenant');
        $centralConn = config('tenancy.central_connection', 'mysql');
        $base        = config("database.connections.{$centralConn}", []);

        config(["database.connections.{$tenantConn}" => array_merge($base, [
            'host'     => $iglesia->db_host     ?: ($base['host']     ?? null),
            'port'     => $iglesia->db_port     ?: ($base['port']     ?? null),
            'database' => $iglesia->db_database,
            'username' => $iglesia->db_username ?: ($base['username'] ?? null),
            'password' => $iglesia->db_password ?: ($base['password'] ?? null),
        ])]);

        \Illuminate\Support\Facades\DB::purge($tenantConn);

        return static::on($tenantConn)->first();
    }
}