<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CmsPageController;
use App\Http\Controllers\Admin\CmsPostController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\EditorUploadController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\GalleryAlbumController;
use App\Http\Controllers\Admin\GraphicController as AdminGraphicController;
use App\Http\Controllers\Admin\ImpactMetricController;
use App\Http\Controllers\Admin\LicenseRegistryController as AdminLicenseRegistryController;
use App\Http\Controllers\Admin\MediaAssetController;
use App\Http\Controllers\Admin\MediaPickerController;
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\Admin\PersonController;
use App\Http\Controllers\Admin\PressReleaseController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\PurchasePriceController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\TrashController;
use App\Http\Controllers\Admin\VideoController as AdminVideoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest', 'throttle:admin-auth'])->group(function (): void {
    Route::get('/admin/login', [AuthController::class, 'create'])->name('login');
    Route::post('/admin/login', [AuthController::class, 'store'])->name('admin.login.store');
});

Route::middleware(['auth', 'admin', 'throttle:admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', DashboardController::class)->middleware('permission:dashboard.view')->name('dashboard');

    foreach ([
        ['pages', CmsPageController::class, 'pages', []],
        ['categories', CategoryController::class, 'categories', []],
        ['posts', CmsPostController::class, 'posts', []],
        ['projects', AdminProjectController::class, 'projects', []],
        ['faqs', FaqController::class, 'faqs', []],
        ['impact-metrics', ImpactMetricController::class, 'impact', ['impact-metrics' => 'impactMetric']],
        ['press-releases', PressReleaseController::class, 'press', []],
        ['notices', NoticeController::class, 'notices', []],
        ['gallery', GalleryAlbumController::class, 'gallery', ['gallery' => 'gallery']],
        ['graphics', AdminGraphicController::class, 'graphics', []],
        ['videos', AdminVideoController::class, 'videos', []],
        ['purchase-prices', PurchasePriceController::class, 'prices', []],
        ['license-registry', AdminLicenseRegistryController::class, 'registry', ['license-registry' => 'licenseRegistry']],
        ['media', MediaAssetController::class, 'media', ['media' => 'medium']],
        ['users', AdminUserController::class, 'users', []],
        ['roles', RoleController::class, 'roles', []],
    ] as [$uri, $controller, $permission, $parameters]) {
        Route::resource($uri, $controller)->parameters($parameters)->only(['index'])->middleware("permission:{$permission}.view");
        Route::resource($uri, $controller)->parameters($parameters)->only(['create', 'store'])->middleware(["permission:{$permission}.create", 'throttle:admin-write']);
        Route::resource($uri, $controller)->parameters($parameters)->only(['edit', 'update'])->middleware(["permission:{$permission}.update", 'throttle:admin-write']);
        Route::resource($uri, $controller)->parameters($parameters)->only(['destroy'])->middleware(["permission:{$permission}.delete", 'throttle:admin-write']);
    }

    Route::post('license-registry/import', [AdminLicenseRegistryController::class, 'import'])->middleware(['permission:registry.import', 'throttle:admin-import'])->name('license-registry.import');
    Route::post('editor/images', [EditorUploadController::class, 'image'])->middleware(['permission:media.create', 'throttle:admin-upload'])->name('editor.images.store');
    Route::get('editor/media', [MediaPickerController::class, 'index'])->middleware('permission:media.view')->name('editor.media.index');

    Route::prefix('people/{group}')->name('people.')->group(function (): void {
        Route::get('/', [PersonController::class, 'index'])->middleware('permission:people.view')->name('index');
        Route::get('/create', [PersonController::class, 'create'])->middleware(['permission:people.create', 'throttle:admin-write'])->name('create');
        Route::post('/', [PersonController::class, 'store'])->middleware(['permission:people.create', 'throttle:admin-write'])->name('store');
        Route::get('/{person}/edit', [PersonController::class, 'edit'])->middleware(['permission:people.update', 'throttle:admin-write'])->name('edit');
        Route::put('/{person}', [PersonController::class, 'update'])->middleware(['permission:people.update', 'throttle:admin-write'])->name('update');
        Route::delete('/{person}', [PersonController::class, 'destroy'])->middleware(['permission:people.delete', 'throttle:admin-write'])->name('destroy');
    });

    Route::prefix('documents/{type}')->name('documents.')->group(function (): void {
        Route::get('/', [DocumentController::class, 'index'])->middleware('permission:documents.view')->name('index');
        Route::get('/create', [DocumentController::class, 'create'])->middleware(['permission:documents.create', 'throttle:admin-write'])->name('create');
        Route::post('/', [DocumentController::class, 'store'])->middleware(['permission:documents.create', 'throttle:admin-upload'])->name('store');
        Route::get('/{document}/edit', [DocumentController::class, 'edit'])->middleware(['permission:documents.update', 'throttle:admin-write'])->name('edit');
        Route::put('/{document}', [DocumentController::class, 'update'])->middleware(['permission:documents.update', 'throttle:admin-upload'])->name('update');
        Route::delete('/{document}', [DocumentController::class, 'destroy'])->middleware(['permission:documents.delete', 'throttle:admin-write'])->name('destroy');
    });

    Route::get('activity-logs', [ActivityLogController::class, 'index'])->middleware('permission:activity.view')->name('activity-logs.index');
    Route::get('activity-logs/{activityLog}', [ActivityLogController::class, 'show'])->middleware('permission:activity.view')->name('activity-logs.show');
    Route::get('trash', [TrashController::class, 'index'])->middleware('permission:trash.view')->name('trash.index');
    Route::post('trash/{type}/{id}/restore', [TrashController::class, 'restore'])->middleware(['permission:trash.restore', 'throttle:admin-write'])->name('trash.restore');
    Route::delete('trash/{type}/{id}', [TrashController::class, 'destroy'])->middleware(['permission:trash.delete', 'throttle:admin-write'])->name('trash.destroy');
    Route::get('settings', [SiteSettingController::class, 'index'])->middleware('permission:settings.view')->name('settings.index');
    Route::put('settings', [SiteSettingController::class, 'update'])->middleware(['permission:settings.update', 'throttle:admin-write'])->name('settings.update');
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
});
