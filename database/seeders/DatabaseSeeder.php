<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use App\Models\Faq;
use App\Models\ImpactMetric;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate([
            'email' => env('ADMIN_EMAIL', 'admin@sifinghana.gov.gh'),
        ], [
            'name' => env('ADMIN_NAME', 'SIF Administrator'),
            'password' => env('ADMIN_PASSWORD', 'SIF@2026'),
            'is_admin' => true,
        ]);

        collect([
            ['key' => 'site_name', 'label' => 'Site Name', 'group' => 'general', 'value' => 'SIF Ghana'],
            ['key' => 'contact_email', 'label' => 'Contact Email', 'group' => 'contact', 'value' => 'info@sifinghana.org'],
            ['key' => 'contact_phone', 'label' => 'Contact Phone', 'group' => 'contact', 'value' => '+233 030 295 3279 / 84'],
            ['key' => 'office_address', 'label' => 'Office Address', 'group' => 'contact', 'value' => 'The Social Investment Fund Head Office, Accra, Ghana'],
            ['key' => 'seo_description', 'label' => 'Default SEO Description', 'group' => 'seo', 'value' => 'The Social Investment Fund Ghana official website.'],
        ])->each(fn (array $setting) => SiteSetting::query()->updateOrCreate(
            ['key' => $setting['key']],
            $setting
        ));

        collect(config('sif_projects.projects', []))->each(function (array $project, string $slug): void {
            $statusText = strtolower($project['status'] ?? '');
            $projectStatus = str_contains($statusText, 'completed') ? 'completed' : (str_contains($statusText, 'new') ? 'new' : 'ongoing');
            $zoneKey = array_search($project['zone'] ?? '', Project::ZONES, true) ?: 'd';

            Project::query()->updateOrCreate([
                'slug' => $slug,
            ], [
                'name' => $project['name'] ?? str($slug)->headline()->toString(),
                'full_name' => $project['full_name'] ?? ($project['name'] ?? str($slug)->headline()->toString()),
                'status' => 'published',
                'project_status' => $projectStatus,
                'status_label' => $project['status'] ?? ucfirst($projectStatus),
                'timeline' => $project['timeline'] ?? null,
                'funder' => $project['funder'] ?? null,
                'fund_amount' => $project['fund_amount'] ?? null,
                'zone_key' => $zoneKey,
                'zone_name' => $project['zone'] ?? (Project::ZONES[$zoneKey] ?? null),
                'image' => $project['image'] ?? null,
                'summary' => $project['summary'] ?? null,
                'beneficiaries' => $project['beneficiaries'] ?? null,
                'categories' => ['Infrastructure', 'Community Development'],
                'regions' => $project['regions'] ?? [],
                'objectives' => $project['objectives'] ?? [],
                'outcomes' => $project['outcomes'] ?? [],
                'documents' => [],
                'related_projects' => [],
                'markers' => [],
                'seo_title' => $project['seo_title'] ?? null,
                'seo_description' => $project['seo_description'] ?? null,
                'published_at' => now(),
            ]);
        });

        collect(config('sif_faqs.faqs', []))->each(function (array $faq, int $index): void {
            Faq::query()->updateOrCreate([
                'question' => $faq['question'],
            ], [
                'answer' => $faq['answer'],
                'category' => $faq['category'] ?? 'general',
                'status' => 'published',
                'sort_order' => $index,
                'published_at' => now(),
            ]);
        });

        collect(config('sif_impact.metrics', []))->each(function (array $metric, int $index): void {
            ImpactMetric::query()->updateOrCreate([
                'label' => $metric['label'],
            ], [
                'tier' => $metric['tier'] ?? 'primary',
                'prefix' => $metric['prefix'] ?? null,
                'value' => $metric['value'],
                'suffix' => $metric['suffix'] ?? null,
                'note' => $metric['note'] ?? null,
                'status' => 'published',
                'sort_order' => $index,
                'published_at' => now(),
            ]);
        });

        $this->call(PublicDataSeeder::class);
        $this->call(LiveSifWebsiteSeeder::class);
    }
}
