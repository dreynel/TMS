@extends('layouts.app')

@section('title', 'Overdue Items Report')

@section('content')

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Overdue Unreturned Items Report</h1>
        <p class="text-xs text-slate-500 mt-1">Audit of equipment past due date requiring custodian follow-up.</p>
    </div>

    <div class="flex space-x-2">
        <a href="{{ route('reports.overdue', ['print' => 1]) }}" target="_blank" class="bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs px-4 py-2.5 rounded-xl shadow transition-all flex items-center">
            <i class="fa-solid fa-print mr-2"></i> Print Official Report
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 overflow-hidden">
    @if(count($overdues) > 0)
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs border-collapse">
            <thead>
                <tr class="bg-rose-50 text-rose-800 border-b border-rose-200 uppercase font-bold">
                    <th class="p-3">Borrow Code</th>
                    <th class="p-3">Borrower Name</th>
                    <th class="p-3">ID Number</th>
                    <th class="p-3">Contact Email/Phone</th>
                    <th class="p-3">Expected Return Date</th>
                    <th class="p-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-medium">
                @foreach($overdues as $o)
                <tr class="hover:bg-rose-50/50">
                    <td class="p-3 font-mono font-bold text-rose-900">{{ $o->borrow_code }}</td>
                    <td class="p-3 font-bold text-slate-900">{{ $o->borrower->name }}</td>
                    <td class="p-3 font-mono text-slate-700">{{ $o->borrower->id_number }}</td>
                    <td class="p-3 text-slate-600">{{ $o->borrower->email }} ({{ $o->borrower->phone ?? 'N/A' }})</td>
                    <td class="p-3 font-bold text-rose-700">{{ $o->expected_return_date->format('M d, Y') }}</td>
                    <td class="p-3 text-right">
                        <a href="{{ route('borrowings.show', $o->id) }}" class="bg-rose-600 hover:bg-rose-700 text-white font-bold text-[11px] px-3 py-1.5 rounded-lg shadow">
                            Inspect & Return
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
        <p class="text-xs text-slate-500 py-8 text-center">No overdue items recorded.</p>
    @endif
</div>

@endsection
