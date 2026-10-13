<?php

namespace App\Http\Requests;

use App\Models\CookieConsent;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCookieConsentRequest extends FormRequest
{
    /**
     * Anyone visiting the website can record their own cookie choice.
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
            'consent_id' => ['nullable', 'uuid'],
            'analytics' => ['required', 'boolean'],
            'marketing' => ['required', 'boolean'],
            'preferences' => ['required', 'boolean'],
            'source' => ['required', Rule::in(array_keys(CookieConsent::SOURCES))],
            'gpc' => ['sometimes', 'boolean'],
            'policy_version' => ['required', 'string', 'max:20'],
            'page' => ['nullable', 'string', 'max:255'],
        ];
    }
}
