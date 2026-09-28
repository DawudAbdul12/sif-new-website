<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FaqCmsTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    public function test_admin_can_create_faq_and_show_it_publicly(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.faqs.store'), [
                'question' => 'Can communities request a SIF project?',
                'answer' => 'Community requests are normally coordinated through MMDAs and relevant implementing partners.',
                'category' => 'projects',
                'status' => 'published',
                'sort_order' => 3,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('faqs', [
            'question' => 'Can communities request a SIF project?',
            'category' => 'projects',
            'status' => 'published',
            'sort_order' => 3,
            'created_by' => $this->admin->id,
            'updated_by' => $this->admin->id,
        ]);

        $this->get('/resources')
            ->assertOk()
            ->assertSee('Can communities request a SIF project?')
            ->assertSee('Community requests are normally coordinated through MMDAs');
    }

    public function test_admin_can_update_faq(): void
    {
        $faq = Faq::create([
            'question' => 'Old question?',
            'answer' => 'Old answer.',
            'category' => 'general',
            'status' => 'draft',
            'created_by' => $this->admin->id,
            'updated_by' => $this->admin->id,
        ]);

        $this->actingAs($this->admin)
            ->put(route('admin.faqs.update', $faq), [
                'question' => 'Updated question?',
                'answer' => 'Updated answer for the FAQ.',
                'category' => 'partnerships',
                'status' => 'published',
                'sort_order' => 1,
            ])
            ->assertRedirect(route('admin.faqs.edit', $faq));

        $faq->refresh();

        $this->assertSame('Updated question?', $faq->question);
        $this->assertSame('partnerships', $faq->category);
        $this->assertSame('published', $faq->status);
        $this->assertNotNull($faq->published_at);
    }

    public function test_resources_page_uses_fallback_faqs_when_none_are_published(): void
    {
        $this->get('/resources')
            ->assertOk()
            ->assertSee('Is SIF a government agency?')
            ->assertSee('Common questions about SIF');
    }

    public function test_admin_faq_index_is_available_from_navigation(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.faqs.index'))
            ->assertOk()
            ->assertSee('FAQs')
            ->assertSee('New FAQ');

        $this->actingAs($this->admin)
            ->get('/admin')
            ->assertOk()
            ->assertSee(route('admin.faqs.index'))
            ->assertSee('FAQs');
    }
}
