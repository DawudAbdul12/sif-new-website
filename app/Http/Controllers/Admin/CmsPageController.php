<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use App\Support\UniqueSlug;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CmsPageController extends Controller
{
    public function index(Request $request): View
    {
        $pages = CmsPage::query()
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('search'), fn ($query) => $query->where('title', 'like', '%'.$request->string('search').'%'))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.pages.index', compact('pages'));
    }

    public function create(): View
    {
        return view('admin.pages.create', ['page' => new CmsPage]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = UniqueSlug::make('pages', $data['slug'] ?: $data['title']);
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? now()) : null;
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        $page = CmsPage::create($data);

        return redirect()->route('admin.pages.edit', $page)->with('status', 'Page created.');
    }

    public function edit(CmsPage $page): View
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, CmsPage $page): RedirectResponse
    {
        $data = $this->validated($request, $page);
        $data['slug'] = UniqueSlug::make('pages', $data['slug'] ?: $data['title'], $page);
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? $page->published_at ?? now()) : null;
        $data['updated_by'] = $request->user()->id;

        $page->update($data);

        return redirect()->route('admin.pages.edit', $page)->with('status', 'Page updated.');
    }

    public function destroy(CmsPage $page): RedirectResponse
    {
        $page->delete();

        return redirect()->route('admin.pages.index')->with('status', 'Page deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?CmsPage $page = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'template' => ['required', 'string', 'max:80'],
            'status' => ['required', Rule::in(['draft', 'review', 'published', 'archived'])],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'body' => ['nullable', 'string'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:1000'],
            'published_at' => ['nullable', 'date'],
        ]);
    }
}
