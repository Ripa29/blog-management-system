@extends('frontend.layout')

@section('content')
<div class="min-h-screen bg-gray-50 pt-20">
    <div class="container mx-auto px-6">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4 text-sm">
                <li>
                    <a href="{{ route('home') }}" class="text-gray-500 hover:text-primary-600 transition font-medium">
                        <i class="fas fa-home"></i>
                    </a>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                    <a href="{{ route('home', ['category' => $blog->category->id]) }}"
                        class="ml-4 text-gray-500 hover:text-primary-600 transition font-medium">
                        {{ $blog->category->name }}
                    </a>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                    <span class="ml-4 text-gray-900 font-medium">{{ Str::limit($blog->title, 50) }}</span>
                </li>
            </ol>
        </nav>

        <div class="max-w-4xl mx-auto">
            <!-- Blog Content -->
            <article class="bg-white rounded-3xl shadow-xl overflow-hidden mb-12">
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1581276879432-15e50529f34b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80"
                            alt="{{ $blog->title }}"
                            class="w-full h-96 object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    <div class="absolute bottom-8 left-8 right-8">
                        <span class="bg-accent-500 text-white px-4 py-2 rounded-full text-sm font-medium mb-4 inline-block">
                            {{ $blog->category->name }}
                        </span>
                        <h1 class="text-4xl md:text-5xl font-bold text-white mb-6 leading-tight">
                            {{ $blog->title }}
                        </h1>
                        <div class="flex items-center text-white/90">
                            <img class="w-12 h-12 rounded-full mr-4 border-2 border-white"
                                    src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80"
                                    alt="{{ $blog->author->name }}">
                            <div>
                                <div class="font-semibold text-lg">{{ $blog->author->name }}</div>
                                <div class="text-sm flex items-center">
                                    <i class="far fa-clock mr-2"></i>
                                    {{ $blog->created_at->format('F d, Y') }} • 8 min read
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-8 md:p-12">
                    <div class="prose prose-lg max-w-none prose-headings:text-gray-900 prose-p:text-gray-700 prose-li:text-gray-700">
                        {!! $blog->description !!}
                    </div>

                    <!-- Tags -->
                    <div class="mt-12 pt-8 border-t border-gray-200">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Topics</h4>
                        <div class="flex flex-wrap gap-3">
                            <span class="bg-primary-100 text-primary-800 px-4 py-2 rounded-full text-sm font-medium">
                                #{{ Str::slug($blog->category->name) }}
                            </span>
                            <span class="bg-primary-100 text-primary-800 px-4 py-2 rounded-full text-sm font-medium">
                                #innovation
                            </span>
                            <span class="bg-primary-100 text-primary-800 px-4 py-2 rounded-full text-sm font-medium">
                                #trending
                            </span>
                        </div>
                    </div>

                    <!-- Share Buttons -->
                    <div class="mt-8 flex items-center space-x-4">
                        <span class="text-gray-700 font-medium">Share this article:</span>
                        <div class="flex space-x-3">
                            <button class="w-10 h-10 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition flex items-center justify-center shadow-lg hover-lift">
                                <i class="fab fa-twitter"></i>
                            </button>
                            <button class="w-10 h-10 bg-blue-800 text-white rounded-full hover:bg-blue-900 transition flex items-center justify-center shadow-lg hover-lift">
                                <i class="fab fa-linkedin-in"></i>
                            </button>
                            <button class="w-10 h-10 bg-red-500 text-white rounded-full hover:bg-red-600 transition flex items-center justify-center shadow-lg hover-lift">
                                <i class="fab fa-pinterest"></i>
                            </button>
                            <button class="w-10 h-10 bg-gray-800 text-white rounded-full hover:bg-gray-900 transition flex items-center justify-center shadow-lg hover-lift">
                                <i class="fas fa-link"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Author Bio -->
            <div class="bg-white rounded-3xl shadow-xl p-8 mb-12">
                <div class="flex items-start">
                    <img class="w-20 h-20 rounded-2xl mr-6"
                            src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80"
                            alt="{{ $blog->author->name }}">
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $blog->author->name }}</h3>
                        <p class="text-gray-600 mb-4 leading-relaxed">
                            Technology evangelist and innovation strategist. Passionate about emerging technologies, digital transformation, and helping businesses leverage cutting-edge solutions for growth and competitive advantage.
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-500 hover:text-primary-600 transition">
                                <i class="fab fa-twitter text-xl"></i>
                            </a>
                            <a href="#" class="text-gray-500 hover:text-primary-600 transition">
                                <i class="fab fa-linkedin text-xl"></i>
                            </a>
                            <a href="#" class="text-gray-500 hover:text-primary-600 transition">
                                <i class="fab fa-github text-xl"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Blogs -->
            @if($relatedBlogs->count() > 0)
            <section class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Related Articles</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($relatedBlogs as $relatedBlog)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover-lift group">
                        <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80"
                                alt="{{ $relatedBlog->title }}"
                                class="w-full h-40 object-cover group-hover:scale-110 transition duration-700">
                        <div class="p-4">
                            <span class="text-primary-600 text-xs font-semibold">{{ $relatedBlog->category->name }}</span>
                            <h3 class="text-lg font-bold mt-1 mb-2 line-clamp-2">
                                <a href="{{ route('blog.show', $relatedBlog->slug) }}" class="hover:text-primary-600 transition">
                                    {{ $relatedBlog->title }}
                                </a>
                            </h3>
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span>By {{ $relatedBlog->author->name }}</span>
                                <span>{{ $relatedBlog->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif

            <!-- Back to Home -->
            <div class="text-center pb-3">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center bg-primary-600 text-white px-8 py-4 rounded-2xl hover:bg-primary-700 transition font-semibold shadow-lg hover-lift">
                    <i class="fas fa-arrow-left mr-3"></i>
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
