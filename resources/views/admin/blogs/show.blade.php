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
            <img src="{{ $blog->thumbnail_url }}" alt="{{ $blog->title }}" class="w-full h-64 object-cover">

            <div class="p-6">
                <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded">
                    {{ $blog->category?->name ?? 'No Category' }}
                </span>

                <p class="text-sm text-gray-500 mt-1">
                    By {{ $blog->author?->name ?? 'Unknown Author' }} |
                    {{ $blog->created_at?->format('F d, Y') ?? 'Unknown Date' }}
                </p>

                <div class="mt-4 prose max-w-none">
                    {!! $blog->description !!}
                </div>

                <div class="mt-6 text-sm text-gray-600">
                    <p><strong>Slug:</strong> {{ $blog->slug }}</p>
                    <p><strong>Status:</strong>
                        <span class="{{ $blog->isActive() ? 'text-green-600' : 'text-red-600' }}">
                            {{ $blog->status ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                    <p><strong>Last Updated:</strong> {{ $blog->updated_at?->format('F d, Y H:i') ?? 'Unknown' }}</p>
                </div>

                {{-- Like Button --}}
                <div class="mt-4">
                    <button id="likeBtn" class="bg-red-500 text-white px-4 py-2 rounded">
                        <i class="fas fa-heart mr-2"></i>
                        <span id="likeText">{{ auth()->user()->likedBlogs->contains($blog->id) ? 'Unlike' : 'Like'
                            }}</span>
                        (<span id="likesCount">{{ $blog->likes->count() }}</span>)
                    </button>
                </div>

                {{-- Comments Section --}}
                <div class="mt-6">
                    <h2 class="text-xl font-semibold mb-2">Comments ({{ $blog->comments->count() }})</h2>

                    <div id="commentsList" class="space-y-2 mb-4">
                        @foreach ($blog->comments as $comment)
                        <div class="p-2 border rounded flex justify-between items-start"
                            id="comment-{{ $comment->id }}">
                            <div>
                                <p class="font-semibold">{{ $comment->author?->name ?? 'Unknown' }}</p>
                                <p>{{ $comment->comment }}</p>
                                <p class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</p>
                            </div>
                            <button class="text-red-500 deleteCommentBtn" data-id="{{ $comment->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>

                    <textarea id="commentInput" class="w-full border p-2 rounded"
                        placeholder="Add a comment"></textarea>
                    <button id="addCommentBtn" class="mt-2 bg-blue-600 text-white px-4 py-2 rounded">Submit
                        Comment</button>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
            const blogId = {{ $blog->id }};
            const likeBtn = document.getElementById('likeBtn');
            const likeText = document.getElementById('likeText');
            const likesCount = document.getElementById('likesCount');

            likeBtn.addEventListener('click', function() {
                fetch(`{{ route('admin.blogs.like', $blog) }}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                    }).then(res => res.json())
                    .then(data => {
                        likeText.textContent = data.status === 'liked' ? 'Unlike' : 'Like';
                        likesCount.textContent = data.likes_count;
                    });
            });

            const addCommentBtn = document.getElementById('addCommentBtn');
            const commentInput = document.getElementById('commentInput');
            const commentsList = document.getElementById('commentsList');

            addCommentBtn.addEventListener('click', function() {
                const comment = commentInput.value.trim();
                if (!comment) return;

                fetch(`{{ route('admin.blogs.comment', $blog) }}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            comment
                        })
                    }).then(res => res.json())
                    .then(data => {
                        const commentDiv = document.createElement('div');
                        commentDiv.classList.add('p-2', 'border', 'rounded', 'flex', 'justify-between',
                            'items-start');
                        commentDiv.id = 'comment-' + data.comment.id;
                        commentDiv.innerHTML = `
                        <div>
                            <p class="font-semibold">${data.comment.author.name}</p>
                            <p>${data.comment.comment}</p>
                            <p class="text-xs text-gray-400">Just now</p>
                        </div>
                        <button class="text-red-500 deleteCommentBtn" data-id="${data.comment.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                        commentsList.prepend(commentDiv);
                        commentInput.value = '';
                    });
            });

            commentsList.addEventListener('click', function(e) {
                if (e.target.closest('.deleteCommentBtn')) {
                    const btn = e.target.closest('.deleteCommentBtn');
                    const commentId = btn.dataset.id;

                    fetch(`{{ url('admin/blogs/comment') }}/${commentId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).then(res => {
                        if (res.ok) {
                            const commentDiv = document.getElementById('comment-' + commentId);
                            commentDiv.remove();
                        }
                    });
                }
            });

        });
</script>
@endsection
