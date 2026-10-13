<?php

namespace Tests\Feature;

use App\Enums\RequestStatus;
use App\Models\AffiliateApplication;
use App\Models\ConsultationRequest;
use App\Models\ContactMessage;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AdminInboxTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->actingAs(User::factory()->admin()->create());
    }

    /**
     * @return array<string, array{0: string, 1: class-string<Model>}>
     */
    public static function sections(): array
    {
        return [
            'contact messages' => ['/admin-panel/contact-messages', ContactMessage::class],
            'consultations' => ['/admin-panel/consultations', ConsultationRequest::class],
            'job applications' => ['/admin-panel/job-applications', JobApplication::class],
            'affiliates' => ['/admin-panel/affiliates', AffiliateApplication::class],
        ];
    }

    /**
     * @param  class-string<Model>  $model
     */
    #[DataProvider('sections')]
    public function test_index_lists_records_and_flags_unread_ones(string $uri, string $model): void
    {
        $model::factory()->create(['name' => 'Unread Person']);
        $model::factory()->create(['name' => 'Read Person', 'read_at' => now()]);

        $response = $this->get($uri);

        $response->assertOk();
        $response->assertSee('Unread Person');
        $response->assertSee('Read Person');
        $response->assertSee('unread-dot', false);
    }

    /**
     * @param  class-string<Model>  $model
     */
    #[DataProvider('sections')]
    public function test_index_search_filters_by_name(string $uri, string $model): void
    {
        $model::factory()->create(['name' => 'Alice Wonder']);
        $model::factory()->create(['name' => 'Bob Builder']);

        $this->assertSame(['Alice Wonder'], $this->listedNames("{$uri}?q=wonder"));
    }

    /**
     * @param  class-string<Model>  $model
     */
    #[DataProvider('sections')]
    public function test_index_filters_by_status_and_unread_state(string $uri, string $model): void
    {
        $model::factory()->create(['name' => 'New Person']);
        $model::factory()->create(['name' => 'Closed Person', 'status' => RequestStatus::Closed, 'read_at' => now()]);

        $this->assertSame(['Closed Person'], $this->listedNames("{$uri}?status=closed"));
        $this->assertSame(['New Person'], $this->listedNames("{$uri}?filter=unread"));
    }

    /**
     * @param  class-string<Model>  $model
     */
    #[DataProvider('sections')]
    public function test_viewing_a_record_marks_it_as_read(string $uri, string $model): void
    {
        $record = $model::factory()->create(['name' => 'Detail Person']);

        $response = $this->get("{$uri}/{$record->id}");

        $response->assertOk();
        $response->assertSee('Detail Person');
        $this->assertNotNull($record->fresh()->read_at);
    }

    /**
     * @param  class-string<Model>  $model
     */
    #[DataProvider('sections')]
    public function test_status_and_notes_can_be_updated(string $uri, string $model): void
    {
        $record = $model::factory()->create();

        $response = $this->from("{$uri}/{$record->id}")->put("{$uri}/{$record->id}", ['status' => 'in_progress', 'admin_notes' => 'Called back on Monday.']);

        $response->assertRedirect("{$uri}/{$record->id}");
        $record->refresh();
        $this->assertSame(RequestStatus::InProgress, $record->status);
        $this->assertSame('Called back on Monday.', $record->admin_notes);
    }

    /**
     * @param  class-string<Model>  $model
     */
    #[DataProvider('sections')]
    public function test_invalid_status_is_rejected(string $uri, string $model): void
    {
        $record = $model::factory()->create();

        $response = $this->from("{$uri}/{$record->id}")->put("{$uri}/{$record->id}", ['status' => 'archived']);

        $response->assertSessionHasErrors('status');
        $this->assertSame(RequestStatus::New, $record->fresh()->status);
    }

    /**
     * @param  class-string<Model>  $model
     */
    #[DataProvider('sections')]
    public function test_a_record_can_be_deleted(string $uri, string $model): void
    {
        $record = $model::factory()->create();

        $response = $this->delete("{$uri}/{$record->id}");

        $response->assertRedirect($uri);
        $this->assertModelMissing($record);
    }

    /**
     * @param  class-string<Model>  $model
     */
    #[DataProvider('sections')]
    public function test_unknown_record_returns_404(string $uri, string $model): void
    {
        $this->get("{$uri}/999")->assertNotFound();
    }

    public function test_consultations_can_be_filtered_by_request_type(): void
    {
        ConsultationRequest::factory()->create(['name' => 'Meeting Person']);
        ConsultationRequest::factory()->review()->create(['name' => 'Review Person']);

        $this->assertSame(['Meeting Person'], $this->listedNames('/admin-panel/consultations?type=meeting'));
        $this->assertSame(['Review Person'], $this->listedNames('/admin-panel/consultations?type=review'));
    }

    public function test_review_request_shows_the_challenges_in_plain_words(): void
    {
        $record = ConsultationRequest::factory()->review()->create();

        $response = $this->get("/admin-panel/consultations/{$record->id}");

        $response->assertSee('Too many denied or rejected claims');
        $response->assertSee('Insurance balances older than 90 days');
    }

    public function test_job_application_cv_can_be_downloaded(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('resumes/cv.pdf', 'PDF-CONTENT');
        $application = JobApplication::factory()->create(['name' => 'Jane Doe', 'resume_path' => 'resumes/cv.pdf']);

        $response = $this->get("/admin-panel/job-applications/{$application->id}/resume");

        $response->assertOk();
        $response->assertDownload('jane-doe-cv.pdf');
    }

    public function test_missing_cv_file_returns_404(): void
    {
        Storage::fake('local');
        $application = JobApplication::factory()->create(['resume_path' => 'resumes/gone.pdf']);

        $this->get("/admin-panel/job-applications/{$application->id}/resume")->assertNotFound();
    }

    public function test_deleting_a_job_application_removes_its_cv_file(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('resumes/cv.pdf', 'PDF-CONTENT');
        $application = JobApplication::factory()->create(['resume_path' => 'resumes/cv.pdf']);

        $this->delete("/admin-panel/job-applications/{$application->id}");

        $this->assertModelMissing($application);
        Storage::disk('local')->assertMissing('resumes/cv.pdf');
    }

    public function test_user_content_is_escaped_on_the_detail_page(): void
    {
        $record = ContactMessage::factory()->create(['message' => '<script>alert("x")</script>']);

        $response = $this->get("/admin-panel/contact-messages/{$record->id}");

        $response->assertDontSee('<script>alert("x")</script>', false);
        $response->assertSee('&lt;script&gt;', false);
    }

    /**
     * Names in the list a page shows, in display order.
     *
     * @return array<int, string>
     */
    private function listedNames(string $uri): array
    {
        return $this->get($uri)->assertOk()->viewData('records')->pluck('name')->all();
    }

    public function test_guests_cannot_update_or_delete_records(): void
    {
        auth()->logout();
        $record = ContactMessage::factory()->create();

        $this->put("/admin-panel/contact-messages/{$record->id}", ['status' => 'closed'])->assertRedirect(route('admin.login'));
        $this->delete("/admin-panel/contact-messages/{$record->id}")->assertRedirect(route('admin.login'));

        $this->assertModelExists($record);
    }
}
