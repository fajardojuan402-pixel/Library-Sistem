<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Genre;

class GenreFactory extends Factory
{
    /**
     * El modelo que corresponde a esta fábrica
     *
     * @var string
     */
    protected $model = Genre::class;

    /**
     * Definir el estado por defecto del modelo.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
        ];
    }
}
