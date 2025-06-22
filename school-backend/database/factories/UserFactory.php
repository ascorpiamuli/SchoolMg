<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        $gender = $this->faker->randomElement(['male', 'female']);
        $role   = $this->faker->randomElement(['admin', 'teacher', 'parent', 'accountant']);

        return [
            'username'    => $this->faker->unique()->userName,
            'first_name'  => $this->faker->firstName($gender),
            'last_name'   => $this->faker->lastName,
            'middle_name' => $this->faker->lastName,
            'email'       => $this->faker->unique()->safeEmail,
            'password'    => bcrypt('password'), // default password
            'role'        => $role,
            'phone'       => '+2547' . rand(10000000, 99999999),
            'gender'      => $gender,
            'address'     => $this->faker->address,
            'national_id' => $this->faker->unique()->numerify('########'),
        ];
    }
}
