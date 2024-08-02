<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('it_IT');
        # genero 50 utenti casuali
        for ($i = 0; $i < 50; $i++) {
            DB::table('personas')->insert([
                'nome' => $faker->firstName,
                'cognome' => $faker->lastName,
                'data_di_nascita' => $faker->date($format = 'Y-m-d', $max = '2005-12-31'),
                'email' => $faker->unique()->safeEmail,
                'telefono' => $faker->phoneNumber,
                'codice_fiscale' => Str::upper(Str::random(16)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
