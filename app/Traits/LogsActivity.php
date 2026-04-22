<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Boot the trait to hook into model events.
     */
    protected static function bootLogsActivity()
    {
        static::created(function (Model $model) {
            self::logActivity($model, 'Created');
        });

        static::updated(function (Model $model) {
            self::logActivity($model, 'Updated');
        });

        static::deleted(function (Model $model) {
            self::logActivity($model, 'Deleted');
        });
    }

    /**
     * Record the activity in the audit_logs table.
     */
    protected static function logActivity(Model $model, string $event)
    {
        // Only log if authenticated (admin actions)
        if (!Auth::check()) return;

        $oldValues = $event === 'Updated' ? array_intersect_key($model->getOriginal(), $model->getChanges()) : null;
        $newValues = $event === 'Updated' ? $model->getChanges() : ($event === 'Created' ? $model->toArray() : null);

        // Remove sensitive fields
        unset($newValues['password'], $newValues['remember_token']);
        if ($oldValues) unset($oldValues['password'], $oldValues['remember_token']);

        AuditLog::create([
            'user_id' => Auth::id(),
            'event' => $event,
            'auditable_type' => get_class($model),
            'auditable_id' => $model->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'url' => request()->fullUrl(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
