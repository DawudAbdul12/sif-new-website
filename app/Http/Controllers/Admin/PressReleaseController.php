<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PressRelease;
use App\Support\UniqueSlug;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PressReleaseController extends Controller
{
    public function index(Request $request): View
    {
        $pressReleases = PressRelease::query()
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('search'), fn ($query) => $query->where('title', 'like', '%'.$request->string('search').'%'))
            ->orderByRaw('published_at IS NULL')
            ->latest('published_at')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.press-releases.index', compact('pressReleases'));
    }

    public function create(): View
    {
        return view('admin.press-releases.create', ['pressRelease' => new PressRelease]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = UniqueSlug::make('press_releases', $data['slug'] ?: $data['title']);
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? now()) : null;
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        $this->attachFile($request, $data, 'press-releases');

        $pressRelease = PressRelease::create($data);

        return redirect()->route('admin.press-releases.edit', $pressRelease)->with('status', 'Press release created.');
    }

    public function edit(PressRelease $pressRelease): View
    {
        return view('admin.press-releases.edit', compact('pressRelease'));
    }

    public function update(Request $request, PressRelease $pressRelease): RedirectResponse
    {
        $data = $this->validated($request, $pressRelease);
        $data['slug'] = UniqueSlug::make('press_releases', $data['slug'] ?: $data['title'], $pressRelease);
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? $pressRelease->published_at ?? now()) : null;
        $data['updated_by'] = $request->user()->id;

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($pressRelease->file_path);
            $this->attachFile($request, $data, 'press-releases');
        }

        $pressRelease->update($data);

        return redirect()->route('admin.press-releases.edit', $pressRelease)->with('status', 'Press release updated.');
    }

    public function destroy(PressRelease $pressRelease): RedirectResponse
    {
        $pressRelease->delete();

        return redirect()->route('admin.press-releases.index')->with('status', 'Press release deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?PressRelease $pressRelease = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['draft', 'review', 'published', 'archived'])],
            'brief_description' => ['nullable', 'string', 'max:1200'],
            'body' => ['nullable', 'string'],
            'published_at' => ['nullable', 'date'],
            'file' => [$pressRelease ? 'nullable' : 'required', 'file', 'mimes:pdf,doc,docx,png,jpg,jpeg', 'max:10240'],
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function attachFile(Request $request, array &$data, string $directory): void
    {
        if (! $request->hasFile('file')) {
            return;
        }

        $file = $request->file('file');
        $data['file_path'] = $file->store($directory, 'public');
        $data['file_name'] = $file->getClientOriginalName();
        $data['file_mime_type'] = $file->getMimeType();
        $data['file_size'] = $file->getSize() ?: 0;
    }
}
