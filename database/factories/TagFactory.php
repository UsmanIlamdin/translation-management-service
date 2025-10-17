<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    public function definition(): array
    {
        $tags = ['mobile', 'desktop', 'web', 'api', 'marketing', 'admin'];

        return [
            'name' => $this->faker->unique()->randomElement($tags),
        ];
    }
}
