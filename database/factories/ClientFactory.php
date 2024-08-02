<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ClientFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $secret_id;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => Str::random(10),
            'secret_id' => static::$secret_id ??= Hash::make('secret_id'),
            'remember_token' => Str::random(10),
        ];
    }
}
