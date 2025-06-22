<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\SchoolClass;

class StreamFactory extends Factory
{
    protected static $names = ['North', 'South', 'East', 'West'];
    protected static $index = 0;

    public function definition(): array
    {
        // Ensure only 4 unique names are used, no repeats
        $name = self::$names[self::$index % count(self::$names)];
        self::$index++;

        return [
            'name' => $name,
            'code' => strtoupper($this->faker->lexify('??')),
        ];
    }
}
