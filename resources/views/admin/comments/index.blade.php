@extends('admin.layout')

@section('title', 'Comments')
@section('page-title', 'Manage Comments')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Comments</h1>

        <form method="GET" action="{{ route('admin.comments.index') }}" class="flex items-center space-x-2">
            <input type="text" name="search" value="{{ request('search') }}"
                class="border border-gray-300 rounded px-3 py-1"
                placeholder="Search comments...">
            <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Search</button>
        </form>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">Author</th>
                    <th class="px-4 py-2 text-left">Comment</th>
                    <th class="px-4 py-2 text-left">Blog</th>
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2 text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($comments as $comment)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $comment->author->name ?? 'Anonymous' }}</td>
                        <td class="px-4 py-2">{{ Str::limit($comment->comment, 60) }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('blog.show', $comment->blog->slug) }}" target="_blank" class="text-blue-600 hover:underline">
                                {{ $comment->blog->title }}
                            </a>
                        </td>
                        <td class="px-4 py-2">{{ $comment->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-2 text-center">
                            <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Delete this comment?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">No comments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $comments->links() }}
    </div>
</div>
@endsection
