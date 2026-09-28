<?php

namespace App\Models;

use App\Models\Concerns\RecordsActivity;
use App\Support\UploadUrl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class GalleryImage extends Model
{
    use RecordsActivity, SoftDeletes;

    protected $fillable = [
        'gallery_album_id',
        'media_asset_id',
        'image_path',
        'alt_text',
        'caption',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo(GalleryAlbum::class, 'gallery_album_id');
    }

    public function mediaAsset(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class);
    }

    public function url(): string
    {
        if ($this->mediaAsset) {
            return $this->mediaAsset->url();
        }

        if (str_starts_with($this->image_path, '/storage/') || str_starts_with($this->image_path, 'http')) {
            return $this->image_path;
        }

        return Storage::disk('public')->exists($this->image_path)
            ? UploadUrl::public($this->image_path)
            : asset($this->image_path);
    }
}
