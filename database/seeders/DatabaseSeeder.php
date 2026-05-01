<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Roles
        $pioRole = Role::create(['name' => 'PIO']);
        $studentRole = Role::create(['name' => 'Student']);

        // 2. Create Categories
        Category::create(['name' => 'Exam']);
        Category::create(['name' => 'Project Deadline']);
        Category::create(['name' => 'Class Meeting']);
        Category::create(['name' => 'School Event']);

        // 3. Create a default PIO User
        User::create([
            'name' => 'Admin PIO',
            'email' => 'pio@school.edu',
            'student_number' => 'PIO-001',
            'password' => Hash::make('password'),
            'role_id' => $pioRole->id,
        ]);

        // 4. Create a default Regular Student User
        User::create([
            'name' => 'John Doe',
            'email' => 'student@school.edu',
            'student_number' => 'STU-001',
            'password' => Hash::make('password'),
            'role_id' => $studentRole->id,
        ]);
    }
}