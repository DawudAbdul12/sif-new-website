<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\ActivityLog;
use App\Models\CmsPage;
use App\Models\CmsPost;
use App\Models\Document;
use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use App\Models\Graphic;
use App\Models\LicenseRegistryEntry;
use App\Models\MediaAsset;
use App\Models\Notice;
use App\Models\Person;
use App\Models\PressRelease;
use App\Models\PurchasePrice;
use App\Models\Permission;
use App\Models\Role;
use App\Models\SiteSetting;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCmsTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    public function test_admin_dashboard_shows_cms_metrics(): void
    {
        CmsPage::create(['title' => 'About', 'slug' => 'about', 'created_by' => $this->admin->id]);
        CmsPost::create(['title' => 'Update', 'slug' => 'update', 'created_by' => $this->admin->id]);
        Category::create(['name' => 'Operations', 'slug' => 'operations', 'created_by' => $this->admin->id]);
        PressRelease::create(['title' => 'Release', 'slug' => 'release', 'created_by' => $this->admin->id]);
        Notice::create(['title' => 'Notice', 'slug' => 'notice', 'created_by' => $this->admin->id]);
        GalleryAlbum::create([
            'title' => 'Field Visit',
            'slug' => 'field-visit',
            'created_by' => $this->admin->id,
        ]);
        Video::create([
            'title' => 'Market Update',
            'slug' => 'market-update',
            'video_url' => 'https://www.youtube.com/watch?v=gbaBlQhSAzI',
            'embed_url' => 'https://www.youtube.com/embed/gbaBlQhSAzI',
            'created_by' => $this->admin->id,
        ]);
        Graphic::create([
            'title' => 'Export Compliance Graphic',
            'slug' => 'export-compliance-graphic',
            'status' => 'published',
            'image_path' => 'graphics/export-compliance.jpg',
            'created_by' => $this->admin->id,
        ]);
        PurchasePrice::create([
            'title' => 'GoldBod Approved Purchase Price per Pound',
            'status' => 'published',
            'lbma_pm_price' => 4075,
            'exchange_rate' => 11.64,
            'discount_rate' => 0,
            'total_price_per_pound' => 11330,
            'created_by' => $this->admin->id,
        ]);
        LicenseRegistryEntry::create([
            'category' => 'buyerTier2',
            'registry_number' => 1,
            'business_name' => 'Bullionoak Limited Company',
            'certificate_number' => 'GGB/LB2/T20333/005/25',
            'issued_date' => '2025-06-23',
            'expiry_date' => '2026-06-22',
            'created_by' => $this->admin->id,
        ]);
        Document::create(['type' => 'contracts', 'title' => 'Contract', 'slug' => 'contract', 'created_by' => $this->admin->id]);
        Person::create(['group' => 'board', 'name' => 'Board Member', 'slug' => 'board-member', 'created_by' => $this->admin->id]);

        $this->actingAs($this->admin)
            ->get('/admin')
            ->assertOk()
            ->assertSee('CMS overview')
            ->assertSee('Pages')
            ->assertSee('Posts')
            ->assertSee('Categories')
            ->assertSee('Press')
            ->assertSee('Notices')
            ->assertSee('Videos')
            ->assertSee('Gallery')
            ->assertSee('Graphics')
            ->assertSee('Docs')
            ->assertSee('People');
    }

    public function test_admin_can_create_page(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.pages.store'), [
                'title' => 'Corporate Profile',
                'slug' => '',
                'template' => 'default',
                'status' => 'published',
                'excerpt' => 'GoldBod corporate profile.',
                'body' => 'Full page body.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('pages', [
            'title' => 'Corporate Profile',
            'slug' => 'corporate-profile',
            'status' => 'published',
            'created_by' => $this->admin->id,
        ]);
    }

    public function test_models_record_activity_for_create_update_and_delete(): void
    {
        $this->actingAs($this->admin);

        $page = CmsPage::create([
            'title' => 'Audit Page',
            'slug' => 'audit-page',
            'status' => 'draft',
            'created_by' => $this->admin->id,
        ]);

        $page->update([
            'title' => 'Updated Audit Page',
            'status' => 'published',
            'published_at' => now(),
            'updated_by' => $this->admin->id,
        ]);

        $page->delete();

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'created',
            'subject_type' => CmsPage::class,
            'subject_id' => $page->id,
            'subject_label' => 'Audit Page',
            'causer_id' => $this->admin->id,
        ]);

        $updated = ActivityLog::where('action', 'updated')
            ->where('subject_type', CmsPage::class)
            ->where('subject_id', $page->id)
            ->latest('id')
            ->firstOrFail();

        $this->assertSame('Audit Page', $updated->old_values['title']);
        $this->assertSame('Updated Audit Page', $updated->new_values['title']);
        $this->assertContains('status', $updated->changed_attributes);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'deleted',
            'subject_type' => CmsPage::class,
            'subject_id' => $page->id,
            'subject_label' => 'Updated Audit Page',
            'causer_id' => $this->admin->id,
        ]);
    }

    public function test_activity_log_masks_sensitive_user_fields_and_has_admin_screen(): void
    {
        $this->actingAs($this->admin);

        $user = User::factory()->create([
            'name' => 'Audit User',
            'email' => 'audit-user@example.test',
            'password' => 'secret-password',
        ]);

        $log = ActivityLog::where('subject_type', User::class)
            ->where('subject_id', $user->id)
            ->where('action', 'created')
            ->firstOrFail();

        $this->assertArrayNotHasKey('password', $log->new_values);
        $this->assertArrayNotHasKey('remember_token', $log->new_values);

        $this->actingAs($this->admin)
            ->get(route('admin.activity-logs.index'))
            ->assertOk()
            ->assertSee('Activity Log')
            ->assertSee('Audit User')
            ->assertSee('Created');

        $this->actingAs($this->admin)
            ->get(route('admin.activity-logs.show', $log))
            ->assertOk()
            ->assertSee('Changed Values')
            ->assertSee('Email')
            ->assertSee('Method')
            ->assertDontSee('secret-password');
    }

    public function test_activity_log_ignores_null_changed_attributes(): void
    {
        $this->actingAs($this->admin);

        $log = ActivityLog::create([
            'action' => 'updated',
            'subject_type' => CmsPage::class,
            'subject_id' => 999,
            'subject_label' => 'Dirty Activity Row',
            'causer_id' => $this->admin->id,
            'causer_name' => $this->admin->name,
            'causer_email' => $this->admin->email,
            'old_values' => ['title' => 'Before'],
            'new_values' => ['title' => 'After'],
            'changed_attributes' => [null, 'title'],
        ]);

        $this->get(route('admin.activity-logs.index'))
            ->assertOk()
            ->assertSee('Dirty Activity Row')
            ->assertSee('Title');

        $this->get(route('admin.activity-logs.show', $log))
            ->assertOk()
            ->assertSee('Before')
            ->assertSee('After');
    }

    public function test_post_edit_page_shows_record_specific_activity_log(): void
    {
        $this->actingAs($this->admin);

        $post = CmsPost::create([
            'title' => 'Record Activity Post',
            'slug' => 'record-activity-post',
            'type' => 'news',
            'status' => 'draft',
            'created_by' => $this->admin->id,
            'updated_by' => $this->admin->id,
        ]);

        $post->update([
            'title' => 'Updated Record Activity Post',
            'updated_by' => $this->admin->id,
        ]);

        $this->get(route('admin.posts.edit', $post))
            ->assertOk()
            ->assertSee('Activity Log')
            ->assertSee('Updated')
            ->assertSee('Title')
            ->assertSee('subject_id='.$post->id, false);
    }

    public function test_edit_pages_show_record_specific_activity_log_panel(): void
    {
        $this->actingAs($this->admin);
        Storage::fake('public');
        Storage::disk('public')->put('graphics/panel.jpg', 'image');

        $page = CmsPage::create(['title' => 'Panel Page', 'slug' => 'panel-page', 'created_by' => $this->admin->id]);
        $category = Category::create(['name' => 'Panel Category', 'slug' => 'panel-category', 'created_by' => $this->admin->id]);
        $pressRelease = PressRelease::create(['title' => 'Panel Release', 'slug' => 'panel-release', 'created_by' => $this->admin->id]);
        $notice = Notice::create(['title' => 'Panel Notice', 'slug' => 'panel-notice', 'created_by' => $this->admin->id]);
        $video = Video::create([
            'title' => 'Panel Video',
            'slug' => 'panel-video',
            'video_url' => 'https://www.youtube.com/watch?v=gbaBlQhSAzI',
            'embed_url' => 'https://www.youtube.com/embed/gbaBlQhSAzI',
            'created_by' => $this->admin->id,
        ]);
        $album = GalleryAlbum::create(['title' => 'Panel Album', 'slug' => 'panel-album', 'created_by' => $this->admin->id]);
        $graphic = Graphic::create([
            'title' => 'Panel Graphic',
            'slug' => 'panel-graphic',
            'image_path' => 'graphics/panel.jpg',
            'created_by' => $this->admin->id,
        ]);
        $purchasePrice = PurchasePrice::create([
            'title' => 'Panel Price',
            'status' => 'draft',
            'lbma_pm_price' => 4075,
            'exchange_rate' => 11.64,
            'discount_rate' => 0,
            'total_price_per_pound' => 11330,
            'created_by' => $this->admin->id,
        ]);
        $licenseRegistry = LicenseRegistryEntry::create([
            'category' => 'buyerTier2',
            'registry_number' => 44,
            'business_name' => 'Panel Bullion Limited',
            'certificate_number' => 'GGB/LB2/PANEL/044/26',
            'issued_date' => '2026-01-01',
            'expiry_date' => '2026-12-31',
            'created_by' => $this->admin->id,
        ]);
        $document = Document::create([
            'type' => 'contracts',
            'title' => 'Panel Contract',
            'slug' => 'panel-contract',
            'file_path' => 'repository-documents/panel.pdf',
            'created_by' => $this->admin->id,
        ]);
        $person = Person::create(['group' => 'board', 'name' => 'Panel Person', 'slug' => 'panel-person', 'created_by' => $this->admin->id]);
        $asset = MediaAsset::create([
            'name' => 'Panel Asset',
            'file_path' => 'media/panel.pdf',
            'mime_type' => 'application/pdf',
            'size' => 1200,
            'uploaded_by' => $this->admin->id,
        ]);
        $user = User::factory()->create(['name' => 'Panel User', 'is_admin' => true]);

        $routes = [
            route('admin.pages.edit', $page) => $page,
            route('admin.categories.edit', $category) => $category,
            route('admin.press-releases.edit', $pressRelease) => $pressRelease,
            route('admin.notices.edit', $notice) => $notice,
            route('admin.videos.edit', $video) => $video,
            route('admin.gallery.edit', $album) => $album,
            route('admin.graphics.edit', $graphic) => $graphic,
            route('admin.purchase-prices.edit', $purchasePrice) => $purchasePrice,
            route('admin.license-registry.edit', $licenseRegistry) => $licenseRegistry,
            route('admin.documents.edit', [$document->type, $document]) => $document,
            route('admin.people.edit', [$person->group, $person]) => $person,
            route('admin.media.edit', $asset) => $asset,
            route('admin.users.edit', $user) => $user,
        ];

        foreach ($routes as $url => $model) {
            $this->get($url)
                ->assertOk()
                ->assertSee('Activity Log')
                ->assertSee('subject_id='.$model->getKey(), false);
        }
    }

    public function test_admin_can_restore_soft_deleted_records_from_trash(): void
    {
        $this->actingAs($this->admin);

        $page = CmsPage::create([
            'title' => 'Trash Page',
            'slug' => 'trash-page',
            'status' => 'draft',
            'created_by' => $this->admin->id,
            'updated_by' => $this->admin->id,
        ]);

        $this->delete(route('admin.pages.destroy', $page))
            ->assertRedirect(route('admin.pages.index'));

        $this->assertSoftDeleted('pages', ['id' => $page->id]);

        $this->get(route('admin.pages.index'))
            ->assertOk()
            ->assertDontSee('Trash Page');

        $this->get(route('admin.trash.index', ['type' => 'pages']))
            ->assertOk()
            ->assertSee('Trash Page')
            ->assertSee('Restore')
            ->assertSee('Delete Forever');

        $this->post(route('admin.trash.restore', ['pages', $page->id]))
            ->assertRedirect(route('admin.trash.index', ['type' => 'pages']));

        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'deleted_at' => null,
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'restored',
            'subject_type' => CmsPage::class,
            'subject_id' => $page->id,
            'causer_id' => $this->admin->id,
        ]);
    }

    public function test_cms_models_use_soft_deletes(): void
    {
        $models = [
            User::class,
            CmsPage::class,
            CmsPost::class,
            Category::class,
            PressRelease::class,
            Notice::class,
            GalleryAlbum::class,
            GalleryImage::class,
            Graphic::class,
            Video::class,
            PurchasePrice::class,
            LicenseRegistryEntry::class,
            Document::class,
            Person::class,
            MediaAsset::class,
            SiteSetting::class,
        ];

        foreach ($models as $model) {
            $this->assertContains(SoftDeletes::class, class_uses_recursive($model));
        }
    }

    public function test_admin_can_permanently_delete_records_from_trash(): void
    {
        $this->actingAs($this->admin);

        $post = CmsPost::create([
            'title' => 'Permanent Trash Post',
            'slug' => 'permanent-trash-post',
            'type' => 'news',
            'status' => 'draft',
            'created_by' => $this->admin->id,
            'updated_by' => $this->admin->id,
        ]);

        $post->delete();

        $this->delete(route('admin.trash.destroy', ['posts', $post->id]))
            ->assertRedirect(route('admin.trash.index', ['type' => 'posts']));

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'permanently_deleted',
            'subject_type' => CmsPost::class,
            'subject_id' => $post->id,
            'causer_id' => $this->admin->id,
        ]);
    }

    public function test_admin_can_create_post(): void
    {
        $category = Category::create([
            'name' => 'Operations',
            'slug' => 'operations',
            'type' => 'news',
            'created_by' => $this->admin->id,
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.posts.store'), [
                'title' => 'GoldBod Announces Update',
                'slug' => '',
                'type' => 'news',
                'status' => 'draft',
                'category_id' => $category->id,
                'excerpt' => 'Short summary.',
                'body' => 'Full post body.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('posts', [
            'title' => 'GoldBod Announces Update',
            'slug' => 'goldbod-announces-update',
            'type' => 'news',
            'category_id' => $category->id,
            'category' => 'Operations',
            'status' => 'draft',
            'seo_title' => 'GoldBod Announces Update',
            'seo_description' => 'Short summary.',
        ]);
    }

    public function test_admin_posts_are_ordered_by_recent_publish_date_first(): void
    {
        CmsPost::create([
            'title' => 'Older Published Article',
            'slug' => 'older-published-article',
            'type' => 'article',
            'status' => 'published',
            'published_at' => '2025-03-28 10:00:00',
            'created_by' => $this->admin->id,
        ]);

        CmsPost::create([
            'title' => 'Unpublished Draft Article',
            'slug' => 'unpublished-draft-article',
            'type' => 'article',
            'status' => 'draft',
            'published_at' => null,
            'created_by' => $this->admin->id,
        ]);

        CmsPost::create([
            'title' => 'Recent Published Article',
            'slug' => 'recent-published-article',
            'type' => 'article',
            'status' => 'published',
            'published_at' => '2025-03-29 10:00:00',
            'created_by' => $this->admin->id,
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.posts.index'))
            ->assertOk()
            ->assertSeeInOrder([
                'Recent Published Article',
                'Older Published Article',
                'Unpublished Draft Article',
            ]);
    }

    public function test_news_articles_and_admin_posts_use_short_pagination_window(): void
    {
        foreach (range(1, 180) as $index) {
            CmsPost::create([
                'title' => 'Public News '.$index,
                'slug' => 'public-news-'.$index,
                'type' => 'news',
                'status' => 'published',
                'published_at' => now()->subMinutes($index),
                'created_by' => $this->admin->id,
            ]);

            CmsPost::create([
                'title' => 'Public Article '.$index,
                'slug' => 'public-article-'.$index,
                'type' => 'article',
                'status' => 'published',
                'published_at' => now()->subMinutes($index),
                'created_by' => $this->admin->id,
            ]);
        }

        $this->get(route('pages.news', ['page' => 5]))
            ->assertOk()
            ->assertSee('page=4')
            ->assertSee('page=6')
            ->assertDontSee('page=3')
            ->assertDontSee('page=7');

        $this->get(route('pages.articles', ['page' => 5]))
            ->assertOk()
            ->assertSee('page=4')
            ->assertSee('page=6')
            ->assertDontSee('page=3')
            ->assertDontSee('page=7');

        $this->actingAs($this->admin)
            ->get(route('admin.posts.index', ['page' => 15]))
            ->assertOk()
            ->assertSee('page=14')
            ->assertSee('page=16')
            ->assertDontSee('page=13')
            ->assertDontSee('page=17');
    }

    public function test_duplicate_post_title_gets_numbered_slug(): void
    {
        CmsPost::create([
            'title' => 'GoldBod Announces Update',
            'slug' => 'goldbod-announces-update',
            'type' => 'news',
            'status' => 'draft',
            'created_by' => $this->admin->id,
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.posts.store'), [
                'title' => 'GoldBod Announces Update',
                'slug' => '',
                'type' => 'news',
                'status' => 'draft',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('posts', [
            'title' => 'GoldBod Announces Update',
            'slug' => 'goldbod-announces-update-1',
        ]);
    }

    public function test_duplicate_manual_slug_gets_next_available_number(): void
    {
        CmsPost::create([
            'title' => 'First Post',
            'slug' => 'same-name',
            'type' => 'news',
            'status' => 'draft',
            'created_by' => $this->admin->id,
        ]);

        CmsPost::create([
            'title' => 'Second Post',
            'slug' => 'same-name-1',
            'type' => 'news',
            'status' => 'draft',
            'created_by' => $this->admin->id,
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.posts.store'), [
                'title' => 'Third Post',
                'slug' => 'same-name',
                'type' => 'news',
                'status' => 'draft',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('posts', [
            'title' => 'Third Post',
            'slug' => 'same-name-2',
        ]);
    }

    public function test_updating_item_keeps_own_slug_without_numbering(): void
    {
        $post = CmsPost::create([
            'title' => 'Existing Post',
            'slug' => 'existing-post',
            'type' => 'news',
            'status' => 'draft',
            'created_by' => $this->admin->id,
        ]);

        $this->actingAs($this->admin)
            ->put(route('admin.posts.update', $post), [
                'title' => 'Existing Post',
                'slug' => 'existing-post',
                'type' => 'article',
                'status' => 'draft',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'slug' => 'existing-post',
            'type' => 'article',
        ]);
    }

    public function test_post_seo_falls_back_to_title_and_body_when_empty(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.posts.store'), [
                'title' => 'Responsible Sourcing Programme Expansion',
                'slug' => '',
                'type' => 'article',
                'status' => 'draft',
                'excerpt' => '',
                'body' => '<p>GoldBod expands responsible sourcing controls for licensed market operators.</p>',
                'seo_title' => '',
                'seo_description' => '',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('posts', [
            'slug' => 'responsible-sourcing-programme-expansion',
            'seo_title' => 'Responsible Sourcing Programme Expansion',
            'seo_description' => 'GoldBod expands responsible sourcing controls for licensed market operators.',
        ]);
    }

    public function test_admin_can_manage_post_categories(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.categories.store'), [
                'name' => 'Market Intelligence',
                'slug' => '',
                'type' => 'article',
                'status' => 'active',
                'description' => 'Reports, insights, and market education.',
                'color' => '#d8b449',
                'sort_order' => 2,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('categories', [
            'name' => 'Market Intelligence',
            'slug' => 'market-intelligence',
            'type' => 'article',
            'status' => 'active',
            'sort_order' => 2,
            'created_by' => $this->admin->id,
        ]);
    }

    public function test_admin_can_upload_editor_image_for_wysiwyg(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)
            ->postJson(route('admin.editor.images.store'), [
                'image' => UploadedFile::fake()->create('editor-image.jpg', 128, 'image/jpeg'),
                'alt_text' => 'GoldBod editor image',
                'caption' => 'Editor image caption',
            ])
            ->assertOk()
            ->assertJsonPath('url', fn (string $url): bool => str_starts_with($url, '/storage/editor-images/'))
            ->assertJsonPath('alt_text', 'GoldBod editor image')
            ->assertJsonPath('caption', 'Editor image caption');

        $asset = MediaAsset::where('name', 'editor-image')->firstOrFail();

        Storage::disk('public')->assertExists($asset->file_path);
        $this->assertSame('GoldBod editor image', $asset->alt_text);
    }

    public function test_post_editor_can_select_images_from_media_library(): void
    {
        Storage::fake('public');

        foreach (range(1, 25) as $index) {
            Storage::disk('public')->put("cms-media/library-image-{$index}.jpg", 'image-bytes');

            MediaAsset::create([
                'name' => "Library Image {$index}",
                'file_path' => "cms-media/library-image-{$index}.jpg",
                'mime_type' => 'image/jpeg',
                'size' => 11,
                'alt_text' => "Library alt text {$index}",
                'caption' => "Library caption {$index}",
                'uploaded_by' => $this->admin->id,
            ]);
        }

        MediaAsset::create([
            'name' => 'Board Charter PDF',
            'file_path' => 'cms-media/board-charter.pdf',
            'mime_type' => 'application/pdf',
            'size' => 2048,
            'uploaded_by' => $this->admin->id,
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.posts.create'))
            ->assertOk()
            ->assertSee('Media Library')
            ->assertSee('Load More');

        $this->actingAs($this->admin)
            ->getJson(route('admin.editor.media.index', ['type' => 'images']))
            ->assertOk()
            ->assertJsonCount(20, 'data')
            ->assertJsonPath('meta.per_page', 20)
            ->assertJsonPath('meta.total', 25);

        $this->actingAs($this->admin)
            ->getJson(route('admin.editor.media.index', ['type' => 'images', 'page' => 2]))
            ->assertOk()
            ->assertJsonCount(5, 'data');

        $this->actingAs($this->admin)
            ->getJson(route('admin.editor.media.index', ['type' => 'images', 'search' => 'Image 25']))
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.url', '/storage/cms-media/library-image-25.jpg');
    }

    public function test_media_library_picker_supports_cursor_pagination(): void
    {
        Storage::fake('public');

        foreach (range(1, 45) as $index) {
            MediaAsset::create([
                'name' => "Cursor Image {$index}",
                'file_path' => "cms-media/cursor-image-{$index}.jpg",
                'mime_type' => 'image/jpeg',
                'size' => 11,
                'uploaded_by' => $this->admin->id,
            ]);
        }

        $firstPage = $this->actingAs($this->admin)
            ->getJson(route('admin.editor.media.index', [
                'type' => 'images',
                'cursor_mode' => 1,
                'per_page' => 18,
            ]))
            ->assertOk()
            ->assertJsonCount(18, 'data')
            ->assertJsonPath('meta.cursor_mode', true)
            ->assertJsonPath('meta.has_more', true)
            ->json();

        $this->actingAs($this->admin)
            ->getJson(route('admin.editor.media.index', [
                'type' => 'images',
                'cursor_mode' => 1,
                'per_page' => 18,
                'cursor' => $firstPage['meta']['next_cursor'],
            ]))
            ->assertOk()
            ->assertJsonCount(18, 'data')
            ->assertJsonPath('data.0.id', $firstPage['meta']['next_cursor'] - 1);
    }

    public function test_post_editor_has_premium_feature_image_controls(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.posts.create'))
            ->assertOk()
            ->assertSee('Feature Image')
            ->assertSee('Browse')
            ->assertSee('Library')
            ->assertSee('Preview')
            ->assertSee('Clear')
            ->assertSee('featured_image_file');
    }

    public function test_posts_only_accept_news_and_article_types(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.posts.store'), [
                'title' => 'Press Should Not Live Here',
                'slug' => '',
                'type' => 'press',
                'status' => 'draft',
            ])
            ->assertSessionHasErrors('type');
    }

    public function test_admin_can_create_press_release_with_file(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)
            ->post(route('admin.press-releases.store'), [
                'title' => 'Official Market Update',
                'slug' => '',
                'status' => 'published',
                'brief_description' => 'A short official release summary.',
                'body' => 'Full release details.',
                'file' => UploadedFile::fake()->create('release.pdf', 128, 'application/pdf'),
            ])
            ->assertRedirect();

        $release = PressRelease::firstOrFail();

        Storage::disk('public')->assertExists($release->file_path);
        $this->assertDatabaseHas('press_releases', [
            'title' => 'Official Market Update',
            'slug' => 'official-market-update',
            'status' => 'published',
            'file_name' => 'release.pdf',
        ]);
    }

    public function test_admin_press_releases_are_ordered_by_publish_date_desc(): void
    {
        PressRelease::create([
            'title' => 'Older Release',
            'slug' => 'older-release',
            'status' => 'published',
            'published_at' => now()->subDays(5),
            'created_by' => $this->admin->id,
        ]);

        PressRelease::create([
            'title' => 'Draft Release',
            'slug' => 'draft-release',
            'status' => 'draft',
            'published_at' => null,
            'created_by' => $this->admin->id,
        ]);

        PressRelease::create([
            'title' => 'Latest Release',
            'slug' => 'latest-release',
            'status' => 'published',
            'published_at' => now()->subDay(),
            'created_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.press-releases.index'))
            ->assertOk();

        $response->assertSeeInOrder([
            'Latest Release',
            'Older Release',
            'Draft Release',
        ]);
    }

    public function test_public_press_releases_paginate_eighteen_per_page(): void
    {
        foreach (range(1, 19) as $index) {
            PressRelease::create([
                'title' => 'Public Release '.$index,
                'slug' => 'public-release-'.$index,
                'status' => 'published',
                'published_at' => now()->subMinutes($index),
                'created_by' => $this->admin->id,
            ]);
        }

        $response = $this->get(route('pages.press'))
            ->assertOk()
            ->assertSee('Public Release 1')
            ->assertSee('Public Release 18')
            ->assertDontSee('Public Release 19');

        $visibleText = preg_replace('/\s+/', ' ', strip_tags($response->getContent()));

        $this->assertStringContainsString('Showing 1 to 18 of 19 results', $visibleText);
    }

    public function test_admin_can_create_notice_with_file(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)
            ->post(route('admin.notices.store'), [
                'title' => 'License Renewal Notice',
                'slug' => '',
                'status' => 'published',
                'brief_description' => 'Renewal notice summary.',
                'body' => 'Full notice details.',
                'expires_at' => now()->addMonth()->format('Y-m-d H:i:s'),
                'file' => UploadedFile::fake()->create('notice.pdf', 128, 'application/pdf'),
            ])
            ->assertRedirect();

        $notice = Notice::firstOrFail();

        Storage::disk('public')->assertExists($notice->file_path);
        $this->assertDatabaseHas('notices', [
            'title' => 'License Renewal Notice',
            'slug' => 'license-renewal-notice',
            'status' => 'published',
            'file_name' => 'notice.pdf',
        ]);
    }

    public function test_admin_can_create_video_and_show_it_publicly(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)
            ->post(route('admin.videos.store'), [
                'title' => 'GoldBod Market Update',
                'slug' => '',
                'status' => 'published',
                'description' => 'Weekly gold market update.',
                'video_url' => 'https://www.youtube.com/watch?v=gbaBlQhSAzI',
                'sort_order' => 2,
                'thumbnail' => UploadedFile::fake()->image('market-update.jpg', 800, 450),
            ])
            ->assertRedirect();

        $video = Video::firstOrFail();

        Storage::disk('public')->assertExists($video->thumbnail_path);
        $this->assertDatabaseHas('videos', [
            'title' => 'GoldBod Market Update',
            'slug' => 'goldbod-market-update',
            'status' => 'published',
            'embed_url' => 'https://www.youtube.com/embed/gbaBlQhSAzI',
            'created_by' => $this->admin->id,
        ]);

        $this->get(route('pages.videos'))
            ->assertOk()
            ->assertSee('GoldBod Market Update')
            ->assertSee('https://www.youtube.com/embed/gbaBlQhSAzI');
    }

    public function test_admin_can_create_gallery_album_and_show_it_publicly(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)
            ->post(route('admin.gallery.store'), [
                'title' => 'GoldBod Community Visit',
                'slug' => '',
                'status' => 'published',
                'description' => 'Highlights from a community visit.',
                'sort_order' => 1,
                'images' => [
                    UploadedFile::fake()->image('visit-one.jpg', 900, 600),
                    UploadedFile::fake()->image('visit-two.jpg', 900, 600),
                ],
            ])
            ->assertRedirect();

        $album = GalleryAlbum::with('images')->firstOrFail();

        $this->assertCount(2, $album->images);
        $this->assertNotNull($album->images->first()->media_asset_id);
        $this->assertDatabaseCount('media_assets', 2);
        $this->assertStringStartsWith('gallery/albums/', $album->images->first()->image_path);
        Storage::disk('public')->assertExists($album->images->first()->image_path);
        $this->assertDatabaseHas('media_assets', [
            'id' => $album->images->first()->media_asset_id,
            'file_path' => $album->images->first()->image_path,
            'uploaded_by' => $this->admin->id,
        ]);
        $this->assertDatabaseHas('gallery_albums', [
            'title' => 'GoldBod Community Visit',
            'slug' => 'goldbod-community-visit',
            'status' => 'published',
            'created_by' => $this->admin->id,
        ]);

        $this->get(route('pages.gallery'))
            ->assertOk()
            ->assertSee('GoldBod Community Visit')
            ->assertSee('gallery-modal');
    }

    public function test_gallery_album_can_use_media_library_images(): void
    {
        Storage::fake('public');
        $path = UploadedFile::fake()->image('library-photo.jpg', 900, 600)->store('cms-media', 'public');

        $asset = MediaAsset::create([
            'name' => 'Library Photo',
            'file_path' => $path,
            'mime_type' => 'image/jpeg',
            'size' => 120,
            'alt_text' => 'Library photo alt',
            'caption' => 'Library caption',
            'uploaded_by' => $this->admin->id,
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.gallery.store'), [
                'title' => 'Media Library Album',
                'slug' => '',
                'status' => 'published',
                'description' => 'Built from library images.',
                'cover_media_asset_id' => $asset->id,
                'library_images' => [$asset->id],
            ])
            ->assertRedirect();

        $album = GalleryAlbum::with('images')->firstOrFail();

        $this->assertSame($asset->id, $album->cover_media_asset_id);
        $this->assertSame($asset->id, $album->images->first()->media_asset_id);
        $this->assertDatabaseCount('media_assets', 1);

        $this->get(route('pages.gallery'))
            ->assertOk()
            ->assertSee('Media Library Album')
            ->assertSee('Library photo alt');
    }

    public function test_gallery_cover_upload_is_saved_to_media_library_storage(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)
            ->post(route('admin.gallery.store'), [
                'title' => 'Covered Album',
                'slug' => '',
                'status' => 'published',
                'cover_image' => UploadedFile::fake()->image('cover.jpg', 1200, 750),
                'images' => [
                    UploadedFile::fake()->image('album-photo.jpg', 900, 600),
                ],
            ])
            ->assertRedirect();

        $album = GalleryAlbum::firstOrFail();

        $this->assertNotNull($album->cover_media_asset_id);
        $this->assertStringStartsWith('gallery/covers/', $album->cover_image_path);
        Storage::disk('public')->assertExists($album->cover_image_path);
        $this->assertDatabaseHas('media_assets', [
            'id' => $album->cover_media_asset_id,
            'file_path' => $album->cover_image_path,
            'uploaded_by' => $this->admin->id,
        ]);
    }

    public function test_admin_can_create_graphic_and_show_it_publicly(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)
            ->post(route('admin.graphics.store'), [
                'title' => 'Responsible Sourcing Infographic',
                'slug' => '',
                'status' => 'published',
                'description' => 'A visual guide for responsible gold sourcing.',
                'alt_text' => 'Responsible sourcing infographic',
                'sort_order' => 1,
                'published_at' => now()->format('Y-m-d H:i:s'),
                'image' => UploadedFile::fake()->image('responsible-sourcing.png', 900, 1200),
            ])
            ->assertRedirect();

        $graphic = Graphic::firstOrFail();

        $this->assertNotNull($graphic->media_asset_id);
        $this->assertStringStartsWith('graphics/', $graphic->image_path);
        Storage::disk('public')->assertExists($graphic->image_path);
        $this->assertDatabaseHas('graphics', [
            'title' => 'Responsible Sourcing Infographic',
            'slug' => 'responsible-sourcing-infographic',
            'status' => 'published',
            'media_asset_id' => $graphic->media_asset_id,
            'created_by' => $this->admin->id,
        ]);
        $this->assertDatabaseHas('media_assets', [
            'id' => $graphic->media_asset_id,
            'file_path' => $graphic->image_path,
            'uploaded_by' => $this->admin->id,
        ]);

        $this->get(route('pages.graphics'))
            ->assertOk()
            ->assertSee('Responsible Sourcing Infographic')
            ->assertSee('Responsible sourcing infographic');
    }

    public function test_graphic_can_use_media_library_image(): void
    {
        Storage::fake('public');
        $path = UploadedFile::fake()->image('library-graphic.jpg', 900, 1200)->store('cms-media', 'public');

        $asset = MediaAsset::create([
            'name' => 'Library Graphic',
            'file_path' => $path,
            'mime_type' => 'image/jpeg',
            'size' => 120,
            'alt_text' => 'Library graphic alt',
            'caption' => 'Library graphic caption',
            'uploaded_by' => $this->admin->id,
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.graphics.store'), [
                'title' => 'Media Library Graphic',
                'slug' => '',
                'status' => 'published',
                'description' => 'Selected from the media library.',
                'media_asset_id' => $asset->id,
            ])
            ->assertRedirect();

        $graphic = Graphic::firstOrFail();

        $this->assertSame($asset->id, $graphic->media_asset_id);
        $this->assertSame($asset->file_path, $graphic->image_path);
        $this->assertSame('Library graphic alt', $graphic->alt_text);
        $this->assertDatabaseCount('media_assets', 1);

        $this->get(route('pages.graphics'))
            ->assertOk()
            ->assertSee('Media Library Graphic')
            ->assertSee('Library graphic alt');
    }

    public function test_graphics_are_ordered_by_sort_order_then_publish_date(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('graphics/second-newer.jpg', 'image-bytes');
        Storage::disk('public')->put('graphics/first-older.jpg', 'image-bytes');
        Storage::disk('public')->put('graphics/second-latest.jpg', 'image-bytes');

        Graphic::create([
            'title' => 'Second Position Newer Graphic',
            'slug' => 'second-position-newer-graphic',
            'status' => 'published',
            'image_path' => 'graphics/second-newer.jpg',
            'sort_order' => 2,
            'published_at' => '2026-08-10 09:00:00',
            'created_by' => $this->admin->id,
        ]);

        Graphic::create([
            'title' => 'First Position Older Graphic',
            'slug' => 'first-position-older-graphic',
            'status' => 'published',
            'image_path' => 'graphics/first-older.jpg',
            'sort_order' => 1,
            'published_at' => '2026-08-01 09:00:00',
            'created_by' => $this->admin->id,
        ]);

        Graphic::create([
            'title' => 'Second Position Latest Graphic',
            'slug' => 'second-position-latest-graphic',
            'status' => 'published',
            'image_path' => 'graphics/second-latest.jpg',
            'sort_order' => 2,
            'published_at' => '2026-08-12 09:00:00',
            'created_by' => $this->admin->id,
        ]);

        $this->get(route('pages.graphics'))
            ->assertOk()
            ->assertSeeInOrder([
                'First Position Older Graphic',
                'Second Position Latest Graphic',
                'Second Position Newer Graphic',
            ]);

        $this->actingAs($this->admin)
            ->get(route('admin.graphics.index'))
            ->assertOk()
            ->assertSeeInOrder([
                'First Position Older Graphic',
                'Second Position Latest Graphic',
                'Second Position Newer Graphic',
            ]);
    }

    public function test_admin_can_create_purchase_price_and_show_it_on_homepage(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.purchase-prices.store'), [
                'title' => 'GoldBod Approved Purchase Price per Pound',
                'subtitle' => 'Based on LBMA PM Price | Valid: 2:00 PM - 8:30 PM',
                'status' => 'published',
                'lbma_price_session' => 'PM',
                'lbma_pm_price' => 4075,
                'lbma_price_visibility' => 'visible',
                'rate_label' => 'Exchange Rate',
                'exchange_rate' => 11.64,
                'rate_visibility' => 'visible',
                'secondary_rate_label' => 'BRR for financing window',
                'secondary_rate' => '',
                'secondary_rate_visibility' => 'hidden',
                'discount_rate' => 0,
                'discount_rate_visibility' => 'visible',
                'total_price_per_pound' => 11330,
                'total_price_visibility' => 'visible',
                'bonus_label' => '',
                'bonus_amount' => '',
                'bonus_visibility' => 'hidden',
                'alternate_total_label' => '',
                'alternate_total_amount' => '',
                'alternate_total_visibility' => 'hidden',
                'price_currency' => 'USD',
                'currency' => 'GHS',
                'display_at' => '2026-07-27 14:00:00',
                'valid_from' => now()->subHour()->format('Y-m-d H:i:s'),
                'valid_until' => now()->addHour()->format('Y-m-d H:i:s'),
                'show_on_home' => '1',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('purchase_prices', [
            'title' => 'GoldBod Approved Purchase Price per Pound',
            'status' => 'published',
            'show_on_home' => true,
            'created_by' => $this->admin->id,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('GoldBod Approved Purchase Price per Pound')
            ->assertSee('USD 4,075.00')
            ->assertSee('USD 1 = 11.64')
            ->assertSee('GHS 11,330')
            ->assertDontSee('GHS 11,330.00');
    }

    public function test_purchase_price_popup_supports_faded_and_hidden_rows(): void
    {
        PurchasePrice::create([
            'title' => 'GoldBod Approved Purchase Price per Pound',
            'subtitle' => 'Based on LBMA AM Price | Valid: 9:00 AM - 12:00 PM',
            'status' => 'published',
            'lbma_price_session' => 'AM',
            'lbma_pm_price' => 4028.15,
            'lbma_price_visibility' => 'visible',
            'rate_label' => 'BRR for financing window',
            'exchange_rate' => 11.59,
            'rate_visibility' => 'visible',
            'secondary_rate_label' => 'Exchange Rate',
            'secondary_rate' => 11.59,
            'secondary_rate_visibility' => 'faded',
            'discount_rate' => 0,
            'discount_rate_visibility' => 'hidden',
            'total_price_per_pound' => 11152,
            'total_price_visibility' => 'visible',
            'bonus_label' => "NB: GoldBod's Special Temporary Bonus for licensed Miners per pound",
            'bonus_amount' => 382,
            'bonus_visibility' => 'faded',
            'alternate_total_label' => 'Total price per pound',
            'alternate_total_amount' => 13004,
            'alternate_total_visibility' => 'faded',
            'price_currency' => 'USD',
            'currency' => 'GHS',
            'display_at' => now(),
            'valid_from' => now()->subHour(),
            'valid_until' => now()->addHour(),
            'show_on_home' => true,
            'created_by' => $this->admin->id,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('LBMA AM Price (per ounce)')
            ->assertSee('BRR for financing window')
            ->assertSee('gold-price-row faded', false)
            ->assertSee('Exchange Rate')
            ->assertDontSee('Discount Rate')
            ->assertSee('NB: GoldBod&#039;s Special Temporary Bonus for licensed Miners per pound', false)
            ->assertSee('GHS 11,152')
            ->assertSee('GHS 13,004')
            ->assertDontSee('GHS 11,152.00')
            ->assertDontSee('GHS 13,004.00');
    }

    public function test_admin_can_create_license_registry_entry(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.license-registry.store'), [
                'category' => 'buyerTier2',
                'registry_number' => 1,
                'business_name' => 'BULLIONOAK LIMITED COMPANY',
                'certificate_number' => 'GGB/LB2/T20333/005/25',
                'issued_date' => '2025-06-23',
                'expiry_date' => '2026-06-22',
                'status' => 'active',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('license_registry_entries', [
            'category' => 'buyerTier2',
            'registry_number' => 1,
            'business_name' => 'BULLIONOAK LIMITED COMPANY',
            'certificate_number' => 'GGB/LB2/T20333/005/25',
            'created_by' => $this->admin->id,
        ]);
    }

    public function test_admin_can_import_license_registry_csv(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'license-registry-');
        file_put_contents($path, implode(PHP_EOL, [
            'SNO,REGISTERED BUSINESS NAME,LICENSE CERTIFICATE NUMBER,ISSUED DATE,EXPIRY DATE',
            '1,BULLIONOAK LIMITED COMPANY,GGB/LB2/T20333/005/25,23-Jun-25,22-Jun-26',
            '771,DOTMOSE GOLD BUYING ENTERPRISE,GGB/LB2/T20699/389/26,"May 29,2026","May 28,2027"',
        ]));

        $file = new UploadedFile($path, 'TIER-2-22-2-26.csv', 'text/csv', null, true);

        $this->actingAs($this->admin)
            ->post(route('admin.license-registry.import'), [
                'category' => 'buyerTier2',
                'file' => $file,
            ])
            ->assertRedirect(route('admin.license-registry.index', ['category' => 'buyerTier2']));

        $this->assertDatabaseHas('license_registry_entries', [
            'category' => 'buyerTier2',
            'registry_number' => 1,
            'business_name' => 'BULLIONOAK LIMITED COMPANY',
            'certificate_number' => 'GGB/LB2/T20333/005/25',
            'issued_date' => '2025-06-23 00:00:00',
            'expiry_date' => '2026-06-22 00:00:00',
        ]);

        $this->assertDatabaseHas('license_registry_entries', [
            'category' => 'buyerTier2',
            'registry_number' => 771,
            'business_name' => 'DOTMOSE GOLD BUYING ENTERPRISE',
            'certificate_number' => 'GGB/LB2/T20699/389/26',
            'issued_date' => '2026-05-29 00:00:00',
            'expiry_date' => '2027-05-28 00:00:00',
        ]);
    }

    public function test_public_license_registry_uses_cms_entries(): void
    {
        LicenseRegistryEntry::create([
            'category' => 'buyerTier2',
            'registry_number' => 1,
            'business_name' => 'BULLIONOAK LIMITED COMPANY',
            'certificate_number' => 'GGB/LB2/T20333/005/25',
            'issued_date' => '2025-06-23',
            'expiry_date' => '2026-06-22',
            'status' => 'active',
            'created_by' => $this->admin->id,
        ]);

        $this->get(route('pages.license-registry'))
            ->assertOk()
            ->assertSee('BULLIONOAK LIMITED COMPANY')
            ->assertSee('GGB\/LB2\/T20333\/005\/25', false)
            ->assertSee('Buyer (Tier 2)');
    }

    public function test_public_frontend_pages_use_cms_content(): void
    {
        $post = CmsPost::create([
            'title' => 'Published Market Update',
            'slug' => 'published-market-update',
            'type' => 'news',
            'status' => 'published',
            'excerpt' => 'Market update summary.',
            'body' => '<p>Market update body.</p>',
            'published_at' => now(),
            'created_by' => $this->admin->id,
        ]);

        PressRelease::create([
            'title' => 'Official Published Release',
            'slug' => 'official-published-release',
            'status' => 'published',
            'published_at' => now(),
            'created_by' => $this->admin->id,
        ]);

        Notice::create([
            'title' => 'Official Published Notice',
            'slug' => 'official-published-notice',
            'status' => 'published',
            'published_at' => now(),
            'created_by' => $this->admin->id,
        ]);

        Document::create([
            'type' => 'contracts',
            'title' => 'Published Contract',
            'slug' => 'published-contract',
            'status' => 'published',
            'published_at' => now(),
            'created_by' => $this->admin->id,
        ]);

        Person::create([
            'group' => 'management',
            'name' => 'Published Manager',
            'slug' => 'published-manager',
            'position' => 'Director',
            'status' => 'published',
            'published_at' => now(),
            'created_by' => $this->admin->id,
        ]);

        $this->get('/')->assertOk()->assertSee('Published Market Update')->assertSee('Official Published Release');
        $this->get(route('pages.news'))->assertOk()->assertSee('Published Market Update');
        $this->get(route('pages.news.show', $post->slug))->assertOk()->assertSee('Market update body', false);
        $this->get(route('pages.press'))->assertOk()->assertSee('Official Published Release');
        $this->get(route('pages.notice'))->assertOk()->assertSee('Official Published Notice');
        $this->get(route('pages.contracts'))->assertOk()->assertSee('Published Contract');
        $this->get(route('pages.management'))->assertOk()->assertSee('Published Manager');
    }

    public function test_dynamic_public_pages_render_without_published_content(): void
    {
        foreach ([
            route('home'),
            route('pages.news'),
            route('pages.articles'),
            route('pages.press'),
            route('pages.notice'),
            route('pages.board'),
            route('pages.management'),
            route('pages.contracts'),
            route('pages.trade-reports'),
            route('pages.quarterly-reports'),
            route('pages.audited-financial-statements'),
            route('pages.license-registry'),
        ] as $url) {
            $this->get($url)->assertOk();
        }

        $this->get(route('pages.news-single'))->assertRedirect(route('pages.news'));
        $this->get(route('pages.article-single'))->assertRedirect(route('pages.articles'));
    }

    public function test_updated_public_page_urls_and_legacy_redirects(): void
    {
        $this->assertSame('http://websi.test/about-us', route('pages.about'));
        $this->assertSame('http://websi.test/board-of-directors', route('pages.board'));
        $this->assertSame('http://websi.test/management-team', route('pages.management'));
        $this->assertSame('http://websi.test/press-release', route('pages.press'));
        $this->assertSame('http://websi.test/contact-us', route('pages.contact'));
        $this->assertSame('http://websi.test/notices', route('pages.notice'));
        $this->assertSame('http://websi.test/right-to-information', route('pages.rti'));

        foreach ([
            '/about' => '/about-us',
            '/board' => '/board-of-directors',
            '/management' => '/management-team',
            '/press' => '/press-release',
            '/contact' => '/contact-us',
            '/notice' => '/notices',
            '/rti' => '/right-to-information',
        ] as $oldUrl => $newUrl) {
            $this->get($oldUrl)->assertRedirect($newUrl);
        }
    }

    public function test_admin_can_create_repository_documents_for_each_type(): void
    {
        Storage::fake('public');

        foreach (array_keys(Document::TYPES) as $type) {
            $this->actingAs($this->admin)
                ->post(route('admin.documents.store', $type), [
                    'title' => Document::TYPES[$type].' 2026',
                    'slug' => '',
                    'status' => 'published',
                    'brief_description' => 'Brief repository document description.',
                    'fiscal_year' => '2026',
                    'document_date' => '2026-08-01',
                    'counterparty' => $type === 'contracts' ? 'Gold Coast Refinery' : null,
                    'sort_order' => 1,
                    'file' => UploadedFile::fake()->create($type.'.pdf', 128, 'application/pdf'),
                ])
                ->assertRedirect();
        }

        foreach (array_keys(Document::TYPES) as $type) {
            $this->assertDatabaseHas('documents', [
                'type' => $type,
                'fiscal_year' => '2026',
                'status' => 'published',
            ]);

            $document = Document::where('type', $type)->firstOrFail();

            Storage::disk('public')->assertExists($document->file_path);
            $this->assertStringStartsWith('repository-documents/', $document->file_path);
        }

        $this->assertCount(count(Document::TYPES), Document::all());
    }

    public function test_audited_financial_statements_use_published_date_without_download_footer(): void
    {
        $this->travelTo('2026-08-03 01:57:35');

        Document::create([
            'type' => 'audited-financial-statements',
            'title' => '2025 Audited Financial Statements - Ghana Gold Board',
            'slug' => '2025-audited-financial-statements-ghana-gold-board',
            'status' => 'published',
            'document_date' => '2025-12-31',
            'file_path' => 'repository-documents/audited.pdf',
            'published_at' => '2026-08-10 09:30:00',
            'created_by' => $this->admin->id,
        ]);

        $this->get(route('pages.audited-financial-statements'))
            ->assertOk()
            ->assertSee('August 10, 2026')
            ->assertDontSee('August 3, 2026')
            ->assertDontSee('December 31, 2025')
            ->assertDontSee('View or Download');

        $this->travelBack();
    }

    public function test_contracts_display_by_date_added_without_position_control(): void
    {
        $this->travelTo('2026-08-01 09:00:00');
        Document::create([
            'type' => 'contracts',
            'title' => 'Older Contract With First Position',
            'slug' => 'older-contract-with-first-position',
            'status' => 'published',
            'sort_order' => 0,
            'published_at' => now(),
            'created_by' => $this->admin->id,
        ]);

        $this->travelTo('2026-08-03 09:00:00');
        Document::create([
            'type' => 'contracts',
            'title' => 'Newer Contract With Later Position',
            'slug' => 'newer-contract-with-later-position',
            'status' => 'published',
            'sort_order' => 99,
            'published_at' => now(),
            'created_by' => $this->admin->id,
        ]);
        $this->travelBack();

        $response = $this->get(route('pages.contracts'))
            ->assertOk()
            ->assertDontSee('August 3, 2026');

        $html = $response->getContent();
        $this->assertLessThan(
            strpos($html, 'Older Contract With First Position'),
            strpos($html, 'Newer Contract With Later Position')
        );

        $this->actingAs($this->admin)
            ->get(route('admin.documents.create', 'contracts'))
            ->assertOk()
            ->assertDontSee('Sort Order');
    }

    public function test_admin_can_create_people_profiles_for_board_and_management(): void
    {
        Storage::fake('public');

        foreach (array_keys(Person::GROUPS) as $group) {
            $this->actingAs($this->admin)
                ->post(route('admin.people.store', $group), [
                    'name' => Person::GROUPS[$group].' Profile',
                    'slug' => '',
                    'position' => $group === 'board' ? 'Chairperson' : 'Chief Executive Officer',
                    'department' => $group === 'management' ? 'Executive Office' : null,
                    'appointment_type' => $group === 'board' ? 'Presidential Appointment' : 'Executive',
                    'status' => 'published',
                    'brief_profile' => 'Short profile summary.',
                    'bio' => 'Long biography.',
                    'email' => 'profile-'.$group.'@goldbod.gov.gh',
                    'linkedin_url' => 'https://www.linkedin.com/company/goldbod',
                    'show_seal' => '1',
                    'sort_order' => 1,
                    'photo' => UploadedFile::fake()->create($group.'.jpg', 128, 'image/jpeg'),
                ])
                ->assertRedirect();
        }

        foreach (array_keys(Person::GROUPS) as $group) {
            $this->assertDatabaseHas('people', [
                'group' => $group,
                'status' => 'published',
                'sort_order' => 1,
            ]);

            $person = Person::where('group', $group)->firstOrFail();

            Storage::disk('public')->assertExists($person->photo_path);
            $this->assertStringStartsWith('people/', $person->photo_path);
        }

        $this->assertCount(2, Person::all());
    }

    public function test_admin_can_upload_media(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)
            ->post(route('admin.media.store'), [
                'file' => UploadedFile::fake()->create('report.pdf', 128, 'application/pdf'),
                'name' => 'Quarterly Report',
                'alt_text' => 'Report file',
                'caption' => 'Q4 report',
            ])
            ->assertRedirect(route('admin.media.index'));

        $asset = MediaAsset::firstOrFail();

        Storage::disk('public')->assertExists($asset->file_path);
        $this->assertSame('Quarterly Report', $asset->name);
    }

    public function test_media_library_has_paginated_controls(): void
    {
        foreach (range(1, 25) as $index) {
            MediaAsset::create([
                'name' => "Media Asset {$index}",
                'file_path' => "cms-media/media-asset-{$index}.jpg",
                'mime_type' => 'image/jpeg',
                'size' => 1024,
                'uploaded_by' => $this->admin->id,
            ]);
        }

        $this->actingAs($this->admin)
            ->get(route('admin.media.index', ['per_page' => 12]))
            ->assertOk()
            ->assertSee('12 / page')
            ->assertSee('Showing 1-12 of 25 media assets')
            ->assertSee('Page 1 of 3')
            ->assertSee('pagination', false);
    }

    public function test_media_asset_urls_are_host_relative(): void
    {
        config(['filesystems.disks.public.url' => '/storage']);

        $asset = MediaAsset::create([
            'name' => 'Homepage Gold',
            'file_path' => 'cms-media/homepage-gold.jpg',
            'mime_type' => 'image/jpeg',
            'size' => 2048,
            'uploaded_by' => $this->admin->id,
        ]);

        $this->assertSame('/storage/cms-media/homepage-gold.jpg', $asset->url());
    }

    public function test_uploaded_file_urls_can_use_spaces_url(): void
    {
        config(['filesystems.disks.public.url' => 'https://goldbod.nyc3.digitaloceanspaces.com']);

        $asset = MediaAsset::create([
            'name' => 'Spaces Asset',
            'file_path' => 'cms-media/spaces-asset.jpg',
            'mime_type' => 'image/jpeg',
            'size' => 2048,
            'uploaded_by' => $this->admin->id,
        ]);

        $this->assertSame('https://goldbod.nyc3.digitaloceanspaces.com/cms-media/spaces-asset.jpg', $asset->url());
    }

    public function test_spaces_upload_check_requires_s3_public_disk(): void
    {
        config(['filesystems.disks.public.driver' => 'local']);

        $this->artisan('uploads:check-spaces')
            ->expectsOutput('The public disk is not using s3. Set PUBLIC_FILESYSTEM_DRIVER=s3 in production.')
            ->assertExitCode(1);
    }

    public function test_spaces_upload_check_passes_with_complete_spaces_config(): void
    {
        config([
            'filesystems.disks.public' => [
                'driver' => 's3',
                'key' => 'spaces-key',
                'secret' => 'spaces-secret',
                'region' => 'nyc3',
                'bucket' => 'goldbod',
                'endpoint' => 'https://nyc3.digitaloceanspaces.com',
                'url' => 'https://goldbod.nyc3.digitaloceanspaces.com',
                'use_path_style_endpoint' => false,
                'visibility' => 'public',
                'throw' => false,
            ],
        ]);

        $this->artisan('uploads:check-spaces')
            ->expectsOutput('Public upload disk is configured for DigitalOcean Spaces.')
            ->expectsOutput('Bucket: goldbod')
            ->assertExitCode(0);
    }

    public function test_spaces_upload_check_can_verify_public_probe_file(): void
    {
        Http::fake([
            'https://goldbod.nyc3.digitaloceanspaces.com/health-checks/*' => Http::response('ok'),
        ]);

        Storage::fake('public');

        config([
            'filesystems.disks.public' => [
                'driver' => 's3',
                'key' => 'spaces-key',
                'secret' => 'spaces-secret',
                'region' => 'nyc3',
                'bucket' => 'goldbod',
                'endpoint' => 'https://nyc3.digitaloceanspaces.com',
                'url' => 'https://goldbod.nyc3.digitaloceanspaces.com',
                'use_path_style_endpoint' => false,
                'visibility' => 'public',
                'throw' => false,
            ],
        ]);

        $this->artisan('uploads:check-spaces --write')
            ->expectsOutput('Probe upload is writable, readable, and publicly reachable.')
            ->assertExitCode(0);

        Http::assertSent(fn ($request): bool => str_starts_with($request->url(), 'https://goldbod.nyc3.digitaloceanspaces.com/health-checks/'));
    }

    public function test_admin_can_update_settings(): void
    {
        SiteSetting::create([
            'key' => 'site_name',
            'label' => 'Site Name',
            'group' => 'general',
            'value' => 'GoldBod',
        ]);

        $this->actingAs($this->admin)
            ->put(route('admin.settings.update'), [
                'settings' => ['site_name' => 'Ghana Gold Board'],
            ])
            ->assertRedirect(route('admin.settings.index'));

        $this->assertDatabaseHas('site_settings', [
            'key' => 'site_name',
            'value' => 'Ghana Gold Board',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'updated',
            'subject_type' => SiteSetting::class,
            'subject_label' => 'Site Name',
            'causer_id' => $this->admin->id,
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.settings.index'))
            ->assertOk()
            ->assertSee('Settings Activity')
            ->assertSee('Updated');
    }

    public function test_admin_can_create_another_admin_user(): void
    {
        $role = Role::where('slug', 'content-manager')->firstOrFail();

        $this->actingAs($this->admin)
            ->post(route('admin.users.store'), [
                'name' => 'Content Manager',
                'email' => 'content@goldbod.gov.gh',
                'password' => 'VerySecure123',
                'is_admin' => '1',
                'roles' => [$role->id],
            ])
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'content@goldbod.gov.gh',
            'is_admin' => true,
        ]);

        $this->assertDatabaseHas('role_user', [
            'role_id' => $role->id,
            'user_id' => User::where('email', 'content@goldbod.gov.gh')->value('id'),
        ]);
    }

    public function test_roles_control_admin_access(): void
    {
        $role = Role::create([
            'name' => 'Posts Only',
            'slug' => 'posts-only',
            'description' => 'Can only manage posts.',
        ]);

        $role->permissions()->sync([
            Permission::where('name', 'dashboard.view')->value('id'),
            Permission::where('name', 'posts.view')->value('id'),
        ]);

        $limitedAdmin = User::factory()->create(['is_admin' => true]);
        $limitedAdmin->roles()->sync([$role->id]);

        $this->actingAs($limitedAdmin)
            ->get(route('admin.posts.index'))
            ->assertOk();

        $this->actingAs($limitedAdmin)
            ->get(route('admin.posts.create'))
            ->assertForbidden();

        $this->actingAs($limitedAdmin)
            ->get(route('admin.users.index'))
            ->assertForbidden();
    }
}
