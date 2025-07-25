<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'id' => 1,
            'first_name' => 'system',
            'last_name' => 'admin',
            'email' => 'system@admin.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'role' => UserRole::ADMIN,
        ]);
        User::create([
            'id' => 2,
            'first_name' => 'system',
            'last_name' => 'librarian',
            'email' => 'system@librarian.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'role' => UserRole::LIBRARIAN,
        ]);
        User::create([
            'id' => 3,
            'first_name' => 'system',
            'last_name' => 'student',
            'email' => 'system@student.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'role' => UserRole::STUDENT,
        ]);
    }
}
