<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectCmsTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    public function test_project_form_supports_media_library_and_upload_image_selection(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.projects.create'))
            ->assertOk()
            ->assertSee('Hero / Card Image URL')
            ->assertSee('id="project_image_file"', false)
            ->assertSee('id="project-image-upload"', false)
            ->assertSee('id="project-image-library"', false)
            ->assertSee('admin\/editor\/images', false)
            ->assertSee('admin\/editor\/media', false);
    }

    public function test_project_form_uses_region_multiselect_with_all_ghana_regions(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.projects.create'))
            ->assertOk()
            ->assertSee('id="project-region-picker"', false)
            ->assertSee('name="regions[]"', false);

        foreach (Project::REGIONS as $region) {
            $response->assertSee($region);
        }
    }

    public function test_admin_can_create_project_for_public_pages(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.projects.store'), [
                'name' => 'YEP',
                'slug' => '',
                'full_name' => 'Youth Enterprise Programme',
                'status' => 'published',
                'project_status' => 'ongoing',
                'status_label' => 'Active',
                'timeline' => '2026 - 2028',
                'funder' => 'Government of Ghana',
                'fund_amount' => 'GHS 10M',
                'zone_key' => 'a',
                'zone_name' => '',
                'image' => '/images/yep.jpg',
                'summary' => 'Enterprise support for young people.',
                'beneficiaries' => 'Young entrepreneurs in northern Ghana.',
                'categories_text' => "Employment\nWomen and Youth",
                'regions' => ['Northern', 'Upper East'],
                'objectives_text' => "Create jobs\nSupport MSMEs",
                'outcomes_text' => "New enterprises supported\nAccess to finance expanded",
                'documents_text' => 'Programme brief | /file/yep.pdf',
                'related_projects_text' => 'gwyesco',
                'markers_text' => 'Tamale | 9.4035 | -0.8393',
                'seo_title' => '',
                'seo_description' => '',
                'sort_order' => 2,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('projects', [
            'name' => 'YEP',
            'slug' => 'yep',
            'status' => 'published',
            'project_status' => 'ongoing',
            'zone_name' => 'Savannah Belt',
            'created_by' => $this->admin->id,
            'updated_by' => $this->admin->id,
        ]);

        $project = Project::firstWhere('slug', 'yep');

        $this->assertSame(['Employment', 'Women and Youth'], $project->categories);
        $this->assertSame([['label' => 'Programme brief', 'url' => '/file/yep.pdf']], $project->documents);
        $this->assertSame([['city' => 'Tamale', 'lat' => 9.4035, 'lng' => -0.8393]], $project->markers);

        $this->get('/projects')
            ->assertOk()
            ->assertSee('Youth Enterprise Programme')
            ->assertSee('/projects/yep');

        $this->get('/projects/yep')
            ->assertOk()
            ->assertSee('Youth Enterprise Programme')
            ->assertSee('Enterprise support for young people.')
            ->assertSee('Programme brief');
    }

    public function test_admin_can_update_project_without_changing_its_slug(): void
    {
        $project = Project::create([
            'name' => 'PSDPEP',
            'slug' => 'psdpep',
            'full_name' => 'Post-COVID-19 Skills Development and Productivity Enhancement Project',
            'status' => 'draft',
            'project_status' => 'ongoing',
            'created_by' => $this->admin->id,
            'updated_by' => $this->admin->id,
        ]);

        $this->actingAs($this->admin)
            ->put(route('admin.projects.update', $project), [
                'name' => 'PSDPEP',
                'slug' => 'psdpep',
                'full_name' => 'Post-COVID-19 Skills Development and Productivity Enhancement Project',
                'status' => 'published',
                'project_status' => 'completed',
                'status_label' => 'Completed',
                'timeline' => '2023 - 2027',
                'funder' => 'AfDB and Government of Ghana',
                'fund_amount' => 'US$31.3M',
                'zone_key' => 'd',
                'zone_name' => '',
                'image' => '/images/psdpep.jpg',
                'summary' => 'Updated project summary.',
                'beneficiaries' => 'MSMEs and institutions.',
                'categories_text' => 'Employment',
                'regions_text' => 'Greater Accra',
                'objectives_text' => 'Improve productivity',
                'outcomes_text' => 'Facilities rehabilitated',
                'documents_text' => '',
                'related_projects_text' => '',
                'markers_text' => '',
                'seo_title' => '',
                'seo_description' => '',
                'sort_order' => 1,
            ])
            ->assertRedirect(route('admin.projects.edit', $project));

        $project->refresh();

        $this->assertSame('psdpep', $project->slug);
        $this->assertSame('published', $project->status);
        $this->assertSame('completed', $project->project_status);
        $this->assertNotNull($project->published_at);
        $this->assertSame('Eastern Seaboard', $project->zone_name);
    }

    public function test_public_projects_fall_back_to_config_when_database_has_no_published_projects(): void
    {
        $this->get('/projects')
            ->assertOk()
            ->assertSee('Ghana Women &amp; Youth Employment and Social Cohesion Programme', false);

        $this->get('/projects/gwyesco')
            ->assertOk()
            ->assertSee('Ghana Women &amp; Youth Employment and Social Cohesion Programme', false)
            ->assertSee('30,000+ women and young people');
    }
}
