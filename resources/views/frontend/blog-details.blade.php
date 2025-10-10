@extends('frontend.layout')

@section('title', $blog->title)

@section('content')
<div class="container mx-auto px-4 py-10">

    <!-- Blog Details Card -->
    <div class="max-w-4xl mx-auto bg-white shadow rounded-lg overflow-hidden">
        @if ($blog->thumbnail)
            <img src="{{ $blog->thumbnail_url }}" alt="{{ $blog->title }}" class="w-full h-96 object-cover">
        @endif

        <div class="p-6">
            <h1 class="text-3xl font-bold mb-3">{{ $blog->title }}</h1>
            <p class="text-gray-500 text-sm mb-4">
                By <span class="font-semibold">{{ $blog->author->name ?? 'Unknown Author' }}</span> •
                {{ $blog->created_at->format('M d, Y') }} •
                Category: <span class="font-semibold">{{ $blog->category->name ?? 'Uncategorized' }}</span>
            </p>

            <div class="prose max-w-none text-gray-800 mb-4">
                {!! $blog->description !!}
            </div>

            <!-- Like + Comment + Share -->
            <div class="flex items-center gap-6 mt-4">
                @auth
                    @php
                        $user = auth()->user();
                        $isLiked = $user->likedBlogs->contains($blog->id);
                    @endphp
                    <button id="likeBtn" data-blog-id="{{ $blog->id }}" class="flex items-center gap-2 text-red-500 hover:text-red-600 transition">
                        <i id="likeIcon" class="fas {{ $isLiked ? 'fa-heart' : 'fa-heart text-gray-400' }}"></i>
                        <span id="likesCount">{{ $blog->likes->count() }}</span>
                    </button>
                @else
                    <a href="{{ route('login') }}" class="text-red-500 hover:text-red-600"><i class="far fa-heart"></i> {{ $blog->likes->count() }}</a>
                @endauth

                <div class="text-gray-600">
                    <i class="far fa-comment"></i> 
                    <span id="commentsCountDisplay">{{ $blog->comments->count() }}</span>
                </div>

                <div class="flex items-center gap-2 text-gray-600">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" class="hover:text-blue-600"><i class="fab fa-facebook"></i></a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}" target="_blank" class="hover:text-sky-500"><i class="fab fa-twitter"></i></a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->fullUrl()) }}" target="_blank" class="hover:text-blue-700"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="mt-10">
                <h2 class="text-xl font-semibold mb-4">Comments (<span id="commentsCountDisplay">{{ $blog->comments->count() }}</span>)</h2>

                <!-- Comment List -->
                <div id="commentsList" class="space-y-4 mb-6">
                    @foreach ($blog->comments as $comment)
                        <div class="border p-3 rounded bg-gray-50" data-id="{{ $comment->id }}">
                            <p class="text-gray-800"><strong>{{ $comment->author->name ?? 'Anonymous' }}:</strong> <span class="comment-text">{{ $comment->comment }}</span></p>
                            <p class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                            @if (auth()->id() === $comment->author_id)
                                <button class="editComment text-blue-600 text-sm mt-1">Edit</button>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Add Comment -->
                @auth
                    <form id="commentForm">
                        @csrf
                        <textarea id="commentInput" rows="3" class="w-full border rounded p-2 focus:ring" placeholder="Write a comment..."></textarea>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded mt-2 hover:bg-indigo-700">
                            Post Comment
                        </button>
                    </form>
                @else
                    <p class="text-gray-600 mt-3">Please <a href="{{ route('login') }}" class="text-blue-600">login</a> to comment.</p>
                @endauth
            </div>
        </div>
    </div>

    <!-- Related Blogs -->
    @if ($relatedBlogs->count())
        <div class="mt-12 max-w-4xl mx-auto">
            <h2 class="text-2xl font-bold mb-4">Related Blogs</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($relatedBlogs as $related)
                    <div class="bg-white shadow rounded-lg overflow-hidden hover:shadow-lg transition">
                        @if ($related->thumbnail)
                            <img src="{{ asset('storage/' . $related->thumbnail) }}" alt="{{ $related->title }}" class="w-full h-40 object-cover">
                        @endif
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-2">
                                <a href="{{ route('blog.show', $related->slug) }}" class="hover:text-indigo-600">{{ $related->title }}</a>
                            </h3>
                            <p class="text-gray-500 text-sm mb-2">{{ Str::limit(strip_tags($related->description), 100) }}</p>
                            <div class="flex items-center justify-between text-gray-500 text-sm">
                                <span><i class="far fa-heart"></i> {{ $related->likes->count() }}</span>
                                <span><i class="far fa-comment"></i> {{ $related->comments->count() }}</span>
                                <a href="{{ route('blog.show', $related->slug) }}" class="text-indigo-600 hover:underline flex items-center gap-1">
                                    Read More <i class="fas fa-arrow-right text-sm"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const likeBtn = document.getElementById('likeBtn');
    const likeIcon = document.getElementById('likeIcon');
    const likesCount = document.getElementById('likesCount');
    const commentsList = document.getElementById('commentsList');
    const commentForm = document.getElementById('commentForm');
    const commentsCountDisplay = document.getElementById('commentsCountDisplay');

    // Like / Unlike toggle
    if (likeBtn) {
        likeBtn.addEventListener('click', async () => {
            const blogId = likeBtn.dataset.blogId;
            const response = await fetch(`/blogs/${blogId}/toggle-like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            likesCount.textContent = data.likes_count;
            likeIcon.classList.toggle('text-gray-400');
            likeIcon.classList.toggle('fa-heart');
        });
    }

    // Comment post
    if (commentForm) {
        commentForm.addEventListener('submit', async e => {
            e.preventDefault();
            const comment = document.getElementById('commentInput').value.trim();
            if (!comment) return alert('Please write a comment.');
            const response = await fetch(`/blogs/{{ $blog->id }}/comment`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ comment })
            });
            const data = await response.json();
            if (data.comment) {
                const div = document.createElement('div');
                div.classList.add('border', 'p-3', 'rounded', 'bg-gray-50');
                div.innerHTML = `<p><strong>${data.comment.user.name}:</strong> ${data.comment.comment}</p><p class="text-xs text-gray-500">Just now</p>`;
                commentsList.prepend(div);
                document.getElementById('commentInput').value = '';
                commentsCountDisplay.textContent = parseInt(commentsCountDisplay.textContent) + 1;
            }
        });
    }

    // Comment edit
    document.addEventListener('click', async e => {
        if (e.target.classList.contains('editComment')) {
            const div = e.target.closest('[data-id]');
            const id = div.dataset.id;
            const textEl = div.querySelector('.comment-text');
            const oldText = textEl.textContent;
            const newText = prompt('Edit your comment:', oldText);
            if (newText && newText !== oldText) {
                const response = await fetch(`/blogs/comment/${id}/edit`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ comment: newText })
                });
                const data = await response.json();
                if (data.success) textEl.textContent = newText;
            }
        }
    });
});
</script>
@endpush
