<?php

namespace App\Traits;

trait TracksAuditMetadata
{
    public static function bootTracksAuditMetadata(): void
    {
        static::creating(function ($model): void {
            if (!$model->created_by && auth()->id()) {
                $model->created_by = auth()->id();
            }
        });

        static::updating(function ($model): void {
            if (!$model->updated_by && auth()->id()) {
                $model->updated_by = auth()->id();
            }
        });

        static::deleting(function ($model): void {
            if ($model->isForceDeleting() || $model->deleted_by || !auth()->id()) {
                return;
            }

            $model->deleted_by = auth()->id();
            $model->saveQuietly();
        });
    }
}
