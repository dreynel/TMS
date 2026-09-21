@extends('layouts.app')

@section('title', 'Tool Asset Details - ' . $tool->asset_code)

@section('content')

<!-- Back Link & Action Bar -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <a href="{{ route('tools.index') }}" class="text-xs font-bold text-slate-600 hover:text-blue-900 flex items-center">
        <i class="fa-solid fa-arrow-left mr-2"></i> Back to Tool Inventory
    </a>

    @if(auth()->user()->isAdmin() || auth()->user()->isCustodian())
    <div class="flex space-x-2">
        <a href="{{ route('tools.edit', $tool->id) }}" class="bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs px-4 py-2 rounded-xl shadow transition-all flex items-center">
            <i class="fa-solid fa-pen-to-square mr-1.5"></i> Edit Tool Info
        </a>
        <form action="{{ route('tools.destroy', $tool->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this tool record from BIND-Tech inventory?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs px-4 py-2 rounded-xl shadow transition-all flex items-center">
                <i class="fa-solid fa-trash mr-1.5"></i> Delete
            </button>
        </form>
    </div>
    @endif
</div>

<!-- Main Details Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Left Column: Primary Specifications -->
    <div class="lg:col-span-2 space-y-6">
        
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <span class="font-mono text-xs font-bold text-blue-900 bg-blue-50 border border-blue-200 px-3 py-1 rounded-lg inline-block mb-2">
                        {{ $tool->asset_code }}
                    </span>
                    <h1 class="text-2xl font-black text-slate-900 leading-tight">{{ $tool->name }}</h1>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Category: {{ $tool->category->name }}</p>
                </div>
                <div class="text-right space-y-2">
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold border block {{ $tool->status->badgeClass() }}">
                        {{ $tool->status->label() }}
                    </span>
                    <span class="px-3 py-1 rounded text-xs font-bold border block {{ $tool->condition->badgeClass() }}">
                        {{ $tool->condition->label() }}
                    </span>
                </div>
            </div>

            <!-- Detailed Specifications List -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 my-6 bg-slate-50 p-4 rounded-xl border border-slate-100 text-xs">
                <div>
                    <span class="text-slate-400 font-bold uppercase block text-[10px]">Brand / Model</span>
                    <span class="font-bold text-slate-800 text-sm">{{ $tool->brand_model ?? 'N/A' }}</span>
                </div>

                <div>
                    <span class="text-slate-400 font-bold uppercase block text-[10px]">Serial Number</span>
                    <span class="font-mono font-bold text-slate-800 text-sm">{{ $tool->serial_number ?? 'N/A' }}</span>
                </div>

                <div>
                    <span class="text-slate-400 font-bold uppercase block text-[10px]">Storage / Cabinet Location</span>
                    <span class="font-bold text-amber-700 text-sm flex items-center mt-0.5">
                        <i class="fa-solid fa-location-dot mr-1.5 text-amber-500"></i> {{ $tool->location_storage }}
                    </span>
                </div>

                <div>
                    <span class="text-slate-400 font-bold uppercase block text-[10px]">Stock Availability</span>
                    <span class="font-extrabold text-slate-900 text-sm">
                        <span class="{{ $tool->available_qty > 0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $tool->available_qty }} Available</span> / {{ $tool->total_qty }} Total Units
                    </span>
                </div>
            </div>

            <!-- Description -->
            <div>
                <h4 class="text-xs font-bold text-slate-500 uppercase mb-1">Equipment Description & Notes</h4>
                <p class="text-xs text-slate-700 leading-relaxed bg-white p-3 rounded-lg border border-slate-200">
                    {{ $tool->description ?? 'No additional description provided.' }}
                </p>
            </div>
        </div>

        <!-- Audit Logs / Condition History -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center">
                <i class="fa-solid fa-clock-rotate-left text-blue-600 mr-2"></i> Condition Audit & Event History
            </h3>

            @if(count($tool->logs) > 0)
                <div class="space-y-3">
                    @foreach($tool->logs as $log)
                    <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100 text-xs flex justify-between items-start">
                        <div>
                            <div class="font-bold text-slate-800 capitalize flex items-center">
                                <span class="bg-blue-900 text-white text-[10px] px-2 py-0.5 rounded mr-2 uppercase">{{ $log->action }}</span>
                                {{ $log->remarks }}
                            </div>
                            <div class="text-[11px] text-slate-500 mt-1">
                                Logged by: <span class="font-semibold text-slate-700">{{ $log->user->name ?? 'System' }}</span>
                            </div>
                        </div>
                        <div class="text-right text-[11px] text-slate-400">
                            {{ $log->created_at->format('M d, Y h:i A') }}
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-slate-500 py-4 text-center">No previous condition changes logged.</p>
            @endif
        </div>

    </div>

    <!-- Right Column: Quick Action Box -->
    <div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sticky top-20 space-y-4">
            <h3 class="text-sm font-bold text-slate-900">Borrowing Availability</h3>
            
            @if($tool->available_qty > 0 && $tool->status->value === 'available')
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-800">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-lg mr-2"></i>
                    <span class="font-bold">Ready for Workshop Borrowing</span>
                    <p class="mt-1 text-slate-600">This tool has {{ $tool->available_qty }} available unit(s) ready to be requested.</p>
                </div>

                @if(auth()->user()->isBorrower())
                    @if(auth()->user()->is_approved)
                    <a href="{{ route('borrowings.create') }}" class="w-full bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold py-3 px-4 rounded-xl shadow-md transition-all flex items-center justify-center text-xs">
                        <i class="fa-solid fa-plus-circle mr-2"></i> Submit Borrow Request
                    </a>
                    @else
                    <button disabled class="w-full bg-slate-300 text-slate-600 font-bold py-3 px-4 rounded-xl text-xs cursor-not-allowed">
                        Pending Account Approval
                    </button>
                    @endif
                @endif
            @else
                <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-800">
                    <i class="fa-solid fa-circle-xmark text-rose-600 text-lg mr-2"></i>
                    <span class="font-bold">Currently Unavailable</span>
                    <p class="mt-1 text-slate-600">All units are currently borrowed, under maintenance, or damaged.</p>
                </div>
            @endif

        </div>
    </div>

</div>

@endsection
