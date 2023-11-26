<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ConsultantFactory extends Factory
{
    protected static ?string $password;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'sex' => 'male',
            'nationality' => 'USA',
            'introduction' => fake()->sentence(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('123456'),
            'enabled' => 1,

        ];
    }
}
