@extends('layouts.app')

@section('title', 'Borrowing Requests & Logs')

@section('content')

<div x-data="borrowingManager()" @keydown.escape.window="showRequestModal = false">

    <!-- Header & Request Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Borrowing Requests & Transactions</h1>
            <p class="text-xs text-slate-500 mt-1">Track tool borrowing requests, custodian releases, returns, and overdue items.</p>
        </div>

        @if(auth()->user()->isBorrower())
            @if(auth()->user()->is_approved)
            <button type="button" @click="openRequestModal()" class="bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs px-4 py-2.5 rounded-xl shadow-md transition-all flex items-center">
                <i class="fa-solid fa-plus-circle mr-2"></i> Submit New Borrow Request
            </button>
            @else
            <button type="button" @click="promptPendingApproval()" class="bg-slate-300 text-slate-600 font-bold text-xs px-4 py-2.5 rounded-xl shadow-sm transition-all flex items-center cursor-not-allowed">
                <i class="fa-solid fa-lock mr-2"></i> Account Pending Approval
            </button>
            @endif
        @endif
    </div>

    <!-- Status Filter Tabs (Scrollable on mobile) -->
    <div class="bg-white rounded-2xl border border-slate-200 p-2.5 sm:p-3 shadow-sm mb-6 flex items-center overflow-x-auto table-responsive gap-2 text-xs">
        <a href="{{ route('borrowings.index') }}" class="shrink-0 px-3.5 py-2 rounded-xl font-bold transition-all {{ empty($status) ? 'bg-slate-900 text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
            All Requests
        </a>
        @foreach($statuses as $st)
            <a href="{{ route('borrowings.index', ['status' => $st->value]) }}" class="shrink-0 px-3.5 py-2 rounded-xl font-bold transition-all {{ $status === $st->value ? 'bg-slate-900 text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                {{ $st->label() }}
            </a>
        @endforeach
    </div>

    <!-- Borrowings Data: Dual View (Mobile Cards + Desktop Table) -->
    @if(count($borrowings) > 0)
    
    <!-- 1. Mobile Cards View (Visible on screens < md) -->
    <div class="block md:hidden space-y-4 mb-6">
        @foreach($borrowings as $b)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 hover:shadow-md transition-all">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <span class="font-mono text-xs font-bold text-blue-900 bg-blue-50 border border-blue-200 px-2.5 py-1 rounded-lg">
                    {{ $b->borrow_code }}
                </span>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold border {{ $b->status->badgeClass() }}">
                    {{ $b->status->label() }}
                </span>
            </div>

            <div class="py-3 space-y-2 text-xs">
                <div>
                    <span class="text-slate-400 font-bold uppercase text-[10px] block">Borrower</span>
                    <span class="font-bold text-slate-900 text-sm">{{ $b->borrower->name }}</span>
                    <span class="text-slate-500 font-mono text-[11px] block">{{ $b->borrower->id_number }} • {{ $b->borrower->department_course }}</span>
                </div>

                <div>
                    <span class="text-slate-400 font-bold uppercase text-[10px] block">Items Requested</span>
                    <div class="space-y-1 mt-0.5">
                        @foreach($b->items as $item)
                            <div class="text-slate-800 font-semibold bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-100 flex items-center justify-between">
                                <span class="truncate mr-2">• {{ $item->tool->name }}</span>
                                <span class="text-blue-900 font-bold text-[11px] shrink-0 font-mono">{{ $item->quantity_requested }} qty</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 pt-1">
                    <div class="bg-slate-50 p-2 rounded-xl border border-slate-100">
                        <span class="text-slate-400 font-bold uppercase text-[9px] block">Request Date</span>
                        <span class="font-semibold text-slate-700 text-[11px]">{{ $b->request_date->format('M d, Y') }}</span>
                    </div>
                    <div class="bg-slate-50 p-2 rounded-xl border border-slate-100">
                        <span class="text-slate-400 font-bold uppercase text-[9px] block">Return Due</span>
                        <span class="font-bold text-amber-700 text-[11px]">{{ $b->expected_return_date->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100">
                <a href="{{ route('borrowings.show', $b->id) }}" class="w-full bg-blue-900 hover:bg-blue-950 text-white font-bold text-xs py-2.5 rounded-xl shadow transition-colors flex items-center justify-center">
                    <span>View / Process Transaction</span>
                    <i class="fa-solid fa-arrow-right ml-2 text-amber-400"></i>
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- 2. Desktop Table View (Visible on screens >= md) -->
    <div class="hidden md:block bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
        <div class="table-responsive">
            <table class="w-full text-left text-xs border-collapse min-w-[750px]">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase font-bold sticky top-0 z-10">
                        <th class="p-4">Borrow Code</th>
                        <th class="p-4">Borrower Details</th>
                        <th class="p-4">Items Requested</th>
                        <th class="p-4">Request Date</th>
                        <th class="p-4">Expected Return</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($borrowings as $b)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="p-4 font-mono font-bold text-blue-900">{{ $b->borrow_code }}</td>
                        <td class="p-4">
                            <div class="font-bold text-slate-900">{{ $b->borrower->name }}</div>
                            <div class="text-[11px] text-slate-500 font-mono">{{ $b->borrower->id_number }} • {{ $b->borrower->department_course }}</div>
                        </td>
                        <td class="p-4">
                            <div class="space-y-1">
                                @foreach($b->items as $item)
                                    <div class="text-slate-800 font-semibold">
                                        • {{ $item->tool->name }} <span class="text-slate-500 font-normal">({{ $item->quantity_requested }} qty)</span>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                        <td class="p-4 text-slate-600">{{ $b->request_date->format('M d, Y h:i A') }}</td>
                        <td class="p-4 text-slate-600 font-semibold">{{ $b->expected_return_date->format('M d, Y') }}</td>
                        <td class="p-4">
                            <span class="px-3 py-1 rounded-full text-[10px] font-extrabold border {{ $b->status->badgeClass() }}">
                                {{ $b->status->label() }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <a href="{{ route('borrowings.show', $b->id) }}" class="bg-blue-900 hover:bg-blue-950 text-white font-bold text-[11px] px-3.5 py-2 rounded-xl shadow inline-flex items-center">
                                <span>View / Process</span>
                                <i class="fa-solid fa-chevron-right ml-1.5 text-[10px] text-amber-400"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 mb-8">
        {{ $borrowings->links() }}
    </div>

    @else
    <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-500 shadow-sm">
        <i class="fa-solid fa-hand-holding-hand text-5xl text-slate-300 mb-3"></i>
        <h3 class="text-base font-bold text-slate-700">No borrowing records found.</h3>
        <p class="text-xs text-slate-500 mt-1">Submit a new request or change filter status.</p>
    </div>
    @endif

    <!-- MODAL: SUBMIT NEW BORROW REQUEST (Modular Modal) -->
    @if(auth()->user()->isBorrower())
    <div x-show="showRequestModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl max-w-3xl w-full p-6 sm:p-8 shadow-2xl border border-slate-200 max-h-[92vh] overflow-y-auto" @click.away="showRequestModal = false">
            
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <div>
                    <h2 class="text-lg font-black text-slate-900">Submit Tool Borrowing Request</h2>
                    <p class="text-xs text-slate-500">Workshop & laboratory activity equipment requisition</p>
                </div>
                <button type="button" @click="showRequestModal = false" class="text-slate-400 hover:text-slate-600 text-lg p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('borrowings.store') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Borrower Header Pill -->
                <div class="bg-blue-50 p-3 rounded-xl border border-blue-100 grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs">
                    <div>
                        <span class="text-slate-400 font-bold uppercase text-[10px] block">Borrower Name</span>
                        <span class="font-bold text-slate-900">{{ auth()->user()->name }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-bold uppercase text-[10px] block">ID Number</span>
                        <span class="font-mono font-bold text-blue-900">{{ auth()->user()->id_number }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-bold uppercase text-[10px] block">Department / Course</span>
                        <span class="font-bold text-slate-900">{{ auth()->user()->department_course }}</span>
                    </div>
                </div>

                <!-- Purpose & Dates -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Date Needed *</label>
                        <input type="datetime-local" name="request_date" value="{{ date('Y-m-d\TH:i') }}" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Expected Return Date *</label>
                        <input type="date" name="expected_return_date" value="{{ date('Y-m-d', strtotime('+1 day')) }}" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Purpose / Course Activity *</label>
                    <textarea name="purpose" rows="2" required placeholder="e.g. Laboratory Activity #3 for Circuit Assembly Workshop"
                        class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600"></textarea>
                </div>

                <!-- Dynamic Tool Items Selector -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-[11px] font-bold text-slate-900 uppercase">Select Tools to Borrow *</label>
                        <button type="button" @click="addRow()" class="bg-blue-900 hover:bg-blue-950 text-white font-bold text-xs px-2.5 py-1 rounded-lg shadow-sm">
                            <i class="fa-solid fa-plus mr-1"></i> Add Tool Item
                        </button>
                    </div>

                    <div class="space-y-2.5">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-200 flex flex-col sm:flex-row items-center gap-2.5">
                                <div class="flex-grow w-full">
                                    <select :name="'tools[' + index + '][tool_id]'" x-model="item.tool_id" required
                                        class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-semibold focus:ring-2 focus:ring-blue-600">
                                        <option value="">-- Choose Equipment / Instrument --</option>
                                        @foreach($availableTools as $tool)
                                            <option value="{{ $tool->id }}">
                                                [{{ $tool->asset_code }}] {{ $tool->name }} (Available: {{ $tool->available_qty }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="w-full sm:w-28">
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

                <div class="pt-3 border-t border-slate-100 flex justify-end space-x-2">
                    <button type="button" @click="showRequestModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs rounded-xl shadow-md">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>

@push('scripts')
<script>
    function borrowingManager() {
        return {
            showRequestModal: false,
            items: [
                { tool_id: '', quantity: 1 }
            ],
            openRequestModal() {
                this.showRequestModal = true;
            },
            addRow() {
                this.items.push({ tool_id: '', quantity: 1 });
            },
            removeRow(index) {
                if (this.items.length > 1) {
                    this.items.splice(index, 1);
                }
            },
            promptPendingApproval() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Account Pending Approval',
                    text: 'Your borrower profile is queued for verification by BIND-Tech Tool Custodians. Once approved, borrowing will be unlocked.',
                    confirmButtonColor: '#002B49'
                });
            }
        };
    }
</script>
@endpush

@endsection
