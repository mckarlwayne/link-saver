@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center">
    <div class="text-center">
        <div class="mb-6">
            <i class="bi bi-exclamation-octagon text-6xl text-red-500"></i>
        </div>
        <h1 class="text-4xl font-bold text-gray-900 mb-4">500 - Server Error</h1>
        <p class="text-xl text-gray-600 mb-8">Something went wrong on our end. Please try again later.</p>
        <a href="{{ url('/') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
            <i class="bi bi-house-door mr-2"></i>Go Home
        </a>
    </div>
</div>
@endsection