<?php

use App\Http\Controllers\FrontendContentController;
use App\Http\Controllers\ProjectController;
use App\Models\Project;
use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('home');
Route::redirect('/about', '/about-us')->name('about');
Route::redirect('/board', '/board-of-directors')->name('board');
Route::view('/board-of-directors', 'pages.board');
Route::view('/leadership', 'pages.leadership')->name('leadership');
Route::view('/departments', 'pages.departments')->name('departments');
Route::get('/projects', [ProjectController::class, 'index'])->name('projects');
Route::get('/projects/{slug}', [ProjectController::class, 'show'])->name('project-detail');
Route::view('/news', 'pages.news')->name('news');
Route::get('/news/{post:slug}', [FrontendContentController::class, 'showNews'])->name('pages.news.show');
Route::get('/articles/{post:slug}', [FrontendContentController::class, 'showArticle'])->name('pages.articles.show');
Route::view('/resources', 'pages.resources')->name('resources');
Route::redirect('/contact', '/contact-us')->name('contact');
Route::view('/complaint', 'pages.complaint')->name('complaint');
Route::view('/privacy', 'pages.privacy')->name('privacy');
Route::view('/terms', 'pages.terms')->name('terms');
Route::view('/accessibility', 'pages.accessibility')->name('accessibility');
Route::view('/sitemap', 'pages.sitemap')->name('sitemap');

// CMS/public compatibility route names used by admin preview links and legacy public partials.
Route::view('/about-us', 'pages.about')->name('pages.about');
Route::view('/board-of-directors', 'pages.board')->name('pages.board');
Route::view('/management-team', 'pages.leadership')->name('pages.management');
Route::view('/organogram', 'pages.departments')->name('pages.organogram');
Route::view('/news-and-media', 'pages.news')->name('pages.news');
Route::view('/articles', 'pages.news')->name('pages.articles');
Route::get('/press-release', [FrontendContentController::class, 'press'])->name('pages.press');
Route::get('/notices', [FrontendContentController::class, 'notices'])->name('pages.notice');
Route::get('/videos', [FrontendContentController::class, 'videos'])->name('pages.videos');
Route::get('/gallery', [FrontendContentController::class, 'gallery'])->name('pages.gallery');
Route::get('/graphics', [FrontendContentController::class, 'graphics'])->name('pages.graphics');
Route::view('/resources/repository', 'pages.resources')->name('pages.repository');
Route::get('/license-registry', [FrontendContentController::class, 'licenseRegistry'])->name('pages.license-registry');
Route::get('/audited-financial-statements', fn () => app(FrontendContentController::class)->documents('audited-financial-statements'))->name('pages.audited-financial-statements');
Route::get('/quarterly-reports', fn () => app(FrontendContentController::class)->documents('quarterly-reports'))->name('pages.quarterly-reports');
Route::get('/trade-reports', fn () => app(FrontendContentController::class)->documents('trade-reports'))->name('pages.trade-reports');
Route::get('/contracts', fn () => app(FrontendContentController::class)->documents('contracts'))->name('pages.contracts');
Route::view('/right-to-information', 'pages.resources')->name('pages.rti');
Route::view('/licensing', 'pages.contact')->name('pages.licensing');
Route::view('/contact-us', 'pages.contact')->name('pages.contact');
Route::view('/corporate-social-responsibility', 'pages.resources')->name('pages.corporate-social-responsibility');
Route::view('/insurance-policy', 'pages.resources')->name('pages.insurance-policy');
Route::view('/responsible-sourcing', 'pages.resources')->name('pages.responsible-sourcing');
Route::redirect('/news-single', '/news-and-media')->name('pages.news-single');
Route::redirect('/article-single', '/articles')->name('pages.article-single');
Route::redirect('/management', '/management-team');
Route::redirect('/press', '/press-release');
Route::redirect('/notice', '/notices');
Route::redirect('/rti', '/right-to-information');

Route::get('/sitemap.xml', function () {
    $seoBaseUrl = rtrim(config('app.seo_url', 'https://sifinghana.gov.gh'), '/');
    $paths = [
        '/',
        '/about',
        '/board-of-directors',
        '/leadership',
        '/departments',
        '/projects',
        '/news',
        '/resources',
        '/contact',
        '/complaint',
        '/privacy',
        '/terms',
        '/accessibility',
        '/sitemap',
    ];

    $routes = array_map(fn ($path) => $seoBaseUrl . ($path === '/' ? '' : $path), $paths);

    $projectSlugs = collect(Project::publishedFrontendProjects())->pluck('id')->filter()->all();

    foreach ($projectSlugs as $slug) {
        $routes[] = $seoBaseUrl . '/projects/' . $slug;
    }

    $xml = view('sitemap-xml', [
        'urls' => array_unique($routes),
        'lastmod' => now()->toDateString(),
    ])->render();

    return response($xml, 200)->header('Content-Type', 'application/xml');
})->name('sitemap.xml');

require __DIR__.'/admin.php';
