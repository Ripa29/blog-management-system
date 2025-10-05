@extends('admin.layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Blog Details</h1>
            <div class="space-x-2">
                <a href="{{ route('admin.blogs.edit', $blog) }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-edit mr-2"></i>Edit Blog
                </a>
                <a href="{{ route('admin.blogs.index') }}"
                   class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            {{-- Thumbnail --}}
            <img src="{{ $blog->thumbnail_url ?? asset('images/default-thumbnail.png') }}"
                 alt="{{ $blog->title }}"
                 class="w-full h-64 object-cover">

            <div class="p-6">
                {{-- Category --}}
                <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded">
                    {{ $blog->category?->name ?? 'No Category' }}
                </span>

                {{-- Author and Date --}}
                <p class="text-sm text-gray-500 mt-1">
                    By {{ $blog->author?->name ?? 'Unknown Author' }} |
                    {{ $blog->created_at?->format('F d, Y') ?? 'Unknown Date' }}
                </p>

                {{-- Blog Description --}}
                <div class="mt-4 prose max-w-none">
                    {!! $blog->description !!}
                </div>

                {{-- Blog Info --}}
                <div class="mt-6 text-sm text-gray-600">
                    <p><strong>Slug:</strong> {{ $blog->slug }}</p>
                    <p><strong>Status:</strong>
                        <span class="{{ $blog->isActive() ? 'text-green-600' : 'text-red-600' }}">
                            {{ $blog->status }}
                        </span>
                    </p>
                    <p><strong>Last Updated:</strong> {{ $blog->updated_at?->format('F d, Y H:i') ?? 'Unknown' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
