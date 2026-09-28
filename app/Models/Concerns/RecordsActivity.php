<?php

namespace App\Models\Concerns;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Throwable;

trait RecordsActivity
{
    protected static function bootRecordsActivity(): void
    {
        static::created(fn (Model $model) => $model->recordActivity('created'));

        static::updated(function (Model $model): void {
            $changes = $model->activityChanges();

            if ($changes !== []) {
                $model->recordActivity('updated', $changes);
            }
        });

        static::deleted(function (Model $model): void {
            if (method_exists($model, 'isForceDeleting') && $model->isForceDeleting()) {
                return;
            }

            $model->recordActivity('deleted');
        });

        static::restored(fn (Model $model) => $model->recordActivity('restored'));

        static::forceDeleted(fn (Model $model) => $model->recordActivity('permanently_deleted'));
    }

    protected function recordActivity(string $action, ?array $changes = null): void
    {
        if (! $this->shouldRecordActivity()) {
            return;
        }

        $user = Auth::user();
        $request = request();

        ActivityLog::withoutEvents(function () use ($action, $changes, $user, $request): void {
            ActivityLog::create([
                'action' => $action,
                'subject_type' => $this::class,
                'subject_id' => $this->getKey(),
                'subject_label' => $this->activityLabel(),
                'causer_id' => $user?->id,
                'causer_name' => $user?->name,
                'causer_email' => $user?->email,
                'old_values' => $this->activityOldValues($action, $changes),
                'new_values' => $this->activityNewValues($action, $changes),
                'changed_attributes' => $this->activityChangedAttributes($action, $changes),
                'metadata' => $this->activityMetadata(),
                'url' => app()->runningInConsole() ? null : $request->fullUrl(),
                'request_method' => app()->runningInConsole() ? null : $request->method(),
                'ip_address' => app()->runningInConsole() ? null : $request->ip(),
                'user_agent' => app()->runningInConsole() ? null : $request->userAgent(),
            ]);
        });
    }

    protected function shouldRecordActivity(): bool
    {
        try {
            return Schema::hasTable('activity_logs');
        } catch (Throwable) {
            return false;
        }
    }

    protected function activityChanges(): array
    {
        $changes = collect($this->getChanges())
            ->except($this->activityIgnoredAttributes())
            ->all();

        if ($changes === []) {
            return [];
        }

        $old = [];

        foreach (array_keys($changes) as $attribute) {
            $old[$attribute] = $this->getOriginal($attribute);
        }

        return [
            'old' => $this->sanitizeActivityValues($old),
            'new' => $this->sanitizeActivityValues($changes),
        ];
    }

    protected function activityOldValues(string $action, ?array $changes): ?array
    {
        if ($action === 'updated') {
            return $changes['old'] ?? null;
        }

        if (in_array($action, ['deleted', 'permanently_deleted'], true)) {
            return $this->sanitizeActivityValues($this->getOriginal());
        }

        return null;
    }

    protected function activityNewValues(string $action, ?array $changes): ?array
    {
        if ($action === 'updated') {
            return $changes['new'] ?? null;
        }

        if ($action === 'created') {
            return $this->sanitizeActivityValues($this->getAttributes());
        }

        return null;
    }

    protected function activityChangedAttributes(string $action, ?array $changes): ?array
    {
        if ($action === 'updated') {
            return array_keys($changes['new'] ?? []);
        }

        if ($action === 'created') {
            return array_keys($this->sanitizeActivityValues($this->getAttributes()));
        }

        if ($action === 'restored') {
            return $this->sanitizeActivityValues($this->getAttributes());
        }

        if (in_array($action, ['deleted', 'permanently_deleted'], true)) {
            return array_keys($this->sanitizeActivityValues($this->getOriginal()));
        }

        return null;
    }

    protected function activityIgnoredAttributes(): array
    {
        return ['created_at', 'updated_at', 'deleted_at', 'remember_token'];
    }

    protected function activityMetadata(): array
    {
        return [
            'table' => $this->getTable(),
            'model' => $this::class,
            'primary_key' => $this->getKeyName(),
        ];
    }

    protected function sanitizeActivityValues(array $values): array
    {
        $hidden = method_exists($this, 'getHidden') ? $this->getHidden() : [];
        $sensitive = array_merge($hidden, $this->activityIgnoredAttributes(), ['password', 'remember_token']);

        return collect($values)
            ->reject(fn ($value, string $key): bool => in_array($key, $sensitive, true))
            ->map(fn ($value) => is_string($value) && strlen($value) > 5000 ? substr($value, 0, 5000).'...' : $value)
            ->all();
    }

    protected function activityLabel(): ?string
    {
        foreach (['title', 'name', 'business_name', 'label', 'key', 'email', 'file_name', 'slug'] as $attribute) {
            if (filled($this->getAttribute($attribute))) {
                return (string) $this->getAttribute($attribute);
            }
        }

        return $this->getKey() ? class_basename($this).' #'.$this->getKey() : class_basename($this);
    }
}
