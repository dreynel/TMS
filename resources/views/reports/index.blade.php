@extends('layouts.app')

@section('title', 'BIND-Tech Official Reports Hub')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Official Reports & Audit Center</h1>
    <p class="text-xs text-slate-500 mt-1">Generate, filter, view, and print official BIND-Tech management reports.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    
    <!-- 1. Inventory Report Card -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
        <div>
            <div class="w-12 h-12 bg-blue-50 text-blue-900 rounded-xl flex items-center justify-center text-xl font-bold mb-4">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-900">1. Tool Inventory Report</h3>
            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                Complete audit of all equipment assets, storage rack locations, total quantities, current available stock, and asset tags.
            </p>
        </div>
        <div class="mt-6 flex space-x-2">
            <a href="{{ route('reports.inventory') }}" class="flex-grow text-center bg-blue-900 hover:bg-blue-950 text-white font-bold text-xs py-2.5 rounded-xl shadow">
                View Report Table
            </a>
            <a href="{{ route('reports.inventory', ['print' => 1]) }}" target="_blank" class="bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs py-2.5 px-4 rounded-xl shadow">
                <i class="fa-solid fa-print mr-1"></i> Print / PDF
            </a>
        </div>
    </div>

    <!-- 2. Borrowing History Report Card -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
        <div>
            <div class="w-12 h-12 bg-amber-50 text-amber-700 rounded-xl flex items-center justify-center text-xl font-bold mb-4">
                <i class="fa-solid fa-history"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-900">2. Borrowing History & Audit Logs</h3>
            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                Comprehensive log of tool releases, borrower details, activities, approval custodians, and transaction timelines.
            </p>
        </div>
        <div class="mt-6 flex space-x-2">
            <a href="{{ route('reports.borrowing_history') }}" class="flex-grow text-center bg-blue-900 hover:bg-blue-950 text-white font-bold text-xs py-2.5 rounded-xl shadow">
                View Report Table
            </a>
            <a href="{{ route('reports.borrowing_history', ['print' => 1]) }}" target="_blank" class="bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs py-2.5 px-4 rounded-xl shadow">
                <i class="fa-solid fa-print mr-1"></i> Print / PDF
            </a>
        </div>
    </div>

    <!-- 3. Overdue Items Report Card -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
        <div>
            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center text-xl font-bold mb-4">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-900">3. Overdue Items Report</h3>
            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                Highlights unreturned equipment past expected return dates, student contact info, and days overdue.
            </p>
        </div>
        <div class="mt-6 flex space-x-2">
            <a href="{{ route('reports.overdue') }}" class="flex-grow text-center bg-blue-900 hover:bg-blue-950 text-white font-bold text-xs py-2.5 rounded-xl shadow">
                View Report Table
            </a>
            <a href="{{ route('reports.overdue', ['print' => 1]) }}" target="_blank" class="bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs py-2.5 px-4 rounded-xl shadow">
                <i class="fa-solid fa-print mr-1"></i> Print / PDF
            </a>
        </div>
    </div>

    <!-- 4. Tool Condition Audit Report Card -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
        <div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-700 rounded-xl flex items-center justify-center text-xl font-bold mb-4">
                <i class="fa-solid fa-clipboard-check"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-900">4. Tool Condition & Damage Audit Report</h3>
            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                Tracks tool condition transitions (New -> Good -> Damaged) and custodian return inspection notes over time.
            </p>
        </div>
        <div class="mt-6 flex space-x-2">
            <a href="{{ route('reports.condition_audit') }}" class="flex-grow text-center bg-blue-900 hover:bg-blue-950 text-white font-bold text-xs py-2.5 rounded-xl shadow">
                View Report Table
            </a>
            <a href="{{ route('reports.condition_audit', ['print' => 1]) }}" target="_blank" class="bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs py-2.5 px-4 rounded-xl shadow">
                <i class="fa-solid fa-print mr-1"></i> Print / PDF
            </a>
        </div>
    </div>

</div>

@endsection
