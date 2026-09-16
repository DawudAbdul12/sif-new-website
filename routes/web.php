<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('home');
Route::view('/about', 'pages.about')->name('about');
Route::view('/board-of-directors', 'pages.board')->name('board');
Route::view('/leadership', 'pages.leadership')->name('leadership');
Route::view('/departments', 'pages.departments')->name('departments');
Route::view('/projects', 'pages.projects')->name('projects');
Route::get('/projects/{slug}', function ($slug) {
    $project = config("sif_projects.projects.$slug");
    abort_unless($project, 404);

    return view('pages.project-detail', compact('slug', 'project'));
})->name('project-detail');
Route::view('/news', 'pages.news')->name('news');
Route::view('/resources', 'pages.resources')->name('resources');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/complaint', 'pages.complaint')->name('complaint');
Route::view('/privacy', 'pages.privacy')->name('privacy');
Route::view('/terms', 'pages.terms')->name('terms');
Route::view('/accessibility', 'pages.accessibility')->name('accessibility');
Route::view('/sitemap', 'pages.sitemap')->name('sitemap');

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

    foreach (array_keys(config('sif_projects.projects', [])) as $slug) {
        $routes[] = $seoBaseUrl . '/projects/' . $slug;
    }

    $xml = view('sitemap-xml', [
        'urls' => array_unique($routes),
        'lastmod' => now()->toDateString(),
    ])->render();

    return response($xml, 200)->header('Content-Type', 'application/xml');
})->name('sitemap.xml');
