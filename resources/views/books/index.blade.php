<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Book System | Demo App Dashboard & Mobile API</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN for instant, rich aesthetic styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        display: ['Space Grotesk', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .glass-panel {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glass-card {
            background: rgba(30, 41, 59, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-card:hover {
            transform: translateY(-4px);
            border-color: rgba(99, 102, 241, 0.4);
            box-shadow: 0 20px 25px -5px rgba(99, 102, 241, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.4);
        }
        .glow-effect {
            position: relative;
        }
        .glow-effect::before {
            content: '';
            position: absolute;
            top: -2px; left: -2px; right: -2px; bottom: -2px;
            background: linear-gradient(45deg, #6366f1, #a855f7, #ec4899);
            border-radius: inherit;
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .glow-effect:hover::before {
            opacity: 0.7;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 font-sans selection:bg-indigo-500 selection:text-white flex flex-col">

    <!-- Top Navigation -->
    <header class="sticky top-0 z-40 glass-panel border-b border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand Logo -->
            <div class="flex items-center space-x-3">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-indigo-600 via-purple-600 to-pink-500 p-0.5 shadow-lg shadow-indigo-500/20">
                    <div class="w-full h-full bg-slate-950 rounded-[10px] flex items-center justify-center">
                        <i class="fa-solid fa-book-open-reader text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-pink-400 text-xl"></i>
                    </div>
                </div>
                <div>
                    <h1 class="font-display font-bold text-xl tracking-tight text-white flex items-center gap-2">
                        BOOK VAULT <span class="text-xs px-2 py-0.5 rounded-full bg-indigo-500/20 text-indigo-400 font-sans font-semibold border border-indigo-500/30">demo-app</span>
                    </h1>
                    <p class="text-xs text-slate-400 font-medium">Laravel 12 REST API & Dashboard System</p>
                </div>
            </div>

            <!-- Stats Header Quick Badges -->
            <div class="hidden md:flex items-center space-x-4">
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900/80 border border-slate-800 text-xs">
                    <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                    <span class="text-slate-400">Total Books:</span>
                    <span class="font-bold text-white">{{ $stats['total'] }}</span>
                </div>
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900/80 border border-slate-800 text-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span class="text-slate-400">Available:</span>
                    <span class="font-bold text-emerald-400">{{ $stats['available'] }}</span>
                </div>
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900/80 border border-slate-800 text-xs">
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    <span class="text-slate-400">Top Rated:</span>
                    <span class="font-bold text-amber-300">{{ $stats['top_rated'] }}</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center space-x-3">
                <button onclick="openApiModal()" class="hidden sm:inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-700/60 text-sm font-semibold transition-all">
                    <i class="fa-solid fa-mobile-screen text-indigo-400"></i> Mobile API Docs
                </button>
                <button onclick="openCreateModal()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white text-sm font-semibold shadow-lg shadow-indigo-600/30 transition-all hover:scale-[1.02] active:scale-[0.98]">
                    <i class="fa-solid fa-plus text-xs"></i> Add New Book
                </button>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        <!-- Flash Alert Message -->
        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center justify-between animate-fade-in">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                    <span class="font-medium text-sm">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-200">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        <!-- Hero Stats Banner -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="glass-panel p-5 rounded-2xl flex items-center space-x-4 border border-slate-800">
                <div class="w-12 h-12 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 text-xl">
                    <i class="fa-solid fa-books"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Total Collection</p>
                    <p class="text-2xl font-bold font-display text-white mt-0.5">{{ $stats['total'] }}</p>
                </div>
            </div>

            <div class="glass-panel p-5 rounded-2xl flex items-center space-x-4 border border-slate-800">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 text-xl">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">In Stock</p>
                    <p class="text-2xl font-bold font-display text-emerald-400 mt-0.5">{{ $stats['available'] }}</p>
                </div>
            </div>

            <div class="glass-panel p-5 rounded-2xl flex items-center space-x-4 border border-slate-800">
                <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400 text-xl">
                    <i class="fa-solid fa-star"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Highly Rated</p>
                    <p class="text-2xl font-bold font-display text-amber-300 mt-0.5">{{ $stats['top_rated'] }}</p>
                </div>
            </div>

            <div class="glass-panel p-5 rounded-2xl flex items-center space-x-4 border border-slate-800">
                <div class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 text-xl">
                    <i class="fa-solid fa-tags"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Genres</p>
                    <p class="text-2xl font-bold font-display text-purple-300 mt-0.5">{{ $stats['categories'] }}</p>
                </div>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="glass-panel p-4 rounded-2xl border border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4">
            
            <!-- Search Input -->
            <form action="{{ route('books.index') }}" method="GET" class="w-full md:w-96 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search title, author, genre, ISBN..." 
                    class="w-full pl-11 pr-10 py-2.5 rounded-xl bg-slate-900 border border-slate-700/70 text-slate-200 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all placeholder:text-slate-500">
                @if($search)
                    <a href="{{ route('books.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300">
                        <i class="fa-solid fa-xmark text-xs"></i>
                    </a>
                @endif
            </form>

            <!-- Genre Tabs / Buttons -->
            <div class="flex items-center overflow-x-auto w-full md:w-auto space-x-2 pb-2 md:pb-0 scrollbar-none">
                <a href="{{ route('books.index', array_merge(request()->query(), ['genre' => ''])) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap transition-all {{ empty($genre) ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800' }}">
                   All Genres
                </a>
                @foreach($genres as $g)
                    <a href="{{ route('books.index', array_merge(request()->query(), ['genre' => $g])) }}" 
                       class="px-3.5 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap transition-all {{ $genre === $g ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800' }}">
                       {{ $g }}
                    </a>
                @endforeach
            </div>

            <!-- Sort dropdown -->
            <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                <span class="text-xs text-slate-400 font-medium">Sort:</span>
                <select onchange="window.location.href=this.value" class="bg-slate-900 text-slate-300 border border-slate-700/70 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-indigo-500">
                    <option value="{{ route('books.index', array_merge(request()->query(), ['sort' => 'latest'])) }}" {{ $sortBy === 'latest' ? 'selected' : '' }}>Latest Added</option>
                    <option value="{{ route('books.index', array_merge(request()->query(), ['sort' => 'rating'])) }}" {{ $sortBy === 'rating' ? 'selected' : '' }}>Highest Rating</option>
                    <option value="{{ route('books.index', array_merge(request()->query(), ['sort' => 'price_asc'])) }}" {{ $sortBy === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="{{ route('books.index', array_merge(request()->query(), ['sort' => 'price_desc'])) }}" {{ $sortBy === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="{{ route('books.index', array_merge(request()->query(), ['sort' => 'title'])) }}" {{ $sortBy === 'title' ? 'selected' : '' }}>Title (A-Z)</option>
                </select>
            </div>
        </div>

        <!-- Books Grid Showcase -->
        @if($books->isEmpty())
            <div class="glass-panel rounded-2xl p-12 text-center max-w-md mx-auto border border-slate-800 my-12">
                <div class="w-16 h-16 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center mx-auto text-slate-500 text-2xl mb-4">
                    <i class="fa-solid fa-book-open"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-1">No Books Found</h3>
                <p class="text-slate-400 text-sm mb-6">We couldn't find any books matching your criteria.</p>
                <a href="{{ route('books.index') }}" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-indigo-400 text-sm font-semibold border border-slate-700">
                    Reset Filters
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($books as $book)
                    <div class="glass-card rounded-2xl overflow-hidden flex flex-col justify-between group">
                        
                        <!-- Book Cover Image Section -->
                        <div class="relative h-64 overflow-hidden bg-slate-900">
                            <img src="{{ $book->cover_image ?? 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&w=600&q=80' }}" 
                                 alt="{{ $book->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>
                            
                            <!-- Badges -->
                            <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold tracking-wide bg-slate-950/80 backdrop-blur-md text-indigo-300 border border-indigo-500/30">
                                    {{ $book->genre }}
                                </span>
                            </div>

                            <div class="absolute top-3 right-3">
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold flex items-center gap-1.5 {{ $book->is_available ? 'bg-emerald-950/90 text-emerald-400 border border-emerald-500/40' : 'bg-rose-950/90 text-rose-400 border border-rose-500/40' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $book->is_available ? 'bg-emerald-400' : 'bg-rose-400' }}"></span>
                                    {{ $book->is_available ? 'Available' : 'Borrowed' }}
                                </span>
                            </div>

                            <!-- Floating Price Tag -->
                            <div class="absolute bottom-3 left-3">
                                <span class="text-lg font-bold font-display text-white drop-shadow-md">
                                    ${{ number_format($book->price, 2) }}
                                </span>
                            </div>

                            <!-- Rating Badge -->
                            <div class="absolute bottom-3 right-3 bg-slate-950/80 backdrop-blur-md px-2 py-0.5 rounded-md border border-slate-800 text-xs font-bold text-amber-400 flex items-center gap-1">
                                <i class="fa-solid fa-star text-[10px]"></i> {{ number_format($book->rating, 1) }}
                            </div>
                        </div>

                        <!-- Card Content -->
                        <div class="p-5 flex-grow flex flex-col justify-between">
                            <div>
                                <h3 class="font-bold text-base text-white line-clamp-1 group-hover:text-indigo-400 transition-colors" title="{{ $book->title }}">
                                    {{ $book->title }}
                                </h3>
                                <p class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                                    <i class="fa-regular fa-user text-[10px]"></i> {{ $book->author }}
                                    @if($book->published_year)
                                        <span class="text-slate-600">•</span>
                                        <span>{{ $book->published_year }}</span>
                                    @endif
                                </p>
                                
                                <p class="text-xs text-slate-400 mt-3 line-clamp-2 leading-relaxed">
                                    {{ $book->description ?? 'No description available for this book.' }}
                                </p>
                            </div>

                            <!-- ISBN & Actions -->
                            <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs">
                                <span class="text-[11px] font-mono text-slate-500">
                                    ISBN: {{ $book->isbn ? Str::limit($book->isbn, 13) : 'N/A' }}
                                </span>

                                <div class="flex items-center space-x-1">
                                    <!-- View Modal Trigger -->
                                    <button onclick="viewBook({{ json_encode($book) }})" title="View Details" 
                                            class="w-8 h-8 rounded-lg bg-slate-900 hover:bg-slate-800 text-slate-400 hover:text-white flex items-center justify-center border border-slate-800 transition-all">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>

                                    <!-- Edit Modal Trigger -->
                                    <button onclick="editBook({{ json_encode($book) }})" title="Edit Book" 
                                            class="w-8 h-8 rounded-lg bg-slate-900 hover:bg-indigo-950 text-slate-400 hover:text-indigo-300 flex items-center justify-center border border-slate-800 hover:border-indigo-500/40 transition-all">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>

                                    <!-- Delete Button -->
                                    <button onclick="confirmDeleteBook({{ $book->id }}, '{{ addslashes($book->title) }}')" title="Delete Book" 
                                            class="w-8 h-8 rounded-lg bg-slate-900 hover:bg-rose-950 text-slate-400 hover:text-rose-400 flex items-center justify-center border border-slate-800 hover:border-rose-500/40 transition-all">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pt-6 border-t border-slate-800">
                {{ $books->links() }}
            </div>
        @endif

    </main>

    <!-- Footer -->
    <footer class="glass-panel border-t border-slate-800 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-code text-indigo-400"></i>
                <span>Demo App Book System • Powered by <strong>Laravel 12</strong> & Tailwind CSS</span>
            </div>
            <div class="flex items-center space-x-4">
                <span class="px-2.5 py-1 rounded bg-slate-900 border border-slate-800 font-mono text-[11px] text-indigo-400">API: /api/v1/books</span>
                <span>Ready for Android & iOS mobile integration</span>
            </div>
        </div>
    </footer>

    <!-- ==================== MODALS ==================== -->

    <!-- CREATE BOOK MODAL -->
    <div id="createModal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
        <div class="glass-panel rounded-2xl border border-slate-700/80 max-w-lg w-full p-6 shadow-2xl overflow-y-auto max-h-[90vh]">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-plus-circle text-indigo-400"></i> Create New Book
                </h3>
                <button onclick="closeModal('createModal')" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('books.store') }}" method="POST" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Title *</label>
                    <input type="text" name="title" required placeholder="e.g. Clean Architecture" 
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Author *</label>
                        <input type="text" name="author" required placeholder="e.g. Robert C. Martin" 
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-sm text-white focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Genre *</label>
                        <input type="text" name="genre" required placeholder="e.g. Technology" 
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-sm text-white focus:outline-none focus:border-indigo-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">ISBN</label>
                        <input type="text" name="isbn" placeholder="9780134494166" 
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-sm text-white focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Year</label>
                        <input type="number" name="published_year" placeholder="2024" min="1500" max="2030" 
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-sm text-white focus:outline-none focus:border-indigo-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Price ($)</label>
                        <input type="number" step="0.01" name="price" placeholder="29.99" 
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-sm text-white focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Rating (1-5)</label>
                        <input type="number" step="0.1" name="rating" placeholder="4.8" min="1" max="5" 
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-sm text-white focus:outline-none focus:border-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Cover Image URL</label>
                    <input type="url" name="cover_image" placeholder="https://images.unsplash.com/..." 
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Description</label>
                    <textarea name="description" rows="3" placeholder="Enter book overview..." 
                        class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-sm text-white focus:outline-none focus:border-indigo-500"></textarea>
                </div>

                <div class="flex items-center space-x-2 pt-2">
                    <input type="checkbox" name="is_available" id="create_available" value="1" checked class="w-4 h-4 text-indigo-600 rounded bg-slate-900 border-slate-700">
                    <label for="create_available" class="text-sm font-medium text-slate-300">Mark as Available in Stock</label>
                </div>

                <div class="pt-4 flex justify-end gap-3 border-t border-slate-800">
                    <button type="button" onclick="closeModal('createModal')" class="px-4 py-2 rounded-xl bg-slate-900 text-slate-400 hover:text-white text-sm">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm shadow-lg shadow-indigo-600/30">Save Book</button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT BOOK MODAL -->
    <div id="editModal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
        <div class="glass-panel rounded-2xl border border-slate-700/80 max-w-lg w-full p-6 shadow-2xl overflow-y-auto max-h-[90vh]">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fa-regular fa-pen-to-square text-indigo-400"></i> Edit Book
                </h3>
                <button onclick="closeModal('editModal')" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form id="editForm" method="POST" class="mt-4 space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Title *</label>
                    <input type="text" id="edit_title" name="title" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Author *</label>
                        <input type="text" id="edit_author" name="author" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-sm text-white focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Genre *</label>
                        <input type="text" id="edit_genre" name="genre" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-sm text-white focus:outline-none focus:border-indigo-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">ISBN</label>
                        <input type="text" id="edit_isbn" name="isbn" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-sm text-white focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Year</label>
                        <input type="number" id="edit_published_year" name="published_year" min="1500" max="2030" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-sm text-white focus:outline-none focus:border-indigo-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Price ($)</label>
                        <input type="number" step="0.01" id="edit_price" name="price" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-sm text-white focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Rating (1-5)</label>
                        <input type="number" step="0.1" id="edit_rating" name="rating" min="1" max="5" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-sm text-white focus:outline-none focus:border-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Cover Image URL</label>
                    <input type="url" id="edit_cover_image" name="cover_image" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Description</label>
                    <textarea id="edit_description" name="description" rows="3" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-sm text-white focus:outline-none focus:border-indigo-500"></textarea>
                </div>

                <div class="flex items-center space-x-2 pt-2">
                    <input type="checkbox" name="is_available" id="edit_available" value="1" class="w-4 h-4 text-indigo-600 rounded bg-slate-900 border-slate-700">
                    <label for="edit_available" class="text-sm font-medium text-slate-300">Mark as Available in Stock</label>
                </div>

                <div class="pt-4 flex justify-end gap-3 border-t border-slate-800">
                    <button type="button" onclick="closeModal('editModal')" class="px-4 py-2 rounded-xl bg-slate-900 text-slate-400 hover:text-white text-sm">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm shadow-lg shadow-indigo-600/30">Update Book</button>
                </div>
            </form>
        </div>
    </div>

    <!-- VIEW BOOK DETAILS MODAL -->
    <div id="viewModal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
        <div class="glass-panel rounded-2xl border border-slate-700/80 max-w-lg w-full p-6 shadow-2xl overflow-y-auto max-h-[90vh]">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                <span id="view_genre" class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30"></span>
                <button onclick="closeModal('viewModal')" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="mt-4 flex flex-col sm:flex-row gap-6">
                <div class="w-full sm:w-1/3 h-56 rounded-xl overflow-hidden bg-slate-900 shrink-0 border border-slate-800">
                    <img id="view_cover" src="" alt="Book Cover" class="w-full h-full object-cover">
                </div>

                <div class="w-full sm:w-2/3 space-y-3">
                    <h2 id="view_title" class="text-xl font-bold text-white font-display"></h2>
                    <p id="view_author" class="text-sm text-slate-400 flex items-center gap-1.5"></p>
                    
                    <div class="flex items-center gap-4 py-2 border-y border-slate-800 text-xs">
                        <div>
                            <span class="text-slate-500 block">Price</span>
                            <span id="view_price" class="font-bold text-white text-base"></span>
                        </div>
                        <div>
                            <span class="text-slate-500 block">Rating</span>
                            <span id="view_rating" class="font-bold text-amber-400 text-base"></span>
                        </div>
                        <div>
                            <span class="text-slate-500 block">Status</span>
                            <span id="view_status" class="font-bold"></span>
                        </div>
                    </div>

                    <div>
                        <span class="text-xs text-slate-500 block font-mono">ISBN: <span id="view_isbn" class="text-slate-300"></span></span>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Description</p>
                        <p id="view_description" class="text-xs text-slate-300 leading-relaxed"></p>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-800 flex justify-end">
                <button onclick="closeModal('viewModal')" class="px-5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm">Close</button>
            </div>
        </div>
    </div>

    <!-- DELETE CONFIRMATION FORM (Hidden) -->
    <form id="deleteForm" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <!-- MOBILE API PLAYGROUND MODAL -->
    <div id="apiModal" class="fixed inset-0 z-50 hidden bg-slate-950/85 backdrop-blur-md flex items-center justify-center p-4">
        <div class="glass-panel rounded-2xl border border-slate-700 max-w-3xl w-full p-6 shadow-2xl overflow-y-auto max-h-[90vh]">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-indigo-500/20 text-indigo-400 flex items-center justify-center border border-indigo-500/30">
                        <i class="fa-solid fa-code"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Mobile REST API Endpoints</h3>
                        <p class="text-xs text-slate-400">Complete JSON routes ready for Android (Kotlin/Java) & iOS (Swift) integration</p>
                    </div>
                </div>
                <button onclick="closeModal('apiModal')" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="mt-6 space-y-6">
                <!-- Endpoints Table -->
                <div class="overflow-x-auto rounded-xl border border-slate-800">
                    <table class="w-full text-left text-xs text-slate-300">
                        <thead class="bg-slate-900 text-slate-400 font-semibold border-b border-slate-800 uppercase tracking-wider">
                            <tr>
                                <th class="p-3">Method</th>
                                <th class="p-3">Endpoint Route</th>
                                <th class="p-3">Description</th>
                                <th class="p-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/80 bg-slate-950/50">
                            <tr>
                                <td class="p-3"><span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400 font-bold border border-emerald-500/30">GET</span></td>
                                <td class="p-3 font-mono text-indigo-300">/api/v1/books</td>
                                <td class="p-3">Fetch list of books (supports ?search=, ?genre=, ?sort=)</td>
                                <td class="p-3 text-right">
                                    <button onclick="testApi('/api/v1/books')" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-indigo-600 text-white font-medium text-[11px] transition-all">Test GET</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-3"><span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400 font-bold border border-emerald-500/30">GET</span></td>
                                <td class="p-3 font-mono text-indigo-300">/api/v1/books/{id}</td>
                                <td class="p-3">Fetch single book details</td>
                                <td class="p-3 text-right">
                                    <button onclick="testApi('/api/v1/books/1')" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-indigo-600 text-white font-medium text-[11px] transition-all">Test ID 1</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-3"><span class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-400 font-bold border border-indigo-500/30">POST</span></td>
                                <td class="p-3 font-mono text-indigo-300">/api/v1/books</td>
                                <td class="p-3">Create new book with JSON payload</td>
                                <td class="p-3 text-right"><span class="text-slate-500">JSON Body</span></td>
                            </tr>
                            <tr>
                                <td class="p-3"><span class="px-2 py-0.5 rounded bg-amber-500/20 text-amber-400 font-bold border border-amber-500/30">PUT</span></td>
                                <td class="p-3 font-mono text-indigo-300">/api/v1/books/{id}</td>
                                <td class="p-3">Update book attributes</td>
                                <td class="p-3 text-right"><span class="text-slate-500">JSON Body</span></td>
                            </tr>
                            <tr>
                                <td class="p-3"><span class="px-2 py-0.5 rounded bg-rose-500/20 text-rose-400 font-bold border border-rose-500/30">DELETE</span></td>
                                <td class="p-3 font-mono text-indigo-300">/api/v1/books/{id}</td>
                                <td class="p-3">Delete book record</td>
                                <td class="p-3 text-right"><span class="text-slate-500">URL Parameter</span></td>
                            </tr>
                            <tr>
                                <td class="p-3"><span class="px-2 py-0.5 rounded bg-purple-500/20 text-purple-400 font-bold border border-purple-500/30">GET</span></td>
                                <td class="p-3 font-mono text-indigo-300">/api/v1/genres</td>
                                <td class="p-3">Get unique genres list & counts</td>
                                <td class="p-3 text-right">
                                    <button onclick="testApi('/api/v1/genres')" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-indigo-600 text-white font-medium text-[11px] transition-all">Test Genres</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-3"><span class="px-2 py-0.5 rounded bg-blue-500/20 text-blue-400 font-bold border border-blue-500/30">GET</span></td>
                                <td class="p-3 font-mono text-indigo-300">/api/v1/stats</td>
                                <td class="p-3">Get library stats for mobile dashboard</td>
                                <td class="p-3 text-right">
                                    <button onclick="testApi('/api/v1/stats')" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-indigo-600 text-white font-medium text-[11px] transition-all">Test Stats</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Interactive API Response Box -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Live API JSON Response Output</label>
                    <pre id="apiOutput" class="p-4 rounded-xl bg-slate-900 border border-slate-800 text-emerald-400 font-mono text-xs overflow-x-auto max-h-60">Click any "Test" button above to view live JSON data response...</pre>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-800 flex justify-end">
                <button onclick="closeModal('apiModal')" class="px-5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm">Close</button>
            </div>
        </div>
    </div>

    <!-- JavaScript Handlers -->
    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        function openCreateModal() {
            openModal('createModal');
        }

        function openApiModal() {
            openModal('apiModal');
        }

        function editBook(book) {
            document.getElementById('editForm').action = '/books/' + book.id;
            document.getElementById('edit_title').value = book.title || '';
            document.getElementById('edit_author').value = book.author || '';
            document.getElementById('edit_genre').value = book.genre || '';
            document.getElementById('edit_isbn').value = book.isbn || '';
            document.getElementById('edit_published_year').value = book.published_year || '';
            document.getElementById('edit_price').value = book.price || '';
            document.getElementById('edit_rating').value = book.rating || '';
            document.getElementById('edit_cover_image').value = book.cover_image || '';
            document.getElementById('edit_description').value = book.description || '';
            document.getElementById('edit_available').checked = Boolean(book.is_available);
            
            openModal('editModal');
        }

        function viewBook(book) {
            document.getElementById('view_title').innerText = book.title;
            document.getElementById('view_author').innerHTML = '<i class="fa-regular fa-user"></i> ' + book.author + (book.published_year ? ' (' + book.published_year + ')' : '');
            document.getElementById('view_genre').innerText = book.genre;
            document.getElementById('view_price').innerText = '$' + (parseFloat(book.price) || 0).toFixed(2);
            document.getElementById('view_rating').innerText = '★ ' + (parseFloat(book.rating) || 0).toFixed(1);
            document.getElementById('view_isbn').innerText = book.isbn || 'N/A';
            document.getElementById('view_description').innerText = book.description || 'No detailed description available.';
            document.getElementById('view_cover').src = book.cover_image || 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&w=600&q=80';
            
            const statusEl = document.getElementById('view_status');
            if (book.is_available) {
                statusEl.className = 'font-bold text-emerald-400';
                statusEl.innerText = 'In Stock';
            } else {
                statusEl.className = 'font-bold text-rose-400';
                statusEl.innerText = 'Borrowed';
            }

            openModal('viewModal');
        }

        function confirmDeleteBook(id, title) {
            if (confirm('Are you sure you want to delete the book "' + title + '"?')) {
                const form = document.getElementById('deleteForm');
                form.action = '/books/' + id;
                form.submit();
            }
        }

        async function testApi(url) {
            const output = document.getElementById('apiOutput');
            output.innerText = 'Loading request to ' + url + ' ...';
            try {
                const response = await fetch(url, {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await response.json();
                output.innerText = JSON.stringify(data, null, 2);
            } catch (err) {
                output.innerText = 'Error executing request: ' + err.message;
            }
        }
    </script>
</body>
</html>
