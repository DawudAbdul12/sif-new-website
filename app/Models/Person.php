<?php

namespace App\Models;

use App\Models\Concerns\RecordsActivity;
use App\Support\UploadUrl;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Person extends Model
{
    use RecordsActivity, SoftDeletes;

    public const GROUPS = [
        'board' => 'Board of Directors',
        'management' => 'Management Team',
    ];

    protected $fillable = [
        'group',
        'name',
        'slug',
        'position',
        'department',
        'appointment_type',
        'status',
        'brief_profile',
        'bio',
        'photo_path',
        'email',
        'phone',
        'linkedin_url',
        'show_seal',
        'sort_order',
        'published_at',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'show_seal' => 'boolean',
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

    public function scopeForGroup(Builder $query, string $group): Builder
    {
        return $query->where('group', $group);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')->whereNotNull('published_at');
    }

    public function photoUrl(): string
    {
        return $this->photo_path
            ? UploadUrl::public($this->photo_path)
            : asset('images/placeholder-avatar.svg');
    }

    public function groupLabel(): string
    {
        return self::GROUPS[$this->group] ?? str($this->group)->headline()->toString();
    }
}
