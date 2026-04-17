<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Instructor extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'instructores';

    protected $fillable = [
        'feligres_id',
        'fecha_ingreso',
        'estado',
        'path_firma',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
    ];

    public function feligres()
    {
        return $this->belongsTo(Feligres::class, 'feligres_id');
    }

    public function iglesia()
    {
        return $this->feligres()->with('iglesia');
    }

    public function auditLogs()
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }

    public static function resolveIdFromAuthEmail(?string $authEmail): ?int
    {
        $email = strtolower(trim((string) $authEmail));

        if ($email === '') {
            return null;
        }

        $instructorByEmail = self::query()->whereHas('feligres.persona', function ($q) use ($email) {
            $q->whereRaw('LOWER(email) = ?', [$email]);
        })->first();

        if ($instructorByEmail) {
            return (int) $instructorByEmail->id;
        }

        if (preg_match('/^instructor\.([0-9]+)(?:\+[0-9]+)?@tenant\.local$/', $email, $matches)) {
            $dni = $matches[1] ?? null;

            if ($dni) {
                $instructorByDni = self::query()->whereHas('feligres.persona', function ($q) use ($dni) {
                    $q->where('dni', $dni);
                })->first();

                if ($instructorByDni) {
                    return (int) $instructorByDni->id;
                }
            }
        }

        [$localPart, $domain] = array_pad(explode('@', $email, 2), 2, '');
        if ($localPart === '' || $domain !== 'gmail.com') {
            return null;
        }

        $normalizedLocalPart = preg_replace('/[^a-z0-9\+]/', '', Str::lower(Str::ascii($localPart)));
        if ($normalizedLocalPart === '') {
            return null;
        }

        $candidate = self::query()
            ->with('feligres.persona')
            ->get()
            ->first(function (self $instructor) use ($normalizedLocalPart) {
                $persona = $instructor->feligres?->persona;
                if (! $persona) {
                    return false;
                }

                $baseLocalPart = self::buildInstructorEmailLocalPart($persona);
                if ($baseLocalPart === '') {
                    return false;
                }

                return $normalizedLocalPart === $baseLocalPart
                    || str_starts_with($normalizedLocalPart, $baseLocalPart . '+');
            });

        return $candidate ? (int) $candidate->id : null;
    }

    private static function buildInstructorEmailLocalPart(Persona $persona): string
    {
        $primerNombre = Str::lower(Str::ascii(trim((string) ($persona->primer_nombre ?? ''))));
        $primerApellido = Str::lower(Str::ascii(trim((string) ($persona->primer_apellido ?? ''))));
        $fechaNacimiento = preg_replace('/[^0-9]/', '', (string) ($persona->fecha_nacimiento ?? ''));

        if (strlen($fechaNacimiento) > 8) {
            $fechaNacimiento = substr($fechaNacimiento, 0, 8);
        }

        $localPart = preg_replace('/[^a-z0-9]/', '', $primerNombre . $primerApellido . $fechaNacimiento);

        if ($localPart !== '') {
            return $localPart;
        }

        $dni = preg_replace('/[^0-9]/', '', (string) ($persona->dni ?? ''));
        $suffix = $dni !== '' ? $dni : (string) $persona->id;

        return preg_replace('/[^a-z0-9]/', '', 'instructor' . $suffix);
    }
}