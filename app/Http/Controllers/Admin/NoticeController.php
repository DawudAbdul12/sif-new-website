<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Support\UniqueSlug;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class NoticeController extends Controller
{
    public function index(Request $request): View
    {
        $notices = Notice::query()
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('search'), fn ($query) => $query->where('title', 'like', '%'.$request->string('search').'%'))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.notices.index', compact('notices'));
    }

    public function create(): View
    {
        return view('admin.notices.create', ['notice' => new Notice]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = UniqueSlug::make('notices', $data['slug'] ?: $data['title']);
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? now()) : null;
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        $this->attachFile($request, $data, 'notices');

        $notice = Notice::create($data);

        return redirect()->route('admin.notices.edit', $notice)->with('status', 'Notice created.');
    }

    public function edit(Notice $notice): View
    {
        return view('admin.notices.edit', compact('notice'));
    }

    public function update(Request $request, Notice $notice): RedirectResponse
    {
        $data = $this->validated($request, $notice);
        $data['slug'] = UniqueSlug::make('notices', $data['slug'] ?: $data['title'], $notice);
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? $notice->published_at ?? now()) : null;
        $data['updated_by'] = $request->user()->id;

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($notice->file_path);
            $this->attachFile($request, $data, 'notices');
        }

        $notice->update($data);

        return redirect()->route('admin.notices.edit', $notice)->with('status', 'Notice updated.');
    }

    public function destroy(Notice $notice): RedirectResponse
    {
        $notice->delete();

        return redirect()->route('admin.notices.index')->with('status', 'Notice deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Notice $notice = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['draft', 'review', 'published', 'archived'])],
            'brief_description' => ['nullable', 'string', 'max:1200'],
            'body' => ['nullable', 'string'],
            'published_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:published_at'],
            'file' => [$notice ? 'nullable' : 'required', 'file', 'mimes:pdf,doc,docx,png,jpg,jpeg', 'max:10240'],
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
