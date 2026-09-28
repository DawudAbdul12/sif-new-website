<?php

namespace App\Models;

use App\Models\Concerns\RecordsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class Faq extends Model
{
    use RecordsActivity, SoftDeletes;

    public const STATUSES = ['draft', 'review', 'published', 'archived'];

    public const CATEGORIES = [
        'general' => 'General',
        'partnerships' => 'Partnerships',
        'complaints' => 'Complaints',
        'projects' => 'Projects',
        'funding' => 'Funding',
    ];

    protected $fillable = [
        'question',
        'answer',
        'category',
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

    public function categoryLabel(): string
    {
        return self::CATEGORIES[$this->category] ?? 'General';
    }

    public function frontendPayload(): array
    {
        return [
            'question' => $this->question,
            'answer' => $this->answer,
            'category' => $this->category,
            'category_label' => $this->categoryLabel(),
        ];
    }

    public static function publishedFrontendFaqs(): array
    {
        if (Schema::hasTable('faqs')) {
            $faqs = self::query()->published()->ordered()->get();

            if ($faqs->isNotEmpty()) {
                return $faqs->map->frontendPayload()->all();
            }
        }

        return self::fallbackFrontendFaqs();
    }

    public static function fallbackFrontendFaqs(): array
    {
        return config('sif_faqs.faqs', []);
    }

    public function activityLabel(): string
    {
        return $this->question;
    }
}
