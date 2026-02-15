<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin WarungDigi',
            'email' => 'admin@warungdigi.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);
    }
}
