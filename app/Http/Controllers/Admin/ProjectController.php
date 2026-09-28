<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Project;
use App\Support\UniqueSlug;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $projects = Project::query()
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('project_status'), fn ($query) => $query->where('project_status', $request->string('project_status')))
            ->when($request->filled('zone_key'), fn ($query) => $query->where('zone_key', $request->string('zone_key')))
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = '%'.$request->string('search').'%';
                $query->where(fn ($inner) => $inner
                    ->where('name', 'like', $search)
                    ->orWhere('full_name', 'like', $search)
                    ->orWhere('summary', 'like', $search)
                    ->orWhere('funder', 'like', $search));
            })
            ->ordered()
            ->paginate(12)
            ->withQueryString();

        return view('admin.projects.index', compact('projects'));
    }

    public function create(): View
    {
        return view('admin.projects.create', [
            'project' => new Project([
                'status' => 'draft',
                'project_status' => 'ongoing',
                'zone_key' => 'd',
                'sort_order' => 0,
            ]),
            'recordActivityLogs' => collect(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = UniqueSlug::make('projects', $data['slug'] ?: $data['name']);
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? now()) : null;
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        $project = Project::create($data);

        return redirect()->route('admin.projects.edit', $project)->with('status', 'Project created.');
    }

    public function edit(Project $project): View
    {
        return view('admin.projects.edit', [
            'project' => $project,
            'recordActivityLogs' => ActivityLog::query()
                ->with('causer:id,name,email')
                ->where('subject_type', Project::class)
                ->where('subject_id', $project->id)
                ->recent()
                ->limit(8)
                ->get(),
        ]);
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $data = $this->validated($request, $project);
        $data['slug'] = UniqueSlug::make('projects', $data['slug'] ?: $data['name'], $project);
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? $project->published_at ?? now()) : null;
        $data['updated_by'] = $request->user()->id;

        $project->update($data);

        return redirect()->route('admin.projects.edit', $project)->with('status', 'Project updated.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return redirect()->route('admin.projects.index')->with('status', 'Project deleted.');
    }

    private function validated(Request $request, ?Project $project = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'slug' => ['nullable', 'string', 'max:120'],
            'full_name' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::in(Project::STATUSES)],
            'project_status' => ['required', Rule::in(Project::PROJECT_STATUSES)],
            'status_label' => ['nullable', 'string', 'max:120'],
            'timeline' => ['nullable', 'string', 'max:120'],
            'funder' => ['nullable', 'string', 'max:255'],
            'fund_amount' => ['nullable', 'string', 'max:120'],
            'zone_key' => ['nullable', Rule::in(array_keys(Project::ZONES))],
            'zone_name' => ['nullable', 'string', 'max:120'],
            'image' => ['nullable', 'string', 'max:500'],
            'summary' => ['nullable', 'string', 'max:1600'],
            'beneficiaries' => ['nullable', 'string', 'max:1200'],
            'categories_text' => ['nullable', 'string'],
            'regions' => ['nullable', 'array'],
            'regions.*' => ['string', Rule::in(Project::REGIONS)],
            'regions_text' => ['nullable', 'string'],
            'objectives_text' => ['nullable', 'string'],
            'outcomes_text' => ['nullable', 'string'],
            'documents_text' => ['nullable', 'string'],
            'related_projects_text' => ['nullable', 'string'],
            'markers_text' => ['nullable', 'string'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'published_at' => ['nullable', 'date'],
        ]);

        $data['zone_name'] = $data['zone_name'] ?: (Project::ZONES[$data['zone_key'] ?? ''] ?? null);
        $data['categories'] = $this->lines($request->input('categories_text'));
        $data['regions'] = $request->filled('regions')
            ? array_values(array_unique($request->input('regions', [])))
            : $this->lines($request->input('regions_text'));
        $data['objectives'] = $this->lines($request->input('objectives_text'));
        $data['outcomes'] = $this->lines($request->input('outcomes_text'));
        $data['related_projects'] = $this->lines($request->input('related_projects_text'));
        $data['documents'] = $this->documentRows($request->input('documents_text'));
        $data['markers'] = $this->markerRows($request->input('markers_text'));
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        if (blank($data['seo_title'] ?? null)) {
            $data['seo_title'] = Str::limit($data['full_name'], 65, '');
        }

        if (blank($data['seo_description'] ?? null)) {
            $data['seo_description'] = Str::limit(trim(preg_replace('/\s+/', ' ', (string) ($data['summary'] ?? ''))), 160, '');
        }

        return collect($data)->except([
            'categories_text',
            'regions_text',
            'objectives_text',
            'outcomes_text',
            'documents_text',
            'related_projects_text',
            'markers_text',
        ])->all();
    }

    private function lines(?string $value): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $value))
            ->map(fn (string $line): string => trim($line))
            ->filter()
            ->values()
            ->all();
    }

    private function documentRows(?string $value): array
    {
        return collect($this->lines($value))
            ->map(function (string $line): ?array {
                [$label, $url] = array_pad(array_map('trim', explode('|', $line, 2)), 2, null);

                if (! $label || ! $url) {
                    return null;
                }

                return compact('label', 'url');
            })
            ->filter()
            ->values()
            ->all();
    }

    private function markerRows(?string $value): array
    {
        return collect($this->lines($value))
            ->map(function (string $line): ?array {
                [$city, $lat, $lng] = array_pad(array_map('trim', explode('|', $line, 3)), 3, null);

                if (! $city || ! is_numeric($lat) || ! is_numeric($lng)) {
                    return null;
                }

                return [
                    'city' => $city,
                    'lat' => (float) $lat,
                    'lng' => (float) $lng,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }
}
