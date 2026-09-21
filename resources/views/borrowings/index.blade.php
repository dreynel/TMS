@extends('layouts.app')

@section('title', 'Borrowing Requests & Logs')

@section('content')

<!-- Header & Request Button -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Borrowing Requests & Transactions</h1>
        <p class="text-xs text-slate-500 mt-1">Track tool borrowing requests, custodian releases, returns, and overdue items.</p>
    </div>

    @if(auth()->user()->isBorrower())
    <a href="{{ route('borrowings.create') }}" class="bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs px-4 py-2.5 rounded-xl shadow transition-all flex items-center">
        <i class="fa-solid fa-plus-circle mr-2"></i> Submit New Borrow Request
    </a>
    @endif
</div>

<!-- Status Filter Tabs -->
<div class="bg-white rounded-2xl border border-slate-200 p-3 shadow-sm mb-6 flex flex-wrap gap-2 text-xs">
    <a href="{{ route('borrowings.index') }}" class="px-3.5 py-2 rounded-xl font-bold transition-all {{ empty($status) ? 'bg-slate-900 text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
        All Requests
    </a>
    @foreach($statuses as $st)
        <a href="{{ route('borrowings.index', ['status' => $st->value]) }}" class="px-3.5 py-2 rounded-xl font-bold transition-all {{ $status === $st->value ? 'bg-slate-900 text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
            {{ $st->label() }}
        </a>
    @endforeach
</div>

<!-- Borrowings Table -->
@if(count($borrowings) > 0)
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase font-bold">
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
                <tr class="hover:bg-slate-50 transition-colors">
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
                        <a href="{{ route('borrowings.show', $b->id) }}" class="bg-blue-900 hover:bg-blue-950 text-white font-bold text-[11px] px-3.5 py-2 rounded-xl shadow inline-block">
                            View / Process
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $borrowings->links() }}
</div>

@else
<div class="bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-500 shadow-sm">
    <i class="fa-solid fa-hand-holding-hand text-5xl text-slate-300 mb-3"></i>
    <h3 class="text-base font-bold text-slate-700">No borrowing records found.</h3>
    <p class="text-xs text-slate-500 mt-1">Submit a new request or change filter status.</p>
</div>
@endif

@endsection
