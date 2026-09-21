@extends('layouts.app')

@section('title', 'Register New Tool Asset')

@section('content')

<div class="max-w-3xl mx-auto bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
    
    <div class="flex items-center justify-between mb-6 border-b border-slate-100 pb-4">
        <div>
            <h1 class="text-xl font-black text-slate-900">Register New Tool Asset</h1>
            <p class="text-xs text-slate-500 mt-0.5">BIND-Tech Inventory Central Registry</p>
        </div>
        <a href="{{ route('tools.index') }}" class="text-xs font-bold text-slate-600 hover:text-blue-900">
            <i class="fa-solid fa-arrow-left mr-1"></i> Cancel
        </a>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 rounded text-rose-800 text-xs font-medium">
            <ul>
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tools.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Asset Tag / Code</label>
                <input type="text" name="asset_code" value="{{ old('asset_code') }}"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono font-bold focus:ring-2 focus:ring-blue-600"
                    placeholder="Leave blank to auto-generate (e.g., BIND-HT-001)">
                <span class="text-[10px] text-slate-400">Optional: System will auto-generate based on category code if blank.</span>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tool Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600"
                    placeholder="e.g. Digital Multimeter 1000V">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Category *</label>
                <select name="category_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
                    <option value="">-- Select Category --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }} ({{ $cat->code }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Brand & Model</label>
                <input type="text" name="brand_model" value="{{ old('brand_model') }}"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600"
                    placeholder="e.g. Fluke 117 / DeWalt DCD771">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Serial Number</label>
                <input type="text" name="serial_number" value="{{ old('serial_number') }}"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono focus:ring-2 focus:ring-blue-600"
                    placeholder="e.g. SN-884920">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Storage / Rack Location *</label>
                <input type="text" name="location_storage" value="{{ old('location_storage', 'BIND-Tech Main Cabinet') }}" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600"
                    placeholder="e.g. Cabinet A - Shelf 2">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Total Quantity *</label>
                <input type="number" name="total_qty" value="{{ old('total_qty', 1) }}" min="1" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Availability Status *</label>
                <select name="status" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
                    @foreach($statuses as $st)
                        <option value="{{ $st->value }}" {{ old('status') == $st->value ? 'selected' : '' }}>{{ $st->label() }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tool Condition *</label>
                <select name="condition" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
                    @foreach($conditions as $cond)
                        <option value="{{ $cond->value }}" {{ old('condition', 'good') == $cond->value ? 'selected' : '' }}>{{ $cond->label() }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tool Description & Technical Specs</label>
            <textarea name="description" rows="3"
                class="w-full p-4 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600"
                placeholder="Enter tool specs, safety instructions, or calibration details...">{{ old('description') }}</textarea>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end space-x-3">
            <a href="{{ route('tools.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-blue-900 hover:bg-blue-950 text-white font-bold text-xs rounded-xl shadow-md">
                Save & Register Asset
            </button>
        </div>

    </form>

</div>

@endsection
