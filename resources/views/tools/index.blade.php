@extends('layouts.app')

@section('title', 'Tool Inventory Catalog')

@section('content')

<!-- Header & Action Button -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">BIND-Tech Tool Inventory Catalog</h1>
        <p class="text-xs text-slate-500 mt-1">Centralized registry of workshop tools, instruments, and equipment.</p>
    </div>

    @if(auth()->user()->isAdmin() || auth()->user()->isCustodian())
    <a href="{{ route('tools.create') }}" class="bg-blue-900 hover:bg-blue-950 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow transition-all flex items-center">
        <i class="fa-solid fa-plus-circle mr-2 text-amber-400"></i> Register New Tool Asset
    </a>
    @endif
</div>

<!-- Search & Filter Card -->
<div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm mb-6">
    <form action="{{ route('tools.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
        
        <!-- Search Input -->
        <div class="md:col-span-2">
            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Search Keywords</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text" name="search" value="{{ $search }}"
                    class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:bg-white"
                    placeholder="Search asset code, tool name, brand...">
            </div>
        </div>

        <!-- Category Filter -->
        <div>
            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Category</label>
            <select name="category_id" class="w-full py-2 px-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Status Filter -->
        <div>
            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Availability Status</label>
            <select name="status" class="w-full py-2 px-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
                <option value="">All Statuses</option>
                @foreach($statuses as $st)
                    <option value="{{ $st->value }}" {{ $status == $st->value ? 'selected' : '' }}>{{ $st->label() }}</option>
                @endforeach
            </select>
        </div>

        <!-- Submit Filter Button -->
        <div class="flex items-end space-x-2">
            <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-2 px-3 rounded-xl text-xs transition-all shadow">
                Filter
            </button>
            <a href="{{ route('tools.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-2 px-3 rounded-xl text-xs transition-all text-center">
                Reset
            </a>
        </div>

    </form>
</div>

<!-- Tool Grid -->
@if(count($tools) > 0)
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    @foreach($tools as $tool)
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all flex flex-col justify-between overflow-hidden">
        
        <div class="p-5">
            <!-- Header Badge & Asset Code -->
            <div class="flex justify-between items-start mb-3">
                <span class="font-mono text-xs font-bold text-blue-900 bg-blue-50 border border-blue-200 px-2.5 py-1 rounded-lg">
                    {{ $tool->asset_code }}
                </span>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold border {{ $tool->status->badgeClass() }}">
                    {{ $tool->status->label() }}
                </span>
            </div>

            <!-- Title & Model -->
            <h3 class="text-base font-bold text-slate-900 leading-tight hover:text-blue-700 transition-colors">
                <a href="{{ route('tools.show', $tool->id) }}">{{ $tool->name }}</a>
            </h3>
            <p class="text-xs text-slate-500 mt-1">
                <i class="fa-solid fa-tag text-slate-400 mr-1"></i> {{ $tool->category->name }}
                @if($tool->brand_model)
                     • {{ $tool->brand_model }}
                @endif
            </p>

            <!-- Physical Storage Location -->
            <div class="mt-3 text-xs text-slate-600 bg-slate-50 p-2.5 rounded-xl border border-slate-100 flex items-center">
                <i class="fa-solid fa-location-dot text-amber-500 mr-2 text-sm"></i>
                <span class="truncate font-medium">{{ $tool->location_storage }}</span>
            </div>

            <!-- Description snippet -->
            @if($tool->description)
                <p class="text-xs text-slate-500 mt-3 line-clamp-2">{{ $tool->description }}</p>
            @endif
        </div>

        <!-- Footer Stock & Condition Bar -->
        <div class="bg-slate-50 px-5 py-3 border-t border-slate-100 flex justify-between items-center text-xs">
            <div>
                <span class="text-slate-400 text-[11px] block font-semibold">Available Stock</span>
                <span class="font-extrabold {{ $tool->available_qty > 0 ? 'text-emerald-700' : 'text-rose-600' }}">
                    {{ $tool->available_qty }} of {{ $tool->total_qty }} Units
                </span>
            </div>

            <div class="flex items-center space-x-2">
                <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $tool->condition->badgeClass() }}">
                    {{ $tool->condition->label() }}
                </span>
                <a href="{{ route('tools.show', $tool->id) }}" class="bg-blue-900 hover:bg-blue-950 text-white text-[11px] font-bold px-3 py-1.5 rounded-lg shadow">
                    View
                </a>
            </div>
        </div>

    </div>
    @endforeach
</div>

<!-- Pagination Links -->
<div class="mt-6">
    {{ $tools->links() }}
</div>

@else
<div class="bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-500 shadow-sm">
    <i class="fa-solid fa-toolbox text-5xl text-slate-300 mb-3"></i>
    <h3 class="text-base font-bold text-slate-700">No tools found matching your criteria.</h3>
    <p class="text-xs text-slate-500 mt-1">Try resetting search filters or adding new tools to inventory.</p>
</div>
@endif

@endsection
