<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class JournalEntryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'content' => fake()->paragraph(3),
            'date' => fake()->date(),
            'location' => fake()->city(),
            'mood' => fake()->randomElement([
                'happy', 'sad', 'neutral', 'excited', 'angry'
            ]),
            'user_id' => 1,
        ];
    }
}