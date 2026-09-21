@extends('layouts.app')

@section('title', 'Submit Tool Borrowing Request')

@section('content')

<div class="max-w-4xl mx-auto bg-white rounded-2xl border border-slate-200 shadow-sm p-8" x-data="borrowForm()">
    
    <div class="flex items-center justify-between mb-6 border-b border-slate-100 pb-4">
        <div>
            <h1 class="text-xl font-black text-slate-900">Submit Tool Borrowing Request</h1>
            <p class="text-xs text-slate-500 mt-0.5">BIND-Tech Tool Management - ISAT U Dumangas</p>
        </div>
        <a href="{{ route('borrowings.index') }}" class="text-xs font-bold text-slate-600 hover:text-blue-900">
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

    <form action="{{ route('borrowings.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Borrower Info Header -->
        <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
            <div>
                <span class="text-slate-400 font-bold uppercase text-[10px]">Borrower Name</span>
                <span class="font-bold text-slate-900 block">{{ auth()->user()->name }}</span>
            </div>
            <div>
                <span class="text-slate-400 font-bold uppercase text-[10px]">Student / ID Number</span>
                <span class="font-mono font-bold text-slate-900 block">{{ auth()->user()->id_number }}</span>
            </div>
            <div>
                <span class="text-slate-400 font-bold uppercase text-[10px]">Department / Course</span>
                <span class="font-bold text-slate-900 block">{{ auth()->user()->department_course }}</span>
            </div>
        </div>

        <!-- Purpose & Dates -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Date Needed *</label>
                <input type="datetime-local" name="request_date" value="{{ old('request_date', date('Y-m-d\TH:i')) }}" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Expected Return Date *</label>
                <input type="date" name="expected_return_date" value="{{ old('expected_return_date', date('Y-m-d', strtotime('+1 day'))) }}" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Purpose / Course Subject Activity *</label>
            <textarea name="purpose" rows="2" required
                class="w-full p-4 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600"
                placeholder="e.g. Laboratory Activity #3 for BIT-Electronics Circuit Assembly Workshop">{{ old('purpose') }}</textarea>
        </div>

        <!-- Tool Selection Section -->
        <div>
            <div class="flex justify-between items-center mb-3">
                <label class="block text-xs font-bold text-slate-900 uppercase">Select Tools to Borrow *</label>
                <button type="button" @click="addRow()" class="bg-blue-900 hover:bg-blue-950 text-white font-bold text-xs px-3 py-1.5 rounded-lg shadow">
                    <i class="fa-solid fa-plus mr-1"></i> Add Tool Item
                </button>
            </div>

            <div class="space-y-3">
                <template x-for="(item, index) in items" :key="index">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 flex flex-col sm:flex-row items-center gap-3">
                        <div class="flex-grow w-full">
                            <select :name="'tools[' + index + '][tool_id]'" x-model="item.tool_id" required
                                class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-semibold focus:ring-2 focus:ring-blue-600">
                                <option value="">-- Choose Tool / Equipment --</option>
                                @foreach($availableTools as $tool)
                                    <option value="{{ $tool->id }}">
                                        [{{ $tool->asset_code }}] {{ $tool->name }} (Available: {{ $tool->available_qty }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-full sm:w-32">
                            <input type="number" :name="'tools[' + index + '][quantity]'" x-model="item.quantity" min="1" required
                                class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-center font-bold"
                                placeholder="Qty">
                        </div>

                        <div>
                            <button type="button" @click="removeRow(index)" x-show="items.length > 1" class="text-rose-600 hover:text-rose-800 p-2">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end space-x-3">
            <a href="{{ route('borrowings.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs rounded-xl shadow-md">
                Submit Request
            </button>
        </div>

    </form>

</div>

@push('scripts')
<script>
    function borrowForm() {
        return {
            items: [
                { tool_id: '', quantity: 1 }
            ],
            addRow() {
                this.items.push({ tool_id: '', quantity: 1 });
            },
            removeRow(index) {
                if (this.items.length > 1) {
                    this.items.splice(index, 1);
                }
            }
        }
    }
</script>
@endpush

@endsection
