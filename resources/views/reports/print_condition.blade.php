@extends('layouts.print')

@section('title', 'Tool Condition & Maintenance Official Audit Log')

@section('content')

<div class="mb-6">
    <h2 class="text-xl font-black text-slate-900 uppercase">Tool Condition & Maintenance Official Audit Log</h2>
    <p class="text-xs text-slate-500">Physical Equipment Condition & Return Inspection History - BIND-Tech Department</p>
</div>

<table class="w-full text-left text-xs border border-slate-300 border-collapse mb-8">
    <thead>
        <tr class="bg-slate-100 text-slate-800 border-b border-slate-300 uppercase font-bold">
            <th class="p-2 border border-slate-300">#</th>
            <th class="p-2 border border-slate-300">Asset Code</th>
            <th class="p-2 border border-slate-300">Tool Name</th>
            <th class="p-2 border border-slate-300">Action</th>
            <th class="p-2 border border-slate-300">New Condition</th>
            <th class="p-2 border border-slate-300">Logged By</th>
            <th class="p-2 border border-slate-300">Inspection Remarks</th>
            <th class="p-2 border border-slate-300">Timestamp</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-slate-200">
        @foreach($logs as $index => $l)
        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50' }}">
            <td class="p-2 border border-slate-300 text-center font-mono">{{ $index + 1 }}</td>
            <td class="p-2 border border-slate-300 font-mono font-bold text-slate-900">{{ $l->tool->asset_code ?? 'N/A' }}</td>
            <td class="p-2 border border-slate-300 font-bold text-slate-900">{{ $l->tool->name ?? 'Deleted Tool' }}</td>
            <td class="p-2 border border-slate-300 uppercase text-[10px] font-bold">{{ $l->action }}</td>
            <td class="p-2 border border-slate-300 font-semibold">{{ $l->new_condition ? $l->new_condition->label() : 'N/A' }}</td>
            <td class="p-2 border border-slate-300">{{ $l->user->name ?? 'System' }}</td>
            <td class="p-2 border border-slate-300 text-slate-600">{{ $l->remarks }}</td>
            <td class="p-2 border border-slate-300 text-slate-500">{{ $l->created_at->format('M d, Y h:i A') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
