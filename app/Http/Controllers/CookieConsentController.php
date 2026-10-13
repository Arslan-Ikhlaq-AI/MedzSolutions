<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCookieConsentRequest;
use App\Models\CookieConsent;
use App\Models\PageVisit;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class CookieConsentController extends Controller
{
    /**
     * Records a cookie decision. The visitor's browser keeps the choice in its own cookie; this
     * stores a copy as proof of consent and for the admin report. Saying no to analytics also
     * deletes any visit history recorded for this visitor.
     */
    public function store(StoreCookieConsentRequest $request): JsonResponse
    {
        $choices = $request->safe()->only(['analytics', 'marketing', 'preferences']);
        $consentId = $request->validated('consent_id') ?? (string) Str::uuid();

        CookieConsent::create([
            ...$request->safe()->only(['policy_version', 'page', 'source']),
            'gpc' => $request->boolean('gpc'),
            ...$choices,
            'consent_id' => $consentId,
            'action' => CookieConsent::actionFor(...array_map('boolval', $choices)),
        ]);

        if (! $choices['analytics']) {
            PageVisit::where('visitor_id', $consentId)->delete();
        }

        return response()->json(['consent_id' => $consentId], 201);
    }
}
