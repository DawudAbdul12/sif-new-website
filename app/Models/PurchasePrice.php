<?php

namespace App\Models;

use App\Models\Concerns\RecordsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchasePrice extends Model
{
    use RecordsActivity, SoftDeletes;

    protected $fillable = [
        'title',
        'subtitle',
        'status',
        'lbma_price_session',
        'lbma_pm_price',
        'lbma_price_visibility',
        'rate_label',
        'exchange_rate',
        'rate_visibility',
        'secondary_rate_label',
        'secondary_rate',
        'secondary_rate_visibility',
        'discount_rate',
        'discount_rate_visibility',
        'total_price_per_pound',
        'total_price_visibility',
        'bonus_label',
        'bonus_amount',
        'bonus_visibility',
        'alternate_total_label',
        'alternate_total_amount',
        'alternate_total_visibility',
        'price_currency',
        'currency',
        'display_at',
        'valid_from',
        'valid_until',
        'show_on_home',
        'sort_order',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'lbma_pm_price' => 'decimal:2',
            'exchange_rate' => 'decimal:4',
            'secondary_rate' => 'decimal:4',
            'discount_rate' => 'decimal:2',
            'total_price_per_pound' => 'decimal:2',
            'bonus_amount' => 'decimal:2',
            'alternate_total_amount' => 'decimal:2',
            'display_at' => 'datetime',
            'valid_from' => 'datetime',
            'valid_until' => 'datetime',
            'show_on_home' => 'boolean',
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
        return $query->where('status', 'published');
    }

    public function scopeVisibleOnHome(Builder $query): Builder
    {
        return $query
            ->published()
            ->where('show_on_home', true)
            ->where(fn (Builder $query) => $query->whereNull('valid_from')->orWhere('valid_from', '<=', now()))
            ->where(fn (Builder $query) => $query->whereNull('valid_until')->orWhere('valid_until', '>=', now()));
    }
}
