@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Header -->
    <header class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-4">Link-Saver</h1>
                <p class="text-xl md:text-2xl mb-8 max-w-2xl mx-auto">Organize and manage your favorite links in one beautiful place.</p>
                <button class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors shadow-lg" onclick="openModal('addLinkModal')">
                    <i class="bi bi-plus-circle mr-2"></i>Add New Link
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Links Grid -->
            <div class="lg:col-span-3">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Your Saved Links</h2>
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">{{ $links->count() }} links</span>
                </div>

                <!-- Search Form -->
                <form method="GET" class="mb-6">
                    <div class="flex">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search links by title or URL..." class="flex-1 px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-r-md hover:bg-blue-700 transition-colors">
                            <i class="bi bi-search"></i>
                        </button>
                        @if(request('search'))
                            <a href="{{ route('links.index') }}" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition-colors">
                                <i class="bi bi-x"></i>
                            </a>
                        @endif
                    </div>
                </form>

                @if($links->count())
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($links as $link)
                            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300 overflow-hidden border border-transparent hover:border-blue-200">
                                <div class="p-6">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="space-y-1">
                                            <h5 class="text-lg font-semibold text-gray-900 mb-1 truncate" title="{{ $link->title }}">
                                                {{ $link->title }}
                                            </h5>
                                            <p class="text-xs text-gray-500 truncate" title="{{ $link->url }}">
                                                {{ parse_url($link->url, PHP_URL_HOST) ?? $link->url }} • {{ $link->created_at->diffForHumans() }} • {{ $link->visits }} visits
                                            </p>
                                        </div>
                                        @php
                                            $statusText = 'Live';
                                            $statusClass = 'text-green-600 bg-green-100';

                                            if ($link->visits === 0) {
                                                $statusText = 'Fresh';
                                                $statusClass = 'text-blue-600 bg-blue-100';
                                            }

                                            if ($link->created_at->lt(now()->subDays(14)) && $link->visits === 0) {
                                                $statusText = 'Dormant';
                                                $statusClass = 'text-gray-600 bg-gray-100';
                                            }

                                            if ($link->visits > 50) {
                                                $statusText = 'Trending';
                                                $statusClass = 'text-purple-600 bg-purple-100';
                                            }
                                        @endphp

                                        <span class="text-xs font-semibold {{ $statusClass }} px-2 py-1 rounded-full">{{ $statusText }}</span>
                                    </div>

                                    <p class="text-gray-600 text-sm mb-4 break-all">
                                        <a href="{{ $link->url }}" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center truncate">
                                            {{ Str::limit($link->url, 60) }}
                                            <i class="bi bi-box-arrow-up-right ml-1"></i>
                                        </a>
                                    </p>

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('links.visit', $link->id) }}" target="_blank" class="bg-blue-100 text-blue-700 px-3 py-1 rounded text-xs font-medium hover:bg-blue-200 transition-colors">
                                                <i class="bi bi-arrow-up-right-circle mr-1"></i>Open
                                            </a>
                                            <button type="button" class="bg-gray-100 text-gray-700 px-3 py-1 rounded text-xs font-medium hover:bg-gray-200 transition-colors" onclick="copyLink('{{ addslashes($link->url) }}', this)">
                                                <i class="bi bi-clipboard mr-1"></i>Copy
                                            </button>
                                        </div>

                                        <div class="flex items-center space-x-2">
                                            <button class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition-colors" onclick="editLink({{ $link->id }}, '{{ addslashes($link->title) }}', '{{ addslashes($link->url) }}')">
                                                <i class="bi bi-pencil"></i> Edit
                                            </button>
                                            <button class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 transition-colors" onclick="deleteLink({{ $link->id }})">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-16">
                        <div class="mb-6">
                            <i class="bi bi-link-45deg text-6xl text-gray-400"></i>
                        </div>
                        <h3 class="text-2xl font-semibold text-gray-900 mb-2">No links saved yet</h3>
                        <p class="text-gray-600 mb-6 max-w-md mx-auto">Start building your collection by adding your first link.</p>
                        <button class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors" onclick="openModal('addLinkModal')">
                            <i class="bi bi-plus-circle mr-2"></i>Add Your First Link
                        </button>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h5 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="bi bi-bar-chart mr-2 text-blue-600"></i>Quick Stats
                    </h5>
                    <div class="space-y-4">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-blue-600">{{ $links->count() }}</div>
                            <div class="text-sm text-gray-600">Total Links</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-600">{{ $links->where('created_at', '>=', now()->startOfWeek())->count() }}</div>
                            <div class="text-sm text-gray-600">This Week</div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                @if($links->count())
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h5 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="bi bi-clock-history mr-2 text-blue-600"></i>Recent Activity
                    </h5>
                    <div class="space-y-3">
                        @foreach($links->sortByDesc('created_at')->take(3) as $link)
                            <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                                <span class="text-sm text-gray-900 truncate flex-1">{{ $link->title }}</span>
                                <span class="text-xs text-gray-500 ml-2">{{ $link->created_at->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </main>
</div>

<!-- Add Link Modal -->
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50" id="addLinkModal">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bi bi-plus-circle mr-2 text-blue-600"></i>Add New Link
                </h3>
                <button class="text-gray-400 hover:text-gray-600" onclick="closeModal('addLinkModal')">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <form id="addLinkForm" action="{{ route('links.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="modalTitle" class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="modalTitle" name="title" required>
                    <p class="mt-1 text-sm text-gray-500">Give your link a descriptive name</p>
                </div>
                <div>
                    <label for="modalUrl" class="block text-sm font-medium text-gray-700">URL</label>
                    <input type="url" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="modalUrl" name="url" required>
                    <p class="mt-1 text-sm text-gray-500">Enter the full URL including https://</p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors" onclick="closeModal('addLinkModal')">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <i class="bi bi-check-circle mr-1"></i>Save Link
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Link Modal -->
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50" id="editLinkModal">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bi bi-pencil mr-2 text-blue-600"></i>Edit Link
                </h3>
                <button class="text-gray-400 hover:text-gray-600" onclick="closeModal('editLinkModal')">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <form id="editLinkForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="editTitle" class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="editTitle" name="title" required>
                    <p class="mt-1 text-sm text-gray-500">Update the link title</p>
                </div>
                <div>
                    <label for="editUrl" class="block text-sm font-medium text-gray-700">URL</label>
                    <input type="url" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="editUrl" name="url" required>
                    <p class="mt-1 text-sm text-gray-500">Update the URL</p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors" onclick="closeModal('editLinkModal')">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <i class="bi bi-check-circle mr-1"></i>Update Link
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50" id="deleteLinkModal">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-red-600 flex items-center">
                    <i class="bi bi-exclamation-triangle mr-2"></i>Confirm Delete
                </h3>
                <button class="text-gray-400 hover:text-gray-600" onclick="closeModal('deleteLinkModal')">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <p class="text-gray-600 mb-4">Are you sure you want to delete this link? This action cannot be undone.</p>
            <p class="text-gray-800 font-medium mb-4" id="deleteLinkTitle"></p>
            <div class="flex justify-end space-x-3">
                <button class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors" onclick="closeModal('deleteLinkModal')">Cancel</button>
                <form id="deleteLinkForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                        <i class="bi bi-trash mr-1"></i>Delete Link
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function editLink(id, title, url) {
    document.getElementById('editTitle').value = title;
    document.getElementById('editUrl').value = url;
    document.getElementById('editLinkForm').action = `/links/${id}`;
    openModal('editLinkModal');
}

function deleteLink(id) {
    // For simplicity, just set the action and open modal
    document.getElementById('deleteLinkForm').action = `/links/${id}`;
    document.getElementById('deleteLinkTitle').textContent = 'Link will be deleted';
    openModal('deleteLinkModal');
}

function copyLink(url, button) {
    if (!navigator.clipboard) {
        alert('Clipboard API not supported in this browser.');
        return;
    }

    navigator.clipboard.writeText(url).then(() => {
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="bi bi-clipboard-check mr-1"></i>Copied';
        button.classList.remove('bg-gray-100');
        button.classList.add('bg-green-100', 'text-green-700');

        setTimeout(() => {
            button.innerHTML = originalText;
            button.classList.add('bg-gray-100');
            button.classList.remove('bg-green-100', 'text-green-700');
        }, 1500);
    }).catch(() => {
        alert('Could not copy link. Please copy manually.');
    });
}

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('bg-gray-600')) {
        const modals = document.querySelectorAll('.fixed.inset-0');
        modals.forEach(modal => modal.classList.add('hidden'));
    }
});
</script>
@endsection