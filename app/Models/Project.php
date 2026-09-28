<?php

namespace App\Models;

use App\Models\Concerns\RecordsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class Project extends Model
{
    use RecordsActivity, SoftDeletes;

    public const STATUSES = ['draft', 'review', 'published', 'archived'];

    public const PROJECT_STATUSES = ['new', 'ongoing', 'completed'];

    public const ZONES = [
        'a' => 'Savannah Belt',
        'b' => 'Forest & Transition',
        'c' => 'Western Coast',
        'd' => 'Eastern Seaboard',
    ];

    public const REGIONS = [
        'Ahafo',
        'Ashanti',
        'Bono',
        'Bono East',
        'Central',
        'Eastern',
        'Greater Accra',
        'North East',
        'Northern',
        'Oti',
        'Savannah',
        'Upper East',
        'Upper West',
        'Volta',
        'Western',
        'Western North',
    ];

    protected $fillable = [
        'name',
        'slug',
        'full_name',
        'status',
        'project_status',
        'status_label',
        'timeline',
        'funder',
        'fund_amount',
        'zone_key',
        'zone_name',
        'image',
        'summary',
        'beneficiaries',
        'categories',
        'regions',
        'objectives',
        'outcomes',
        'documents',
        'related_projects',
        'markers',
        'seo_title',
        'seo_description',
        'sort_order',
        'published_at',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'categories' => 'array',
            'regions' => 'array',
            'objectives' => 'array',
            'outcomes' => 'array',
            'documents' => 'array',
            'related_projects' => 'array',
            'markers' => 'array',
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

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->latest('published_at')->latest();
    }

    public function statusText(): string
    {
        return match ($this->project_status) {
            'new' => 'New',
            'completed' => 'Completed',
            default => 'Ongoing',
        };
    }

    public function frontendPayload(): array
    {
        return [
            'id' => $this->slug,
            'name' => $this->name,
            'fullName' => $this->full_name,
            'status' => $this->project_status,
            'statusLabel' => $this->status_label ?: $this->statusText(),
            'category' => array_values($this->categories ?: []),
            'zone' => $this->zone_key ?: 'd',
            'zoneName' => $this->zone_name ?: (self::ZONES[$this->zone_key] ?? ''),
            'regions' => array_values($this->regions ?: []),
            'timeline' => $this->timeline ?: '',
            'funder' => $this->funder ?: '',
            'fundAmount' => $this->fund_amount ?: '',
            'image' => $this->image ?: '/images/placeholder-social-infra.svg',
            'summary' => $this->summary ?: '',
            'objectives' => array_values($this->objectives ?: []),
            'beneficiaries' => $this->beneficiaries ?: '',
            'outcomes' => array_values($this->outcomes ?: []),
            'docs' => array_values($this->documents ?: []),
            'related' => array_values($this->related_projects ?: []),
            'markers' => array_values($this->markers ?: []),
        ];
    }

    public function detailPayload(): array
    {
        return [
            'name' => $this->name,
            'full_name' => $this->full_name,
            'status' => $this->status_label ?: $this->statusText(),
            'timeline' => $this->timeline,
            'funder' => $this->funder,
            'fund_amount' => $this->fund_amount,
            'zone' => $this->zone_name ?: (self::ZONES[$this->zone_key] ?? null),
            'regions' => $this->regions ?: [],
            'image' => $this->image,
            'summary' => $this->summary,
            'objectives' => $this->objectives ?: [],
            'outcomes' => $this->outcomes ?: [],
            'beneficiaries' => $this->beneficiaries,
            'seo_title' => $this->seo_title ?: $this->full_name.' | SIF Ghana',
            'seo_description' => $this->seo_description ?: Str::limit((string) $this->summary, 160, ''),
        ];
    }

    public static function publishedFrontendProjects(): array
    {
        if (Schema::hasTable('projects')) {
            $projects = self::query()->published()->ordered()->get();

            if ($projects->isNotEmpty()) {
                return $projects->map->frontendPayload()->all();
            }
        }

        return self::fallbackFrontendProjects();
    }

    public static function fallbackFrontendProjects(): array
    {
        $projects = config('sif_projects.projects', []);

        return collect($projects)->map(function (array $project, string $slug): array {
            $status = str_contains(strtolower($project['status'] ?? ''), 'completed') ? 'completed' : (str_contains(strtolower($project['status'] ?? ''), 'new') ? 'new' : 'ongoing');

            return [
                'id' => $slug,
                'name' => $project['name'] ?? '',
                'fullName' => $project['full_name'] ?? '',
                'status' => $status,
                'statusLabel' => $project['status'] ?? Str::headline($status),
                'category' => self::fallbackCategories($project),
                'zone' => array_search($project['zone'] ?? '', self::ZONES, true) ?: 'd',
                'zoneName' => $project['zone'] ?? '',
                'regions' => $project['regions'] ?? [],
                'timeline' => $project['timeline'] ?? '',
                'funder' => $project['funder'] ?? '',
                'fundAmount' => $project['fund_amount'] ?? '',
                'image' => $project['image'] ?? '',
                'summary' => $project['summary'] ?? '',
                'objectives' => $project['objectives'] ?? [],
                'beneficiaries' => $project['beneficiaries'] ?? '',
                'outcomes' => $project['outcomes'] ?? [],
                'docs' => [],
                'related' => [],
                'markers' => [],
            ];
        })->values()->all();
    }

    private static function fallbackCategories(array $project): array
    {
        $text = strtolower(($project['full_name'] ?? '').' '.($project['summary'] ?? ''));
        $categories = [];

        if (str_contains($text, 'infrastructure') || str_contains($text, 'rural') || str_contains($text, 'urban')) {
            $categories[] = 'Infrastructure';
        }

        if (str_contains($text, 'employment') || str_contains($text, 'skills')) {
            $categories[] = 'Employment';
        }

        if (str_contains($text, 'women') || str_contains($text, 'youth')) {
            $categories[] = 'Women and Youth';
        }

        if ($categories === []) {
            $categories[] = 'Community Development';
        }

        return $categories;
    }

    public function activityLabel(): string
    {
        return $this->name;
    }
}
