<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Official BIND-Tech Report') - ISAT U Dumangas Campus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; font-size: 12px; }
            .page-break { page-break-after: always; }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 p-6 md:p-10 min-h-screen">

    <!-- Print Action Bar -->
    <div class="no-print max-w-5xl mx-auto mb-6 flex justify-between items-center bg-slate-900 text-white p-4 rounded-xl shadow-lg">
        <a href="{{ route('reports.index') }}" class="text-slate-300 hover:text-white font-medium text-sm">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Back to Reports
        </a>
        <button onclick="window.print()" class="bg-amber-500 hover:bg-amber-600 text-slate-950 px-5 py-2 rounded-lg font-bold text-sm shadow flex items-center transition-all">
            <i class="fa-solid fa-print mr-2"></i> Print Report / Save PDF
        </button>
    </div>

    <!-- Official Report Document Paper -->
    <div class="max-w-5xl mx-auto bg-white border border-slate-200 p-8 shadow-xl rounded-2xl print:border-none print:shadow-none print:p-0">
        
        <!-- ISAT U Dumangas Official Letterhead Header -->
        <div class="border-b-2 border-slate-900 pb-4 mb-6 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-slate-900 text-amber-400 rounded-full flex items-center justify-center font-black text-2xl shadow">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <div>
                    <h4 class="text-xs uppercase font-bold tracking-widest text-slate-500">Republic of the Philippines</h4>
                    <h2 class="text-lg font-black tracking-tight text-slate-900 uppercase">Iloilo Science and Technology University</h2>
                    <h3 class="text-sm font-semibold text-amber-700">DUMANGAS CAMPUS - BIND-TECH DEPARTMENT</h3>
                    <p class="text-[11px] text-slate-500">PD Monfort South, Dumangas, Iloilo | Tool Management & Audit System</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-xs text-slate-500 font-medium">Date Generated:</div>
                <div class="text-sm font-bold text-slate-800">{{ date('F d, Y - h:i A') }}</div>
            </div>
        </div>

        <!-- Document Content -->
        @yield('content')

        <!-- Signature Block for Official Reports -->
        <div class="mt-16 pt-8 border-t border-slate-200 grid grid-cols-2 gap-8 text-center text-xs">
            <div>
                <div class="border-b border-slate-400 w-48 mx-auto mb-1 font-bold text-slate-900">
                    {{ auth()->user()->name }}
                </div>
                <div class="text-slate-500 uppercase font-semibold">Prepared By: {{ auth()->user()->role->label() }}</div>
            </div>
            <div>
                <div class="border-b border-slate-400 w-48 mx-auto mb-1 font-bold text-slate-900">
                    Engr. BIND-Tech Head / Administrator
                </div>
                <div class="text-slate-500 uppercase font-semibold">Noted & Approved By</div>
            </div>
        </div>

    </div>

</body>
</html>
