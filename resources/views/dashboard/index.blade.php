@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-slate-900 via-blue-900 to-slate-900 rounded-2xl p-6 mb-8 text-white shadow-xl relative overflow-hidden">
    <div class="absolute right-0 top-0 opacity-10 text-9xl transform translate-x-8 -translate-y-8 pointer-events-none">
        <i class="fa-solid fa-toolbox"></i>
    </div>
    <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between">
        <div>
            <div class="inline-block bg-amber-400 text-slate-950 text-xs px-2.5 py-1 rounded-full font-extrabold uppercase mb-2">
                BIND-Tech Tool Management System
            </div>
            <h1 class="text-2xl md:text-3xl font-black">Welcome back, {{ $user->name }}!</h1>
            <p class="text-sm text-slate-300 mt-1">
                Account Role: <span class="text-amber-300 font-semibold">{{ $user->role->label() }}</span> 
                @if($user->department_course)
                    | {{ $user->department_course }}
                @endif
            </p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
            @if($user->isBorrower())
                <a href="{{ route('borrowings.create') }}" class="bg-amber-400 hover:bg-amber-300 text-slate-950 px-4 py-2.5 rounded-xl font-bold text-sm shadow-md transition-all flex items-center">
                    <i class="fa-solid fa-plus-circle mr-2"></i> Request Borrow Tools
                </a>
            @else
                <a href="{{ route('tools.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl font-bold text-sm shadow-md transition-all flex items-center">
                    <i class="fa-solid fa-plus-circle mr-2"></i> Add New Tool
                </a>
                <a href="{{ route('reports.index') }}" class="bg-slate-800 hover:bg-slate-700 text-amber-300 border border-amber-400/30 px-4 py-2.5 rounded-xl font-bold text-sm shadow-md transition-all flex items-center">
                    <i class="fa-solid fa-print mr-2"></i> View Reports
                </a>
            @endif
        </div>
    </div>
</div>

<!-- Borrower Unapproved Alert -->
@if($user->isBorrower() && !$user->is_approved)
<div class="mb-8 bg-amber-50 border-l-4 border-amber-500 p-5 rounded-xl shadow-sm">
    <div class="flex items-start">
        <i class="fa-solid fa-clock-rotate-left text-amber-600 text-2xl mr-4 mt-0.5"></i>
        <div>
            <h3 class="font-bold text-amber-900 text-base">Borrower Account Pending Custodian Approval</h3>
            <p class="text-sm text-amber-800 mt-1">
                Your account is currently queued for verification by BIND-Tech Tool Custodians. Once approved, you can browse tools and submit borrowing requests online.
            </p>
        </div>
    </div>
</div>
@endif

<!-- KPI Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase">Total Equipment Stock</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ number_format($stats['total_tools']) }}</h3>
                <span class="text-xs text-emerald-600 font-semibold mt-1 inline-block">
                    <i class="fa-solid fa-check-circle mr-1"></i> {{ number_format($stats['available_tools']) }} Available Now
                </span>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-800 rounded-xl flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase">Pending Requests</p>
                <h3 class="text-2xl font-black text-amber-600 mt-1">{{ number_format($stats['pending_requests']) }}</h3>
                <span class="text-xs text-slate-500 mt-1 inline-block">Awaiting Custodian Review</span>
            </div>
            <div class="w-12 h-12 bg-amber-50 text-amber-700 rounded-xl flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-clock"></i>
            </div>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase">Active Borrowed Tools</p>
                <h3 class="text-2xl font-black text-blue-700 mt-1">{{ number_format($stats['active_released']) }}</h3>
                <span class="text-xs text-slate-500 mt-1 inline-block">Currently in Student/Staff Use</span>
            </div>
            <div class="w-12 h-12 bg-indigo-50 text-indigo-700 rounded-xl flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-hand-holding"></i>
            </div>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase">Overdue Returns</p>
                <h3 class="text-2xl font-black text-rose-600 mt-1">{{ number_format($stats['overdue_count']) }}</h3>
                <span class="text-xs text-rose-500 font-semibold mt-1 inline-block">Past Expected Return Date</span>
            </div>
            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </div>
    </div>
</div>

<!-- Main Section: Activity & Tables -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Pending Approval & Active Borrowings (2 cols) -->
    <div class="lg:col-span-2 space-y-8">
        
        <!-- Custodian Pending Queue -->
        @if(($user->isAdmin() || $user->isCustodian()) && count($pendingBorrowings) > 0)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-bold text-slate-900 flex items-center">
                    <i class="fa-solid fa-bell text-amber-500 mr-2"></i> Pending Borrowing Approval Queue
                </h2>
                <a href="{{ route('borrowings.index', ['status' => 'pending']) }}" class="text-xs font-bold text-blue-700 hover:underline">View All</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase font-bold">
                            <th class="p-3">Borrow Code</th>
                            <th class="p-3">Borrower</th>
                            <th class="p-3">Purpose</th>
                            <th class="p-3">Expected Return</th>
                            <th class="p-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($pendingBorrowings as $row)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="p-3 font-mono font-bold text-blue-900">{{ $row->borrow_code }}</td>
                            <td class="p-3 font-semibold text-slate-800">{{ $row->borrower->name }}</td>
                            <td class="p-3 text-slate-600 truncate max-w-xs">{{ $row->purpose }}</td>
                            <td class="p-3 text-slate-600">{{ $row->expected_return_date->format('M d, Y') }}</td>
                            <td class="p-3 text-right">
                                <a href="{{ route('borrowings.show', $row->id) }}" class="bg-blue-900 hover:bg-blue-950 text-white text-[11px] font-bold px-3 py-1.5 rounded-lg shadow inline-block">
                                    Review Request
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Borrower's Recent Transactions -->
        @if($user->isBorrower())
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-bold text-slate-900 flex items-center">
                    <i class="fa-solid fa-history text-blue-600 mr-2"></i> My Borrowing Requests & Status
                </h2>
                <a href="{{ route('borrowings.index') }}" class="text-xs font-bold text-blue-700 hover:underline">View All</a>
            </div>

            @if(count($userBorrowings) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase font-bold">
                            <th class="p-3">Code</th>
                            <th class="p-3">Purpose</th>
                            <th class="p-3">Return Date</th>
                            <th class="p-3">Status</th>
                            <th class="p-3 text-right">Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($userBorrowings as $row)
                        <tr class="hover:bg-slate-50">
                            <td class="p-3 font-mono font-bold text-blue-900">{{ $row->borrow_code }}</td>
                            <td class="p-3 text-slate-700 truncate max-w-xs">{{ $row->purpose }}</td>
                            <td class="p-3 text-slate-600">{{ $row->expected_return_date->format('M d, Y') }}</td>
                            <td class="p-3">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $row->status->badgeClass() }}">
                                    {{ $row->status->label() }}
                                </span>
                            </td>
                            <td class="p-3 text-right">
                                <a href="{{ route('borrowings.show', $row->id) }}" class="text-blue-700 font-bold hover:underline">View</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <p class="text-xs text-slate-500 py-6 text-center">You have no active or previous borrowing records.</p>
            @endif
        </div>
        @endif

        <!-- Quick Access Tool Catalog Preview -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-bold text-slate-900 flex items-center">
                    <i class="fa-solid fa-wrench text-amber-500 mr-2"></i> BIND-Tech Available Tools Catalog
                </h2>
                <a href="{{ route('tools.index') }}" class="text-xs font-bold text-blue-700 hover:underline">Browse Full Catalog</a>
            </div>
            <p class="text-xs text-slate-500 mb-4">Search & verify real-time equipment availability for workshop exercises.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach(\App\Models\Tool::take(4)->get() as $tool)
                <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50 flex justify-between items-center">
                    <div>
                        <span class="text-[10px] font-mono font-bold text-slate-400">{{ $tool->asset_code }}</span>
                        <h4 class="text-xs font-bold text-slate-900">{{ $tool->name }}</h4>
                        <p class="text-[11px] text-slate-500">{{ $tool->category->name }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-extrabold {{ $tool->available_qty > 0 ? 'text-emerald-700 bg-emerald-100' : 'text-rose-700 bg-rose-100' }} px-2 py-0.5 rounded-full">
                            {{ $tool->available_qty }} / {{ $tool->total_qty }} Left
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- Side Overdue Warning & System Quick Info (1 col) -->
    <div class="space-y-6">
        
        <!-- Overdue Warning Panel -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h3 class="text-sm font-bold text-slate-900 flex items-center mb-3">
                <i class="fa-solid fa-triangle-exclamation text-rose-600 mr-2"></i> Overdue Return Alerts
            </h3>

            @if(count($overdueBorrowings) > 0)
                <div class="space-y-3">
                    @foreach($overdueBorrowings as $overdue)
                    <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs">
                        <div class="flex justify-between font-bold text-rose-900">
                            <span>{{ $overdue->borrow_code }}</span>
                            <span class="text-[10px] bg-rose-600 text-white px-2 py-0.5 rounded-full">OVERDUE</span>
                        </div>
                        <p class="font-semibold text-slate-800 mt-1">{{ $overdue->borrower->name }} ({{ $overdue->borrower->id_number }})</p>
                        <p class="text-[11px] text-rose-700 mt-0.5">Due: {{ $overdue->expected_return_date->format('M d, Y h:i A') }}</p>
                        <a href="{{ route('borrowings.show', $overdue->id) }}" class="mt-2 text-[11px] text-blue-800 font-bold hover:underline inline-block">Process Return / View</a>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-slate-500 py-4 text-center">No overdue items currently recorded!</p>
            @endif
        </div>

        <!-- Campus Quick Info -->
        <div class="bg-slate-900 text-slate-300 rounded-2xl p-6 shadow-md border border-slate-800 text-xs space-y-3">
            <h3 class="font-bold text-amber-400 text-sm flex items-center">
                <i class="fa-solid fa-circle-info mr-2"></i> ISAT U BIND-Tech Regulations
            </h3>
            <p>1. Borrowers must submit online requests at least 1 day prior to workshop schedule.</p>
            <p>2. Tools released must be inspected for existing damage upon handover.</p>
            <p>3. Overdue items will suspend borrower privileges until returned and cleared.</p>
        </div>

    </div>

</div>

@endsection
