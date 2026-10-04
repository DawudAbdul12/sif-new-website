<?php

namespace App\Http\Controllers;

use App\Models\CmsPost;
use App\Models\Document;
use App\Models\GalleryAlbum;
use App\Models\Graphic;
use App\Models\LicenseRegistryEntry;
use App\Models\Notice;
use App\Models\PressRelease;
use App\Models\Video;
use Illuminate\View\View;

class FrontendContentController extends Controller
{
    public function press(): View
    {
        return view('pages.press', [
            'pressReleases' => PressRelease::query()
                ->published()
                ->latest('published_at')
                ->paginate(18)
                ->withQueryString(),
        ]);
    }

    public function notices(): View
    {
        return view('pages.notices', [
            'notices' => Notice::query()
                ->published()
                ->latest('published_at')
                ->paginate(18)
                ->withQueryString(),
        ]);
    }

    public function videos(): View
    {
        return view('pages.videos', [
            'videos' => Video::query()
                ->published()
                ->orderBy('sort_order')
                ->latest('published_at')
                ->paginate(18)
                ->withQueryString(),
        ]);
    }

    public function gallery(): View
    {
        return view('pages.gallery', [
            'albums' => GalleryAlbum::query()
                ->with(['images', 'coverMedia'])
                ->published()
                ->orderBy('sort_order')
                ->latest('published_at')
                ->paginate(18)
                ->withQueryString(),
        ]);
    }

    public function graphics(): View
    {
        return view('pages.graphics', [
            'graphics' => Graphic::query()
                ->with('mediaAsset')
                ->published()
                ->orderBy('sort_order')
                ->latest('published_at')
                ->paginate(18)
                ->withQueryString(),
        ]);
    }

    public function licenseRegistry(): View
    {
        return view('pages.license-registry', [
            'entries' => LicenseRegistryEntry::query()
                ->published()
                ->orderBy('category')
                ->orderBy('registry_number')
                ->paginate(50)
                ->withQueryString(),
        ]);
    }

    public function documents(string $type): View
    {
        abort_unless(array_key_exists($type, Document::TYPES), 404);

        $documents = Document::query()
            ->published()
            ->forType($type);

        if ($type === 'contracts') {
            $documents->latest('published_at')->latest();
        } else {
            $documents->orderBy('sort_order')->latest('published_at')->latest();
        }

        return view('pages.repository-documents', [
            'type' => $type,
            'title' => Document::TYPES[$type],
            'documents' => $documents->paginate(18)->withQueryString(),
        ]);
    }

    public function showNews(CmsPost $post): View
    {
        abort_unless($post->type === 'news' && $post->status === 'published' && $post->published_at, 404);

        return $this->showPost($post, 'News & Media', 'Related News');
    }

    public function showArticle(CmsPost $post): View
    {
        abort_unless($post->type === 'article' && $post->status === 'published' && $post->published_at, 404);

        return $this->showPost($post, 'Articles', 'Related Articles');
    }

    private function showPost(CmsPost $post, string $fallbackLabel, string $relatedTitle): View
    {
        $relatedPosts = CmsPost::query()
            ->with('categoryRelation')
            ->published()
            ->where('type', $post->type)
            ->whereKeyNot($post->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('pages.post-show', compact('post', 'fallbackLabel', 'relatedTitle', 'relatedPosts'));
    }
}
