<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CmsPage;
use App\Models\CmsPost;
use App\Models\Document;
use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use App\Models\Graphic;
use App\Models\Person;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LiveSifWebsiteSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/sif_live_content.json');

        if (! is_file($path)) {
            $this->command?->warn('No scraped SIF snapshot found at '.$path);
            return;
        }

        $data = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
        $now = now();
        $adminId = DB::table('users')->where('is_admin', true)->value('id');
        $categoryIds = $this->ensureCategories($data['posts'] ?? [], $adminId);

        foreach ($data['pages'] ?? [] as $page) {
            CmsPage::query()->updateOrCreate(
                ['slug' => $page['slug']],
                [
                    'title' => $page['title'],
                    'template' => $page['template'] ?? 'default',
                    'status' => 'published',
                    'excerpt' => $page['excerpt'] ?? null,
                    'body' => $page['body_html'] ?? null,
                    'seo_title' => $page['seo_title'] ?? $page['title'],
                    'seo_description' => $page['seo_description'] ?? ($page['excerpt'] ?? null),
                    'published_at' => $now,
                    'created_by' => $adminId,
                    'updated_by' => $adminId,
                ]
            );
        }

        foreach ($data['posts'] ?? [] as $post) {
            CmsPost::query()->updateOrCreate(
                ['slug' => $post['slug']],
                [
                    'title' => $post['title'],
                    'type' => 'news',
                    'category_id' => $categoryIds[$post['category'] ?? 'Programme Updates'] ?? null,
                    'category' => $post['category'] ?? 'Programme Updates',
                    'status' => 'published',
                    'excerpt' => $post['excerpt'] ?? null,
                    'body' => $post['body'] ?? null,
                    'featured_image' => $post['featured_image'] ?? null,
                    'seo_title' => $post['title'].' | SIF Ghana',
                    'seo_description' => $post['excerpt'] ?? null,
                    'published_at' => $now,
                    'created_by' => $adminId,
                    'updated_by' => $adminId,
                ]
            );
        }

        foreach ($data['documents'] ?? [] as $index => $document) {
            Document::query()->updateOrCreate(
                ['slug' => $document['slug']],
                [
                    'type' => $document['type'] ?? 'publications',
                    'title' => $document['title'],
                    'status' => 'published',
                    'brief_description' => $document['brief_description'] ?? null,
                    'file_path' => $document['file_path'] ?? null,
                    'file_name' => $document['file_name'] ?? null,
                    'file_mime_type' => $document['file_mime_type'] ?? null,
                    'sort_order' => ($index + 1) * 10,
                    'published_at' => $now,
                    'created_by' => $adminId,
                    'updated_by' => $adminId,
                ]
            );
        }

        foreach ($data['people'] ?? [] as $index => $person) {
            Person::query()->updateOrCreate(
                ['slug' => $person['slug']],
                [
                    'group' => $person['group'] ?? 'management',
                    'name' => $person['name'],
                    'position' => $person['position'] ?? null,
                    'status' => 'published',
                    'brief_profile' => $person['brief_profile'] ?? null,
                    'bio' => $person['bio'] ?? null,
                    'photo_path' => $person['photo_path'] ?? null,
                    'show_seal' => true,
                    'sort_order' => ($index + 1) * 10,
                    'published_at' => $now,
                    'created_by' => $adminId,
                    'updated_by' => $adminId,
                ]
            );
        }

        foreach ($data['projects'] ?? [] as $project) {
            Project::query()->updateOrCreate(
                ['slug' => $project['slug']],
                [
                    'name' => $project['name'],
                    'full_name' => $project['full_name'] ?? $project['name'],
                    'status' => 'published',
                    'project_status' => 'ongoing',
                    'status_label' => 'Published',
                    'zone_key' => 'd',
                    'zone_name' => Project::ZONES['d'] ?? null,
                    'image' => $project['image'] ?? null,
                    'summary' => $project['summary'] ?? null,
                    'objectives' => [],
                    'outcomes' => [],
                    'documents' => [],
                    'related_projects' => [],
                    'markers' => [],
                    'seo_title' => $project['name'].' | SIF Ghana',
                    'seo_description' => $project['summary'] ?? null,
                    'published_at' => $now,
                ]
            );
        }

        foreach ($data['gallery_albums'] ?? [] as $albumIndex => $albumData) {
            $images = $albumData['images'] ?? [];
            $album = GalleryAlbum::query()->updateOrCreate(
                ['slug' => $albumData['slug']],
                [
                    'title' => $albumData['title'],
                    'status' => 'published',
                    'description' => $albumData['description'] ?? null,
                    'cover_image_path' => $albumData['cover_image_path'] ?? null,
                    'sort_order' => ($albumIndex + 1) * 10,
                    'published_at' => $now,
                    'created_by' => $adminId,
                    'updated_by' => $adminId,
                ]
            );

            foreach ($images as $imageIndex => $image) {
                GalleryImage::query()->updateOrCreate(
                    ['gallery_album_id' => $album->id, 'image_path' => $image['url']],
                    [
                        'alt_text' => $image['alt'] ?? $albumData['title'],
                        'caption' => $image['alt'] ?? null,
                        'sort_order' => ($imageIndex + 1) * 10,
                    ]
                );
            }
        }

        foreach ($data['graphics'] ?? [] as $index => $graphic) {
            Graphic::query()->updateOrCreate(
                ['slug' => $graphic['slug']],
                [
                    'title' => $graphic['title'],
                    'status' => 'published',
                    'description' => $graphic['description'] ?? null,
                    'image_path' => $graphic['image_path'],
                    'alt_text' => $graphic['alt_text'] ?? $graphic['title'],
                    'sort_order' => ($index + 1) * 10,
                    'published_at' => $now,
                    'created_by' => $adminId,
                    'updated_by' => $adminId,
                ]
            );
        }
    }

    private function ensureCategories(array $posts, ?int $adminId): array
    {
        $names = collect($posts)->pluck('category')->filter()->unique()->values();
        $ids = [];

        foreach ($names as $index => $name) {
            $category = Category::query()->updateOrCreate(
                ['slug' => str($name)->slug()->toString()],
                [
                    'name' => $name,
                    'type' => 'news',
                    'status' => 'active',
                    'description' => 'Scraped from the live SIF Ghana website.',
                    'color' => '#17472d',
                    'sort_order' => ($index + 1) * 10,
                    'created_by' => $adminId,
                    'updated_by' => $adminId,
                ]
            );

            $ids[$name] = $category->id;
        }

        return $ids;
    }
}
