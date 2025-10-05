@extends('frontend.layout')

@section('content')
<div class="min-h-screen bg-white flex items-center justify-center py-12">
    <div class="container mx-auto px-6 text-center">
        <!-- Main Content -->
        <div class="max-w-2xl mx-auto">
            <!-- Icon -->
            <div class="w-32 h-32 bg-gradient-to-br from-primary-500 to-accent-400 rounded-3xl mx-auto mb-8 flex items-center justify-center shadow-xl">
                <i class="fas fa-bolt text-white text-5xl"></i>
            </div>

            <!-- Error Text -->
            <h1 class="text-8xl font-bold text-primary-600 mb-4 font-mono">404</h1>

            <!-- Message -->
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Page Not Found</h2>
            <p class="text-gray-600 mb-8 text-lg leading-relaxed">
                The page you're looking for doesn't exist or has been moved.
            </p>

            <!-- Action Button -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('home') }}"
                    class="bg-primary-600 text-white px-8 py-3 rounded-xl hover:bg-primary-700 transition font-semibold flex items-center justify-center">
                    <i class="fas fa-home mr-2"></i>
                    Back to Home
                </a>

                <button onclick="history.back()"
                        class="border border-gray-300 text-gray-700 px-8 py-3 rounded-xl hover:bg-gray-50 transition font-semibold flex items-center justify-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Go Back
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
