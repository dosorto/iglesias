<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model
{
    use SoftDeletes, \App\Traits\Auditable;

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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->created_by && auth()->user()) {
                $model->created_by = auth()->user()->id;
            }
        });

        static::deleting(function ($model) {
            if (!$model->deleted_by && auth()->user()) {
                $model->deleted_by = auth()->user()->id;
                $model->save();
            }
        });

        static::updating(function ($model) {
            if (!$model->updated_by && auth()->user()) {
                $model->updated_by = auth()->user()->id;
            }
        });
    }
}
