<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        $genres = ['Fiction', 'Technology', 'Science Fiction', 'Business', 'Design', 'History', 'Self-Help'];
        
        $sampleCovers = [
            'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1532012197267-da84d127e765?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1541963463532-d68292c34b19?auto=format&fit=crop&w=600&q=80'
        ];

        return [
            'title' => fake()->catchPhrase(),
            'author' => fake()->name(),
            'isbn' => fake()->unique()->isbn13(),
            'genre' => fake()->randomElement($genres),
            'description' => fake()->paragraph(3),
            'cover_image' => fake()->randomElement($sampleCovers),
            'published_year' => fake()->numberBetween(1995, 2026),
            'price' => fake()->randomFloat(2, 9.99, 49.99),
            'rating' => fake()->randomFloat(2, 3.5, 5.0),
            'is_available' => fake()->boolean(85),
        ];
    }
}
