<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::create([
            'client_id' => 'client_1',
            'secret_id' => Hash::make('secret_1'),
            'role_id' => 1,
        ]);

        Client::create([
            'client_id' => 'client_2',
            'secret_id' => Hash::make('secret_2'),
            'role_id' => 2,
        ]);
    }
}
