<?php

namespace App\Models;

use App\Models\Concerns\RecordsActivity;
use App\Support\UploadUrl;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class GalleryAlbum extends Model
{
    use RecordsActivity, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'status',
        'description',
        'cover_image_path',
        'cover_media_asset_id',
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

    public function images(): HasMany
    {
        return $this->hasMany(GalleryImage::class)->orderBy('sort_order')->oldest();
    }

    public function coverMedia(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'cover_media_asset_id');
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

    public function coverUrl(): string
    {
        if ($this->coverMedia) {
            return $this->coverMedia->url();
        }

        $firstImage = $this->images->first();

        if ($firstImage) {
            return $firstImage->url();
        }

        $path = $this->cover_image_path;

        if (! $path) {
            return asset('images/11-5-scaled.jpg');
        }

        if (str_starts_with($path, '/storage/') || str_starts_with($path, 'http')) {
            return $path;
        }

        return Storage::disk('public')->exists($path)
            ? UploadUrl::public($path)
            : asset($path);
    }
}
