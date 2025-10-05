@extends('frontend.layout')

@section('title', 'Home')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-primary-900 via-primary-700 to-primary-600 text-white py-20 overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <img src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=1920&q=80" alt="Code Background" class="w-full h-full object-cover">
    </div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-sm rounded-full px-4 py-2 mb-6">
                <i class="fas fa-fire text-accent-500"></i>
                <span class="text-sm font-medium">Latest Tech Insights & Tutorials</span>
            </div>

            <h1 class="text-5xl md:text-6xl font-bold font-heading mb-6 leading-tight">
                Code. Learn. <span class="text-accent-500">Innovate.</span>
            </h1>

            <p class="text-xl md:text-2xl mb-8 text-gray-200">
                Discover cutting-edge programming tutorials, tech insights, and developer resources at Wire.
            </p>

            <!-- Search Form -->
            <form action="{{ route('home') }}" method="GET" class="max-w-2xl mx-auto mb-8">
                <div class="flex bg-white rounded-xl shadow-2xl overflow-hidden">
                    <input type="text" name="search" value="{{ request('search') }}"
                            class="flex-1 px-6 py-4 text-gray-800 focus:outline-none"
                            placeholder="Search articles, topics...">
                    <button type="submit" class="bg-primary-600 text-white px-8 py-4 hover:bg-primary-700 transition font-semibold">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <div class="flex flex-wrap justify-center gap-4 text-sm">
                <span class="bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full">
                    <i class="fas fa-book-open mr-2"></i>{{ \App\Models\Blog::where('status', 1)->count() }}+ Articles
                </span>
                <span class="bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full">
                    <i class="fas fa-users mr-2"></i>{{ \App\Models\User::count() }}+ Authors
                </span>
                <span class="bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full">
                    <i class="fas fa-folder mr-2"></i>{{ \App\Models\BlogCategory::count() }}+ Categories
                </span>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold font-heading text-gray-900 mb-4">
                Explore <span class="text-primary-600">Categories</span>
            </h2>
            <p class="text-gray-600 text-lg">Browse articles by your favorite topics</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($categories as $category)
            <a href="{{ route('home', ['category' => $category->id]) }}"
                class="group bg-gradient-to-br from-gray-50 to-white border-2 border-gray-200 rounded-xl p-6 text-center hover-lift hover:border-primary-500 transition-all">
                <div class="w-14 h-14 bg-primary-100 rounded-xl mx-auto mb-4 flex items-center justify-center group-hover:bg-primary-600 group-hover:scale-110 transition-all duration-300">
                    <i class="fas fa-{{
                        $category->name == 'Technology' ? 'laptop-code' :
                        ($category->name == 'Travel' ? 'plane' :
                        ($category->name == 'Food' ? 'utensils' :
                        ($category->name == 'Lifestyle' ? 'heart' :
                        ($category->name == 'Business' ? 'briefcase' :
                        ($category->name == 'Health' ? 'heartbeat' : 'folder')))))
                    }} text-primary-600 text-xl group-hover:text-white transition-colors"></i>
                </div>
                <h3 class="font-semibold text-gray-800 mb-1">{{ $category->name }}</h3>
                <p class="text-sm text-gray-500">{{ $category->blogs_count ?? 0 }} articles</p>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Latest Blogs Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="flex justify-between items-center mb-12">
            <div>
                <h2 class="text-4xl font-bold font-heading text-gray-900 mb-2">
                    Latest <span class="text-primary-600">Articles</span>
                </h2>
                <p class="text-gray-600">Fresh content from our community</p>
            </div>

            <form action="{{ route('home') }}" method="GET" class="hidden md:block">
                <select name="category" onchange="this.form.submit()"
                        class="border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-primary-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        @if($blogs->isEmpty())
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">No Articles Found</h3>
                <p class="text-gray-600 mb-6">
                    @if(request('search'))
                        No results for "{{ request('search') }}". Try different keywords.
                    @else
                        Check back soon for new content!
                    @endif
                </p>
                @if(request('search') || request('category'))
                    <a href="{{ route('home') }}" class="inline-block bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition">
                        <i class="fas fa-times mr-2"></i>Clear Filters
                    </a>
                @endif
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($blogs as $blog)
                <article class="bg-white rounded-xl shadow-lg overflow-hidden hover-lift group">
                    <div class="relative overflow-hidden h-48">
                        <img src="https://images.unsplash.com/photo-{{
                            ['1637050087981-13fcadf46d8f', '1488590528505-98d2b5aba04b', '1504639725590-34d0984388bd',
                                '1517694712202-14dd9538aa97', '1551288049-bebda4e38f71'][($loop->index % 5)]
                        }}?w=800&q=80"
                            alt="{{ $blog->title }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <div class="absolute top-4 left-4">
                            <span class="bg-primary-600 text-white px-3 py-1 rounded-full text-xs font-semibold">
                                {{ $blog->category->name ?? 'Uncategorized' }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="flex items-center text-sm text-gray-500 mb-3">
                            <i class="fas fa-user-circle mr-2"></i>
                            <span class="font-medium">{{ $blog->author->name ?? 'Unknown Author' }}</span>
                            <span class="mx-2">•</span>
                            <i class="fas fa-calendar mr-2"></i>
                            <span>{{ $blog->created_at->format('M d, Y') }}</span>
                        </div>

                        <h3 class="text-xl font-bold text-gray-800 mb-3 group-hover:text-primary-600 transition line-clamp-2">
                            <a href="{{ route('blog.show', $blog->slug) }}">
                                {{ $blog->title }}
                            </a>
                        </h3>

                        <p class="text-gray-600 mb-4 line-clamp-3">
                            {{ $blog->excerpt }}
                        </p>

                        <a href="{{ route('blog.show', $blog->slug) }}"
                            class="inline-flex items-center text-primary-600 hover:text-primary-700 font-semibold group">
                            Read More
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition"></i>
                        </a>
                    </div>
                </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12 flex justify-center">
                {{ $blogs->links() }}
            </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-primary-600 to-primary-800 text-white">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-4xl font-bold font-heading mb-4">Ready to Start Your Journey?</h2>
        <p class="text-xl mb-8 text-gray-100">Join thousands of developers learning and growing with Wire</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('register') }}" class="bg-white text-primary-600 px-8 py-4 rounded-lg hover:bg-gray-100 transition font-semibold shadow-xl">
                <i class="fas fa-rocket mr-2"></i>Get Started Free
            </a>
            <a href="{{ route('home') }}" class="bg-primary-700 text-white px-8 py-4 rounded-lg hover:bg-primary-900 transition font-semibold border-2 border-white">
                <i class="fas fa-book-open mr-2"></i>Explore Articles
            </a>
        </div>
    </div>
</section>
@endsection
