<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Blog;
use App\Models\BlogComment;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    /**
     * Display all published blogs
     */

    public function index(Request $request)
    {
        $query = Blog::with(['category', 'author'])
            ->where('status', 1)
            ->whereHas('author')
            ->whereHas('category');

        // Search by title
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        $blogs = $query->latest()->paginate(9);

        $categories = BlogCategory::withCount(['blogs' => function ($query) {
            $query->where('status', 1);
        }])
            ->where('status', 1)
            ->get();

        $featuredBlogs = Blog::with(['category', 'author'])
            ->where('status', 1)
            ->whereHas('author')
            ->whereHas('category')
            ->latest()
            ->limit(3)
            ->get();

        return view('frontend.home', compact('blogs', 'categories', 'featuredBlogs'));
    }

    /**
     * Show a single blog with related blogs
     */
    public function show($slug)
    {
        $blog = Blog::with(['category', 'author', 'likes', 'comments.author'])
            ->where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        $relatedBlogs = Blog::where('category_id', $blog->category_id)
            ->where('id', '!=', $blog->id)
            ->where('status', 1)
            ->latest()
            ->take(6)
            ->get();

        return view('frontend.blog-details', compact('blog', 'relatedBlogs'));
    }

    /**
     * Toggle Like/Unlike
     */
    public function toggleLike(Blog $blog)
    {
        $user = Auth::user();

        if ($user->likedBlogs()->where('blog_id', $blog->id)->exists()) {
            $user->likedBlogs()->detach($blog->id);
            $status = 'unliked';
        } else {
            $user->likedBlogs()->attach($blog->id);
            $status = 'liked';
        }

        return response()->json([
            'status' => $status,
            'likes_count' => $blog->likes()->count(),
        ]);
    }

    /**
     * Store a new comment
     */
    public function storeComment(Request $request, Blog $blog)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        $comment = $blog->comments()->create([
            'author_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        return response()->json([
            'comment' => [
                'user' => Auth::user(),
                'comment' => $comment->comment,
            ],
        ]);
    }

    /**
     * Update existing comment
     */
    public function updateComment(Request $request, BlogComment $comment)
    {
        $request->validate(['comment' => 'required|string|max:500']);

        if ($comment->author_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment->update(['comment' => $request->comment]);

        return response()->json(['success' => true]);
    }
}
