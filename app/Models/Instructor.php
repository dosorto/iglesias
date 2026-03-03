<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    // Relación con el feligrés asociado
    public function feligres()
    {
        return $this->belongsTo(Feligres::class, 'feligres_id');
    }

    // Acceso directo a la persona asociada al instructor
    public function persona()
    {
        return $this->feligres()->with('persona');
    }

    // Acceso a la iglesia a través del feligrés
    public function iglesia()
    {
        return $this->feligres()->with('iglesia');
    }

    // Relación con la firma u otros datos específicos del instructor
    // Si tienes auditoría
    public function auditLogs()
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }
}