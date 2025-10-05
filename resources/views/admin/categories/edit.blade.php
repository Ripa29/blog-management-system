@extends('admin.layout')

@section('content')
<div class="container px-4 py-8 mx-auto">
    <div class="max-w-2xl mx-auto">
        <h1 class="mb-6 text-3xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-pen-to-square text-blue-600"></i>
            Edit Category
        </h1>

        <div class="p-6 bg-white rounded-lg shadow-md">
            <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-700">Category Name</label>
                    <input type="text" name="name" id="name"
                            value="{{ old('name', $category->name) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="status" class="block mb-2 text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="1" {{ old('status', $category->status) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status', $category->status) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.categories.index') }}"
                        class="inline-flex items-center gap-2 px-6 py-2 text-white bg-gray-600 rounded-lg hover:bg-gray-700 transition">
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </a>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                        <i class="fa-solid fa-save"></i> Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
