<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $curatedBooks = [
            [
                'title' => 'Clean Code: A Handbook of Agile Software Craftsmanship',
                'author' => 'Robert C. Martin',
                'isbn' => '9780132350884',
                'genre' => 'Technology',
                'description' => 'Even bad code can function. But if code isn\'t clean, it can bring a development organization to its knees. Every year, countless hours and significant resources are lost because of poorly written code.',
                'cover_image' => 'https://images.unsplash.com/photo-1532012197267-da84d127e765?auto=format&fit=crop&w=600&q=80',
                'published_year' => 2008,
                'price' => 37.99,
                'rating' => 4.80,
                'is_available' => true,
            ],
            [
                'title' => 'Designing Data-Intensive Applications',
                'author' => 'Martin Kleppmann',
                'isbn' => '9781449373320',
                'genre' => 'Technology',
                'description' => 'Data is at the center of many challenges in system design today. Learn key principles, algorithms, and trade-offs when building reliable, scalable, and maintainable systems.',
                'cover_image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=600&q=80',
                'published_year' => 2017,
                'price' => 44.99,
                'rating' => 4.95,
                'is_available' => true,
            ],
            [
                'title' => 'Dune',
                'author' => 'Frank Herbert',
                'isbn' => '9780441172719',
                'genre' => 'Science Fiction',
                'description' => 'Set on the desert planet Arrakis, Dune is the story of Paul Atreides, heir to a noble family tasked with ruling an inhospitable world where the only thing of value is the spice melange.',
                'cover_image' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=600&q=80',
                'published_year' => 1965,
                'price' => 18.50,
                'rating' => 4.70,
                'is_available' => true,
            ],
            [
                'title' => 'Atomic Habits',
                'author' => 'James Clear',
                'isbn' => '9780735211292',
                'genre' => 'Self-Help',
                'description' => 'No matter your goals, Atomic Habits offers a proven framework for improving every day. Learn practical strategies that will teach you exactly how to form good habits.',
                'cover_image' => 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&w=600&q=80',
                'published_year' => 2018,
                'price' => 21.99,
                'rating' => 4.90,
                'is_available' => true,
            ],
            [
                'title' => 'The Design of Everyday Things',
                'author' => 'Don Norman',
                'isbn' => '9780465050659',
                'genre' => 'Design',
                'description' => 'Even the smartest among us can feel inept trying to figure out which light switch to turn on or which door handle to pull. Learn the cognitive fundamentals of great UX design.',
                'cover_image' => 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&w=600&q=80',
                'published_year' => 2013,
                'price' => 26.00,
                'rating' => 4.65,
                'is_available' => false,
            ]
        ];

        foreach ($curatedBooks as $bookData) {
            Book::create($bookData);
        }
    }
}
