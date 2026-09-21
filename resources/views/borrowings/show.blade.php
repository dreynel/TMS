@extends('layouts.app')

@section('title', 'Borrowing Transaction - ' . $borrowing->borrow_code)

@section('content')

<!-- Back Link & Title Header -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <a href="{{ route('borrowings.index') }}" class="text-xs font-bold text-slate-600 hover:text-blue-900 mb-2 inline-block">
            <i class="fa-solid fa-arrow-left mr-1"></i> Back to Borrowing List
        </a>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center">
            Transaction Code: <span class="font-mono text-blue-900 ml-2">{{ $borrowing->borrow_code }}</span>
        </h1>
    </div>

    <div>
        <span class="px-4 py-1.5 rounded-full text-xs font-extrabold border shadow-sm {{ $borrowing->status->badgeClass() }}">
            {{ $borrowing->status->label() }}
        </span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="{ showReturnModal: false }">
    
    <!-- Left Column: Details & Items -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Borrower & Request Info Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
            <h2 class="text-sm font-bold text-slate-900 uppercase border-b border-slate-100 pb-2">
                Borrower Profile & Request Info
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs bg-slate-50 p-4 rounded-xl border border-slate-100">
                <div>
                    <span class="text-slate-400 font-bold uppercase text-[10px]">Borrower Full Name</span>
                    <span class="font-bold text-slate-900 block text-sm">{{ $borrowing->borrower->name }}</span>
                    <span class="text-slate-500 font-mono">{{ $borrowing->borrower->id_number }} • {{ $borrowing->borrower->department_course }}</span>
                </div>

                <div>
                    <span class="text-slate-400 font-bold uppercase text-[10px]">Contact Email / Phone</span>
                    <span class="font-semibold text-slate-800 block">{{ $borrowing->borrower->email }}</span>
                    <span class="text-slate-500">{{ $borrowing->borrower->phone ?? 'No phone provided' }}</span>
                </div>

                <div>
                    <span class="text-slate-400 font-bold uppercase text-[10px]">Date Needed</span>
                    <span class="font-bold text-slate-800 block">{{ $borrowing->request_date->format('F d, Y - h:i A') }}</span>
                </div>

                <div>
                    <span class="text-slate-400 font-bold uppercase text-[10px]">Expected Return Date</span>
                    <span class="font-bold text-amber-700 block">{{ $borrowing->expected_return_date->format('F d, Y') }}</span>
                </div>
            </div>

            <div>
                <span class="text-slate-400 font-bold uppercase text-[10px] block mb-1">Purpose / Activity</span>
                <p class="text-xs text-slate-800 bg-white p-3 rounded-lg border border-slate-200">
                    {{ $borrowing->purpose }}
                </p>
            </div>

            @if($borrowing->custodian)
            <div class="text-xs text-slate-500 pt-2 border-t border-slate-100">
                Processed / Approved by: <span class="font-bold text-slate-800">{{ $borrowing->custodian->name }}</span>
            </div>
            @endif

            @if($borrowing->rejection_reason)
            <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-800">
                <span class="font-bold">Rejection Reason:</span> {{ $borrowing->rejection_reason }}
            </div>
            @endif
        </div>

        <!-- Requested Items Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h2 class="text-sm font-bold text-slate-900 uppercase mb-4">
                Requested Equipment & Return Condition Inspection
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase font-bold">
                            <th class="p-3">Asset Code</th>
                            <th class="p-3">Tool Name</th>
                            <th class="p-3">Location Storage</th>
                            <th class="p-3 text-center">Qty Requested</th>
                            <th class="p-3">Release Condition</th>
                            <th class="p-3">Return Condition</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($borrowing->items as $item)
                        <tr>
                            <td class="p-3 font-mono font-bold text-blue-900">{{ $item->tool->asset_code }}</td>
                            <td class="p-3 font-bold text-slate-900">{{ $item->tool->name }}</td>
                            <td class="p-3 text-slate-600">{{ $item->tool->location_storage }}</td>
                            <td class="p-3 text-center font-bold text-slate-800">{{ $item->quantity_requested }}</td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $item->condition_upon_release->badgeClass() }}">
                                    {{ $item->condition_upon_release->label() }}
                                </span>
                            </td>
                            <td class="p-3">
                                @if($item->condition_upon_return)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $item->condition_upon_return->badgeClass() }}">
                                        {{ $item->condition_upon_return->label() }}
                                    </span>
                                @else
                                    <span class="text-slate-400 font-normal">Pending Return</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Right Column: Custodian Action Panel -->
    <div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sticky top-20 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2 uppercase">
                Custodian Control Panel
            </h3>

            <!-- 1. If Pending -> Approve / Reject -->
            @if($borrowing->status->value === 'pending' && (auth()->user()->isAdmin() || auth()->user()->isCustodian()))
                <p class="text-xs text-slate-600">Review request purpose and verify stock availability before approving.</p>

                <form action="{{ route('borrowings.approve', $borrowing->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs shadow transition-all mb-2">
                        <i class="fa-solid fa-check-circle mr-1.5"></i> Approve Borrow Request
                    </button>
                </form>

                <form action="{{ route('borrowings.reject', $borrowing->id) }}" method="POST" class="space-y-2">
                    @csrf
                    <input type="text" name="rejection_reason" placeholder="Enter reason if rejecting..." required
                        class="w-full p-2 bg-slate-50 border border-slate-300 rounded-lg text-xs">
                    <button type="submit" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded-xl text-xs shadow transition-all">
                        <i class="fa-solid fa-times-circle mr-1.5"></i> Reject Request
                    </button>
                </form>
            @endif

            <!-- 2. If Approved / Pending -> Release / Checkout Tools -->
            @if(in_array($borrowing->status->value, ['pending', 'approved']) && (auth()->user()->isAdmin() || auth()->user()->isCustodian()))
                <div class="pt-2 border-t border-slate-100">
                    <form action="{{ route('borrowings.release', $borrowing->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-blue-900 hover:bg-blue-950 text-white font-bold py-3 px-4 rounded-xl text-xs shadow-md transition-all">
                            <i class="fa-solid fa-hand-holding-medical mr-1.5 text-amber-400"></i> Release / Handover Tools Now
                        </button>
                    </form>
                </div>
            @endif

            <!-- 3. If Released / Overdue -> Process Tool Return -->
            @if(in_array($borrowing->status->value, ['released', 'overdue']) && (auth()->user()->isAdmin() || auth()->user()->isCustodian()))
                <button @click="showReturnModal = true" class="w-full bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold py-3 px-4 rounded-xl text-xs shadow-md transition-all">
                    <i class="fa-solid fa-rotate-left mr-1.5"></i> Process Tool Return & Condition Inspection
                </button>
            @endif

            <!-- If Already Returned -->
            @if($borrowing->status->value === 'returned')
                <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-800 font-bold text-center">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-lg mr-1"></i> Transaction Complete & Restocked
                </div>
            @endif
        </div>
    </div>

    <!-- Return Modal -->
    <div x-show="showReturnModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl border border-slate-200" @click.away="showReturnModal = false">
            <h3 class="text-base font-bold text-slate-900 mb-2">Process Return & Inspect Condition</h3>
            <p class="text-xs text-slate-500 mb-4">Record tool physical condition upon return before restocking into inventory.</p>

            <form action="{{ route('borrowings.return', $borrowing->id) }}" method="POST" class="space-y-4">
                @csrf
                
                @foreach($borrowing->items as $item)
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                    <div class="font-bold text-xs text-slate-900">{{ $item->tool->name }} [{{ $item->tool->asset_code }}]</div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase">Return Condition</label>
                            <select name="items[{{ $item->id }}][condition]" required class="w-full p-2 bg-white border border-slate-300 rounded-lg text-xs font-semibold">
                                @foreach($toolConditions as $cond)
                                    <option value="{{ $cond->value }}" {{ $cond->value === 'good' ? 'selected' : '' }}>{{ $cond->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase">Inspection Remarks</label>
                            <input type="text" name="items[{{ $item->id }}][notes]" placeholder="e.g. Returned in good condition"
                                class="w-full p-2 bg-white border border-slate-300 rounded-lg text-xs">
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="pt-2 flex justify-end space-x-2">
                    <button type="button" @click="showReturnModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold text-xs rounded-xl">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-blue-900 text-white font-bold text-xs rounded-xl shadow">
                        Confirm Return & Restock Stock
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection
