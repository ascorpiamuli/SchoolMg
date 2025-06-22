<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

class TeacherFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'       => User::factory()->create(['role' => 'teacher'])->id, // ensures valid teacher user
            'staff_id'      => 'TCH-' . $this->faker->unique()->numberBetween(1000, 9999),
            'tsc_number'    => 'TSC-' . $this->faker->unique()->numerify('TSC-######'),
            'dept'          => $this->faker->randomElement(['Sciences', 'Humanities', 'Languages', 'ICT']),
            'qualification' => $this->faker->randomElement(['B.Ed', 'M.Ed', 'PGDE', 'Diploma in Education']),
            'subjects'      => ['English', 'Mathematics'], // assuming stored as JSON
            'employer'      => $this->faker->randomElement(['TSC', 'BoM', 'Private']),
            'salutation'    => $this->faker->randomElement(['Mr.', 'Mrs.', 'Ms.', 'Dr.']),
            //'class_stream_id' => null,

            'date_of_birth' => $this->faker->date('Y-m-d', '-25 years'), // realistic age
        ];
    }
}
