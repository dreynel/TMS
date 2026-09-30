@extends('layouts.app')

@section('title', 'Tool Condition & Damage Audit Report')

@section('content')

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Tool Condition & Damage Audit Log</h1>
        <p class="text-xs text-slate-500 mt-1">Audit log tracking changes in physical condition, repairs, and returns.</p>
    </div>

    <div class="w-full sm:w-auto">
        <a href="{{ route('reports.condition_audit', ['print' => 1]) }}" target="_blank" class="w-full sm:w-auto justify-center bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs px-4 py-2.5 rounded-xl shadow transition-all flex items-center">
            <i class="fa-solid fa-print mr-2"></i> Print Official Report
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-6 overflow-hidden">
    @if(count($logs) > 0)
    <!-- Mobile Cards View -->
    <div class="block md:hidden space-y-4">
        @foreach($logs as $l)
        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-2.5 text-xs">
            <div class="flex justify-between items-start">
                <span class="font-mono font-bold text-blue-900 bg-blue-50 border border-blue-200 px-2.5 py-0.5 rounded text-[11px]">{{ $l->tool->asset_code ?? 'N/A' }}</span>
                <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold bg-slate-800 text-white">
                    {{ $l->action }}
                </span>
            </div>

            <div>
                <h3 class="font-bold text-slate-900 text-sm">{{ $l->tool->name ?? 'Deleted Tool' }}</h3>
                <p class="text-[11px] text-slate-500">{{ $l->remarks }}</p>
            </div>

            <div class="grid grid-cols-2 gap-2 bg-white p-2.5 rounded-lg border border-slate-100 text-[11px]">
                <div>
                    <span class="text-slate-400 uppercase text-[9px] block font-semibold">Prev Condition</span>
                    <span class="text-slate-600 font-medium">{{ $l->previous_condition ? $l->previous_condition->label() : 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 uppercase text-[9px] block font-semibold">New Condition</span>
                    @if($l->new_condition)
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $l->new_condition->badgeClass() }}">
                            {{ $l->new_condition->label() }}
                        </span>
                    @else
                        <span class="text-slate-400">N/A</span>
                    @endif
                </div>
            </div>

            <div class="flex justify-between items-center text-[10px] text-slate-400 pt-1 border-t border-slate-200">
                <span>By: <strong class="text-slate-600">{{ $l->user->name ?? 'System' }}</strong></span>
                <span>{{ $l->created_at->format('M d, Y h:i A') }}</span>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block table-responsive">
        <table class="w-full text-left text-xs border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase font-bold sticky top-0 z-10">
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
