<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ImpactMetric;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ImpactMetricController extends Controller
{
    public function index(Request $request): View
    {
        $metrics = ImpactMetric::query()
            ->when($request->filled('tier'), fn ($query) => $query->where('tier', $request->string('tier')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = '%'.$request->string('search').'%';
                $query->where(fn ($inner) => $inner
                    ->where('label', 'like', $search)
                    ->orWhere('note', 'like', $search)
                    ->orWhere('value', 'like', $search));
            })
            ->ordered()
            ->paginate(12)
            ->withQueryString();

        return view('admin.impact-metrics.index', compact('metrics'));
    }

    public function create(): View
    {
        return view('admin.impact-metrics.create', [
            'impactMetric' => new ImpactMetric([
                'tier' => 'primary',
                'status' => 'draft',
                'sort_order' => 0,
            ]),
            'recordActivityLogs' => collect(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? now()) : null;
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        $impactMetric = ImpactMetric::create($data);

        return redirect()->route('admin.impact-metrics.edit', $impactMetric)->with('status', 'Impact metric created.');
    }

    public function edit(ImpactMetric $impactMetric): View
    {
        return view('admin.impact-metrics.edit', [
            'impactMetric' => $impactMetric,
            'recordActivityLogs' => ActivityLog::query()
                ->with('causer:id,name,email')
                ->where('subject_type', ImpactMetric::class)
                ->where('subject_id', $impactMetric->id)
                ->recent()
                ->limit(8)
                ->get(),
        ]);
    }

    public function update(Request $request, ImpactMetric $impactMetric): RedirectResponse
    {
        $data = $this->validated($request);
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? $impactMetric->published_at ?? now()) : null;
        $data['updated_by'] = $request->user()->id;

        $impactMetric->update($data);

        return redirect()->route('admin.impact-metrics.edit', $impactMetric)->with('status', 'Impact metric updated.');
    }

    public function destroy(ImpactMetric $impactMetric): RedirectResponse
    {
        $impactMetric->delete();

        return redirect()->route('admin.impact-metrics.index')->with('status', 'Impact metric deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'tier' => ['required', Rule::in(array_keys(ImpactMetric::TIERS))],
            'prefix' => ['nullable', 'string', 'max:40'],
            'value' => ['required', 'string', 'max:40'],
            'suffix' => ['nullable', 'string', 'max:40'],
            'label' => ['required', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(ImpactMetric::STATUSES)],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'published_at' => ['nullable', 'date'],
        ]);

        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return $data;
    }
}
