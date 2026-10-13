<?php

namespace App\Http\Controllers\Admin;

use App\Models\ConsultationRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ConsultationRequestController extends InboxController
{
    protected function key(): string
    {
        return 'consultations';
    }

    protected function query(Request $request): Builder
    {
        return ConsultationRequest::query()->when(
            in_array($request->query('type'), ['meeting', 'review'], true),
            fn (Builder $query) => $query->where('type', $request->query('type')),
        );
    }

    protected function tabs(): array
    {
        return [
            ['value' => '', 'label' => 'All'],
            ['value' => 'meeting', 'label' => 'Meeting requests'],
            ['value' => 'review', 'label' => 'Practice reviews'],
        ];
    }

    protected function columns(): array
    {
        return [
            'Type' => fn (ConsultationRequest $record): string => $record->type === 'meeting' ? 'Meeting request' : 'Practice review',
            'Practice' => fn (ConsultationRequest $record): string => $this->joined([$record->practice_name, $record->specialty]),
            'Preferred date' => fn (ConsultationRequest $record): string => $record->preferred_date?->format('M j, Y') ?? '—',
        ];
    }

    protected function searchColumns(): array
    {
        return ['name', 'email', 'phone', 'practice_name', 'specialty'];
    }

    protected function details(Model $record): array
    {
        $challenges = collect($record->challenges ?? [])
            ->map(fn (string $key): string => config("site.revenue_challenges.{$key}", $key))
            ->all();

        return [
            ['label' => 'Request type', 'value' => $record->type === 'meeting' ? 'Meeting request (demo call)' : 'Free practice review'],
            ['label' => 'Name', 'value' => $record->name],
            ['label' => 'Email', 'value' => $record->email, 'kind' => 'email'],
            ['label' => 'Phone', 'value' => $record->phone, 'kind' => 'phone'],
            ['label' => 'Practice', 'value' => $record->practice_name],
            ['label' => 'Specialty', 'value' => $record->specialty],
            ['label' => 'Preferred date', 'value' => $record->preferred_date?->format('l, F j, Y')],
            ['label' => 'Revenue challenges', 'value' => $challenges, 'kind' => 'tags'],
        ];
    }
}
