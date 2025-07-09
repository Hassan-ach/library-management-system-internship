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
            'first_name' => 'Alice',
            'last_name' => 'Johnson',
            'email' => 'alice@student.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'role' => UserRole::STUDENT,
        ]);

        User::create([
            'first_name' => 'Bob',
            'last_name' => 'Smith',
            'email' => 'bob@librarian.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'role' => UserRole::LIBRARIAN,
        ]);

        User::create([
            'first_name' => 'Charlie',
            'last_name' => 'Brown',
            'email' => 'charlie@admin.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'role' => UserRole::ADMIN,
        ]);

        User::create([
            'first_name' => 'Dana',
            'last_name' => 'White',
            'email' => 'dana@student.com',
            'password' => Hash::make('password'),
            'is_active' => false,
            'role' => UserRole::STUDENT,
        ]);

        User::create([
            'first_name' => 'Eli',
            'last_name' => 'Black',
            'email' => 'eli@librarian.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'role' => UserRole::LIBRARIAN, ]);
    }
}
