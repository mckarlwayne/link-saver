<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Link-Saver') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-white shadow-lg border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center space-x-2 text-xl font-bold text-gray-900 hover:text-blue-600 transition-colors">
                        <i class="bi bi-link-45deg text-blue-600"></i>
                        <span>Link-Saver</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('links.index') }}" class="flex items-center space-x-1 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            <i class="bi bi-house-door"></i>
                            <span>Home</span>
                        </a>
                        <div class="relative" id="userMenuWrapper">
                            <button id="userMenuButton" class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span class="hidden md:block">{{ Auth::user()->name }}</span>
                                <i class="bi bi-chevron-down text-xs"></i>
                            </button>
                            <div id="userMenuDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 border border-gray-200">
                                <a href="{{ route('profile.edit') ?? '#' }}" class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="bi bi-pencil-square"></i>
                                    <span>Edit Profile</span>
                                </a>
                                <a href="#" onclick="openModal('logoutModal')" class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Logout</span>
                                </a>
                            </div>
                        </div>
                    @else
                        <button onclick="openModal('loginModal')" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors">
                            <i class="bi bi-box-arrow-in-right mr-1"></i>Login
                        </button>
                        <button onclick="openModal('registerModal')" class="bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700 transition-colors">
                            <i class="bi bi-person-plus mr-1"></i>Register
                        </button>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    @if(session('status'))
        <div id="welcomeAlert" class="fixed top-16 right-4 z-50 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center space-x-2 transition-opacity duration-500 opacity-100">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('status') }}</span>
            <button onclick="document.getElementById('welcomeAlert').remove()" class="ml-2 text-white opacity-75 hover:opacity-100">&times;</button>
        </div>
    @endif

    <main class="py-4">
        @yield('content')
    </main>

    <!-- Login Modal -->
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" id="loginModal">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Login to Your Account</h3>
                    <button class="text-gray-400 hover:text-gray-600" onclick="closeModal('loginModal')">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <form id="loginForm" class="space-y-4">
                    @csrf
                    <div>
                        <label for="loginEmail" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="loginEmail" name="email" required>
                        <small class="text-red-500 text-xs hidden" id="loginEmailError"></small>
                    </div>
                    <div>
                        <label for="loginPassword" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="loginPassword" name="password" required>
                        <small class="text-red-500 text-xs hidden" id="loginPasswordError"></small>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Login
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Logout Modal -->
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" id="logoutModal">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Logout</h3>
                    <button class="text-gray-400 hover:text-gray-600" onclick="closeModal('logoutModal')">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <p class="text-gray-600 mb-4">Are you sure you want to logout?</p>
                <div class="flex justify-end space-x-2">
                    <button class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400" onclick="closeModal('logoutModal')">Cancel</button>
                    <button class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700" id="logoutBtn">Logout</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" id="registerModal">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Create New Account</h3>
                    <button class="text-gray-400 hover:text-gray-600" onclick="closeModal('registerModal')">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <form id="registerForm" class="space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="name" name="name" required>
                        <small class="text-red-500 text-xs hidden" id="nameError"></small>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="email" name="email" required>
                        <small class="text-red-500 text-xs hidden" id="emailError"></small>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="password" name="password" required>
                        <small class="text-red-500 text-xs hidden" id="passwordError"></small>
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input type="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="password_confirmation" name="password_confirmation" required>
                        <small class="text-red-500 text-xs hidden" id="confirmError"></small>
                    </div>
                    <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        Register
                    </button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>