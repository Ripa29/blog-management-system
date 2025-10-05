@extends('admin.layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-layer-group text-blue-600"></i>
            Category Management
        </h1>
        <a href="{{ route('admin.categories.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i>Add New Category
        </a>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form action="{{ route('admin.categories.index') }}" method="GET" class="flex gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Search categories...">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-search mr-2"></i>Search
            </button>
            <a href="{{ route('admin.categories.index') }}" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition">
                <i class="fas fa-refresh mr-2"></i>Reset
            </a>
        </form>
    </div>

    <!-- Categories Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blogs Count</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($categories as $category)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $category->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $category->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <select onchange="updateStatus('{{ route('admin.categories.status', $category) }}', this.value)"
                                    class="text-sm border rounded px-2 py-1 {{ $category->isActive() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <option value="1" {{ $category->isActive() ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !$category->isActive() ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $category->blogs_count ?? $category->blogs->count() }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $category->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 flex items-center gap-2 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </a>
                            <!-- Delete -->
                            <form id="delete-category-{{ $category->id }}" action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                            <button onclick="if(confirm('Are you sure you want to delete this category?')) { document.getElementById('delete-category-{{ $category->id }}').submit(); }"
                                class="text-red-600 hover:text-red-800 flex items-center gap-1">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No categories found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $categories->links() }}
        </div>
    </div>
</div>

<!-- Success/Error Message Toast -->
<div id="toast" class="hidden fixed top-4 right-4 p-4 rounded-lg shadow-lg text-white max-w-sm z-50"></div>
@endsection

@section('scripts')
<script>
function deleteCategory(url) {
    if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
        // Show loading state
        const deleteBtn = event.target;
        const originalText = deleteBtn.innerHTML;
        deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Deleting...';
        deleteBtn.disabled = true;

        // Make AJAX request
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            showToast(data.success || 'Category deleted successfully!', 'success');
            // Reload the page after a short delay
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        })
        .catch(error => {
            console.error('Error:', error);
            showToast(error.message || 'Error deleting category. Please try again.', 'error');
            // Reset button state
            deleteBtn.innerHTML = originalText;
            deleteBtn.disabled = false;
        });
    }
}

function updateStatus(url, status) {
    // Show loading state on the select
    const select = event.target;
    const originalValue = select.value;

    // Make AJAX request
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        showToast(data.success || 'Status updated successfully!', 'success');
        // Update select styles based on status
        if (status === '1') {
            select.className = 'text-sm border rounded px-2 py-1 bg-green-100 text-green-800';
        } else {
            select.className = 'text-sm border rounded px-2 py-1 bg-red-100 text-red-800';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast(error.message || 'Error updating status. Please try again.', 'error');
        // Revert select value
        select.value = originalValue;
    });
}

function showToast(message, type) {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg text-white max-w-sm z-50 ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
    toast.classList.remove('hidden');

    setTimeout(() => {
        toast.classList.add('hidden');
    }, 3000);
}
</script>
@endsection
