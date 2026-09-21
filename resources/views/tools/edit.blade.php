@extends('layouts.app')

@section('title', 'Edit Tool Asset - ' . $tool->asset_code)

@section('content')

<div class="max-w-3xl mx-auto bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
    
    <div class="flex items-center justify-between mb-6 border-b border-slate-100 pb-4">
        <div>
            <h1 class="text-xl font-black text-slate-900">Edit Tool Asset [{{ $tool->asset_code }}]</h1>
            <p class="text-xs text-slate-500 mt-0.5">BIND-Tech Inventory Central Registry</p>
        </div>
        <a href="{{ route('tools.show', $tool->id) }}" class="text-xs font-bold text-slate-600 hover:text-blue-900">
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

    <form action="{{ route('tools.update', $tool->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Asset Tag / Code</label>
                <input type="text" value="{{ $tool->asset_code }}" disabled
                    class="w-full px-4 py-2.5 bg-slate-100 border border-slate-300 rounded-xl text-xs font-mono font-bold text-slate-600 cursor-not-allowed">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tool Name *</label>
                <input type="text" name="name" value="{{ old('name', $tool->name) }}" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Category *</label>
                <select name="category_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $tool->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Brand & Model</label>
                <input type="text" name="brand_model" value="{{ old('brand_model', $tool->brand_model) }}"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Serial Number</label>
                <input type="text" name="serial_number" value="{{ old('serial_number', $tool->serial_number) }}"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono focus:ring-2 focus:ring-blue-600">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Storage Location *</label>
                <input type="text" name="location_storage" value="{{ old('location_storage', $tool->location_storage) }}" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Total Quantity *</label>
                <input type="number" name="total_qty" value="{{ old('total_qty', $tool->total_qty) }}" min="1" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Availability Status *</label>
                <select name="status" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
                    @foreach($statuses as $st)
                        <option value="{{ $st->value }}" {{ old('status', $tool->status->value) == $st->value ? 'selected' : '' }}>{{ $st->label() }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tool Condition *</label>
                <select name="condition" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
                    @foreach($conditions as $cond)
                        <option value="{{ $cond->value }}" {{ old('condition', $tool->condition->value) == $cond->value ? 'selected' : '' }}>{{ $cond->label() }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Description & Notes</label>
            <textarea name="description" rows="3"
                class="w-full p-4 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">{{ old('description', $tool->description) }}</textarea>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end space-x-3">
            <a href="{{ route('tools.show', $tool->id) }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-blue-900 hover:bg-blue-950 text-white font-bold text-xs rounded-xl shadow-md">
                Update Asset Record
            </button>
        </div>

    </form>

</div>

@endsection
