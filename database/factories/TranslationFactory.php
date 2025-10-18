<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TranslationFactory extends Factory
{
    public function definition(): array
    {
        $locales = ['en', 'fr', 'es'];

        return [
            'locale' => $this->faker->randomElement($locales),
            'key' => $this->faker->unique()->lexify('key_??????'),
            'content' => $this->faker->sentence(8),
        ];
    }
}
