@extends('layouts.app')

@section('title', 'Borrowing History Report')

@section('content')

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Borrowing History & Transaction Log</h1>
        <p class="text-xs text-slate-500 mt-1">Audit trail of equipment releases, return dates, and custodian approvals.</p>
    </div>

    <div class="flex space-x-2">
        <a href="{{ route('reports.borrowing_history', array_merge(request()->all(), ['print' => 1])) }}" target="_blank" class="bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs px-4 py-2.5 rounded-xl shadow transition-all flex items-center">
            <i class="fa-solid fa-print mr-2"></i> Print Official Report
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase font-bold">
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
