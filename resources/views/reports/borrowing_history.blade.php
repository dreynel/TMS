@extends('layouts.app')

@section('title', 'Borrowing History Report')

@section('content')

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Borrowing History & Transaction Log</h1>
        <p class="text-xs text-slate-500 mt-1">Audit trail of equipment releases, return dates, and custodian approvals.</p>
    </div>

    <div class="w-full sm:w-auto">
        <a href="{{ route('reports.borrowing_history', array_merge(request()->all(), ['print' => 1])) }}" target="_blank" class="w-full sm:w-auto justify-center bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs px-4 py-2.5 rounded-xl shadow transition-all flex items-center">
            <i class="fa-solid fa-print mr-2"></i> Print Official Report
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-6 overflow-hidden">
    <!-- Mobile Cards View -->
    <div class="block md:hidden space-y-4">
        @foreach($borrowings as $b)
        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-2.5 text-xs">
            <div class="flex justify-between items-start">
                <span class="font-mono font-bold text-blue-900 bg-blue-50 border border-blue-200 px-2.5 py-0.5 rounded text-[11px]">{{ $b->borrow_code }}</span>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $b->status->badgeClass() }}">
                    {{ $b->status->label() }}
                </span>
            </div>

            <div>
                <h3 class="font-bold text-slate-900 text-sm">{{ $b->borrower->name }}</h3>
                <p class="text-[11px] text-slate-500 font-mono">{{ $b->borrower->id_number }} • {{ $b->borrower->department_course }}</p>
            </div>

            <div class="bg-white p-2.5 rounded-lg border border-slate-100 text-slate-700 text-[11px]">
                <span class="text-slate-400 font-bold uppercase text-[9px] block">Purpose</span>
                <p class="line-clamp-2 mt-0.5">{{ $b->purpose }}</p>
            </div>

            <div class="grid grid-cols-2 gap-2 pt-1 text-[11px]">
                <div class="bg-white p-2 rounded-lg border border-slate-100">
                    <span class="text-slate-400 uppercase text-[9px] block font-semibold">Borrowed Date</span>
                    <span class="font-semibold text-slate-700">{{ $b->request_date->format('M d, Y') }}</span>
                </div>
                <div class="bg-white p-2 rounded-lg border border-slate-100">
                    <span class="text-slate-400 uppercase text-[9px] block font-semibold">Returned Date</span>
                    <span class="font-semibold text-slate-700">{{ $b->returned_at ? $b->returned_at->format('M d, Y') : 'Pending Return' }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block table-responsive">
        <table class="w-full text-left text-xs border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase font-bold sticky top-0 z-10">
                    <th class="p-3">Borrow Code</th>
                    <th class="p-3">Borrower Name</th>
                    <th class="p-3">Department / ID</th>
                    <th class="p-3">Purpose</th>
                    <th class="p-3">Request Date</th>
                    <th class="p-3">Returned Date</th>
                    <th class="p-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-medium">
                @foreach($borrowings as $b)
                <tr class="hover:bg-slate-50">
                    <td class="p-3 font-mono font-bold text-blue-900">{{ $b->borrow_code }}</td>
                    <td class="p-3 font-bold text-slate-900">{{ $b->borrower->name }}</td>
                    <td class="p-3 text-slate-600">{{ $b->borrower->id_number }} • {{ $b->borrower->department_course }}</td>
                    <td class="p-3 text-slate-700 truncate max-w-xs">{{ $b->purpose }}</td>
                    <td class="p-3 text-slate-600">{{ $b->request_date->format('M d, Y') }}</td>
                    <td class="p-3 text-slate-600">{{ $b->returned_at ? $b->returned_at->format('M d, Y') : 'N/A' }}</td>
                    <td class="p-3">
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $b->status->badgeClass() }}">
                            {{ $b->status->label() }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
