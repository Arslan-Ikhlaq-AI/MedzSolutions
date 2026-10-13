<?php

namespace App\Http\Controllers\Admin;

use App\Models\AffiliateApplication;
use Illuminate\Database\Eloquent\Model;

class AffiliateApplicationController extends InboxController
{
    protected function key(): string
    {
        return 'affiliates';
    }

    protected function columns(): array
    {
        return [
            'Partner type' => fn (AffiliateApplication $record): string => $record->partner_type ?: '—',
            'Company' => fn (AffiliateApplication $record): string => $record->company ?: '—',
            'Email' => fn (AffiliateApplication $record): string => $record->email,
        ];
    }

    protected function searchColumns(): array
    {
        return ['name', 'email', 'phone', 'company', 'partner_type', 'audience'];
    }

    protected function details(Model $record): array
    {
        return [
            ['label' => 'Name', 'value' => $record->name],
            ['label' => 'Partner type', 'value' => $record->partner_type],
            ['label' => 'Company', 'value' => $record->company],
            ['label' => 'Email', 'value' => $record->email, 'kind' => 'email'],
            ['label' => 'Phone', 'value' => $record->phone, 'kind' => 'phone'],
            ['label' => 'Website', 'value' => $record->website, 'kind' => 'link'],
            ['label' => 'How they work with practices', 'value' => $record->audience, 'kind' => 'long'],
            ['label' => 'Message', 'value' => $record->message, 'kind' => 'long'],
        ];
    }
}
