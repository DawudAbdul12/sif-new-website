<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\CmsPost;
use App\Support\UniqueSlug;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CmsPostController extends Controller
{
    public function index(Request $request): View
    {
        $posts = CmsPost::query()
            ->with('categoryRelation')
            ->when($request->filled('type'), fn ($query) => $query->where('type', $request->string('type')))
            ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->integer('category_id')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('search'), fn ($query) => $query->where('title', 'like', '%'.$request->string('search').'%'))
            ->orderByRaw('published_at IS NULL')
            ->latest('published_at')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.posts.index', [
            'posts' => $posts,
            'categories' => Category::ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.posts.create', [
            'post' => new CmsPost,
            'categories' => Category::ordered()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = UniqueSlug::make('posts', $data['slug'] ?: $data['title']);
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? now()) : null;
        $data['category'] = $this->categoryName($data['category_id'] ?? null);
        $data = $this->withSeoDefaults($data);
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        $post = CmsPost::create($data);

        return redirect()->route('admin.posts.edit', $post)->with('status', 'Post created.');
    }

    public function edit(CmsPost $post): View
    {
        return view('admin.posts.edit', [
            'post' => $post,
            'categories' => Category::ordered()->get(),
            'recordActivityLogs' => ActivityLog::query()
                ->with('causer:id,name,email')
                ->where('subject_type', CmsPost::class)
                ->where('subject_id', $post->id)
                ->recent()
                ->limit(8)
                ->get(),
        ]);
    }

    public function update(Request $request, CmsPost $post): RedirectResponse
    {
        $data = $this->validated($request, $post);
        $data['slug'] = UniqueSlug::make('posts', $data['slug'] ?: $data['title'], $post);
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? $post->published_at ?? now()) : null;
        $data['category'] = $this->categoryName($data['category_id'] ?? null);
        $data = $this->withSeoDefaults($data);
        $data['updated_by'] = $request->user()->id;

        $post->update($data);

        return redirect()->route('admin.posts.edit', $post)->with('status', 'Post updated.');
    }

    public function destroy(CmsPost $post): RedirectResponse
    {
        $post->delete();

        return redirect()->route('admin.posts.index')->with('status', 'Post deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?CmsPost $post = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'type' => ['required', Rule::in(['news', 'article'])],
            'category_id' => ['nullable', 'integer', Rule::exists('categories', 'id')],
            'status' => ['required', Rule::in(['draft', 'review', 'published', 'archived'])],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'body' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'string', 'max:255'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:1000'],
            'published_at' => ['nullable', 'date'],
        ]);
    }

    private function categoryName(null|int|string $categoryId): ?string
    {
        if (! $categoryId) {
            return null;
        }

        return Category::whereKey($categoryId)->value('name');
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function withSeoDefaults(array $data): array
    {
        if (blank($data['seo_title'] ?? null)) {
            $data['seo_title'] = Str::limit($data['title'], 60, '');
        }

        if (blank($data['seo_description'] ?? null)) {
            $description = ($data['excerpt'] ?? null) ?: strip_tags((string) ($data['body'] ?? ''));
            $data['seo_description'] = Str::limit(trim(preg_replace('/\s+/', ' ', $description)), 160, '');
        }

        return $data;
    }
}
