<?php

namespace App\Models;

use App\Models\Concerns\RecordsActivity;
use App\Support\UploadUrl;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use RecordsActivity, SoftDeletes;

    public const TYPES = [
        'publications' => 'Publications',
        'annual-reports' => 'Annual Reports',
        'procurement-notices' => 'Procurement Notices',
        'environmental-social-documents' => 'Environmental & Social Documents',
        'policies-downloads' => 'Policies & Downloads',
        'audited-financial-statements' => 'Audited Financial Statements',
        'quarterly-reports' => 'Quarterly Reports',
        'trade-reports' => 'Trade Reports',
        'contracts' => 'Contracts',
    ];

    protected $fillable = [
        'type',
        'title',
        'slug',
        'status',
        'brief_description',
        'fiscal_year',
        'document_date',
        'counterparty',
        'cover_image_path',
        'file_path',
        'file_name',
        'file_mime_type',
        'file_size',
        'sort_order',
        'published_at',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'document_date' => 'date',
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

    public function scopeForType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')->whereNotNull('published_at');
    }

    public function fileUrl(): ?string
    {
        return UploadUrl::public($this->file_path);
    }

    public function coverImageUrl(): ?string
    {
        return UploadUrl::public($this->cover_image_path);
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? str($this->type)->headline()->toString();
    }
}
