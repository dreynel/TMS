<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - BIND-Tech Tool Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl p-8 border border-white/20">
        
        <!-- Header Brand -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-amber-400 text-slate-950 rounded-2xl mx-auto flex items-center justify-center text-3xl font-black shadow-lg mb-3">
                <i class="fa-solid fa-wrench"></i>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">BIND-Tech TMS</h1>
            <p class="text-xs text-amber-600 font-bold uppercase tracking-wider mt-1">ISAT U - Dumangas Campus</p>
            <p class="text-xs text-slate-500 mt-2">Tool Management & Borrowing System</p>
        </div>

        @if($errors->any())
            <div class="mb-4 p-3 bg-rose-50 border-l-4 border-rose-500 rounded text-rose-800 text-xs font-medium">
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('success'))
            <div class="mb-4 p-3 bg-emerald-50 border-l-4 border-emerald-500 rounded text-emerald-800 text-xs font-medium">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition-all"
                        placeholder="student@isatu.edu.ph">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <input type="password" name="password" required
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition-all"
                        placeholder="••••••••">
                </div>
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center text-slate-600 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 mr-2">
                    Remember me
                </label>
            </div>

            <button type="submit" class="w-full bg-blue-900 hover:bg-blue-950 text-white font-bold py-3 px-4 rounded-xl shadow-lg transition-all flex items-center justify-center space-x-2">
                <span>Sign In to System</span>
                <i class="fa-solid fa-arrow-right text-amber-400"></i>
            </button>
        </form>

        <!-- Quick Credentials Box for Testing -->
        <div class="mt-8 pt-6 border-t border-slate-200 text-xs">
            <p class="font-bold text-slate-700 mb-2">Default Accounts (Testing):</p>
            <div class="space-y-1 text-slate-600 font-mono text-[11px]">
                <div class="flex justify-between bg-slate-100 p-1.5 rounded">
                    <span>Admin:</span> <span class="font-semibold text-slate-900">admin@isatu.edu.ph</span> / password
                </div>
                <div class="flex justify-between bg-slate-100 p-1.5 rounded">
                    <span>Custodian:</span> <span class="font-semibold text-slate-900">custodian@isatu.edu.ph</span> / password
                </div>
                <div class="flex justify-between bg-slate-100 p-1.5 rounded">
                    <span>Borrower:</span> <span class="font-semibold text-slate-900">student@isatu.edu.ph</span> / password
                </div>
            </div>

            <div class="mt-4 text-center">
                <a href="{{ route('register') }}" class="text-blue-700 hover:underline font-bold">
                    Need a borrower account? Register here
                </a>
            </div>
        </div>

    </div>

</body>
</html>
