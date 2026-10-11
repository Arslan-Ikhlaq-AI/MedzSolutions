<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Handles both home page forms: "meeting" (schedule a call) and "review" (free practice review).
 */
class StoreConsultationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(['meeting', 'review'])],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'practice_name' => ['exclude_unless:type,meeting', 'nullable', 'string', 'max:255'],
            'specialty' => ['exclude_unless:type,meeting', 'nullable', Rule::in(collect(config('site.specialities'))->pluck('name')->all())],
            'preferred_date' => ['exclude_unless:type,meeting', 'required', 'date', 'after_or_equal:today'],
            'challenges' => ['exclude_unless:type,review', 'required', 'array'],
            'challenges.*' => ['exclude_unless:type,review', Rule::in(array_keys(config('site.revenue_challenges')))],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'challenges.required' => 'Please choose at least one challenge.',
        ];
    }
}
