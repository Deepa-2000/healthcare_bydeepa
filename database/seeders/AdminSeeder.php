<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'], // Ensure uniqueness
            [
                'name' => 'SuperAdmin',
                'email' => 'admin@example.in',
                'password' => Hash::make('admin123'), 
                'role' => 'admin',
            ]
        );

    }
}
