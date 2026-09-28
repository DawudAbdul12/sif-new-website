<?php

namespace App\Models;

use App\Models\Concerns\RecordsActivity;
use App\Support\UploadUrl;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Video extends Model
{
    use RecordsActivity, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'status',
        'description',
        'video_url',
        'embed_url',
        'thumbnail_path',
        'sort_order',
        'published_at',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'sort_order' => 'integer',
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

    public function thumbnailUrl(): string
    {
        if (! $this->thumbnail_path) {
            return asset('images/New-Project-1.jpg');
        }

        if (str_starts_with($this->thumbnail_path, '/storage/') || str_starts_with($this->thumbnail_path, 'http')) {
            return $this->thumbnail_path;
        }

        return Storage::disk('public')->exists($this->thumbnail_path)
            ? UploadUrl::public($this->thumbnail_path)
            : asset($this->thumbnail_path);
    }
}
