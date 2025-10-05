@extends('admin.layout')

@section('content')
<div class="container px-4 py-8 mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-users text-blue-600"></i> User Management
        </h1>
        <a href="{{ route('admin.users.create') }}" class="px-4 py-2 text-white transition bg-blue-600 rounded-lg hover:bg-blue-700">
            <i class="mr-2 fas fa-plus"></i>Add New User
        </a>
    </div>

    <!-- Search and Filter -->
    <div class="p-6 mb-6 bg-white rounded-lg shadow-md">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Search users...">
            </div>
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Status</option>
                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="px-6 py-2 text-white transition bg-blue-600 rounded-lg hover:bg-blue-700">
                <i class="mr-2 fas fa-search"></i>Filter
            </button>
            <a href="{{ route('admin.users.index') }}" class="px-6 py-2 text-white transition bg-gray-600 rounded-lg hover:bg-gray-700">
                <i class="mr-2 fas fa-refresh"></i>Reset
            </a>
        </form>
    </div>

    <!-- Users Table -->
    <div class="overflow-hidden bg-white rounded-lg shadow-md">
        <div class="overflow-x-auto">
            <table class="min-w-full data-table">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Blogs Count</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Created At</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $user->id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <select onchange="updateStatus('{{ route('admin.users.status', $user) }}', this.value)"
                                    class="text-sm border rounded px-2 py-1 {{ $user->isActive() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}"
                                    {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                <option value="1" {{ $user->isActive() ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !$user->isActive() ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @if($user->id === auth()->id())
                                <span class="text-xs text-gray-500">(You)</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $user->blogs->count() }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="flex items-center gap-2px-6 py-4 space-x-2 text-sm font-medium whitespace-nowrap">
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-900">
                                <i class="mr-1 fas fa-edit"></i>Edit
                            </a>
                            @if($user->id !== auth()->id())
                            <!-- Delete -->
                            <form id="delete-user-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                            <button onclick="if(confirm('Are you sure you want to delete this user?')) { document.getElementById('delete-user-{{ $user->id }}').submit(); }"
                                class="text-red-600 hover:text-red-800 flex items-center gap-1">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-sm text-center text-gray-500">No users found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>

function ajaxDelete(url) {
    if (confirm('Are you sure you want to delete this user?')) {
        $.ajax({
            url: url,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                alert(res.success || 'Deleted successfully!');
                location.reload();
            },
            error: function(xhr) {
                alert(xhr.responseJSON.error || 'Error occurred while deleting');
            }
        });
    }
}

</script>
@endsection
