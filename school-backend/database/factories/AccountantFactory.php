<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AccountantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'       => \App\Models\User::factory(),
            'qualification' => $this->faker->randomElement(['CPA', 'B.Com', 'MBA']),
        ];
    }

}

