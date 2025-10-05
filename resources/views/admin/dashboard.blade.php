@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="space-y-8">

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Blogs -->
        <div class="bg-white shadow-md rounded-xl p-6 flex items-center border-l-4 border-blue-500 transform hover:scale-105 transition">
            <div class="p-4 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 shadow-lg flex items-center justify-center">
                <i class="fas fa-newspaper text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 font-medium">Total Blogs</p>
                <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Blog::count() }}</p>
            </div>
        </div>

        <!-- Active Blogs -->
        <div class="bg-white shadow-md rounded-xl p-6 flex items-center border-l-4 border-green-500 transform hover:scale-105 transition">
            <div class="p-4 rounded-lg bg-gradient-to-br from-green-500 to-green-600 shadow-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 font-medium">Active Blogs</p>
                <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Blog::where('status', 1)->count() }}</p>
            </div>
        </div>

        <!-- Categories -->
        <div class="bg-white shadow-md rounded-xl p-6 flex items-center border-l-4 border-purple-500 transform hover:scale-105 transition">
            <div class="p-4 rounded-lg bg-gradient-to-br from-purple-500 to-purple-600 shadow-lg flex items-center justify-center">
                <i class="fas fa-folder text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 font-medium">Categories</p>
                <p class="text-3xl font-bold text-gray-900">{{ \App\Models\BlogCategory::count() }}</p>
            </div>
        </div>

        <!-- Users -->
        <div class="bg-white shadow-md rounded-xl p-6 flex items-center border-l-4 border-orange-500 transform hover:scale-105 transition">
            <div class="p-4 rounded-lg bg-gradient-to-br from-orange-500 to-orange-600 shadow-lg flex items-center justify-center">
                <i class="fas fa-users text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 font-medium">Total Users</p>
                <p class="text-3xl font-bold text-gray-900">{{ \App\Models\User::count() }}</p>
            </div>
        </div>
    </div>

    <!-- Quick Action Buttons -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('admin.blogs.create') }}" class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl shadow-lg p-6 hover:shadow-xl transform
        hover:scale-105 transition flex flex-col items-center justify-center">
            <i class="fas fa-plus-circle text-3xl mb-2"></i>
            <h3 class="text-lg font-bold mb-1">Create New Blog</h3>
            <p class="text-blue-100 text-sm text-center">Start writing a new article</p>
        </a>

        <a href="{{ route('admin.categories.create') }}" class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl
        shadow-lg p-6 hover:shadow-xl transform hover:scale-105 transition flex flex-col items-center justify-center">
            <i class="fas fa-folder-plus text-3xl mb-2"></i>
            <h3 class="text-lg font-bold mb-1">Add Category</h3>
            <p class="text-purple-100 text-sm text-center">Create a new blog category</p>
        </a>

        <a href="{{ route('admin.users.create') }}" class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl shadow-lg p-6 hover:shadow-xl transform hover:scale-105 transition flex flex-col items-center justify-center">
            <i class="fas fa-user-plus text-3xl mb-2"></i>
            <h3 class="text-lg font-bold mb-1">Add User</h3>
            <p class="text-green-100 text-sm text-center">Invite a new team member</p>
        </a>
    </div>

    <!-- Recent Blogs Table with Filter & Search -->

<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h3 class="text-xl font-bold text-gray-900 flex-1">Recent Blogs</h3>

        <!-- Search + Filter + Reset Buttons -->
        <div class="flex flex-col sm:flex-row sm:items-center gap-2 flex-1 sm:justify-end">
            <input type="text" id="searchBlog" placeholder="Search blogs..."
                class="border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-auto" />

            <select id="statusFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>

            <button id="resetFilter" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-2 rounded-lg transition">
                Reset
            </button>
        </div>
    </div>

    <div class="p-6 overflow-x-auto">
        <table id="recentBlogsTable" class="min-w-full text-left">
            <thead class="bg-gray-100">
                <tr class="text-gray-600 text-sm uppercase font-medium">
                    <th class="py-3 px-4">Title</th>
                    <th class="py-3 px-4">Category</th>
                    <th class="py-3 px-4">Author</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4">Created</th>
                    <th class="py-3 px-4">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach(\App\Models\Blog::with(['category','author'])->latest()->take(50)->get() as $blog)
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-3 px-4">{{ Str::limit($blog->title, 40) }}</td>
                    <td class="py-3 px-4">{{ $blog->category?->name ?? 'N/A' }}</td>
                    <td class="py-3 px-4">{{ $blog->author?->name ?? 'N/A' }}</td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $blog->status == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $blog->status == 1 ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="py-3 px-4">{{ $blog->created_at->format('M d, Y') }}</td>
                    <td class="py-3 px-4 flex items-center gap-2">
                        <a href="{{ route('admin.blogs.show', $blog) }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
                            <i class="fas fa-eye"></i> Show
                        </a>
                        <a href="{{ route('admin.blogs.edit', $blog) }}" class="flex items-center gap-1 text-green-600 hover:text-green-800">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form id="delete-blog-{{ $blog->id }}" action="{{ route('admin.blogs.destroy', $blog) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="if(confirm('Are you sure?')) { this.closest('form').submit(); }"
                                class="text-red-600 hover:text-red-800 flex items-center gap-1">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>


</div>

@push('scripts')
<script>
    $(document).ready(function() {
        var table = $('#recentBlogsTable').DataTable({
            order: [[4, 'desc']],
            pageLength: 10,
            lengthChange: false,
            searching: false,
            language: { search: "_INPUT_", searchPlaceholder: "Search blogs..." }
        });

        // Status filter
        $('#statusFilter').on('change', function () {
            var status = $(this).val();
            if(status === "") {
                table.column(3).search('').draw();
            } else {
                var text = status == "1" ? "Active" : "Inactive";
                table.column(3).search(text).draw();
            }
        });

        // Reset button
        $('#resetFilter').on('click', function() {
            $('#statusFilter').val('');
            table.search('').columns().search('').draw();
        });
    });
</script>
@endpush
@endsection
