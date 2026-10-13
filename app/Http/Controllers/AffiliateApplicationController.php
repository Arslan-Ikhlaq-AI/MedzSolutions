<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAffiliateApplicationRequest;
use App\Models\AffiliateApplication;
use App\Support\LeadMailer;
use App\Support\VisitorConsent;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AffiliateApplicationController extends Controller
{
    public function create(): View
    {
        return view('site', [
            'page' => 'affiliate',
            'title' => 'Affiliate Program',
            'description' => 'Partner with Medz-Solutions as a referral or white-label partner and earn recurring commissions on the healthcare practices you bring.',
            'props' => [
                'content' => config('site.affiliate_page'),
                'headerImage' => asset(config('site.affiliate_page.header_image')),
                'programImage' => asset(config('site.affiliate_page.program.image')),
                'action' => route('affiliate.store'),
            ],
        ]);
    }

    public function store(StoreAffiliateApplicationRequest $request, LeadMailer $mailer): RedirectResponse
    {
        $application = AffiliateApplication::create([
            ...$request->validated(),
            'visitor_id' => VisitorConsent::fromRequest($request)->trackableId(),
        ]);

        $mailer->submitted($application, 'affiliate');

        return back()
            ->with('status', config('site.form_messages.affiliate'))
            ->with('form', 'affiliate');
    }
}
