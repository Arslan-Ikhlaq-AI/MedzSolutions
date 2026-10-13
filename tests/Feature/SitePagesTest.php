<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SitePagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function listingPages(): array
    {
        return [
            'home' => ['/', 'home'],
            'about' => ['/about-us', 'about'],
            'team' => ['/meet-our-team', 'team'],
            'specialities' => ['/specialities', 'specialities'],
            'software' => ['/software', 'software'],
            'compliance' => ['/compliance', 'compliance'],
            'faqs' => ['/faqs', 'faqs'],
            'services' => ['/services', 'services.index'],
            'resources' => ['/resources', 'resources.index'],
            'blog' => ['/blogs', 'posts.index'],
            'careers' => ['/careers', 'careers.index'],
            'affiliate program' => ['/affiliate-program', 'affiliate'],
            'contact' => ['/contact-us', 'contact'],
        ];
    }

    #[DataProvider('listingPages')]
    public function test_public_page_renders_its_react_page(string $uri, string $page): void
    {
        $response = $this->get($uri);

        $response->assertOk();
        $response->assertViewIs('site');
        $response->assertViewHas('page', $page);
    }

    public function test_every_configured_entry_has_a_detail_page(): void
    {
        $detailUris = collect([
            'services' => '/services/',
            'resources' => '/resources/',
            'jobs' => '/careers/',
        ])->flatMap(fn (string $prefix, string $key) => collect(config("site.{$key}"))->map(fn (array $entry): string => $prefix.$entry['slug']));

        foreach ($detailUris as $uri) {
            $this->get($uri)->assertOk();
        }

        $this->assertNotEmpty($detailUris);
    }

    public function test_service_page_shows_the_requested_service(): void
    {
        config()->set('site.services', [
            $this->entry('denial-management', 'Denial Management'),
            $this->entry('medical-coding', 'Medical Coding'),
        ]);

        $response = $this->get('/services/medical-coding');

        $response->assertOk();
        $response->assertViewHas('page', 'services.show');
        $response->assertViewHas('title', 'Medical Coding');
        $response->assertViewHas('props', fn (array $props): bool => $props['service']['slug'] === 'medical-coding'
            && $props['service']['url'] === url('/services/medical-coding'));
    }

    public function test_detail_page_renders_markdown_and_strips_raw_html(): void
    {
        config()->set('site.services', [
            $this->entry('billing', 'Billing', "## What is included\n\nClean claims.<script>alert('x')</script>"),
        ]);

        $response = $this->get('/services/billing');

        $response->assertOk();
        $response->assertViewHas('props', function (array $props): bool {
            $body = $props['service']['body'];

            return str_contains($body, '<h2>What is included</h2>')
                && ! str_contains($body, '<script>');
        });
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function unknownDetailPages(): array
    {
        return [
            'service' => ['/services/does-not-exist'],
            'resource' => ['/resources/does-not-exist'],
            'job' => ['/careers/does-not-exist'],
            'post' => ['/blogs/does-not-exist'],
        ];
    }

    #[DataProvider('unknownDetailPages')]
    public function test_unknown_slug_returns_404(string $uri): void
    {
        $response = $this->get($uri);

        $response->assertNotFound();
        $response->assertSee('Page Not Found');
    }

    public function test_unknown_route_renders_the_site_404_page(): void
    {
        $response = $this->get('/no-such-page');

        $response->assertNotFound();
        $response->assertSee('<title>Page Not Found – Medz-Solutions</title>', false);
    }

    public function test_home_page_features_six_services_and_five_faqs(): void
    {
        config()->set('site.services', collect(range(1, 7))->map(fn (int $number): array => $this->entry("service-{$number}", "Service {$number}"))->all());
        config()->set('site.faqs', collect(range(1, 6))->map(fn (int $number): array => ['question' => "Question {$number}?", 'answer' => 'Answer.'])->all());

        $response = $this->get('/');

        $response->assertOk();
        $response->assertViewHas('props', fn (array $props): bool => $props['services']->count() === 6
            && $props['faqs']->count() === 5);
    }

    public function test_published_post_has_a_detail_page(): void
    {
        $post = Post::factory()->create(['title' => 'Understanding Denials']);

        $response = $this->get("/blogs/{$post->slug}");

        $response->assertOk();
        $response->assertViewHas('page', 'posts.show');
        $response->assertViewHas('title', 'Understanding Denials');
    }

    public function test_draft_and_scheduled_posts_are_not_public(): void
    {
        $draft = Post::factory()->draft()->create();
        $scheduled = Post::factory()->scheduled()->create();

        $this->get("/blogs/{$draft->slug}")->assertNotFound();
        $this->get("/blogs/{$scheduled->slug}")->assertNotFound();
        $this->get('/blogs')->assertOk()->assertViewHas('props', fn (array $props): bool => $props['posts']->isEmpty());
    }

    public function test_blog_lists_the_newest_posts_first(): void
    {
        Post::factory()->create(['slug' => 'oldest', 'published_at' => '2026-01-10']);
        Post::factory()->create(['slug' => 'newest', 'published_at' => '2026-06-01']);
        Post::factory()->create(['slug' => 'middle', 'published_at' => '2026-03-15']);

        $response = $this->get('/blogs');

        $response->assertOk();
        $response->assertViewHas('props', fn (array $props): bool => $props['posts']->pluck('slug')->all() === ['newest', 'middle', 'oldest']);
    }

    public function test_blog_search_returns_posts_whose_title_or_excerpt_matches(): void
    {
        Post::factory()->create(['slug' => 'fixing-denials', 'title' => 'Fixing Claim Denials', 'category' => 'General', 'published_at' => '2026-05-01']);
        Post::factory()->create(['slug' => 'credentialing', 'excerpt' => 'Avoid DENIAL delays with early enrollment.', 'category' => 'General', 'published_at' => '2026-04-01']);
        Post::factory()->create(['slug' => 'coding-tips', 'title' => 'Coding Tips', 'excerpt' => 'Habits that help.', 'category' => 'General', 'published_at' => '2026-03-01']);

        $response = $this->get('/blogs?search=denial');

        $response->assertOk();
        $response->assertViewHas('props', fn (array $props): bool => $props['posts']->pluck('slug')->all() === ['fixing-denials', 'credentialing']
            && $props['filters'] === ['search' => 'denial', 'category' => '']);
    }

    public function test_blog_category_filter_returns_only_posts_in_that_category(): void
    {
        Post::factory()->create(['slug' => 'denials-one', 'category' => 'Denials', 'published_at' => '2026-05-01']);
        Post::factory()->create(['slug' => 'coding-one', 'category' => 'Coding', 'published_at' => '2026-04-01']);
        Post::factory()->create(['slug' => 'denials-two', 'category' => 'Denials', 'published_at' => '2026-03-01']);
        Post::factory()->draft()->create(['category' => 'Coding']);

        $response = $this->get('/blogs?category=Denials');

        $response->assertOk();
        $response->assertViewHas('props', fn (array $props): bool => $props['posts']->pluck('slug')->all() === ['denials-one', 'denials-two']
            && $props['categories'] === [['name' => 'Coding', 'count' => 1], ['name' => 'Denials', 'count' => 2]]);
    }

    public function test_post_page_shows_a_formatted_date_reading_time_and_rendered_markdown(): void
    {
        Post::factory()->create([
            'slug' => 'long-read',
            'published_at' => '2026-03-05',
            'body' => "## Key point\n\n".str_repeat('word ', 450).'<script>alert(1)</script>',
        ]);

        $response = $this->get('/blogs/long-read');

        $response->assertOk();
        $response->assertViewHas('props', fn (array $props): bool => $props['post']['date'] === 'March 5, 2026'
            && $props['post']['reading_time'] === '3 min read'
            && str_contains($props['post']['body'], '<h2>Key point</h2>')
            && ! str_contains($props['post']['body'], '<script>'));
    }

    public function test_careers_search_returns_only_matching_positions(): void
    {
        config()->set('site.jobs', [
            $this->jobEntry('medical-coder', 'Certified Medical Coder'),
            $this->jobEntry('ar-specialist', 'AR Specialist'),
        ]);

        $response = $this->get('/careers?search=coder');

        $response->assertOk();
        $response->assertViewHas('props', fn (array $props): bool => $props['jobs']->pluck('slug')->all() === ['medical-coder']
            && $props['search'] === 'coder');
    }

    public function test_job_page_posts_applications_to_that_job(): void
    {
        config()->set('site.jobs', [
            $this->jobEntry('medical-coder', 'Certified Medical Coder'),
            $this->jobEntry('ar-specialist', 'AR Specialist'),
        ]);

        $response = $this->get('/careers/medical-coder');

        $response->assertOk();
        $response->assertViewHas('props', fn (array $props): bool => $props['action'] === url('/careers/medical-coder/apply')
            && $props['otherJobs']->pluck('slug')->all() === ['ar-specialist']);
    }

    public function test_contact_page_embeds_the_free_google_map_for_the_configured_location(): void
    {
        config()->set('site.company.map', ['query' => 'Austin, TX', 'zoom' => 12]);
        config()->set('services.google_maps.embed_key', null);

        $response = $this->get('/contact-us');

        $response->assertOk();
        $response->assertViewHas('shared', fn (array $shared): bool => $shared['company']['map'] === [
            'embed' => 'https://maps.google.com/maps?q=Austin%2C+TX&z=12&output=embed',
            'directions' => 'https://www.google.com/maps/search/?api=1&query=Austin%2C+TX',
        ]);
        $response->assertViewHas('props', fn (array $props): bool => $props['headerImage'] === asset('images/photos/contact-hero.jpg')
            && $props['sideImage'] === asset('images/photos/contact-specialist.jpg'));
    }

    public function test_contact_page_uses_the_maps_embed_api_when_a_key_is_configured(): void
    {
        config()->set('site.company.map', ['query' => 'Austin, TX', 'zoom' => 12]);
        config()->set('services.google_maps.embed_key', 'test-key');

        $response = $this->get('/contact-us');

        $response->assertOk();
        $response->assertViewHas('shared', fn (array $shared): bool => $shared['company']['map']['embed'] === 'https://www.google.com/maps/embed/v1/place?key=test-key&q=Austin%2C+TX&zoom=12');
    }

    public function test_every_page_shares_the_service_navigation(): void
    {
        config()->set('site.services', [
            $this->entry('denial-management', 'Denial Management'),
            $this->entry('medical-coding', 'Medical Coding'),
        ]);

        $response = $this->get('/about-us');

        $response->assertOk();
        $response->assertViewHas('shared', fn (array $shared): bool => $shared['services'] === [
            ['title' => 'Denial Management', 'url' => url('/services/denial-management')],
            ['title' => 'Medical Coding', 'url' => url('/services/medical-coding')],
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function entry(string $slug, string $title, string $body = 'Body copy.', string $excerpt = 'Short summary.'): array
    {
        return [
            'slug' => $slug,
            'title' => $title,
            'excerpt' => $excerpt,
            'body' => $body,
        ];
    }

    /**
     * @return array<string, string>
     */
    private function jobEntry(string $slug, string $title): array
    {
        return [
            ...$this->entry($slug, $title),
            'department' => 'Billing',
            'location' => 'Remote',
            'type' => 'Full-time',
        ];
    }
}
