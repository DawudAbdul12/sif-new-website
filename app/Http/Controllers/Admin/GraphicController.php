<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Graphic;
use App\Models\MediaAsset;
use App\Support\UniqueSlug;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class GraphicController extends Controller
{
    public function index(Request $request): View
    {
        $statusCounts = Graphic::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $graphics = Graphic::query()
            ->with('mediaAsset')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('search'), fn ($query) => $query->where('title', 'like', '%'.$request->string('search').'%'))
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->latest('updated_at')
            ->paginate(12)
            ->withQueryString();

        return view('admin.graphics.index', compact('graphics', 'statusCounts'));
    }

    public function create(): View
    {
        return view('admin.graphics.create', ['graphic' => new Graphic(['status' => 'draft'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = UniqueSlug::make('graphics', $data['slug'] ?: $data['title']);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? now()) : null;
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        $this->attachImage($request, $data);
        $this->attachLibraryImage($request, $data);

        $graphic = Graphic::create($data);

        return redirect()->route('admin.graphics.edit', $graphic)->with('status', 'Graphic created.');
    }

    public function edit(Graphic $graphic): View
    {
        return view('admin.graphics.edit', ['graphic' => $graphic->load('mediaAsset')]);
    }

    public function update(Request $request, Graphic $graphic): RedirectResponse
    {
        $data = $this->validated($request, $graphic);
        $data['slug'] = UniqueSlug::make('graphics', $data['slug'] ?: $data['title'], $graphic);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? $graphic->published_at ?? now()) : null;
        $data['updated_by'] = $request->user()->id;

        if ($request->hasFile('image')) {
            $this->deleteLegacyImage($graphic);
            $this->attachImage($request, $data);
        } else {
            $this->attachLibraryImage($request, $data);
        }

        $graphic->update($data);

        return redirect()->route('admin.graphics.edit', $graphic)->with('status', 'Graphic updated.');
    }

    public function destroy(Graphic $graphic): RedirectResponse
    {
        $graphic->delete();

        return redirect()->route('admin.graphics.index')->with('status', 'Graphic deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Graphic $graphic = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['draft', 'review', 'published', 'archived'])],
            'description' => ['nullable', 'string', 'max:1200'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999999'],
            'published_at' => ['nullable', 'date'],
            'image' => [$graphic ? 'nullable' : 'required_without:media_asset_id', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:5120'],
            'media_asset_id' => [$graphic ? 'nullable' : 'required_without:image', 'integer', 'exists:media_assets,id'],
            'alt_text' => ['nullable', 'string', 'max:255'],
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function attachImage(Request $request, array &$data): void
    {
        if (! $request->hasFile('image')) {
            return;
        }

        $file = $request->file('image');
        $path = $file->store('graphics', 'public');

        if (! is_string($path) || $path === '') {
            throw ValidationException::withMessages([
                'image' => 'The graphic could not be uploaded. Please check the storage bucket configuration and try again.',
            ]);
        }

        $asset = MediaAsset::create([
            'name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) ?: $data['title'],
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize() ?: 0,
            'alt_text' => ($data['alt_text'] ?? null) ?: $data['title'],
            'caption' => $data['description'] ?? null,
            'uploaded_by' => $request->user()->id,
        ]);

        $data['media_asset_id'] = $asset->id;
        $data['image_path'] = $asset->file_path;
        $data['alt_text'] = $asset->alt_text;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function attachLibraryImage(Request $request, array &$data): void
    {
        if (! $request->filled('media_asset_id')) {
            return;
        }

        $asset = MediaAsset::query()
            ->where('mime_type', 'like', 'image/%')
            ->findOrFail($request->integer('media_asset_id'));

        $data['media_asset_id'] = $asset->id;
        $data['image_path'] = $asset->file_path;
        $data['alt_text'] = ($data['alt_text'] ?? null) ?: ($asset->alt_text ?: $data['title']);
    }

    private function deleteLegacyImage(Graphic $graphic): void
    {
        if ($graphic->media_asset_id) {
            return;
        }

        Storage::disk('public')->delete($graphic->image_path);
    }
}
