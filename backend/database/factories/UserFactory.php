<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class UserFactory extends Factory
{
    /**
     * El modelo que corresponde a esta fábrica
     *
     */
    protected $model = User::class;

    /**
     * Definir el estado por defecto del modelo.
     *
     */
    public function definition(): array
    {
        return [
            'name'  => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
        ];
    }
}
