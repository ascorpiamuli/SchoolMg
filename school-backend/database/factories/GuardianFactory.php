<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GuardianFactory extends Factory
{
    public function definition(): array
    {
        return [
            'occupation' => $this->faker->jobTitle,
        ];
    }
}
