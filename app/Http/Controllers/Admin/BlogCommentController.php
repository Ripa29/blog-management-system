<?php

namespace App\Http\Controllers\Admin;

use App\Models\BlogComment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogCommentController extends Controller
{
    /**
     * Display a listing of all comments.
     */
    public function index(Request $request)
    {
        $query = BlogComment::with(['author', 'blog'])->latest();

        // Optional search by blog title or commenter name
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->whereHas('blog', fn($q) => $q->where('title', 'like', "%{$search}%"))
                    ->orWhereHas('author', fn($q) => $q->where('name', 'like', "%{$search}%"))
                    ->orWhere('comment', 'like', "%{$search}%");
        }

        $comments = $query->paginate(10);

        return view('admin.comments.index', compact('comments'));
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(BlogComment $comment)
    {
        $comment->delete();

        return redirect()
            ->route('admin.comments.index')
            ->with('success', 'Comment deleted successfully.');
    }
}
