<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Accountant;
use App\Models\Guardian;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create 10 teacher users, then attach teacher profiles
        User::factory()
            ->count(45)
            ->state(['role' => 'teacher'])
            ->create()
            ->each(function ($user) {
                Teacher::factory()->create([
                    'user_id' => $user->id,
                ]);
            });

        // 2. Create 5 parent users with guardian profiles
        $guardians = User::factory()
            ->count(45)
            ->state(['role' => 'parent'])
            ->create()
            ->map(function ($user) {
                return Guardian::factory()->create([
                    'user_id' => $user->id,
                ]);
            });

        // 3. Create 3 accountant users with profiles
        User::factory()
            ->count(3)
            ->state(['role' => 'accountant'])
            ->create()
            ->each(function ($user) {
                Accountant::factory()->create([
                    'user_id' => $user->id,
                ]);
            });

        // 4. Create 1 predictable admin
        User::factory()->create([
            'first_name' => 'Super',
            'last_name'  => 'Admin',
            'email'      => 'admin@example.com',
            'role'       => 'admin',
            'password'   => bcrypt('password'),
        ]);

        // 5. Seed students (after teachers exist)
        
        $this->call([
            SchoolClassSeeder::class,
            StreamSeeder::class,
            ClassStreamSeeder::class,  // 👈 MUST be before students
            StudentSeeder::class,
        ]);

        // 6. Attach guardians to students via pivot table
        $students = Student::all();
        $relations = ['father', 'mother', 'guardian', 'aunt', 'uncle'];

        foreach ($guardians as $guardian) {
            // Randomly pick 2 students for each guardian
            $guardian->students()->attach(
                $students->random(2)->pluck('id')->toArray(),
                ['relation' => $relations[array_rand($relations)]]
            );
        }
    }
}
