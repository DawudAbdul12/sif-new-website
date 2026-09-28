<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaAsset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MediaAssetController extends Controller
{
    public function index(Request $request): View
    {
        $data = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'per_page' => ['nullable', 'integer', 'in:12,18,24,36,48'],
        ]);

        $perPage = (int) ($data['per_page'] ?? 18);

        $assets = MediaAsset::query()
            ->when($request->filled('search'), fn ($query) => $query->where('name', 'like', '%'.$request->string('search').'%'))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.media.index', compact('assets', 'perPage'));
    }

    public function create(): View
    {
        return view('admin.media.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'file' => ['required', 'file', 'max:10240'],
            'name' => ['required', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:1000'],
        ]);

        $file = $request->file('file');
        $path = $file->store('cms-media', 'public');

        MediaAsset::create([
            'name' => $data['name'],
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize() ?: 0,
            'alt_text' => $data['alt_text'] ?? null,
            'caption' => $data['caption'] ?? null,
            'uploaded_by' => $request->user()->id,
        ]);

        return redirect()->route('admin.media.index')->with('status', 'Media uploaded.');
    }

    public function edit(MediaAsset $medium): View
    {
        return view('admin.media.edit', ['asset' => $medium]);
    }

    public function update(Request $request, MediaAsset $medium): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:1000'],
        ]);

        $medium->update($data);

        return redirect()->route('admin.media.edit', $medium)->with('status', 'Media updated.');
    }

    public function destroy(MediaAsset $medium): RedirectResponse
    {
        $medium->delete();

        return redirect()->route('admin.media.index')->with('status', 'Media deleted.');
    }
}
