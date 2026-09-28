<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(Request $request): View
    {
        $faqs = Faq::query()
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->string('category')))
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = '%'.$request->string('search').'%';
                $query->where(fn ($inner) => $inner
                    ->where('question', 'like', $search)
                    ->orWhere('answer', 'like', $search));
            })
            ->ordered()
            ->paginate(12)
            ->withQueryString();

        return view('admin.faqs.index', compact('faqs'));
    }

    public function create(): View
    {
        return view('admin.faqs.create', [
            'faq' => new Faq([
                'category' => 'general',
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

        $faq = Faq::create($data);

        return redirect()->route('admin.faqs.edit', $faq)->with('status', 'FAQ created.');
    }

    public function edit(Faq $faq): View
    {
        return view('admin.faqs.edit', [
            'faq' => $faq,
            'recordActivityLogs' => ActivityLog::query()
                ->with('causer:id,name,email')
                ->where('subject_type', Faq::class)
                ->where('subject_id', $faq->id)
                ->recent()
                ->limit(8)
                ->get(),
        ]);
    }

    public function update(Request $request, Faq $faq): RedirectResponse
    {
        $data = $this->validated($request);
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? $faq->published_at ?? now()) : null;
        $data['updated_by'] = $request->user()->id;

        $faq->update($data);

        return redirect()->route('admin.faqs.edit', $faq)->with('status', 'FAQ updated.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return redirect()->route('admin.faqs.index')->with('status', 'FAQ deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string', 'max:3000'],
            'category' => ['required', Rule::in(array_keys(Faq::CATEGORIES))],
            'status' => ['required', Rule::in(Faq::STATUSES)],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'published_at' => ['nullable', 'date'],
        ]);

        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return $data;
    }
}
