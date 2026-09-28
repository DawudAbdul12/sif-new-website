<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\CmsPage;
use App\Models\CmsPost;
use App\Models\Document;
use App\Models\Faq;
use App\Models\GalleryAlbum;
use App\Models\Graphic;
use App\Models\ImpactMetric;
use App\Models\LicenseRegistryEntry;
use App\Models\MediaAsset;
use App\Models\Notice;
use App\Models\Person;
use App\Models\PressRelease;
use App\Models\Project;
use App\Models\PurchasePrice;
use App\Models\User;
use App\Models\Video;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $publishedPosts = CmsPost::published()->count();
        $publishedPages = CmsPage::published()->count();

        return view('admin.dashboard.index', [
            'pageCount' => CmsPage::count(),
            'postCount' => CmsPost::count(),
            'publishedPostCount' => $publishedPosts,
            'draftPostCount' => CmsPost::where('status', 'draft')->count(),
            'publishedPageCount' => $publishedPages,
            'projectCount' => Project::count(),
            'faqCount' => Faq::count(),
            'impactMetricCount' => ImpactMetric::count(),
            'categoryCount' => Category::count(),
            'pressReleaseCount' => PressRelease::count(),
            'noticeCount' => Notice::count(),
            'galleryCount' => GalleryAlbum::count(),
            'graphicCount' => Graphic::count(),
            'videoCount' => Video::count(),
            'purchasePriceCount' => PurchasePrice::count(),
            'licenseRegistryCount' => LicenseRegistryEntry::count(),
            'documentCount' => Document::count(),
            'peopleCount' => Person::count(),
            'mediaCount' => MediaAsset::count(),
            'adminCount' => User::where('is_admin', true)->count(),
            'activityCount' => ActivityLog::count(),
            'trashCount' => $this->trashCount(),
            'recentPosts' => CmsPost::with('categoryRelation')->latest()->take(5)->get(),
            'recentActivities' => ActivityLog::with('causer:id,name,email')->recent()->take(6)->get(),
        ]);
    }

    private function trashCount(): int
    {
        return collect([
            'users',
            'pages',
            'posts',
            'projects',
            'faqs',
            'impact_metrics',
            'categories',
            'press_releases',
            'notices',
            'gallery_albums',
            'gallery_images',
            'graphics',
            'videos',
            'purchase_prices',
            'license_registry_entries',
            'documents',
            'people',
            'media_assets',
            'roles',
            'site_settings',
        ])->sum(fn (string $table): int => (int) DB::table($table)->whereNotNull('deleted_at')->count());
    }
}
