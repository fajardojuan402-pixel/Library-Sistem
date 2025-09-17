<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Book;
use App\Models\Author;
use App\Models\Genre;

class BookFactory extends Factory
{
    /**
     * El modelo que corresponde a esta fábrica
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Definir el estado por defecto del modelo.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'author_id' => Author::factory(),
            'genre_id'  => Genre::factory(),
            'isbn' => $this->faker->unique()->isbn13(),
            'total_copies' => $this->faker->numberBetween(1, 5),
            'available_copies' => function (array $attributes) {
                return $attributes['total_copies'];
            },
        ];
    }
}
