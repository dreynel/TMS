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

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .swal2-popup { font-family: 'Inter', sans-serif !important; border-radius: 1rem !important; }
        /* Responsive Table Custom Scrollbars */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .table-responsive::-webkit-scrollbar {
            height: 6px;
            width: 6px;
        }
        .table-responsive::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        .table-responsive::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- Top Navigation Header with Mobile Drawer -->
    <header x-data="{ mobileMenuOpen: false }" class="bg-isatu-navy text-white shadow-lg sticky top-0 z-50 border-b-4 border-isatu-gold">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- Brand / Campus Title -->
                <div class="flex items-center space-x-2 sm:space-x-3 min-w-0">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 sm:space-x-3 group min-w-0">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 shrink-0 rounded-full bg-isatu-gold text-isatu-navy flex items-center justify-center font-extrabold text-base sm:text-lg shadow-md group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-wrench"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="font-bold text-base sm:text-lg leading-tight tracking-wide text-white group-hover:text-amber-300 transition-colors truncate">BIND-Tech TMS</div>
                            <div class="text-[9px] sm:text-[10px] text-amber-200 tracking-wider uppercase font-semibold truncate">ISAT U - Dumangas Campus</div>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
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

                <!-- User Profile & Actions (Desktop & Mobile Hamburger) -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <div class="text-right hidden sm:block">
                        <div class="text-xs font-semibold text-white truncate max-w-[130px]">{{ auth()->user()->name }}</div>
                        <div class="text-[11px] text-amber-300 capitalize font-medium">{{ auth()->user()->role->label() }}</div>
                    </div>

                    <form action="{{ route('logout') }}" method="POST" class="hidden sm:block">
                        @csrf
                        <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white text-xs px-3 py-2 rounded-lg font-semibold transition-colors flex items-center shadow">
                            <i class="fa-solid fa-right-from-bracket mr-1.5"></i> Logout
                        </button>
                    </form>

                    <!-- Mobile Hamburger Toggle Button -->
                    <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-amber-300 hover:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400" aria-label="Toggle Navigation Menu">
                        <i class="fa-solid text-xl" :class="mobileMenuOpen ? 'fa-xmark' : 'fa-bars'"></i>
                    </button>
                </div>
                @endauth

            </div>
        </div>

        <!-- Mobile Drawer Navigation -->
        @auth
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0 -translate-y-2" 
             x-transition:enter-end="opacity-100 translate-y-0" 
             x-transition:leave="transition ease-in duration-150" 
             x-transition:leave-start="opacity-100 translate-y-0" 
             x-transition:leave-end="opacity-0 -translate-y-2" 
             class="md:hidden bg-slate-900 border-t border-slate-800 px-4 pt-3 pb-5 space-y-2 shadow-2xl">
            
            <!-- Mobile User Identity Pill -->
            <div class="flex items-center justify-between p-3 bg-slate-800 rounded-xl mb-3 border border-slate-700">
                <div class="min-w-0">
                    <div class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</div>
                    <div class="text-[11px] text-slate-400 font-mono">{{ auth()->user()->id_number ?? auth()->user()->email }}</div>
                </div>
                <span class="px-2.5 py-1 text-[10px] font-extrabold uppercase rounded-full bg-amber-400 text-slate-950 shrink-0">
                    {{ auth()->user()->role->label() }}
                </span>
            </div>

            <!-- Mobile Nav Links -->
            <a href="{{ route('dashboard') }}" @click="mobileMenuOpen = false" class="flex items-center px-3.5 py-2.5 rounded-xl text-xs font-bold transition-colors {{ request()->routeIs('dashboard') ? 'bg-amber-400 text-slate-950 shadow' : 'text-slate-200 hover:bg-slate-800' }}">
                <i class="fa-solid fa-gauge w-6 mr-2"></i> Dashboard
            </a>
            
            <a href="{{ route('tools.index') }}" @click="mobileMenuOpen = false" class="flex items-center px-3.5 py-2.5 rounded-xl text-xs font-bold transition-colors {{ request()->routeIs('tools.*') ? 'bg-amber-400 text-slate-950 shadow' : 'text-slate-200 hover:bg-slate-800' }}">
                <i class="fa-solid fa-toolbox w-6 mr-2"></i> Tool Inventory Catalog
            </a>
            
            <a href="{{ route('borrowings.index') }}" @click="mobileMenuOpen = false" class="flex items-center px-3.5 py-2.5 rounded-xl text-xs font-bold transition-colors {{ request()->routeIs('borrowings.*') ? 'bg-amber-400 text-slate-950 shadow' : 'text-slate-200 hover:bg-slate-800' }}">
                <i class="fa-solid fa-hand-holding-hand w-6 mr-2"></i> Borrowing Requests
            </a>

            @if(auth()->user()->isAdmin() || auth()->user()->isCustodian())
            <a href="{{ route('users.index') }}" @click="mobileMenuOpen = false" class="flex items-center px-3.5 py-2.5 rounded-xl text-xs font-bold transition-colors {{ request()->routeIs('users.*') ? 'bg-amber-400 text-slate-950 shadow' : 'text-slate-200 hover:bg-slate-800' }}">
                <i class="fa-solid fa-users-gear w-6 mr-2"></i> User Approvals & Roles
            </a>
            
            <a href="{{ route('reports.index') }}" @click="mobileMenuOpen = false" class="flex items-center px-3.5 py-2.5 rounded-xl text-xs font-bold transition-colors {{ request()->routeIs('reports.*') ? 'bg-amber-400 text-slate-950 shadow' : 'text-slate-200 hover:bg-slate-800' }}">
                <i class="fa-solid fa-file-invoice w-6 mr-2"></i> Official Reports
            </a>
            @endif

            <!-- Mobile Logout Button -->
            <div class="pt-2 border-t border-slate-800 mt-2">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-rose-600 hover:bg-rose-700 text-white text-xs px-4 py-2.5 rounded-xl font-bold transition-colors flex items-center justify-center shadow">
                        <i class="fa-solid fa-right-from-bracket mr-2"></i> Log Out
                    </button>
                </form>
            </div>
        </div>
        @endauth
    </header>

    <!-- Global Alert Banners & Main Content -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-6">
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

    <!-- Global SweetAlert2 Handlers & Helpers -->
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        // Trigger SWAL Toasts for Flash Messages
        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ addslashes(session('success')) }}"
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Action Failed',
                text: "{{ addslashes(session('error')) }}",
                confirmButtonColor: '#002B49'
            });
        @endif

        // Global Helper: Confirm Action
        window.swalConfirm = function(options, onConfirm) {
            Swal.fire({
                title: options.title || 'Are you sure?',
                text: options.text || 'Do you want to proceed with this action?',
                icon: options.icon || 'warning',
                showCancelButton: true,
                confirmButtonColor: options.confirmColor || '#002B49',
                cancelButtonColor: '#64748b',
                confirmButtonText: options.confirmText || 'Yes, proceed',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed && typeof onConfirm === 'function') {
                    onConfirm();
                }
            });
        };

        // Global Helper: Confirm Form Delete
        window.swalDelete = function(event, itemName) {
            event.preventDefault();
            const form = event.target.closest('form');
            Swal.fire({
                title: 'Delete Asset / Record?',
                text: itemName ? `Are you sure you want to delete "${itemName}"? This cannot be undone.` : 'Are you sure you want to permanently delete this record?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed && form) {
                    form.submit();
                }
            });
        };
    </script>

    @stack('scripts')
</body>
</html>
