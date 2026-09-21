<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_fetch_books_api_list(): void
    {
        Book::factory(3)->create();

        $response = $this->getJson('/api/v1/books');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'success',
                     'message',
                     'data' => [
                         '*' => ['id', 'title', 'author', 'genre', 'price', 'rating', 'is_available']
                     ],
                     'meta'
                 ]);
    }

    public function test_can_create_book_via_api(): void
    {
        $payload = [
            'title' => 'Mastering Laravel 12',
            'author' => 'Taylor Otwell',
            'isbn' => '9781234567890',
            'genre' => 'Technology',
            'description' => 'Comprehensive guide to building modern PHP applications.',
            'price' => 49.99,
            'rating' => 5.0,
            'published_year' => 2026,
            'is_available' => true,
        ];

        $response = $this->postJson('/api/v1/books', $payload);

        $response->assertStatus(201)
                 ->assertJson([
                     'status' => 201,
                     'success' => true,
                 ]);

        $this->assertDatabaseHas('books', ['title' => 'Mastering Laravel 12']);
    }

    public function test_can_update_book_via_api(): void
    {
        $book = Book::factory()->create(['title' => 'Old Title']);

        $response = $this->putJson("/api/v1/books/{$book->id}", [
            'title' => 'Updated Title',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('books', ['id' => $book->id, 'title' => 'Updated Title']);
    }

    public function test_can_delete_book_via_api(): void
    {
        $book = Book::factory()->create();

        $response = $this->deleteJson("/api/v1/books/{$book->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    public function test_can_fetch_library_stats(): void
    {
        Book::factory(5)->create();

        $response = $this->getJson('/api/v1/stats');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => ['total_books', 'available_books', 'borrowed_books', 'top_rated_count', 'total_genres']
                 ]);
    }
}
