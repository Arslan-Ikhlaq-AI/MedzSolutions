<?php

namespace Tests\Feature;

use App\Models\JobApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class JobApplicationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        Storage::fake('local');
        config()->set('site.jobs', [[
            'slug' => 'medical-coder',
            'title' => 'Certified Medical Coder',
            'department' => 'Coding',
            'location' => 'Remote',
            'type' => 'Full-time',
            'excerpt' => 'Code outpatient encounters.',
            'body' => 'Role description.',
        ]]);
    }

    public function test_application_with_a_cv_is_stored_for_the_job(): void
    {
        $response = $this->from('/careers/medical-coder')->post('/careers/medical-coder/apply', $this->payload());

        $response->assertRedirect('/careers/medical-coder');
        $response->assertSessionHas('status', 'Thank you for applying! Our hiring team will review your application and reply within five working days.');
        $response->assertSessionHas('form', 'application');

        $application = JobApplication::sole();
        $this->assertSame('medical-coder', $application->job_slug);
        $this->assertSame('Certified Medical Coder', $application->job_title);
        $this->assertSame('applicant@example.com', $application->email);
        Storage::disk('local')->assertExists($application->resume_path);
    }

    public function test_empty_application_is_rejected_with_required_field_errors(): void
    {
        $response = $this->from('/careers/medical-coder')->post('/careers/medical-coder/apply', []);

        $response->assertRedirect('/careers/medical-coder');
        $response->assertSessionHasErrors([
            'name' => 'The name field is required.',
            'email' => 'The email field is required.',
            'resume' => 'The resume field is required.',
        ]);

        $this->assertDatabaseCount('job_applications', 0);
    }

    public function test_cv_that_is_not_a_pdf_or_word_document_is_rejected(): void
    {
        $response = $this->from('/careers/medical-coder')->post('/careers/medical-coder/apply', [
            ...$this->payload(),
            'resume' => UploadedFile::fake()->create('cv.exe', 100, 'application/x-msdownload'),
        ]);

        $response->assertSessionHasErrors(['resume' => 'The resume field must be a file of type: pdf, doc, docx.']);

        $this->assertDatabaseCount('job_applications', 0);
    }

    public function test_cv_larger_than_5_mb_is_rejected(): void
    {
        $response = $this->from('/careers/medical-coder')->post('/careers/medical-coder/apply', [
            ...$this->payload(),
            'resume' => UploadedFile::fake()->create('cv.pdf', 5121, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors(['resume' => 'The resume field must not be greater than 5120 kilobytes.']);

        $this->assertDatabaseCount('job_applications', 0);
    }

    public function test_application_for_an_unknown_job_returns_404_and_stores_nothing(): void
    {
        $response = $this->post('/careers/does-not-exist/apply', $this->payload());

        $response->assertNotFound();

        $this->assertDatabaseCount('job_applications', 0);
        Storage::disk('local')->assertDirectoryEmpty('/');
    }

    public function test_sixth_application_within_a_minute_returns_429(): void
    {
        foreach (range(1, 5) as $attempt) {
            $this->from('/careers/medical-coder')->post('/careers/medical-coder/apply', $this->payload())->assertRedirect('/careers/medical-coder');
        }

        $response = $this->from('/careers/medical-coder')->post('/careers/medical-coder/apply', $this->payload());

        $response->assertTooManyRequests();

        $this->assertDatabaseCount('job_applications', 5);
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(): array
    {
        return [
            '_form' => 'application',
            'name' => 'Sample Applicant',
            'email' => 'applicant@example.com',
            'phone' => '+1 555 010 0150',
            'linkedin_url' => 'https://www.linkedin.com/in/sample-applicant',
            'cover_letter' => 'I have five years of outpatient coding experience.',
            'resume' => UploadedFile::fake()->create('cv.pdf', 200, 'application/pdf'),
        ];
    }
}
