@extends('layouts.print')

@section('title', 'Tool Inventory Audit Report - ISAT U Dumangas')

@section('content')

<div class="mb-6">
    <h2 class="text-xl font-black text-slate-900 uppercase">Official Tool Inventory Audit Report</h2>
    <p class="text-xs text-slate-500">Master Stock Registry & Asset Location Log - BIND-Tech Department</p>
</div>

<table class="w-full text-left text-xs border border-slate-300 border-collapse mb-8">
    <thead>
        <tr class="bg-slate-100 text-slate-800 border-b border-slate-300 uppercase font-bold">
            <th class="p-2 border border-slate-300">#</th>
            <th class="p-2 border border-slate-300">Asset Code</th>
            <th class="p-2 border border-slate-300">Tool Name</th>
            <th class="p-2 border border-slate-300">Category</th>
            <th class="p-2 border border-slate-300">Brand / Model</th>
            <th class="p-2 border border-slate-300">Location Storage</th>
            <th class="p-2 border border-slate-300 text-center">Total</th>
            <th class="p-2 border border-slate-300 text-center">Available</th>
            <th class="p-2 border border-slate-300">Condition</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-slate-200">
        @foreach($tools as $index => $t)
        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50' }}">
            <td class="p-2 border border-slate-300 text-center font-mono">{{ $index + 1 }}</td>
            <td class="p-2 border border-slate-300 font-mono font-bold text-slate-900">{{ $t->asset_code }}</td>
            <td class="p-2 border border-slate-300 font-bold text-slate-900">{{ $t->name }}</td>
            <td class="p-2 border border-slate-300">{{ $t->category->name }}</td>
            <td class="p-2 border border-slate-300">{{ $t->brand_model ?? 'N/A' }}</td>
            <td class="p-2 border border-slate-300">{{ $t->location_storage }}</td>
            <td class="p-2 border border-slate-300 text-center font-bold">{{ $t->total_qty }}</td>
            <td class="p-2 border border-slate-300 text-center font-bold text-emerald-800">{{ $t->available_qty }}</td>
            <td class="p-2 border border-slate-300 font-semibold uppercase text-[10px]">{{ $t->condition->label() }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
