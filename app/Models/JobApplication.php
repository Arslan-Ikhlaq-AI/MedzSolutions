<?php

namespace App\Models;

use App\Models\Concerns\HasInboxStatus;
use Database\Factories\JobApplicationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['visitor_id', 'job_slug', 'job_title', 'name', 'email', 'phone', 'linkedin_url', 'cover_letter', 'resume_path'])]
class JobApplication extends Model
{
    /** @use HasFactory<JobApplicationFactory> */
    use HasFactory, HasInboxStatus;
}
