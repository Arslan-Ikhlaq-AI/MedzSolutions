<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function create(): View
    {
        return view('site', [
            'page' => 'contact',
            'title' => 'Contact Us',
            'description' => 'Contact Medz-Solutions for a free consultation about medical billing, coding, credentialing or revenue cycle management.',
            'props' => [
                'content' => config('site.contact_page'),
                'headerImage' => asset(config('site.contact_page.header_image')),
                'sideImage' => asset(config('site.contact_page.side_image')),
            ],
        ]);
    }

    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        ContactMessage::create($request->validated());

        return back()
            ->with('status', config('site.form_messages.contact'))
            ->with('form', 'contact');
    }
}
