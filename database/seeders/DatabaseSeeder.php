<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\Employee::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'is_admin' => 1,
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
        ]);
        \App\Models\User::factory()->create([
            'name' => 'User',
            'email' => 'user@example.com',
            'is_admin' => 0,
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
        ]);
    }
}
