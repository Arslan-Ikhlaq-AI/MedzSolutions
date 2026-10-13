<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePageVisitRequest extends FormRequest
{
    /**
     * Anyone browsing the website may report their own page view; it is only kept with consent.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'path' => ['required', 'string', 'starts_with:/', 'max:255'],
            'title' => ['nullable', 'string', 'max:160'],
            'referrer' => ['nullable', 'url:http,https', 'max:1000'],
            'utm_source' => ['nullable', 'string', 'max:100'],
            'utm_medium' => ['nullable', 'string', 'max:100'],
            'utm_campaign' => ['nullable', 'string', 'max:100'],
        ];
    }
}
