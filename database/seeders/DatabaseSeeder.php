<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Mark Caesar Aluad',
            'email' => 'johndoe@example.com',
            'role' => 'admin',
            'password' => '12345678'
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Jennifer Doe',
            'email' => 'jenniferdoe@example.com',
            'role' => 'user',
            'password' => '12345678'
        ]);
    }
}