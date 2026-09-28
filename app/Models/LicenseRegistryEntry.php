<?php

namespace App\Models;

use App\Models\Concerns\RecordsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LicenseRegistryEntry extends Model
{
    use RecordsActivity, SoftDeletes;

    public const CATEGORIES = [
        'aggregator' => 'Aggregator',
        'sfa' => 'Self-Financing Aggregator',
        'buyerTier2' => 'Buyer (Tier 2)',
        'buyerTier1' => 'Buyer (Tier 1)',
        'refinery' => 'Refinery License',
        'jewelleryA' => 'Jewellery / Fabrication License (A)',
        'jewelleryB' => 'Jewellery / Fabrication License (B)',
        'jewelleryC' => 'Jewellery Fabrication License (C)',
    ];

    protected $fillable = [
        'category',
        'registry_number',
        'business_name',
        'certificate_number',
        'issued_date',
        'expiry_date',
        'status',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'registry_number' => 'integer',
            'issued_date' => 'date',
            'expiry_date' => 'date',
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
        return $query->where('status', 'active');
    }

    public function categoryLabel(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }
}
