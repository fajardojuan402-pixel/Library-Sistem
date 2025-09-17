<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Author;

class AuthorFactory extends Factory
{
    /**
     * El modelo que corresponde a esta fábrica
     *
     */
    protected $model = Author::class;

    /**
     * Definir el estado por defecto del modelo.
     *
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'bio'  => $this->faker->sentence(),
        ];
    }
}
