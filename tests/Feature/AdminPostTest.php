<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminPostTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        Storage::fake('public');
        $this->admin = User::factory()->admin()->create();
        $this->actingAs($this->admin);
    }

    public function test_index_lists_posts_with_their_status(): void
    {
        Post::factory()->create(['title' => 'Live Article']);
        Post::factory()->draft()->create(['title' => 'Hidden Draft']);
        Post::factory()->scheduled()->create(['title' => 'Future Article']);

        $response = $this->get('/admin-panel/posts');

        $response->assertOk();
        $response->assertSeeInOrder(['Live Article', 'Published']);
        $response->assertSee('Hidden Draft');
        $response->assertSee('Scheduled');
    }

    public function test_index_can_be_filtered_by_status_and_search(): void
    {
        Post::factory()->create(['title' => 'Live Article', 'category' => 'Coding']);
        Post::factory()->create(['title' => 'Another Live', 'category' => 'Denials']);
        Post::factory()->draft()->create(['title' => 'Hidden Draft', 'category' => 'Coding']);

        $this->assertSame(['Hidden Draft'], $this->get('/admin-panel/posts?status=draft')->viewData('posts')->pluck('title')->all());
        $this->assertSame(['Another Live'], $this->get('/admin-panel/posts?q=denials')->viewData('posts')->pluck('title')->all());
    }

    public function test_a_draft_is_saved_with_the_signed_in_admin_as_author_and_a_generated_web_address(): void
    {
        $response = $this->post('/admin-panel/posts', $this->payload(['title' => 'Five Billing Mistakes & Fixes', 'slug' => '']));

        $post = Post::sole();
        $response->assertRedirect(route('admin.posts.edit', $post));
        $this->assertSame('five-billing-mistakes-fixes', $post->slug);
        $this->assertSame('draft', $post->status);
        $this->assertSame($this->admin->id, $post->user_id);
        $this->assertNull($post->published_at);
    }

    public function test_publishing_without_a_date_publishes_now_and_shows_on_the_public_blog(): void
    {
        $this->travelTo('2026-10-14 09:30:00');

        $this->post('/admin-panel/posts', $this->payload(['title' => 'Fresh News', 'slug' => '', 'status' => 'published']));

        $post = Post::sole();
        $this->assertSame('2026-10-14 09:30:00', $post->published_at->toDateTimeString());
        $this->get('/blogs/fresh-news')->assertOk();
        $this->get('/blogs')->assertSee('Fresh News');
    }

    public function test_a_future_publish_date_keeps_the_post_off_the_public_blog_until_then(): void
    {
        $this->travelTo('2026-10-14 09:30:00');
        $this->post('/admin-panel/posts', $this->payload(['title' => 'Coming Soon', 'slug' => '', 'status' => 'published', 'published_at' => '2026-10-20T08:00']));

        $this->get('/blogs/coming-soon')->assertNotFound();

        $this->travelTo('2026-10-20 08:01:00');
        $this->get('/blogs/coming-soon')->assertOk();
    }

    public function test_a_featured_image_is_stored_and_shown_on_the_post(): void
    {
        $this->post('/admin-panel/posts', $this->payload([
            'title' => 'With Picture',
            'slug' => '',
            'status' => 'published',
            'featured_image' => UploadedFile::fake()->image('cover.jpg', 1200, 800),
        ]));

        $post = Post::sole();
        Storage::disk('public')->assertExists($post->featured_image);
        $this->get('/blogs/with-picture')->assertViewHas('props', fn (array $props): bool => $props['post']['image'] === Storage::disk('public')->url($post->featured_image));
    }

    public function test_empty_form_is_rejected_with_required_field_errors(): void
    {
        $response = $this->from('/admin-panel/posts/create')->post('/admin-panel/posts', []);

        $response->assertRedirect('/admin-panel/posts/create');
        $response->assertSessionHasErrors([
            'title' => 'The title field is required.',
            'category' => 'The category field is required.',
            'excerpt' => 'The excerpt field is required.',
            'body' => 'The body field is required.',
        ]);
        $this->assertDatabaseCount('posts', 0);
    }

    public function test_a_web_address_that_is_already_used_is_rejected(): void
    {
        Post::factory()->create(['slug' => 'taken']);

        $response = $this->from('/admin-panel/posts/create')->post('/admin-panel/posts', $this->payload(['slug' => 'taken']));

        $response->assertSessionHasErrors(['slug' => 'Another post already uses this web address. Choose a different one.']);
        $this->assertDatabaseCount('posts', 1);
    }

    public function test_a_file_that_is_not_an_image_is_rejected(): void
    {
        $response = $this->from('/admin-panel/posts/create')->post('/admin-panel/posts', $this->payload([
            'featured_image' => UploadedFile::fake()->create('notes.pdf', 100, 'application/pdf'),
        ]));

        $response->assertSessionHasErrors('featured_image');
        $this->assertDatabaseCount('posts', 0);
    }

    public function test_an_image_larger_than_4_mb_is_rejected(): void
    {
        $response = $this->from('/admin-panel/posts/create')->post('/admin-panel/posts', $this->payload([
            'featured_image' => UploadedFile::fake()->image('huge.jpg')->size(4097),
        ]));

        $response->assertSessionHasErrors(['featured_image' => 'The featured image field must not be greater than 4096 kilobytes.']);
    }

    public function test_status_must_be_draft_or_published(): void
    {
        $response = $this->from('/admin-panel/posts/create')->post('/admin-panel/posts', $this->payload(['status' => 'archived']));

        $response->assertSessionHasErrors('status');
    }

    public function test_edit_page_shows_the_post(): void
    {
        $post = Post::factory()->create(['title' => 'Editable Post']);

        $this->get("/admin-panel/posts/{$post->id}/edit")->assertOk()->assertSee('Editable Post');
    }

    public function test_a_post_can_be_updated_and_keeps_its_own_web_address(): void
    {
        $post = Post::factory()->create(['title' => 'Old Title', 'slug' => 'old-title']);

        $response = $this->from("/admin-panel/posts/{$post->id}/edit")->put("/admin-panel/posts/{$post->id}", $this->payload([
            'title' => 'New Title',
            'slug' => 'old-title',
            'category' => 'Coding',
            'status' => 'published',
        ]));

        $response->assertRedirect("/admin-panel/posts/{$post->id}/edit");
        $post->refresh();
        $this->assertSame('New Title', $post->title);
        $this->assertSame('old-title', $post->slug);
        $this->assertSame('Coding', $post->category);
    }

    public function test_updating_an_existing_published_post_keeps_its_original_publish_date(): void
    {
        $post = Post::factory()->create(['published_at' => '2026-02-01 10:00:00']);

        $this->put("/admin-panel/posts/{$post->id}", $this->payload(['slug' => $post->slug, 'status' => 'published']));

        $this->assertSame('2026-02-01 10:00:00', $post->fresh()->published_at->toDateTimeString());
    }

    public function test_a_published_post_can_be_moved_back_to_draft_and_disappears_from_the_blog(): void
    {
        $post = Post::factory()->create(['slug' => 'live-post']);
        $this->get('/blogs/live-post')->assertOk();

        $this->put("/admin-panel/posts/{$post->id}", $this->payload(['slug' => 'live-post', 'status' => 'draft']));

        $this->get('/blogs/live-post')->assertNotFound();
    }

    public function test_replacing_the_image_deletes_the_old_file(): void
    {
        Storage::disk('public')->put('posts/old.jpg', 'old');
        $post = Post::factory()->create(['featured_image' => 'posts/old.jpg']);

        $this->put("/admin-panel/posts/{$post->id}", $this->payload(['slug' => $post->slug, 'featured_image' => UploadedFile::fake()->image('new.jpg')]));

        Storage::disk('public')->assertMissing('posts/old.jpg');
        Storage::disk('public')->assertExists($post->fresh()->featured_image);
    }

    public function test_the_image_can_be_removed(): void
    {
        Storage::disk('public')->put('posts/old.jpg', 'old');
        $post = Post::factory()->create(['featured_image' => 'posts/old.jpg']);

        $this->put("/admin-panel/posts/{$post->id}", $this->payload(['slug' => $post->slug, 'remove_image' => '1']));

        $this->assertNull($post->fresh()->featured_image);
        Storage::disk('public')->assertMissing('posts/old.jpg');
    }

    public function test_deleting_a_post_removes_it_and_its_image(): void
    {
        Storage::disk('public')->put('posts/old.jpg', 'old');
        $post = Post::factory()->create(['featured_image' => 'posts/old.jpg']);

        $response = $this->delete("/admin-panel/posts/{$post->id}");

        $response->assertRedirect(route('admin.posts.index'));
        $this->assertModelMissing($post);
        Storage::disk('public')->assertMissing('posts/old.jpg');
    }

    public function test_a_visitor_without_admin_access_cannot_create_posts(): void
    {
        auth()->logout();
        $this->actingAs(User::factory()->create());

        $this->post('/admin-panel/posts', $this->payload())->assertForbidden();

        $this->assertDatabaseCount('posts', 0);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function payload(array $overrides = []): array
    {
        return [
            'title' => 'A Useful Article',
            'slug' => 'a-useful-article',
            'category' => 'Revenue Cycle',
            'excerpt' => 'A short summary of the article.',
            'body' => "## Heading\n\nSome helpful body text.",
            'artwork' => 'pattern',
            'status' => 'draft',
            ...$overrides,
        ];
    }
}
