@extends('admin.layout')
@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4">Likes for: {{ $blog->title }}</h1>
    <table class="w-full table-auto bg-white shadow rounded">
        <thead>
            <tr class="bg-gray-200">
                <th class="px-4 py-2">#</th>
                <th class="px-4 py-2">User Name</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Liked At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($likes as $index => $like)
                <tr class="text-center border-b">
                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                    <td class="px-4 py-2">{{ $like->user->name }}</td>
                    <td class="px-4 py-2">{{ $like->user->email }}</td>
                    <td class="px-4 py-2">{{ $like->created_at->format('F d, Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
