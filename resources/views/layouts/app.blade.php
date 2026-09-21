<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BIND-Tech Tool Management System') - ISAT U Dumangas</title>
    
    <!-- Tailwind CSS CDN & FontAwesome Icons -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        isatu: {
                            navy: '#002B49',
                            gold: '#D4AF37',
                            blue: '#005691',
                            light: '#F4F6F9',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- Top Navigation Header -->
    <header class="bg-isatu-navy text-white shadow-lg sticky top-0 z-50 border-b-4 border-isatu-gold">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- Brand / Campus Title -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                        <div class="w-10 h-10 rounded-full bg-isatu-gold text-isatu-navy flex items-center justify-center font-extrabold text-lg shadow-md group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-wrench"></i>
                        </div>
                        <div>
                            <div class="font-bold text-lg leading-tight tracking-wide text-white group-hover:text-amber-300 transition-colors">BIND-Tech TMS</div>
                            <div class="text-[10px] text-amber-200 tracking-wider uppercase font-semibold">ISAT U - Dumangas Campus</div>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                @auth
                <nav class="hidden md:flex space-x-1 font-medium text-sm">
                    <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('dashboard') ? 'bg-slate-800 text-amber-400 font-semibold' : 'text-slate-200' }}">
                        <i class="fa-solid fa-gauge mr-1.5"></i> Dashboard
                    </a>
                    
                    <a href="{{ route('tools.index') }}" class="px-3 py-2 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('tools.*') ? 'bg-slate-800 text-amber-400 font-semibold' : 'text-slate-200' }}">
                        <i class="fa-solid fa-toolbox mr-1.5"></i> Tool Inventory
                    </a>
                    
                    <a href="{{ route('borrowings.index') }}" class="px-3 py-2 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('borrowings.*') ? 'bg-slate-800 text-amber-400 font-semibold' : 'text-slate-200' }}">
                        <i class="fa-solid fa-hand-holding-hand mr-1.5"></i> Borrowing Requests
                    </a>

                    @if(auth()->user()->isAdmin() || auth()->user()->isCustodian())
                    <a href="{{ route('users.index') }}" class="px-3 py-2 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('users.*') ? 'bg-slate-800 text-amber-400 font-semibold' : 'text-slate-200' }}">
                        <i class="fa-solid fa-users-gear mr-1.5"></i> User Approvals
                    </a>
                    
                    <a href="{{ route('reports.index') }}" class="px-3 py-2 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('reports.*') ? 'bg-slate-800 text-amber-400 font-semibold' : 'text-slate-200' }}">
                        <i class="fa-solid fa-file-invoice mr-1.5"></i> Reports
                    </a>
                    @endif
                </nav>

                <!-- User Profile & Logout -->
                <div class="flex items-center space-x-3">
                    <div class="text-right hidden sm:block">
                        <div class="text-xs font-semibold text-white">{{ auth()->user()->name }}</div>
                        <div class="text-[11px] text-amber-300 capitalize font-medium">{{ auth()->user()->role->label() }}</div>
                    </div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white text-xs px-3 py-2 rounded-lg font-semibold transition-colors flex items-center shadow">
                            <i class="fa-solid fa-right-from-bracket mr-1.5"></i> Logout
                        </button>
                    </form>
                </div>
                @endauth

            </div>
        </div>
    </header>

    <!-- Global Alert Banners -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm flex items-center justify-between">
                <div class="flex items-center text-emerald-800 font-medium">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-xl mr-3"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" class="mb-6 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-lg shadow-sm flex items-center justify-between">
                <div class="flex items-center text-rose-800 font-medium">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-xl mr-3"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-rose-500 hover:text-rose-700">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-6 border-t border-slate-800 mt-auto text-xs">
        <div class="max-w-7xl mx-auto px-4 text-center sm:flex sm:justify-between sm:text-left">
            <div>
                <span class="font-bold text-slate-200">BIND-Tech Tool Management System</span> &copy; {{ date('Y') }}
            </div>
            <div class="mt-2 sm:mt-0 text-slate-400">
                Iloilo Science and Technology University - Dumangas Campus
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
