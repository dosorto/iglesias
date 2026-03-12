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
}