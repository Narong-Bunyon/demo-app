<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of books for the Web UI dashboard.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $genre = $request->query('genre');
        $sortBy = $request->query('sort', 'latest');

        $query = Book::query()
            ->search($search)
            ->genre($genre);

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
            default:
                $query->latest();
                break;
        }

        $books = $query->paginate(12)->withQueryString();

        $genres = Book::select('genre')
            ->distinct()
            ->whereNotNull('genre')
            ->pluck('genre');

        $stats = [
            'total' => Book::count(),
            'available' => Book::where('is_available', true)->count(),
            'top_rated' => Book::where('rating', '>=', 4.7)->count(),
            'categories' => $genres->count(),
        ];

        return view('books.index', compact('books', 'genres', 'stats', 'search', 'genre', 'sortBy'));
    }

    /**
     * Store a newly created book from Web UI modal.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
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

        $validated['is_available'] = $request->has('is_available') ? (bool)$request->is_available : true;
        if (empty($validated['price'])) $validated['price'] = 0.00;
        if (empty($validated['rating'])) $validated['rating'] = 5.00;
        if (empty($validated['cover_image'])) {
            $validated['cover_image'] = 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&w=600&q=80';
        }

        $book = Book::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Book created successfully!',
                'data' => $book,
            ], 201);
        }

        return redirect()->route('books.index')->with('success', 'Book created successfully!');
    }

    /**
     * Show details of a single book.
     */
    public function show(Book $book)
    {
        return response()->json([
            'success' => true,
            'data' => $book
        ]);
    }

    /**
     * Update specified book from Web UI modal.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20|unique:books,isbn,' . $book->id,
            'genre' => 'required|string|max:100',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|url|max:1000',
            'published_year' => 'nullable|integer|min:1500|max:2030',
            'price' => 'nullable|numeric|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
            'is_available' => 'nullable|boolean',
        ]);

        $validated['is_available'] = $request->has('is_available') ? (bool)$request->is_available : false;

        $book->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Book updated successfully!',
                'data' => $book,
            ]);
        }

        return redirect()->route('books.index')->with('success', 'Book updated successfully!');
    }

    /**
     * Remove the specified book.
     */
    public function destroy(Request $request, Book $book)
    {
        $book->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Book deleted successfully!',
            ]);
        }

        return redirect()->route('books.index')->with('success', 'Book deleted successfully!');
    }
}
