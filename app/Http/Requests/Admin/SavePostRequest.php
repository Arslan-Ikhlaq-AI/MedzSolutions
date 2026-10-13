<?php

namespace App\Http\Requests\Admin;

use App\Models\Post;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class SavePostRequest extends FormRequest
{
    /**
     * Illustration styles offered for posts without a photo.
     *
     * @var array<string, string>
     */
    public const ARTWORK = [
        'pattern' => 'Abstract',
        'claim' => 'Claim form',
        'analytics' => 'Reports',
        'idcard' => 'Insurance card',
        'calendar' => 'Calendar',
        'credential' => 'Credentials',
        'security' => 'Compliance',
        'support' => 'Conversation',
    ];

    /**
     * Access is already limited to admins by the route middleware.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug((string) ($this->input('slug') ?: $this->input('title'))),
        ]);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var Post|null $post */
        $post = $this->route('post');

        return [
            'title' => ['required', 'string', 'max:160'],
            'slug' => ['required', 'string', 'max:160', Rule::unique('posts', 'slug')->ignore($post)],
            'category' => ['required', 'string', 'max:60'],
            'excerpt' => ['required', 'string', 'max:300'],
            'body' => ['required', 'string', 'max:100000'],
            'featured_image' => ['nullable', File::image()->types(['jpg', 'jpeg', 'png', 'webp'])->max(4 * 1024)],
            'remove_image' => ['boolean'],
            'artwork' => ['required', Rule::in(array_keys(self::ARTWORK))],
            'status' => ['required', Rule::in([Post::STATUS_DRAFT, Post::STATUS_PUBLISHED])],
            'published_at' => ['nullable', 'date'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'slug.unique' => 'Another post already uses this web address. Choose a different one.',
        ];
    }
}
