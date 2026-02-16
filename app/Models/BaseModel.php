<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model
{
    use SoftDeletes, \App\Traits\Auditable, \App\Traits\TracksAuditMetadata;

    protected $fillable = [
        'created_by',
        'deleted_by',
        'updated_by',
    ];

    /**
     * Get all of the model's audit logs.
     */
    public function auditLogs()
    {
        return $this->morphMany(\App\Models\AuditLog::class, 'auditable')->latest();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault(['name' => 'Sistema']);
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by')->withDefault(['name' => '-']);
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by')->withDefault(['name' => '-']);
    }

}
