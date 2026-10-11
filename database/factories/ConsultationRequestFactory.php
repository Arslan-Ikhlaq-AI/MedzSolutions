<?php

namespace Database\Factories;

use App\Models\ConsultationRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ConsultationRequest>
 */
class ConsultationRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => 'meeting',
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'practice_name' => fake()->company().' Clinic',
            'specialty' => 'Cardiology',
            'preferred_date' => now()->addWeek()->toDateString(),
            'challenges' => null,
        ];
    }

    /**
     * A free practice review request from the revenue challenges checklist.
     */
    public function review(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'review',
            'practice_name' => null,
            'specialty' => null,
            'preferred_date' => null,
            'challenges' => ['denials', 'aging_ar'],
        ]);
    }
}
