@extends('admin.layout')

@section('title', 'Blog Management')
@section('page-title', 'Blog Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-blog text-blue-600"></i>
            Blog Management
        </h1>
        <a href="{{ route('admin.blogs.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center gap-1">
            <i class="fas fa-plus"></i> Add New Blog
        </a>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form action="{{ route('admin.blogs.index') }}" method="GET" class="flex gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Search blogs...">
            </div>
            <select name="category" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center gap-1">
                <i class="fas fa-filter"></i> Filter
            </button>
            <a href="{{ route('admin.blogs.index') }}" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition flex items-center gap-1">
                <i class="fas fa-sync-alt"></i> Reset
            </a>
        </form>
    </div>

    <!-- Blogs Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full data-table">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thumbnail</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($blogs as $blog)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $blog->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <img src="{{ $blog->thumbnail_url }}" alt="{{ $blog->title }}" class="w-16 h-12 object-cover rounded">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <a href="{{ route('admin.blogs.show', $blog) }}" class="hover:text-blue-600">
                                {{ Str::limit($blog->title, 50) }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $blog->category->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $blog->author->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <select onchange="updateStatus('{{ route('admin.blogs.status', $blog) }}', this.value)"
                                    class="text-sm border rounded px-2 py-1 {{ $blog->isActive() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <option value="1" {{ $blog->isActive() ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !$blog->isActive() ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $blog->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 flex gap-2 items-center whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('admin.blogs.show', $blog) }}" class="text-green-600 hover:text-green-900 flex items-center gap-1">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('admin.blogs.edit', $blog) }}" class="text-blue-600 hover:text-blue-900 flex items-center gap-1">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <!-- Delete -->
                            <form id="delete-blog-{{ $blog->id }}" action="{{ route('admin.blogs.destroy', $blog) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                            <button onclick="if(confirm('Are you sure you want to delete this blog?')) { document.getElementById('delete-blog-{{ $blog->id }}').submit(); }"
                                class="text-red-600 hover:text-red-800 flex items-center gap-1">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">No blogs found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $blogs->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function showToast(type, message) {
    const existing = document.querySelector('.toast');
    if(existing) existing.remove();

    const toast = document.createElement('div');
    toast.classList.add('toast', type === 'success' ? 'toast-success' : 'toast-error');
    toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i> ${message}`;
    document.body.appendChild(toast);

    setTimeout(() => toast.remove(), 3000);
}

function deleteBlog(url) {
    if (confirm('Are you sure you want to delete this blog?')) {
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        })
        .then(res => res.json())
        .then(data => {
            showToast('success', data.success || data.message);
            setTimeout(() => location.reload(), 1000);
        })
        .catch(err => {
            console.error(err);
            showToast('error', 'Error deleting blog');
        });
    }
}

function updateStatus(url, value) {
    fetch(url, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ status: value })
    })
    .then(res => res.json())
    .then(data => {
        showToast('success', data.message);
        setTimeout(() => location.reload(), 1000);
    })
    .catch(err => {
        console.error(err);
        showToast('error', 'Error updating status');
    });
}
</script>
@endsection
