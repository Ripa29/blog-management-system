<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use App\Models\User;
use App\Models\BlogCategory;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBlogs = Blog::count();
        $totalCategories = BlogCategory::count();
        $totalUsers = User::count();

        // Use 'author' relationship instead of 'operator'
        $recentBlogs = Blog::with(['category', 'author'])->latest()->take(10)->get();

        return view('admin.dashboard', compact('totalBlogs', 'totalCategories', 'totalUsers', 'recentBlogs'));
    }
}
