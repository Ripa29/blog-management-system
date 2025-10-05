<?php

namespace App\Http\Controllers\Admin;

use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{

    public function index(Request $request)
    {
        $query = BlogCategory::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->latest()->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50|unique:blog_categories,name',
            'status' => 'required|boolean',
        ]);

        BlogCategory::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(BlogCategory $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, BlogCategory $category)
    {
        $request->validate([
            'name' => 'required|max:50|unique:blog_categories,name,' . $category->id,
            'status' => 'required|boolean',
        ]);

        $category->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(BlogCategory $category)
    {
        if ($category->blogs()->exists()) {
            return response()->json(['error' => 'Cannot delete category with associated blogs.'], 422);
        }

        $category->delete();

            if(request()->ajax()) {

        return response()->noContent();
    }

    return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully');
    }
    public function updateStatus(Request $request, BlogCategory $category)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $category->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
            'status' => $category->getRawOriginal('status')
        ]);
    }
}
