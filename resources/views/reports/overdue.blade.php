@extends('layouts.app')

@section('title', 'Overdue Items Report')

@section('content')

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Overdue Unreturned Items Report</h1>
        <p class="text-xs text-slate-500 mt-1">Audit of equipment past due date requiring custodian follow-up.</p>
    </div>

    <div class="w-full sm:w-auto">
        <a href="{{ route('reports.overdue', ['print' => 1]) }}" target="_blank" class="w-full sm:w-auto justify-center bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs px-4 py-2.5 rounded-xl shadow transition-all flex items-center">
            <i class="fa-solid fa-print mr-2"></i> Print Official Report
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-6 overflow-hidden">
    @if(count($overdues) > 0)
    <!-- Mobile Cards View -->
    <div class="block md:hidden space-y-4">
        @foreach($overdues as $o)
        <div class="p-4 bg-rose-50/70 rounded-xl border border-rose-200 space-y-3 text-xs">
            <div class="flex justify-between items-start">
                <span class="font-mono font-bold text-rose-900 bg-rose-100 border border-rose-300 px-2.5 py-0.5 rounded text-[11px]">{{ $o->borrow_code }}</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-rose-600 text-white">
                    OVERDUE
                </span>
            </div>

            <div>
                <h3 class="font-bold text-slate-900 text-sm">{{ $o->borrower->name }}</h3>
                <p class="text-[11px] text-slate-600 font-mono">{{ $o->borrower->id_number }} • {{ $o->borrower->department_course ?? 'Student' }}</p>
                <p class="text-[11px] text-slate-500 mt-0.5">{{ $o->borrower->email }} @if($o->borrower->phone) ({{ $o->borrower->phone }})@endif</p>
            </div>

            <div class="bg-white p-2.5 rounded-lg border border-rose-200 flex justify-between items-center text-[11px]">
                <span class="text-slate-500 font-medium">Expected Return:</span>
                <span class="font-bold text-rose-700">{{ $o->expected_return_date->format('M d, Y') }}</span>
            </div>

            <div class="pt-1">
                <a href="{{ route('borrowings.show', $o->id) }}" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs py-2 rounded-xl shadow flex items-center justify-center">
                    Inspect & Return Equipment
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block table-responsive">
        <table class="w-full text-left text-xs border-collapse">
            <thead>
                <tr class="bg-rose-50 text-rose-800 border-b border-rose-200 uppercase font-bold sticky top-0 z-10">
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
                        <a href="{{ route('borrowings.show', $o->id) }}" class="bg-rose-600 hover:bg-rose-700 text-white font-bold text-[11px] px-3 py-1.5 rounded-lg shadow inline-block">
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
