@extends('layouts.app')

@section('title', 'Tool Condition & Damage Audit Report')

@section('content')

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Tool Condition & Damage Audit Log</h1>
        <p class="text-xs text-slate-500 mt-1">Audit log tracking changes in physical condition, repairs, and returns.</p>
    </div>

    <div class="flex space-x-2">
        <a href="{{ route('reports.condition_audit', ['print' => 1]) }}" target="_blank" class="bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs px-4 py-2.5 rounded-xl shadow transition-all flex items-center">
            <i class="fa-solid fa-print mr-2"></i> Print Official Report
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 overflow-hidden">
    @if(count($logs) > 0)
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase font-bold">
                    <th class="p-3">Asset Code</th>
                    <th class="p-3">Tool Name</th>
                    <th class="p-3">Action</th>
                    <th class="p-3">Previous Condition</th>
                    <th class="p-3">New Condition</th>
                    <th class="p-3">Logged By</th>
                    <th class="p-3">Remarks</th>
                    <th class="p-3">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-medium">
                @foreach($logs as $l)
                <tr class="hover:bg-slate-50">
                    <td class="p-3 font-mono font-bold text-blue-900">{{ $l->tool->asset_code ?? 'N/A' }}</td>
                    <td class="p-3 font-bold text-slate-900">{{ $l->tool->name ?? 'Deleted Tool' }}</td>
                    <td class="p-3">
                        <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold bg-slate-800 text-white">
                            {{ $l->action }}
                        </span>
                    </td>
                    <td class="p-3 text-slate-500">{{ $l->previous_condition ? $l->previous_condition->label() : 'N/A' }}</td>
                    <td class="p-3">
                        @if($l->new_condition)
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $l->new_condition->badgeClass() }}">
                                {{ $l->new_condition->label() }}
                            </span>
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="p-3 font-semibold text-slate-700">{{ $l->user->name ?? 'System' }}</td>
                    <td class="p-3 text-slate-600 truncate max-w-xs">{{ $l->remarks }}</td>
                    <td class="p-3 text-slate-500">{{ $l->created_at->format('M d, Y h:i A') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
        <p class="text-xs text-slate-500 py-8 text-center">No tool condition log entries found.</p>
    @endif
</div>

@endsection
