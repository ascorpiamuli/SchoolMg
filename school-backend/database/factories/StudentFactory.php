<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StudentFactory extends Factory
{
    public function definition(): array
    {
        $gender = $this->faker->randomElement(['male', 'female']);
        $first = $this->faker->firstName($gender);
        $middle = $this->faker->firstName($gender);
        $last = $this->faker->lastName;

        return [
            'admission_number' => 'ADM' . $this->faker->unique()->numberBetween(1000, 9999),
            'first_name'       => $first,
            'middle_name'      => $middle,
            'last_name'        => $last,
            'gender'           => ucfirst($gender), // "Male" or "Female"
            'date_of_birth'    => $this->faker->date('Y-m-d', '-5 years'), // 5+ yrs old
            'avatar'           => null, // or use fake image URL if needed
            'class_stream_id' => rand(1,36), // FK placeholder, can be set later
            'status'           => 'active',
            'enrolled_at'      => $this->faker->date('Y-m-d'),
        ];
    }
}
