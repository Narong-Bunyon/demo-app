<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookApiController extends Controller
{
    /**
     * GET /api/v1/books
     * List all books for mobile client with filtering, search, and pagination.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $genre = $request->query('genre');
        $available = $request->query('available');
        $sortBy = $request->query('sort', 'latest');
        $perPage = (int) $request->query('per_page', 10);

        $query = Book::query()
            ->search($search)
            ->genre($genre);

        if ($available !== null && $available !== '') {
            $query->where('is_available', filter_var($available, FILTER_VALIDATE_BOOLEAN));
        }

        switch ($sortBy) {
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            default:
                $query->latest();
                break;
        }

        $books = $query->paginate($perPage);

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Books retrieved successfully',
            'data' => $books->items(),
            'meta' => [
                'current_page' => $books->currentPage(),
                'last_page' => $books->lastPage(),
                'per_page' => $books->perPage(),
                'total' => $books->total(),
            ],
        ], 200);
    }

    /**
     * POST /api/v1/books
     * Create a new book from mobile app.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20|unique:books,isbn',
            'genre' => 'required|string|max:100',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|url|max:1000',
            'published_year' => 'nullable|integer|min:1500|max:2030',
            'price' => 'nullable|numeric|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
            'is_available' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        if (!isset($data['cover_image']) || empty($data['cover_image'])) {
            $data['cover_image'] = 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&w=600&q=80';
        }
        if (!isset($data['is_available'])) {
            $data['is_available'] = true;
        }

        $book = Book::create($data);

        return response()->json([
            'status' => 201,
            'success' => true,
            'message' => 'Book created successfully',
            'data' => $book,
        ], 201);
    }

    /**
     * GET /api/v1/books/{id}
     * Get detail of a specific book for mobile app.
     */
    public function show($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'status' => 404,
                'success' => false,
                'message' => 'Book not found',
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Book details retrieved successfully',
            'data' => $book,
        ], 200);
    }

    /**
     * PUT/PATCH /api/v1/books/{id}
     * Update an existing book from mobile app.
     */
    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'status' => 404,
                'success' => false,
                'message' => 'Book not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'author' => 'sometimes|required|string|max:255',
            'isbn' => 'nullable|string|max:20|unique:books,isbn,' . $book->id,
            'genre' => 'sometimes|required|string|max:100',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|url|max:1000',
            'published_year' => 'nullable|integer|min:1500|max:2030',
            'price' => 'nullable|numeric|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
            'is_available' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $book->update($validator->validated());

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Book updated successfully',
            'data' => $book->fresh(),
        ], 200);
    }

    /**
     * DELETE /api/v1/books/{id}
     * Delete a book from mobile app.
     */
    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'status' => 404,
                'success' => false,
                'message' => 'Book not found',
            ], 404);
        }

        $book->delete();

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Book deleted successfully',
        ], 200);
    }

    /**
     * GET /api/v1/genres
     * Get list of genres with book counts.
     */
    public function genres()
    {
        $genres = Book::select('genre')
            ->selectRaw('count(*) as total_books')
            ->groupBy('genre')
            ->get();

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Genres retrieved successfully',
            'data' => $genres,
        ], 200);
    }

    /**
     * GET /api/v1/stats
     * Get overview stats for mobile app dashboard.
     */
    public function stats()
    {
        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Library stats retrieved successfully',
            'data' => [
                'total_books' => Book::count(),
                'available_books' => Book::where('is_available', true)->count(),
                'borrowed_books' => Book::where('is_available', false)->count(),
                'top_rated_count' => Book::where('rating', '>=', 4.7)->count(),
                'total_genres' => Book::distinct('genre')->count('genre'),
            ],
        ], 200);
    }
}
