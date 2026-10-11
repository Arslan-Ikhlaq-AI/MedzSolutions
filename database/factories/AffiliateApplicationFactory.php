<?php

namespace Database\Factories;

use App\Models\AffiliateApplication;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AffiliateApplication>
 */
class AffiliateApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'partner_type' => 'Software Firms & Consultants',
            'company' => fake()->company(),
            'website' => fake()->url(),
            'audience' => 'Independent practices in my region',
            'message' => fake()->paragraph(),
        ];
    }
}
