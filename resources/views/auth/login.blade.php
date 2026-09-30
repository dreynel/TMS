<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In | BIND-Tech Tool Management System - ISAT U Dumangas</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        isatu: {
                            navy: '#002B49',
                            dark: '#001A2D',
                            blue: '#005691',
                            gold: '#D4AF37',
                            goldLight: '#F5E6A3',
                            accent: '#F39C12',
                        }
                    }
                }
            }
        }
    </script>
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-800 min-h-screen flex items-center justify-center p-3 sm:p-6 lg:p-10 relative overflow-x-hidden selection:bg-amber-400 selection:text-slate-950">

    <!-- Ambient Radial Glow Accents in Background -->
    <div class="fixed top-0 left-1/4 w-96 h-96 bg-blue-600/15 rounded-full blur-3xl pointer-events-none -translate-y-1/2"></div>
    <div class="fixed bottom-0 right-1/4 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none translate-y-1/2"></div>

    <!-- Main Container Card (Dual Column on Desktop, Compact on Mobile) -->
    <div class="relative w-full max-w-5xl bg-slate-900/90 rounded-3xl shadow-2xl border border-slate-800/80 overflow-hidden backdrop-blur-xl">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 min-h-[580px]">
            
            <!-- LEFT PANEL: ISAT U Campus Branding & Highlights (Hidden/Compact on small mobile, rich on desktop) -->
            <div class="lg:col-span-6 bg-gradient-to-br from-isatu-dark via-isatu-navy to-slate-900 p-6 sm:p-10 flex flex-col justify-between border-b lg:border-b-0 lg:border-r border-slate-800/80 relative overflow-hidden">
                
                <!-- Background Geometric Pattern Decoration -->
                <div class="absolute -right-16 -bottom-16 w-64 h-64 border border-white/5 rounded-full pointer-events-none"></div>
                <div class="absolute -right-8 -bottom-8 w-48 h-48 border border-amber-400/10 rounded-full pointer-events-none"></div>
                
                <!-- Header / Campus Badges -->
                <div class="relative z-10">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-isatu-gold to-amber-500 text-slate-950 flex items-center justify-center text-2xl font-black shadow-lg shadow-amber-500/20 ring-4 ring-white/10">
                            <i class="fa-solid fa-screwdriver-wrench"></i>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="text-[10px] font-black tracking-widest uppercase bg-amber-400/20 text-amber-300 border border-amber-400/30 px-2 py-0.5 rounded-full">
                                    ISAT U • Dumangas
                                </span>
                                <span class="text-[10px] text-slate-400 font-semibold">Campus</span>
                            </div>
                            <h2 class="text-xs font-semibold text-slate-300 tracking-wide mt-0.5">Bachelor of Industrial Technology</h2>
                        </div>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight leading-tight">
                        BIND-Tech <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-yellow-200 to-amber-400">TMS</span>
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-2 leading-relaxed">
                        Integrated Tool Management, Digital Equipment Borrowing & Custodian Inventory Audit System.
                    </p>

                    <!-- Key Features List -->
                    <div class="mt-8 space-y-3.5 hidden sm:block">
                        <div class="flex items-start space-x-3 text-slate-300">
                            <div class="w-7 h-7 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-amber-300 shrink-0 text-xs mt-0.5">
                                <i class="fa-solid fa-boxes-stacked"></i>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-white">Live Workshop Inventory</div>
                                <div class="text-[11px] text-slate-400">Categorized tool tracking with real-time availability and serial codes.</div>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3 text-slate-300">
                            <div class="w-7 h-7 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-emerald-400 shrink-0 text-xs mt-0.5">
                                <i class="fa-solid fa-clipboard-check"></i>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-white">Fast Borrowing & Returns</div>
                                <div class="text-[11px] text-slate-400">Streamlined custodian sign-offs, return condition grading, and overdue alerts.</div>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3 text-slate-300">
                            <div class="w-7 h-7 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-blue-400 shrink-0 text-xs mt-0.5">
                                <i class="fa-solid fa-chart-pie"></i>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-white">Audit & Overdue Reports</div>
                                <div class="text-[11px] text-slate-400">Automated log generation for compliance and campus accountability.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Campus Info on Left Panel -->
                <div class="mt-8 pt-6 border-t border-white/10 relative z-10 flex items-center justify-between text-[11px] text-slate-400">
                    <span class="flex items-center space-x-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-slate-300 font-medium">System Online • v1.2</span>
                    </span>
                    <span class="text-slate-500 font-mono">Dumangas, Iloilo</span>
                </div>

            </div>

            <!-- RIGHT PANEL: Login Form & Quick Role Selection -->
            <div class="lg:col-span-6 bg-white p-6 sm:p-10 flex flex-col justify-between">
                
                <div>
                    <!-- Form Title -->
                    <div class="mb-6">
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Sign In</h2>
                        <p class="text-xs text-slate-500 mt-1">Access your tool inventory, borrower dashboard, or custodian portal.</p>
                    </div>

                    <!-- Alerts / Flash Messages -->
                    @if(session('error'))
                        <div class="mb-4 p-3.5 bg-rose-50 border-l-4 border-rose-500 rounded-xl text-rose-800 text-xs font-semibold flex items-center shadow-sm">
                            <i class="fa-solid fa-circle-exclamation mr-2.5 text-rose-600 text-base shrink-0"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-4 p-3.5 bg-rose-50 border-l-4 border-rose-500 rounded-xl text-rose-800 text-xs font-semibold shadow-sm">
                            <div class="flex items-center">
                                <i class="fa-solid fa-circle-exclamation mr-2 text-rose-600 text-base shrink-0"></i>
                                <span>{{ $errors->first() }}</span>
                            </div>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="mb-4 p-3.5 bg-emerald-50 border-l-4 border-emerald-500 rounded-xl text-emerald-800 text-xs font-semibold flex items-center shadow-sm">
                            <i class="fa-solid fa-circle-check mr-2.5 text-emerald-600 text-base shrink-0"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Sign In Form -->
                    <form id="loginForm" action="{{ route('login') }}" method="POST" class="space-y-4">
                        @csrf

                        <!-- Username/Email Input -->
                        <div>
                            <label for="loginInput" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-1.5">
                                Username, Email, or ID Number
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-user text-xs"></i>
                                </div>
                                <input type="text" id="loginInput" name="login" value="{{ old('login') ?? old('email') }}" required autofocus
                                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-isatu-blue focus:bg-white focus:border-transparent transition-all placeholder:text-slate-400"
                                    placeholder="e.g. admin, custodian, or student">
                            </div>
                        </div>

                        <!-- Password Input -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="passwordInput" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wide">
                                    Password
                                </label>
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-lock text-xs"></i>
                                </div>
                                <input type="password" id="passwordInput" name="password" required
                                    class="w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-isatu-blue focus:bg-white focus:border-transparent transition-all placeholder:text-slate-400"
                                    placeholder="••••••••">
                                <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none" aria-label="Toggle password visibility">
                                    <i id="passwordToggleIcon" class="fa-solid fa-eye text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between text-xs pt-1">
                            <label class="flex items-center text-slate-600 cursor-pointer select-none">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-isatu-blue focus:ring-isatu-blue mr-2 cursor-pointer">
                                <span class="font-medium text-[11px] sm:text-xs">Remember my session</span>
                            </label>
                            <span class="text-[11px] text-slate-400">ISAT U BIND-Tech</span>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" id="submitBtn" class="w-full bg-isatu-navy hover:bg-isatu-dark active:scale-[0.99] text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-isatu-navy/20 transition-all flex items-center justify-center space-x-2 group">
                            <span class="text-xs sm:text-sm tracking-wide">Sign In to System</span>
                            <i class="fa-solid fa-arrow-right text-amber-400 text-xs group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </form>
                </div>

                <!-- Bottom Section: 1-Click Evaluation Accounts & Registration Link -->
                <div class="mt-6 pt-5 border-t border-slate-200">
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="font-black text-slate-700 uppercase tracking-wider text-[10px]">
                            <i class="fa-solid fa-bolt text-amber-500 mr-1"></i> Quick 1-Click Login (Evaluator Accounts):
                        </span>
                        <span class="text-[10px] text-slate-400 font-mono">pw: <strong class="text-slate-600">password</strong></span>
                    </div>

                    <!-- 3 Role Buttons Side-by-Side (Ultra Responsive Grid) -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                        <!-- Admin -->
                        <button type="button" onclick="fillAndSubmit('admin', 'password', this)" class="role-btn text-left p-2.5 rounded-xl border border-indigo-100 bg-indigo-50/60 hover:bg-indigo-100/80 hover:border-indigo-300 transition-all group flex flex-col justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-6 h-6 rounded-md bg-indigo-600 text-white flex items-center justify-center text-[10px] font-bold shrink-0">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </div>
                                <span class="font-black text-indigo-950 text-xs truncate">Admin</span>
                            </div>
                            <div class="text-[10px] text-indigo-600 font-semibold mt-1.5 flex items-center justify-between">
                                <span>admin</span>
                                <i class="fa-solid fa-arrow-right text-[8px] group-hover:translate-x-0.5 transition-transform"></i>
                            </div>
                        </button>

                        <!-- Custodian -->
                        <button type="button" onclick="fillAndSubmit('custodian', 'password', this)" class="role-btn text-left p-2.5 rounded-xl border border-amber-200 bg-amber-50/70 hover:bg-amber-100/90 hover:border-amber-300 transition-all group flex flex-col justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-6 h-6 rounded-md bg-amber-500 text-slate-950 flex items-center justify-center text-[10px] font-bold shrink-0">
                                    <i class="fa-solid fa-screwdriver-wrench"></i>
                                </div>
                                <span class="font-black text-amber-950 text-xs truncate">Custodian</span>
                            </div>
                            <div class="text-[10px] text-amber-700 font-semibold mt-1.5 flex items-center justify-between">
                                <span>custodian</span>
                                <i class="fa-solid fa-arrow-right text-[8px] group-hover:translate-x-0.5 transition-transform"></i>
                            </div>
                        </button>

                        <!-- Student / Borrower -->
                        <button type="button" onclick="fillAndSubmit('student', 'password', this)" class="role-btn text-left p-2.5 rounded-xl border border-emerald-100 bg-emerald-50/60 hover:bg-emerald-100/80 hover:border-emerald-300 transition-all group flex flex-col justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-6 h-6 rounded-md bg-emerald-600 text-white flex items-center justify-center text-[10px] font-bold shrink-0">
                                    <i class="fa-solid fa-user-graduate"></i>
                                </div>
                                <span class="font-black text-emerald-950 text-xs truncate">Student</span>
                            </div>
                            <div class="text-[10px] text-emerald-700 font-semibold mt-1.5 flex items-center justify-between">
                                <span>student</span>
                                <i class="fa-solid fa-arrow-right text-[8px] group-hover:translate-x-0.5 transition-transform"></i>
                            </div>
                        </button>
                    </div>

                    <!-- Register Link -->
                    <div class="mt-4 pt-3 text-center border-t border-slate-100">
                        <a href="{{ route('register') }}" class="text-isatu-blue hover:text-isatu-navy text-xs font-bold hover:underline inline-flex items-center transition-colors">
                            <i class="fa-solid fa-user-plus mr-1.5 text-slate-400"></i>
                            <span>Need a student borrower account? Register here &rarr;</span>
                        </a>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Interactive Scripts -->
    <script>
        function fillAndSubmit(login, password, btnElement) {
            const loginInput = document.getElementById('loginInput');
            const passwordInput = document.getElementById('passwordInput');
            const submitBtn = document.getElementById('submitBtn');

            loginInput.value = login;
            passwordInput.value = password;

            // Visual feedback on button
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Signing In...';
                submitBtn.classList.add('opacity-90', 'cursor-wait');
            }

            document.getElementById('loginForm').submit();
        }

        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('passwordInput');
            const icon = document.getElementById('passwordToggleIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Auto-refresh page if left idle in a background tab for > 45 minutes
        const pageLoadedAt = Date.now();
        document.addEventListener('visibilitychange', function() {
            if (document.visibilityState === 'visible' && (Date.now() - pageLoadedAt > 45 * 60 * 1000)) {
                window.location.reload();
            }
        });
    </script>

</body>
</html>
