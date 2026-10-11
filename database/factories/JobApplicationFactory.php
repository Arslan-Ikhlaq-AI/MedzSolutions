<?php

namespace Database\Factories;

use App\Models\JobApplication;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobApplication>
 */
class JobApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'job_slug' => 'certified-medical-coder',
            'job_title' => 'Certified Medical Coder',
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'linkedin_url' => 'https://www.linkedin.com/in/'.fake()->userName(),
            'cover_letter' => fake()->paragraph(),
            'resume_path' => 'resumes/'.fake()->uuid().'.pdf',
        ];
    }
}
