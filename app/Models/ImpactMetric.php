<?php

namespace App\Models;

use App\Models\Concerns\RecordsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class ImpactMetric extends Model
{
    use RecordsActivity, SoftDeletes;

    public const STATUSES = ['draft', 'review', 'published', 'archived'];

    public const TIERS = [
        'primary' => 'Primary',
        'secondary' => 'Secondary',
    ];

    protected $fillable = [
        'tier',
        'prefix',
        'value',
        'suffix',
        'label',
        'note',
        'status',
        'sort_order',
        'published_at',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')->whereNotNull('published_at');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->latest('published_at')->latest();
    }

    public function frontendPayload(): array
    {
        return [
            'tier' => $this->tier,
            'prefix' => $this->prefix ?: '',
            'value' => $this->value,
            'suffix' => $this->suffix ?: '',
            'label' => $this->label,
            'note' => $this->note ?: '',
        ];
    }

    public static function publishedFrontendMetrics(): array
    {
        if (Schema::hasTable('impact_metrics')) {
            $metrics = self::query()->published()->ordered()->get();

            if ($metrics->isNotEmpty()) {
                return $metrics->map->frontendPayload()->all();
            }
        }

        return self::fallbackFrontendMetrics();
    }

    public static function fallbackFrontendMetrics(): array
    {
        return config('sif_impact.metrics', []);
    }

    public function activityLabel(): string
    {
        return $this->label;
    }
}
