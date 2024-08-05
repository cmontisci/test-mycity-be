<?php

namespace Database\Seeders;

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
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
            'role_id' => 1,
            'user_type_id' => 1
        ]);

        User::create([
            'name' => 'Regular User',
            'email' => 'user@user.com',
            'password' => Hash::make('user'),
            'role_id' => 2,
            'user_type_id' => 1
        ]);

        User::create([
            'name' => 'Client User 1',
            'client_id' => 'client_1',
            'password' => Hash::make('secret_1'),
            'role_id' => 1,
            'user_type_id' => 2
        ]);

        User::create([
            'name' => 'Client User 2',
            'client_id' => 'client_2',
            'password' => Hash::make('secret_2'),
            'role_id' => 2,
            'user_type_id' => 2
        ]);
    }
}
