<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('home');
Route::view('/about', 'pages.about')->name('about');
Route::view('/board-of-directors', 'pages.board')->name('board');
Route::view('/leadership', 'pages.leadership')->name('leadership');
Route::view('/departments', 'pages.departments')->name('departments');
Route::view('/projects', 'pages.projects')->name('projects');
Route::get('/projects/{slug}', fn($slug) => view('pages.project-detail', compact('slug')))->name('project-detail');
Route::view('/news', 'pages.news')->name('news');
Route::view('/resources', 'pages.resources')->name('resources');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/complaint', 'pages.complaint')->name('complaint');
Route::view('/privacy', 'pages.privacy')->name('privacy');
Route::view('/terms', 'pages.terms')->name('terms');
Route::view('/accessibility', 'pages.accessibility')->name('accessibility');
Route::view('/sitemap', 'pages.sitemap')->name('sitemap');
