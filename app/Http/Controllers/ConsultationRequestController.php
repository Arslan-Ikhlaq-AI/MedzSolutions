<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConsultationRequest;
use App\Models\ConsultationRequest;
use Illuminate\Http\RedirectResponse;

class ConsultationRequestController extends Controller
{
    public function store(StoreConsultationRequest $request): RedirectResponse
    {
        $consultation = ConsultationRequest::create($request->validated());

        return back()
            ->with('status', config("site.form_messages.{$consultation->type}"))
            ->with('form', $consultation->type);
    }
}
