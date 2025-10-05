@extends('admin.layout')

@section('title', 'Edit Blog')
@section('page-title', 'Edit Blog')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Edit Blog</h1>

    <form id="edit-blog-form" action="{{ route('admin.blogs.update', $blog) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
        @csrf
        @method('PUT')

        <!-- Title -->
        <div class="mb-4">
            <label class="block font-semibold mb-2">Title</label>
            <input type="text" name="title" value="{{ old('title', $blog->title) }}" class="w-full border px-3 py-2 rounded" required>
        </div>

        <!-- Category -->
        <div class="mb-4">
            <label class="block font-semibold mb-2">Category</label>
            <select name="category_id" class="w-full border px-3 py-2 rounded" required>
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $blog->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Description -->
        <div class="mb-4">
            <label class="block font-semibold mb-2">Description</label>
            <textarea name="description" rows="6" class="w-full border px-3 py-2 rounded" required>{{ old('description', $blog->description) }}</textarea>
        </div>

        <!-- Thumbnail -->
        <div class="mb-4">
            <label class="block font-semibold mb-2">Thumbnail</label>
            @if($blog->thumbnail)
            <div class="mb-2">
                <img src="{{ asset('storage/'.$blog->thumbnail) }}" alt="Thumbnail" class="w-32 h-32 object-cover rounded border">
            </div>
            @endif
            <input type="file" name="thumbnail" class="w-full">
        </div>

        <!-- Status -->
        <div class="mb-4">
            <label class="block font-semibold mb-2">Status</label>
            <select name="status" class="w-full border px-3 py-2 rounded">
                <option value="1" {{ $blog->status == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ $blog->status == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Update Blog</button>
    </form>
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

document.getElementById('edit-blog-form').addEventListener('submit', function(e) {
    e.preventDefault();

    let form = this;
    let url = form.action;
    let formData = new FormData(form);

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(res => res.json())
    .then(res => {
        if(res.success) {
            showToast('success', res.message);
            setTimeout(() => window.location.href = "{{ route('admin.blogs.index') }}", 1200);
        } else {
            let errors = res.errors ? Object.values(res.errors).flat().join('<br>') : res.message;
            showToast('error', errors || 'Failed to update blog');
        }
    })
    .catch(err => {
        console.error(err);
        showToast('error', 'Something went wrong!');
    });
});
</script>
@endsection
