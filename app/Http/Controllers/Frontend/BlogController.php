<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    public function index(Request $request)
    {

        $query = Blog::with(['category', 'author'])
                    ->where('status', 1)
                    ->whereHas('author')
                    ->whereHas('category');

        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        $blogs = $query->latest()->paginate(9);


        $categories = BlogCategory::withCount(['blogs' => function($query) {
            $query->where('status', 1);
        }])->where('status', 1)->get();

        $featuredBlogs = Blog::with(['category', 'author'])
            ->where('status', 1)
            ->whereHas('author')
            ->whereHas('category')
            ->latest()
            ->limit(3)
            ->get();

        return view('frontend.home', compact('blogs', 'categories', 'featuredBlogs'));
    }

    public function show($slug)
{
    $blog = Blog::where('slug', $slug)
        ->where('status', 1)
        ->whereHas('author')
        ->whereHas('category')
        ->firstOrFail();

    $relatedBlogs = Blog::where('category_id', $blog->category_id)
        ->where('id', '!=', $blog->id)
        ->where('status', 1)
        ->latest()
        ->take(6)
        ->get();

    return view('frontend.blog-details', compact('blog', 'relatedBlogs'));
}

}
