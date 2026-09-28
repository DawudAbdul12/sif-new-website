<?php

namespace App\Models;

use App\Models\Concerns\RecordsActivity;
use App\Support\UploadUrl;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PressRelease extends Model
{
    use RecordsActivity, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'status',
        'brief_description',
        'body',
        'file_path',
        'file_name',
        'file_mime_type',
        'file_size',
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

    public function fileUrl(): ?string
    {
        return UploadUrl::public($this->file_path);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')->whereNotNull('published_at');
    }
}
