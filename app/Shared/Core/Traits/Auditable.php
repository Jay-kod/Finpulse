<?php

namespace App\Shared\Core\Traits;

use Illuminate\Support\Facades\Auth;

trait Auditable
{
    public static function bootAuditable()
    {
        static::creating(function ($model) {
            if (Auth::check() && empty($model->created_by) && \Illuminate\Support\Facades\Schema::hasColumn($model->getTable(), 'created_by')) {
                $model->created_by = Auth::id();
            }
        });

        static::created(function ($model) {
            static::logAuditEvent($model, 'created');
        });

        static::updating(function ($model) {
            if (Auth::check() && empty($model->updated_by) && \Illuminate\Support\Facades\Schema::hasColumn($model->getTable(), 'updated_by')) {
                $model->updated_by = Auth::id();
            }
        });

        static::updated(function ($model) {
            static::logAuditEvent($model, 'updated');
        });

        static::deleted(function ($model) {
            static::logAuditEvent($model, 'deleted');
        });
    }

    protected static function logAuditEvent($model, string $event)
    {
        // Don't log if running in console (e.g. migrations/seeders) without a user, unless desired.
        // Actually, we'll log it but with user_id = null.
        
        $oldValues = [];
        $newValues = [];

        if ($event === 'created') {
            $newValues = $model->getAttributes();
        } elseif ($event === 'updated') {
            $newValues = $model->getChanges();
            foreach ($newValues as $key => $value) {
                $oldValues[$key] = $model->getOriginal($key);
            }
        } elseif ($event === 'deleted') {
            $oldValues = $model->getAttributes();
        }

        \App\Models\AuditLog::create([
            'user_id' => Auth::id(),
            'event' => $event,
            'auditable_type' => get_class($model),
            'auditable_id' => $model->id,
            'old_values' => empty($oldValues) ? null : $oldValues,
            'new_values' => empty($newValues) ? null : $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
