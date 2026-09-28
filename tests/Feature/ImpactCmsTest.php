<?php

namespace Tests\Feature;

use App\Models\ImpactMetric;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImpactCmsTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    public function test_admin_can_create_impact_metric_and_show_it_on_homepage(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.impact-metrics.store'), [
                'tier' => 'primary',
                'prefix' => 'US$',
                'value' => '100',
                'suffix' => 'M+',
                'label' => 'Mobilised for community investment',
                'note' => 'Verified partner total',
                'status' => 'published',
                'sort_order' => 1,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('impact_metrics', [
            'tier' => 'primary',
            'value' => '100',
            'label' => 'Mobilised for community investment',
            'status' => 'published',
            'created_by' => $this->admin->id,
            'updated_by' => $this->admin->id,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Mobilised for community investment')
            ->assertSee('Verified partner total')
            ->assertSee('data-count="100"', false);
    }

    public function test_admin_can_update_impact_metric(): void
    {
        $metric = ImpactMetric::create([
            'tier' => 'secondary',
            'value' => '5',
            'suffix' => '',
            'label' => 'Old metric',
            'status' => 'draft',
            'created_by' => $this->admin->id,
            'updated_by' => $this->admin->id,
        ]);

        $this->actingAs($this->admin)
            ->put(route('admin.impact-metrics.update', $metric), [
                'tier' => 'secondary',
                'prefix' => '',
                'value' => '16',
                'suffix' => '',
                'label' => 'Regions covered across Ghana',
                'note' => '',
                'status' => 'published',
                'sort_order' => 2,
            ])
            ->assertRedirect(route('admin.impact-metrics.edit', $metric));

        $metric->refresh();

        $this->assertSame('16', $metric->value);
        $this->assertSame('Regions covered across Ghana', $metric->label);
        $this->assertSame('published', $metric->status);
        $this->assertNotNull($metric->published_at);
    }

    public function test_homepage_uses_fallback_impact_metrics_when_none_are_published(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Mobilised from development partners since 1998')
            ->assertSee('data-count="83.5"', false)
            ->assertSee('Artisans employed on project sites');
    }

    public function test_admin_impact_index_is_available_from_navigation(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.impact-metrics.index'))
            ->assertOk()
            ->assertSee('Impact Metrics')
            ->assertSee('New Metric');

        $this->actingAs($this->admin)
            ->get('/admin')
            ->assertOk()
            ->assertSee(route('admin.impact-metrics.index'))
            ->assertSee('Impact');
    }
}
