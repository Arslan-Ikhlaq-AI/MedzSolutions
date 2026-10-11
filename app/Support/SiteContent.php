<?php

namespace App\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Reads the marketing site's content from config/site.php and prepares it for the React pages.
 */
class SiteContent
{
    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function services(): Collection
    {
        return $this->summaries('services', 'services.show');
    }

    /**
     * @return array<string, mixed>
     */
    public function service(string $slug): array
    {
        return $this->find('services', 'services.show', $slug);
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function resources(): Collection
    {
        return $this->summaries('resources', 'resources.show');
    }

    /**
     * @return array<string, mixed>
     */
    public function resource(string $slug): array
    {
        return $this->find('resources', 'resources.show', $slug);
    }

    /**
     * Open positions, optionally narrowed to those mentioning the search term.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public function jobs(?string $search = null): Collection
    {
        return $this->search($this->summaries('jobs', 'careers.show'), $search, ['title', 'department', 'excerpt']);
    }

    /**
     * @return array<string, mixed>
     */
    public function job(string $slug): array
    {
        return $this->find('jobs', 'careers.show', $slug);
    }

    /**
     * Blog posts, newest first, optionally narrowed by search term and category.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public function posts(?string $search = null, ?string $category = null): Collection
    {
        $posts = $this->summaries('posts', 'posts.show')
            ->when($category, fn (Collection $posts): Collection => $posts->where('category', $category))
            ->sortByDesc('published_at')
            ->values();

        return $this->search($posts, $search, ['title', 'excerpt', 'category']);
    }

    /**
     * @return array<string, mixed>
     */
    public function post(string $slug): array
    {
        return $this->find('posts', 'posts.show', $slug);
    }

    /**
     * Blog categories with the number of posts in each, alphabetically.
     *
     * @return array<int, array{name: string, count: int}>
     */
    public function postCategories(): array
    {
        return collect(config('site.posts'))
            ->countBy('category')
            ->sortKeys()
            ->map(fn (int $count, string $name): array => ['name' => $name, 'count' => $count])
            ->values()
            ->all();
    }

    /**
     * Entries without their long-form fields, for listing pages and cards.
     *
     * @return Collection<int, array<string, mixed>>
     */
    private function summaries(string $key, string $routeName): Collection
    {
        return $this->entries($key, $routeName)
            ->map(fn (array $entry): array => Arr::except($entry, ['body', 'faqs']));
    }

    /**
     * A single entry with its Markdown body rendered to HTML, or a 404 when the slug is unknown.
     *
     * @return array<string, mixed>
     */
    private function find(string $key, string $routeName, string $slug): array
    {
        $entry = $this->entries($key, $routeName)->firstWhere('slug', $slug) ?? abort(404);

        $entry['body'] = Str::markdown($entry['body'], [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        return $entry;
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $entries
     * @param  array<int, string>  $fields
     * @return Collection<int, array<string, mixed>>
     */
    private function search(Collection $entries, ?string $term, array $fields): Collection
    {
        if (blank($term)) {
            return $entries;
        }

        return $entries
            ->filter(fn (array $entry): bool => Str::contains(implode(' ', Arr::only($entry, $fields)), $term, ignoreCase: true))
            ->values();
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    private function entries(string $key, string $routeName): Collection
    {
        return collect(config("site.{$key}"))->map(function (array $entry) use ($routeName): array {
            $entry['url'] = route($routeName, $entry['slug']);

            if (isset($entry['published_at'])) {
                $entry['date'] = Carbon::parse($entry['published_at'])->format('F j, Y');
                $entry['reading_time'] = max(1, (int) ceil(str_word_count($entry['body']) / 200)).' min read';
            }

            return $entry;
        });
    }
}
