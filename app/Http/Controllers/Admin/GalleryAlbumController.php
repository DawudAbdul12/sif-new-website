<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use App\Models\MediaAsset;
use App\Support\UniqueSlug;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GalleryAlbumController extends Controller
{
    public function index(Request $request): View
    {
        $albums = GalleryAlbum::query()
            ->with(['images.mediaAsset', 'coverMedia'])
            ->withCount('images')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('search'), fn ($query) => $query->where('title', 'like', '%'.$request->string('search').'%'))
            ->orderByDesc('published_at')
            ->latest('updated_at')
            ->paginate(12)
            ->withQueryString();

        return view('admin.gallery.index', compact('albums'));
    }

    public function create(): View
    {
        return view('admin.gallery.create', ['album' => new GalleryAlbum(['status' => 'draft'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = UniqueSlug::make('gallery_albums', $data['slug'] ?: $data['title']);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? now()) : null;
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        $album = GalleryAlbum::create($data);
        $this->attachLibraryImages($request, $album);
        $this->attachImages($request, $album);
        $this->attachCover($request, $album);
        $this->syncCoverFromImages($album);

        return redirect()->route('admin.gallery.edit', $album)->with('status', 'Gallery album created.');
    }

    public function edit(GalleryAlbum $gallery): View
    {
        return view('admin.gallery.edit', ['album' => $gallery->load(['images.mediaAsset', 'coverMedia'])]);
    }

    public function update(Request $request, GalleryAlbum $gallery): RedirectResponse
    {
        $data = $this->validated($request, $gallery);
        $data['slug'] = UniqueSlug::make('gallery_albums', $data['slug'] ?: $data['title'], $gallery);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? $gallery->published_at ?? now()) : null;
        $data['updated_by'] = $request->user()->id;

        $gallery->update($data);
        $this->deleteSelectedImages($request, $gallery);
        $this->attachLibraryImages($request, $gallery);
        $this->attachImages($request, $gallery);

        if ($request->hasFile('cover_image')) {
            $this->deleteLegacyCover($gallery);
            $this->attachCover($request, $gallery);
        } elseif ($request->filled('cover_media_asset_id')) {
            $gallery->update([
                'cover_media_asset_id' => $request->integer('cover_media_asset_id'),
                'cover_image_path' => null,
            ]);
        }

        $this->syncCoverFromImages($gallery);

        return redirect()->route('admin.gallery.edit', $gallery)->with('status', 'Gallery album updated.');
    }

    public function destroy(GalleryAlbum $gallery): RedirectResponse
    {
        $gallery->delete();

        return redirect()->route('admin.gallery.index')->with('status', 'Gallery album deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?GalleryAlbum $album = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['draft', 'review', 'published', 'archived'])],
            'description' => ['nullable', 'string', 'max:1200'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999999'],
            'published_at' => ['nullable', 'date'],
            'cover_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'cover_media_asset_id' => ['nullable', 'integer', 'exists:media_assets,id'],
            'images' => [$album ? 'nullable' : 'required_without:library_images', 'array'],
            'images.*' => ['file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'library_images' => [$album ? 'nullable' : 'required_without:images', 'array'],
            'library_images.*' => ['integer', 'exists:media_assets,id'],
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['integer', 'exists:gallery_images,id'],
        ]);
    }

    private function attachCover(Request $request, GalleryAlbum $album): void
    {
        if (! $request->hasFile('cover_image')) {
            return;
        }

        $asset = $this->createMediaAsset($request, $request->file('cover_image'), $album->title.' cover', 'gallery/covers');

        $album->update([
            'cover_image_path' => $asset->file_path,
            'cover_media_asset_id' => $asset->id,
        ]);
    }

    private function attachImages(Request $request, GalleryAlbum $album): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $nextSort = (int) $album->images()->max('sort_order');

        foreach ($request->file('images', []) as $file) {
            $asset = $this->createMediaAsset($request, $file, $album->title, 'gallery/albums');
            $nextSort++;
            $album->images()->create([
                'media_asset_id' => $asset->id,
                'image_path' => $asset->file_path,
                'alt_text' => $asset->alt_text,
                'caption' => $asset->caption,
                'sort_order' => $nextSort,
            ]);
        }
    }

    private function attachLibraryImages(Request $request, GalleryAlbum $album): void
    {
        $ids = $request->input('library_images', []);

        if (! is_array($ids) || $ids === []) {
            return;
        }

        $existingIds = $album->images()->whereNotNull('media_asset_id')->pluck('media_asset_id')->all();
        $assets = MediaAsset::query()
            ->whereIn('id', array_diff(array_map('intval', $ids), $existingIds))
            ->where('mime_type', 'like', 'image/%')
            ->latest()
            ->get();
        $nextSort = (int) $album->images()->max('sort_order');

        foreach ($assets as $asset) {
            $nextSort++;
            $album->images()->create([
                'media_asset_id' => $asset->id,
                'image_path' => $asset->file_path,
                'alt_text' => $asset->alt_text ?: $album->title,
                'caption' => $asset->caption,
                'sort_order' => $nextSort,
            ]);
        }
    }

    private function deleteSelectedImages(Request $request, GalleryAlbum $album): void
    {
        $ids = $request->input('delete_images', []);

        if (! is_array($ids) || $ids === []) {
            return;
        }

        $album->images()->whereIn('id', $ids)->get()->each(function (GalleryImage $image): void {
            if (
                $image->album
                && (
                    $image->album->cover_image_path === $image->image_path
                    || $image->album->cover_media_asset_id === $image->media_asset_id
                )
            ) {
                $image->album->update([
                    'cover_image_path' => null,
                    'cover_media_asset_id' => null,
                ]);
            }

            $image->delete();
        });
    }

    private function syncCoverFromImages(GalleryAlbum $album): void
    {
        if ($album->cover_image_path || $album->cover_media_asset_id) {
            return;
        }

        $firstImage = $album->images()->first();

        if (! $firstImage) {
            return;
        }

        $album->update([
            'cover_image_path' => $firstImage->image_path,
            'cover_media_asset_id' => $firstImage->media_asset_id,
        ]);
    }

    private function createMediaAsset(Request $request, mixed $file, string $fallbackName, string $directory): MediaAsset
    {
        $path = $file->store($directory, 'public');
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) ?: $fallbackName;

        return MediaAsset::create([
            'name' => $name,
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize() ?: 0,
            'alt_text' => $fallbackName,
            'caption' => null,
            'uploaded_by' => $request->user()->id,
        ]);
    }

    private function deleteLegacyCover(GalleryAlbum $album): void
    {
        if ($album->cover_media_asset_id) {
            return;
        }

        Storage::disk('public')->delete($album->cover_image_path);
    }
}
