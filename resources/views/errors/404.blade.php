@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center">
    <div class="text-center">
        <div class="mb-6">
            <i class="bi bi-exclamation-triangle text-6xl text-yellow-500"></i>
        </div>
        <h1 class="text-4xl font-bold text-gray-900 mb-4">404 - Page Not Found</h1>
        <p class="text-xl text-gray-600 mb-8">Sorry, the page you are looking for does not exist.</p>
        <a href="{{ url('/') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
            <i class="bi bi-house-door mr-2"></i>Go Home
        </a>
    </div>
</div>
@endsection