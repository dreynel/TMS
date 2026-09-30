@extends('layouts.app')

@section('title', 'Tool Inventory Catalog')

@section('content')

<div x-data="toolCatalogManager()" @keydown.escape.window="closeModals()">

    <!-- Header & Action Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">BIND-Tech Tool Inventory Catalog</h1>
            <p class="text-xs text-slate-500 mt-1">Centralized registry of workshop tools, instruments, and equipment.</p>
        </div>

        @if(auth()->user()->isAdmin() || auth()->user()->isCustodian())
        <button type="button" @click="openCreateModal()" class="bg-blue-900 hover:bg-blue-950 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-md transition-all flex items-center">
            <i class="fa-solid fa-plus-circle mr-2 text-amber-400"></i> Register New Tool Asset
        </button>
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

                <div class="flex items-center space-x-1.5">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold border mr-1 {{ $tool->condition->badgeClass() }}">
                        {{ $tool->condition->label() }}
                    </span>

                    <a href="{{ route('tools.show', $tool->id) }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 text-[11px] font-bold px-2.5 py-1.5 rounded-lg transition-colors" title="View Details">
                        <i class="fa-solid fa-eye"></i>
                    </a>

                    @if(auth()->user()->isAdmin() || auth()->user()->isCustodian())
                    <button type="button" @click='openEditModal(@json($tool))' class="bg-amber-400 hover:bg-amber-300 text-slate-950 text-[11px] font-bold px-2.5 py-1.5 rounded-lg transition-colors shadow-sm" title="Quick Edit">
                        <i class="fa-solid fa-pen"></i>
                    </button>

                    <form action="{{ route('tools.destroy', $tool->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="swalDelete(event, '{{ addslashes($tool->name) }} [{{ $tool->asset_code }}]')" class="bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 text-[11px] font-bold px-2.5 py-1.5 rounded-lg transition-colors" title="Delete Tool">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                    @endif
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
        <p class="text-xs text-slate-500 mt-1">Try resetting search filters or registering new tools into inventory.</p>
    </div>
    @endif

    <!-- MODAL 1: REGISTER NEW TOOL ASSET (Modular Modal) -->
    <div x-show="showCreateModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl border border-slate-200 max-h-[90vh] overflow-y-auto" @click.away="showCreateModal = false">
            
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <div>
                    <h2 class="text-lg font-black text-slate-900">Register New Tool Asset</h2>
                    <p class="text-xs text-slate-500">Add workshop equipment into BIND-Tech Inventory</p>
                </div>
                <button type="button" @click="showCreateModal = false" class="text-slate-400 hover:text-slate-600 text-lg p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('tools.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Asset Tag / Code</label>
                        <input type="text" name="asset_code" placeholder="Leave blank to auto-generate"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono font-bold focus:ring-2 focus:ring-blue-600">
                        <span class="text-[10px] text-slate-400">Auto-generated format: BIND-[Category]-001</span>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Tool Name *</label>
                        <input type="text" name="name" required placeholder="e.g. Digital Multimeter 1000V"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Category *</label>
                        <select name="category_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
                            <option value="">-- Choose Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Brand & Model</label>
                        <input type="text" name="brand_model" placeholder="e.g. Fluke 117 / DeWalt 20V"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Serial Number</label>
                        <input type="text" name="serial_number" placeholder="e.g. SN-884920"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono focus:ring-2 focus:ring-blue-600">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Storage Location *</label>
                        <input type="text" name="location_storage" value="BIND-Tech Main Cabinet" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Total Quantity *</label>
                        <input type="number" name="total_qty" value="1" min="1" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Initial Status *</label>
                        <select name="status" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
                            @foreach($statuses as $st)
                                <option value="{{ $st->value }}" {{ $st->value === 'available' ? 'selected' : '' }}>{{ $st->label() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Initial Condition *</label>
                        <select name="condition" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
                            @foreach($conditions as $cond)
                                <option value="{{ $cond->value }}" {{ $cond->value === 'good' ? 'selected' : '' }}>{{ $cond->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Tool Description & Technical Specs</label>
                    <textarea name="description" rows="2" placeholder="Enter specifications or special handling notes..."
                        class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600"></textarea>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end space-x-2">
                    <button type="button" @click="showCreateModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-blue-900 hover:bg-blue-950 text-white font-bold text-xs rounded-xl shadow-md">
                        Save Asset
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: QUICK EDIT TOOL ASSET (Modular Modal) -->
    <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl border border-slate-200 max-h-[90vh] overflow-y-auto" @click.away="showEditModal = false">
            
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <div>
                    <h2 class="text-lg font-black text-slate-900">
                        Edit Tool Asset: <span class="font-mono text-blue-900" x-text="editForm.asset_code"></span>
                    </h2>
                    <p class="text-xs text-slate-500">Update stock quantities, location, or equipment status</p>
                </div>
                <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 text-lg p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form :action="'/tools/' + editForm.id" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Asset Tag / Code</label>
                        <input type="text" x-model="editForm.asset_code" disabled
                            class="w-full px-3 py-2 bg-slate-100 border border-slate-300 rounded-xl text-xs font-mono font-bold text-slate-500 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Tool Name *</label>
                        <input type="text" name="name" x-model="editForm.name" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Category *</label>
                        <select name="category_id" x-model="editForm.category_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Brand & Model</label>
                        <input type="text" name="brand_model" x-model="editForm.brand_model"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Serial Number</label>
                        <input type="text" name="serial_number" x-model="editForm.serial_number"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono focus:ring-2 focus:ring-blue-600">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Storage Location *</label>
                        <input type="text" name="location_storage" x-model="editForm.location_storage" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Total Quantity *</label>
                        <input type="number" name="total_qty" x-model="editForm.total_qty" min="1" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Status *</label>
                        <select name="status" x-model="editForm.status" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
                            @foreach($statuses as $st)
                                <option value="{{ $st->value }}">{{ $st->label() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Condition *</label>
                        <select name="condition" x-model="editForm.condition" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
                            @foreach($conditions as $cond)
                                <option value="{{ $cond->value }}">{{ $cond->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Description & Notes</label>
                    <textarea name="description" x-model="editForm.description" rows="2"
                        class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600"></textarea>
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

</div>

@push('scripts')
<script>
    function toolCatalogManager() {
        return {
            showCreateModal: false,
            showEditModal: false,
            editForm: {
                id: null,
                asset_code: '',
                name: '',
                category_id: '',
                brand_model: '',
                serial_number: '',
                location_storage: '',
                total_qty: 1,
                status: 'available',
                condition: 'good',
                description: '',
            },
            openCreateModal() {
                this.showCreateModal = true;
            },
            openEditModal(tool) {
                this.editForm = {
                    id: tool.id,
                    asset_code: tool.asset_code,
                    name: tool.name,
                    category_id: tool.category_id,
                    brand_model: tool.brand_model || '',
                    serial_number: tool.serial_number || '',
                    location_storage: tool.location_storage,
                    total_qty: tool.total_qty,
                    status: tool.status?.value || tool.status,
                    condition: tool.condition?.value || tool.condition,
                    description: tool.description || '',
                };
                this.showEditModal = true;
            },
            closeModals() {
                this.showCreateModal = false;
                this.showEditModal = false;
            }
        };
    }
</script>
@endpush

@endsection
