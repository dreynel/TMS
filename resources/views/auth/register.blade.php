<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Borrower Account - ISAT U Dumangas TMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-lg w-full bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl p-8 border border-white/20 my-6">
        
        <div class="text-center mb-6">
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Register Borrower Account</h1>
            <p class="text-xs text-amber-600 font-bold uppercase tracking-wider mt-1">BIND-Tech ISAT U Dumangas Campus</p>
        </div>

        @if($errors->any())
            <div class="mb-4 p-3 bg-rose-50 border-l-4 border-rose-500 rounded text-rose-800 text-xs font-medium">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-600 focus:bg-white"
                    placeholder="e.g. Juan Dela Cruz">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Student / Employee ID Number</label>
                    <input type="text" name="id_number" value="{{ old('id_number') }}" required
                        class="w-full px-4 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-600 focus:bg-white"
                        placeholder="ISATU-2026-0042">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Department / Course</label>
                    <input type="text" name="department_course" value="{{ old('department_course') }}" required
                        class="w-full px-4 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-600 focus:bg-white"
                        placeholder="e.g. BIT-Electronics / BSIT">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-600 focus:bg-white"
                        placeholder="student@isatu.edu.ph">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Contact Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="w-full px-4 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-600 focus:bg-white"
                        placeholder="09181234567">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-600 focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-4 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-600 focus:bg-white">
                </div>
            </div>

            <p class="text-[11px] text-amber-700 bg-amber-50 p-2.5 rounded-lg font-medium border border-amber-200">
                <i class="fa-solid fa-circle-info mr-1"></i> Note: Registered borrower accounts require approval from the Tool Custodian or Admin before borrowing tools.
            </p>

            <button type="submit" class="w-full bg-blue-900 hover:bg-blue-950 text-white font-bold py-3 px-4 rounded-xl shadow-lg transition-all">
                Submit Registration
            </button>
        </form>

        <div class="mt-6 text-center text-xs">
            <a href="{{ route('login') }}" class="text-blue-700 hover:underline font-bold">
                Already have an account? Sign in here
            </a>
        </div>

    </div>

</body>
</html>
