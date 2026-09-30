@extends('layouts.app')

@section('title', 'Tool Asset Details - ' . $tool->asset_code)

@section('content')

<div x-data="{ showEditModal: false }">

<!-- Back Link & Action Bar -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <a href="{{ route('tools.index') }}" class="text-xs font-bold text-slate-600 hover:text-blue-900 flex items-center">
        <i class="fa-solid fa-arrow-left mr-2"></i> Back to Tool Inventory
    </a>

    @if(auth()->user()->isAdmin() || auth()->user()->isCustodian())
    <div class="flex space-x-2">
        <button type="button" @click="showEditModal = true" class="bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs px-4 py-2 rounded-xl shadow transition-all flex items-center">
            <i class="fa-solid fa-pen-to-square mr-1.5"></i> Edit Tool Info
        </button>
        <form action="{{ route('tools.destroy', $tool->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="swalDelete(event, '{{ addslashes($tool->name) }} [{{ $tool->asset_code }}]')" class="bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs px-4 py-2 rounded-xl shadow transition-all flex items-center">
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

<!-- QUICK EDIT MODAL (Modular) -->
@if(auth()->user()->isAdmin() || auth()->user()->isCustodian())
<div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl border border-slate-200 max-h-[90vh] overflow-y-auto" @click.away="showEditModal = false">
        
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
            <div>
                <h2 class="text-lg font-black text-slate-900">
                    Edit Tool Asset: <span class="font-mono text-blue-900">{{ $tool->asset_code }}</span>
                </h2>
                <p class="text-xs text-slate-500">Update stock quantities, location, or equipment status</p>
            </div>
            <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 text-lg p-1">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('tools.update', $tool->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Asset Tag / Code</label>
                    <input type="text" value="{{ $tool->asset_code }}" disabled
                        class="w-full px-3 py-2 bg-slate-100 border border-slate-300 rounded-xl text-xs font-mono font-bold text-slate-500 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Tool Name *</label>
                    <input type="text" name="name" value="{{ old('name', $tool->name) }}" required
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Category *</label>
                    <select name="category_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $tool->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Brand & Model</label>
                    <input type="text" name="brand_model" value="{{ old('brand_model', $tool->brand_model) }}"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Serial Number</label>
                    <input type="text" name="serial_number" value="{{ old('serial_number', $tool->serial_number) }}"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Storage Location *</label>
                    <input type="text" name="location_storage" value="{{ old('location_storage', $tool->location_storage) }}" required
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Total Quantity *</label>
                    <input type="number" name="total_qty" value="{{ old('total_qty', $tool->total_qty) }}" min="1" required
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Status *</label>
                    <select name="status" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
                        @foreach($statuses as $st)
                            <option value="{{ $st->value }}" {{ old('status', $tool->status->value) == $st->value ? 'selected' : '' }}>{{ $st->label() }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Condition *</label>
                    <select name="condition" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
                        @foreach($conditions as $cond)
                            <option value="{{ $cond->value }}" {{ old('condition', $tool->condition->value) == $cond->value ? 'selected' : '' }}>{{ $cond->label() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Description & Notes</label>
                <textarea name="description" rows="2"
                    class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">{{ old('description', $tool->description) }}</textarea>
            </div>

            <div class="pt-3 border-t border-slate-100 flex justify-end space-x-2">
                <button type="button" @click="showEditModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-blue-900 hover:bg-blue-950 text-white font-bold text-xs rounded-xl shadow-md">
                    Update Asset
                </button>
            </div>
        </form>
    </div>
</div>
@endif

</div>

@endsection
