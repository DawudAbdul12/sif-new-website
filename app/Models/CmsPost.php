<?php

namespace App\Models;

use App\Models\Concerns\RecordsActivity;
use App\Support\UploadUrl;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class CmsPost extends Model
{
    use RecordsActivity, SoftDeletes;

    protected $table = 'posts';

    protected $fillable = [
        'title',
        'slug',
        'type',
        'category_id',
        'category',
        'status',
        'excerpt',
        'body',
        'featured_image',
        'seo_title',
        'seo_description',
        'published_at',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
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

    public function categoryRelation(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')->whereNotNull('published_at');
    }

    public function imageUrl(): string
    {
        if (! $this->featured_image) {
            return asset($this->type === 'article' ? 'images/sif-logo-new.png' : 'images/18.jpg-1-2048x1365.jpeg');
        }

        if (str_starts_with($this->featured_image, '/storage/') || str_starts_with($this->featured_image, 'http')) {
            return $this->featured_image;
        }

        return Storage::disk('public')->exists($this->featured_image)
            ? UploadUrl::public($this->featured_image)
            : asset($this->featured_image);
    }

    public function publicUrl(): string
    {
        return $this->type === 'article'
            ? route('pages.articles.show', $this->slug)
            : route('pages.news.show', $this->slug);
    }
}
