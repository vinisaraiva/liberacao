<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrador',
            'username' => 'admin',
            'role' => 'ADMIN',
            'email' => 'admin@escola.com',
            'password' => Hash::make('password'),
        ]);

        // Authorizer
        User::create([
            'name' => 'Autorizador',
            'username' => 'autorizador',
            'role' => 'AUTORIZADOR',
            'email' => 'autorizador@escola.com',
            'password' => Hash::make('password'),
        ]);
    }
}
