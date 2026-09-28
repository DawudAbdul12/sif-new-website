<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class ActivityLog extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'action',
        'subject_type',
        'subject_id',
        'subject_label',
        'causer_id',
        'causer_name',
        'causer_email',
        'old_values',
        'new_values',
        'changed_attributes',
        'metadata',
        'url',
        'request_method',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
            'changed_attributes' => 'array',
            'metadata' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function causer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'causer_id');
    }

    public function scopeRecent(Builder $query): Builder
    {
        return $query->latest('created_at')->latest('id');
    }

    public function subjectName(): string
    {
        return class_basename($this->subject_type);
    }

    public function actionLabel(): string
    {
        return Str::headline($this->action);
    }

    public function actionBadgeClass(): string
    {
        return match ($this->action) {
            'created' => 'published',
            'updated' => 'review',
            'deleted' => 'archived',
            'restored' => 'published',
            'permanently_deleted' => 'archived',
            'logged_in', 'logged_out' => 'review',
            default => '',
        };
    }

    public function requestMethodLabel(): string
    {
        return $this->request_method ? strtoupper($this->request_method) : 'n/a';
    }

    public function changedAttributeLabels(): array
    {
        return $this->changedAttributes()
            ->map(fn (string $attribute): string => Str::headline($attribute))
            ->all();
    }

    public function changedRows(): array
    {
        $attributes = $this->changedAttributes()
            ->merge(array_keys($this->old_values ?? []))
            ->merge(array_keys($this->new_values ?? []))
            ->filter(fn (mixed $attribute): bool => is_string($attribute) && $attribute !== '')
            ->unique()
            ->values();

        return $attributes
            ->map(fn (string $attribute): array => [
                'attribute' => $attribute,
                'label' => Str::headline($attribute),
                'old' => $this->formatActivityValue($this->old_values[$attribute] ?? null),
                'new' => $this->formatActivityValue($this->new_values[$attribute] ?? null),
            ])
            ->all();
    }

    private function changedAttributes(): \Illuminate\Support\Collection
    {
        return collect($this->changed_attributes ?? [])
            ->filter(fn (mixed $attribute): bool => is_string($attribute) && $attribute !== '')
            ->values();
    }

    public function metadataRows(): array
    {
        return collect($this->metadata ?? [])
            ->map(fn (mixed $value, string $key): array => [
                'label' => Str::headline($key),
                'value' => $this->formatActivityValue($value),
            ])
            ->values()
            ->all();
    }

    public function subjectAdminUrl(): ?string
    {
        $subject = $this->subject;

        if (! $subject) {
            return null;
        }

        return match ($this->subject_type) {
            CmsPage::class => route('admin.pages.edit', $subject),
            CmsPost::class => route('admin.posts.edit', $subject),
            Project::class => route('admin.projects.edit', $subject),
            Faq::class => route('admin.faqs.edit', $subject),
            ImpactMetric::class => route('admin.impact-metrics.edit', $subject),
            Category::class => route('admin.categories.edit', $subject),
            PressRelease::class => route('admin.press-releases.edit', $subject),
            Notice::class => route('admin.notices.edit', $subject),
            GalleryAlbum::class => route('admin.gallery.edit', $subject),
            Graphic::class => route('admin.graphics.edit', $subject),
            Video::class => route('admin.videos.edit', $subject),
            PurchasePrice::class => route('admin.purchase-prices.edit', $subject),
            LicenseRegistryEntry::class => route('admin.license-registry.edit', $subject),
            MediaAsset::class => route('admin.media.edit', $subject),
            User::class => route('admin.users.edit', $subject),
            Role::class => route('admin.roles.edit', $subject),
            Document::class => route('admin.documents.edit', [$subject->type, $subject]),
            Person::class => route('admin.people.edit', [$subject->group, $subject]),
            GalleryImage::class => $subject->album ? route('admin.gallery.edit', $subject->album) : null,
            SiteSetting::class => route('admin.settings.index'),
            default => null,
        };
    }

    public function browserLabel(): string
    {
        $agent = $this->user_agent;

        if (! $agent) {
            return 'n/a';
        }

        return Str::limit($agent, 120);
    }

    private function formatActivityValue(mixed $value): string
    {
        if ($value === null) {
            return 'null';
        }

        if ($value === true) {
            return 'true';
        }

        if ($value === false) {
            return 'false';
        }

        if (is_array($value) || is_object($value)) {
            return Str::limit(json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?: '', 1000);
        }

        return Str::limit((string) $value, 1000);
    }
}
