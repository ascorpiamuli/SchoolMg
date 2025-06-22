<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Guardian;
use App\Models\Accountant;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::factory()->create([
            'email' => 'admin@example.com',
            'role'  => 'admin',
            'username' => 'admin',
        ]);

        // Teachers
        User::factory()
            ->count(5)
            ->state(['role' => 'teacher'])
            ->create()
            ->each(function ($user) {
                Teacher::factory()->create(['user_id' => $user->id]);
            });

        // Parents
        User::factory()
            ->count(5)
            ->state(['role' => 'parent'])
            ->create()
            ->each(function ($user) {
                Guardian::factory()->create(['user_id' => $user->id]);
            });

        // Accountants
        User::factory()
            ->count(3)
            ->state(['role' => 'accountant'])
            ->create()
            ->each(function ($user) {
                Accountant::factory()->create(['user_id' => $user->id]);
            });
    }
}

