<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use Illuminate\Support\Str;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    /**
     * Display a listing of the blogs with search and filter.
     */
    public function index(Request $request)
    {
        $query = Blog::query()->with(['category', 'author']);

        // Search by title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Pagination
        $blogs = $query->latest()->paginate(10)->withQueryString();

        // Categories for filter dropdown
        $categories = BlogCategory::where('status', 1)->get();

        return view('admin.blogs.index', compact('blogs', 'categories'));
    }

    /**
     * Show the form for creating a new blog.
     */
    public function create()
    {
        $categories = BlogCategory::where('status', 1)->get();
        return view('admin.blogs.create', compact('categories'));
    }

    /**
     * Store a newly created blog in storage.
     */
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

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        Blog::create($data);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog created successfully!');
    }

    /**
     * Show the form for editing the specified blog.
     */
    public function edit(Blog $blog)
    {
        $categories = BlogCategory::where('status', 1)->get();
        return view('admin.blogs.edit', compact('blog', 'categories'));
    }

    /**
     * Update the specified blog in storage.
     */
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

        // Replace old thumbnail if new one uploaded
        if ($request->hasFile('thumbnail')) {
            if ($blog->thumbnail && Storage::disk('public')->exists($blog->thumbnail)) {
                Storage::disk('public')->delete($blog->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $blog->update($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Blog updated successfully!'
            ]);
        }

        return redirect()->route('admin.blogs.index')->with('success', 'Blog updated successfully!');
    }

    /**
     * Remove the specified blog from storage.
     */
    public function destroy(Blog $blog)
    {
        if ($blog->thumbnail && Storage::disk('public')->exists($blog->thumbnail)) {
            Storage::disk('public')->delete($blog->thumbnail);
        }

        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog deleted successfully!');
    }

    /**
     * Toggle blog status (Active/Inactive).
     */
    public function status(Blog $blog)
    {
        $blog->status = !$blog->status;
        $blog->save();

        return response()->json(['message' => 'Blog status updated successfully!']);
    }

    /**
     * Display the specified blog (optional for admin preview).
     */
    public function show(Blog $blog)
    {
        return view('admin.blogs.show', compact('blog'));
    }
}
