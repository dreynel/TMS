@extends('layouts.app')

@section('title', 'Tool Inventory Report')

@section('content')

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Tool Inventory Audit Report</h1>
        <p class="text-xs text-slate-500 mt-1">BIND-Tech Master Tool Stock & Asset Location Report</p>
    </div>

    <div class="w-full sm:w-auto">
        <a href="{{ route('reports.inventory', array_merge(request()->all(), ['print' => 1])) }}" target="_blank" class="w-full sm:w-auto justify-center bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs px-4 py-2.5 rounded-xl shadow transition-all flex items-center">
            <i class="fa-solid fa-print mr-2"></i> Print Official Report
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-6 overflow-hidden">
    <!-- Mobile Cards View -->
    <div class="block md:hidden space-y-4">
        @foreach($tools as $t)
        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-2.5 text-xs">
            <div class="flex justify-between items-start">
                <span class="font-mono font-bold text-blue-900 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded text-[11px]">{{ $t->asset_code }}</span>
                <div class="flex gap-1">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $t->status->badgeClass() }}">
                        {{ $t->status->label() }}
                    </span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $t->condition->badgeClass() }}">
                        {{ $t->condition->label() }}
                    </span>
                </div>
            </div>

            <div>
                <h3 class="font-bold text-slate-900 text-sm">{{ $t->name }}</h3>
                <p class="text-[11px] text-slate-500">{{ $t->category->name }} @if($t->brand_model) • {{ $t->brand_model }}@endif</p>
            </div>

            <div class="text-slate-600 bg-white p-2 rounded-lg border border-slate-100 flex items-center text-[11px]">
                <i class="fa-solid fa-location-dot text-amber-500 mr-1.5"></i>
                <span>{{ $t->location_storage }}</span>
            </div>

            <div class="grid grid-cols-2 gap-2 pt-1">
                <div class="bg-white p-2 rounded-lg border border-slate-100 text-center">
                    <span class="text-slate-400 text-[10px] uppercase block font-semibold">Total Stock</span>
                    <span class="font-bold text-slate-800 text-xs">{{ $t->total_qty }} Units</span>
                </div>
                <div class="bg-white p-2 rounded-lg border border-slate-100 text-center">
                    <span class="text-slate-400 text-[10px] uppercase block font-semibold">Available</span>
                    <span class="font-extrabold text-xs {{ $t->available_qty > 0 ? 'text-emerald-700' : 'text-rose-600' }}">{{ $t->available_qty }} Units</span>
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
                    <th class="p-3">Asset Tag</th>
                    <th class="p-3">Tool Name</th>
                    <th class="p-3">Category</th>
                    <th class="p-3">Brand & Model</th>
                    <th class="p-3">Storage Location</th>
                    <th class="p-3 text-center">Total Qty</th>
                    <th class="p-3 text-center">Available Qty</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Condition</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-medium">
                @foreach($tools as $t)
                <tr class="hover:bg-slate-50">
                    <td class="p-3 font-mono font-bold text-blue-900">{{ $t->asset_code }}</td>
                    <td class="p-3 font-bold text-slate-900">{{ $t->name }}</td>
                    <td class="p-3 text-slate-700">{{ $t->category->name }}</td>
                    <td class="p-3 text-slate-600">{{ $t->brand_model ?? 'N/A' }}</td>
                    <td class="p-3 text-slate-600">{{ $t->location_storage }}</td>
                    <td class="p-3 text-center font-bold text-slate-800">{{ $t->total_qty }}</td>
                    <td class="p-3 text-center font-extrabold {{ $t->available_qty > 0 ? 'text-emerald-700' : 'text-rose-600' }}">{{ $t->available_qty }}</td>
                    <td class="p-3">
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $t->status->badgeClass() }}">
                            {{ $t->status->label() }}
                        </span>
                    </td>
                    <td class="p-3">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $t->condition->badgeClass() }}">
                            {{ $t->condition->label() }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
