<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use App\Models\BlogLike;
use App\Models\BlogComment;
use Illuminate\Support\Str;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::query()->with(['category', 'author', 'likes', 'comments']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $blogs = $query->latest()->paginate(10)->withQueryString();
        $categories = BlogCategory::where('status', 1)->get();

        return view('admin.blogs.index', compact('blogs', 'categories'));
    }

    public function create()
    {
        $categories = BlogCategory::where('status', 1)->get();
        return view('admin.blogs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:blogs,title',
            'category_id' => 'required|exists:blog_categories,id',
            'description' => 'required|string',
            'status' => 'required|in:0,1',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $data = $request->only('title', 'category_id', 'description', 'status');
        $data['author_id'] = auth()->id();
        $data['slug'] = Str::slug($request->title) . '-' . time();

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        Blog::create($data);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog created successfully!');
    }

    public function edit(Blog $blog)
    {
        $categories = BlogCategory::where('status', 1)->get();
        return view('admin.blogs.edit', compact('blog', 'categories'));
    }

    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:blogs,title,' . $blog->id,
            'category_id' => 'required|exists:blog_categories,id',
            'description' => 'required|string',
            'status' => 'required|in:0,1',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $data = $request->only('title', 'category_id', 'description', 'status');

        if ($request->hasFile('thumbnail')) {
            if ($blog->thumbnail && Storage::disk('public')->exists($blog->thumbnail)) {
                Storage::disk('public')->delete($blog->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $blog->update($data);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog updated successfully!');
    }

    public function destroy(Blog $blog)
    {
        if ($blog->thumbnail && Storage::disk('public')->exists($blog->thumbnail)) {
            Storage::disk('public')->delete($blog->thumbnail);
        }

        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog deleted successfully!');
    }

    public function status(Blog $blog)
    {
        $blog->status = !$blog->status;
        $blog->save();

        return response()->json(['message' => 'Blog status updated successfully!']);
    }

    public function show(Blog $blog)
    {
        $blog->load('author', 'category', 'likes', 'comments.author');
        return view('admin.blogs.show', compact('blog'));
    }

    public function toggleLike(Blog $blog, Request $request)
    {
        $userId = $request->user()->id;
        $like = BlogLike::where('blog_id', $blog->id)->where('author_id', $userId)->first();

        if ($like) {
            $like->delete();
            $status = 'unliked';
        } else {
            BlogLike::create(['blog_id' => $blog->id, 'author_id' => $userId]);
            $status = 'liked';
        }

        return response()->json(['status' => $status, 'likes_count' => $blog->likes()->count()]);
    }

    public function addComment(Blog $blog, Request $request)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $comment = BlogComment::create([
            'blog_id' => $blog->id,
            'author_id' => $request->user()->id,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'comment' => $comment->load('author'),
            'comments_count' => $blog->comments()->count(),
        ]);
    }
}
