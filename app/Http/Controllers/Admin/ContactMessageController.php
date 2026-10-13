<?php

namespace App\Http\Controllers\Admin;

use App\Models\ContactMessage;
use Illuminate\Database\Eloquent\Model;

class ContactMessageController extends InboxController
{
    protected function key(): string
    {
        return 'contact-messages';
    }

    protected function columns(): array
    {
        return [
            'Email' => fn (ContactMessage $record): string => $record->email,
            'Phone' => fn (ContactMessage $record): string => $record->phone ?: '—',
        ];
    }

    protected function searchColumns(): array
    {
        return ['name', 'email', 'phone', 'subject', 'message'];
    }

    protected function details(Model $record): array
    {
        return [
            ['label' => 'Name', 'value' => $record->name],
            ['label' => 'Email', 'value' => $record->email, 'kind' => 'email'],
            ['label' => 'Phone', 'value' => $record->phone, 'kind' => 'phone'],
            ['label' => 'Subject', 'value' => $record->subject],
            ['label' => 'Message', 'value' => $record->message, 'kind' => 'long'],
        ];
    }
}
