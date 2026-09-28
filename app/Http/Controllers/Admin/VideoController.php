<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Support\UniqueSlug;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function index(Request $request): View
    {
        $videos = Video::query()
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('search'), fn ($query) => $query->where('title', 'like', '%'.$request->string('search').'%'))
            ->orderByDesc('published_at')
            ->latest('updated_at')
            ->paginate(12)
            ->withQueryString();

        return view('admin.videos.index', compact('videos'));
    }

    public function create(): View
    {
        return view('admin.videos.create', ['video' => new Video(['status' => 'draft'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = UniqueSlug::make('videos', $data['slug'] ?: $data['title']);
        $data['embed_url'] = $this->embedUrl($data['video_url']);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? now()) : null;
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        $this->attachThumbnail($request, $data);

        $video = Video::create($data);

        return redirect()->route('admin.videos.edit', $video)->with('status', 'Video created.');
    }

    public function edit(Video $video): View
    {
        return view('admin.videos.edit', compact('video'));
    }

    public function update(Request $request, Video $video): RedirectResponse
    {
        $data = $this->validated($request, $video);
        $data['slug'] = UniqueSlug::make('videos', $data['slug'] ?: $data['title'], $video);
        $data['embed_url'] = $this->embedUrl($data['video_url']);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? $video->published_at ?? now()) : null;
        $data['updated_by'] = $request->user()->id;

        if ($request->hasFile('thumbnail')) {
            if ($video->thumbnail_path) {
                Storage::disk('public')->delete($video->thumbnail_path);
            }

            $this->attachThumbnail($request, $data);
        }

        $video->update($data);

        return redirect()->route('admin.videos.edit', $video)->with('status', 'Video updated.');
    }

    public function destroy(Video $video): RedirectResponse
    {
        $video->delete();

        return redirect()->route('admin.videos.index')->with('status', 'Video deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Video $video = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['draft', 'review', 'published', 'archived'])],
            'description' => ['nullable', 'string', 'max:1200'],
            'video_url' => ['required', 'url', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999999'],
            'published_at' => ['nullable', 'date'],
            'thumbnail' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function attachThumbnail(Request $request, array &$data): void
    {
        if (! $request->hasFile('thumbnail')) {
            return;
        }

        $data['thumbnail_path'] = $request->file('thumbnail')->store('videos', 'public');
    }

    private function embedUrl(string $url): string
    {
        $parts = parse_url($url);
        $host = strtolower($parts['host'] ?? '');
        $path = trim($parts['path'] ?? '', '/');

        if (str_contains($host, 'youtube.com')) {
            parse_str($parts['query'] ?? '', $query);

            if (str_starts_with($path, 'embed/')) {
                return $url;
            }

            if (! empty($query['v'])) {
                return 'https://www.youtube.com/embed/'.$query['v'];
            }

            if (str_starts_with($path, 'shorts/')) {
                return 'https://www.youtube.com/embed/'.substr($path, 7);
            }
        }

        if (str_contains($host, 'youtu.be')) {
            return 'https://www.youtube.com/embed/'.$path;
        }

        if (str_contains($host, 'vimeo.com')) {
            if (str_contains($host, 'player.vimeo.com')) {
                return $url;
            }

            return 'https://player.vimeo.com/video/'.$path;
        }

        return $url;
    }
}
