<?php

namespace App\Providers;

use App\Models\Faq;
use App\Models\CmsPost;
use App\Models\Document;
use App\Models\ImpactMetric;
use App\Models\Person;
use App\Models\PressRelease;
use App\Models\PurchasePrice;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        foreach (array_keys(config('admin_permissions.permissions', [])) as $permission) {
            Gate::define($permission, fn ($user): bool => $user->hasPermission($permission));
        }

        View::composer('pages.resources', function ($view): void {
            $view->with('faqs', Faq::publishedFrontendFaqs());
            $view->with('documentsByType', Schema::hasTable('documents')
                ? Document::query()
                    ->published()
                    ->orderBy('sort_order')
                    ->latest('published_at')
                    ->get()
                    ->groupBy('type')
                : collect());
        });

        View::composer('pages.news', function ($view): void {
            $type = request()->routeIs('pages.articles') ? 'article' : 'news';
            $newsPosts = Schema::hasTable('posts')
                ? CmsPost::query()
                    ->with('categoryRelation')
                    ->published()
                    ->where('type', $type)
                    ->latest('published_at')
                    ->latest()
                    ->paginate(18)
                    ->withQueryString()
                : collect();
            $postCollection = method_exists($newsPosts, 'getCollection') ? $newsPosts->getCollection() : $newsPosts;

            $view->with('newsPosts', $newsPosts);
            $view->with('newsCategories', $postCollection
                ->map(fn (CmsPost $post) => $post->categoryRelation?->name ?? $post->category ?? 'News')
                ->filter()
                ->unique()
                ->values());
        });

        View::composer('pages.home', function ($view): void {
            $view->with('impactMetrics', ImpactMetric::publishedFrontendMetrics());
            $view->with('featuredDocuments', Schema::hasTable('documents')
                ? Document::query()
                    ->published()
                    ->orderBy('sort_order')
                    ->latest('published_at')
                    ->take(3)
                    ->get()
                : collect());

            $homePosts = Schema::hasTable('posts')
                ? CmsPost::query()
                    ->published()
                    ->where('type', 'news')
                    ->latest('published_at')
                    ->take(3)
                    ->get()
                    ->toBase()
                    ->map(fn (CmsPost $post) => [
                        'title' => $post->title,
                        'label' => $post->categoryRelation?->name ?? $post->category ?? 'News',
                        'date' => $post->published_at?->format('F Y'),
                        'url' => $post->publicUrl(),
                    ])
                : collect();

            $homePress = Schema::hasTable('press_releases')
                ? PressRelease::query()
                    ->published()
                    ->latest('published_at')
                    ->take(3)
                    ->get()
                    ->toBase()
                    ->map(fn (PressRelease $release) => [
                        'title' => $release->title,
                        'label' => 'Press Release',
                        'date' => $release->published_at?->format('F Y'),
                        'url' => route('pages.press'),
                    ])
                : collect();

            $view->with('homeNewsItems', $homePosts
                ->merge($homePress)
                ->take(3)
                ->values());

            $view->with('homePurchasePrice', Schema::hasTable('purchase_prices')
                ? PurchasePrice::query()
                    ->visibleOnHome()
                    ->orderBy('sort_order')
                    ->latest('display_at')
                    ->latest()
                    ->first()
                : null);
        });

        View::composer('pages.leadership', function ($view): void {
            $people = Schema::hasTable('people')
                ? Person::query()
                    ->published()
                    ->forGroup('management')
                    ->orderBy('sort_order')
                    ->get()
                : collect();

            $ceo = $people->first(fn (Person $person) => str_contains(strtolower((string) $person->position), 'chief executive'));

            $view->with('ceoPerson', $ceo);
            $view->with('managementPeople', $people
                ->reject(fn (Person $person) => $ceo && $person->is($ceo))
                ->values());
        });

        View::composer('pages.board', function ($view): void {
            $people = Schema::hasTable('people')
                ? Person::query()
                    ->published()
                    ->forGroup('board')
                    ->orderBy('sort_order')
                    ->get()
                : collect();

            $chair = $people->first(fn (Person $person) => str_contains(strtolower((string) $person->position), 'chair'));

            $view->with('boardChair', $chair);
            $view->with('boardPeople', $people
                ->reject(fn (Person $person) => $chair && $person->is($chair))
                ->values());
        });

        RateLimiter::for('public', function (Request $request) {
            return Limit::perMinute((int) config('security.rate_limits.public', 120))
                ->by($request->ip());
        });

        RateLimiter::for('admin-auth', function (Request $request) {
            return Limit::perMinute((int) config('security.rate_limits.admin_auth', 5))
                ->by($request->ip());
        });

        RateLimiter::for('admin', function (Request $request) {
            return Limit::perMinute((int) config('security.rate_limits.admin', 120))
                ->by(optional($request->user())->id ?: $request->ip());
        });

        RateLimiter::for('admin-write', function (Request $request) {
            return Limit::perMinute((int) config('security.rate_limits.admin_write', 60))
                ->by(optional($request->user())->id ?: $request->ip());
        });

        RateLimiter::for('admin-upload', function (Request $request) {
            return Limit::perMinute((int) config('security.rate_limits.admin_upload', 20))
                ->by(optional($request->user())->id ?: $request->ip());
        });

        RateLimiter::for('admin-import', function (Request $request) {
            return Limit::perMinute((int) config('security.rate_limits.admin_import', 5))
                ->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
