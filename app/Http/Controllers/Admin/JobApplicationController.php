<?php

namespace App\Http\Controllers\Admin;

use App\Models\JobApplication;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class JobApplicationController extends InboxController
{
    protected function key(): string
    {
        return 'job-applications';
    }

    protected function columns(): array
    {
        return [
            'Position' => fn (JobApplication $record): string => $record->job_title,
            'Email' => fn (JobApplication $record): string => $record->email,
        ];
    }

    protected function searchColumns(): array
    {
        return ['name', 'email', 'phone', 'job_title', 'cover_letter'];
    }

    protected function details(Model $record): array
    {
        return [
            ['label' => 'Applicant', 'value' => $record->name],
            ['label' => 'Position', 'value' => $record->job_title],
            ['label' => 'Email', 'value' => $record->email, 'kind' => 'email'],
            ['label' => 'Phone', 'value' => $record->phone, 'kind' => 'phone'],
            ['label' => 'LinkedIn', 'value' => $record->linkedin_url, 'kind' => 'link'],
            ['label' => 'Cover letter', 'value' => $record->cover_letter, 'kind' => 'long'],
        ];
    }

    protected function extraActions(Model $record): array
    {
        return ['Download CV' => route('admin.job-applications.resume', $record->getKey())];
    }

    protected function beforeDelete(Model $record): void
    {
        Storage::disk('local')->delete($record->resume_path);
    }

    public function resume(string $id): StreamedResponse
    {
        $application = JobApplication::findOrFail($id);

        abort_unless(Storage::disk('local')->exists($application->resume_path), 404);

        $extension = pathinfo($application->resume_path, PATHINFO_EXTENSION);

        return Storage::disk('local')->download($application->resume_path, str($application->name)->slug().'-cv.'.$extension);
    }
}
