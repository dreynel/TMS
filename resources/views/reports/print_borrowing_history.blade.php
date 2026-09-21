@extends('layouts.print')

@section('title', 'Borrowing History Audit Report')

@section('content')

<div class="mb-6">
    <h2 class="text-xl font-black text-slate-900 uppercase">Borrowing History & Transaction Audit Log</h2>
    <p class="text-xs text-slate-500">Official Tool Release & Return History - BIND-Tech Department</p>
</div>

<table class="w-full text-left text-xs border border-slate-300 border-collapse mb-8">
    <thead>
        <tr class="bg-slate-100 text-slate-800 border-b border-slate-300 uppercase font-bold">
            <th class="p-2 border border-slate-300">Borrow Code</th>
            <th class="p-2 border border-slate-300">Borrower Name</th>
            <th class="p-2 border border-slate-300">ID / Dept</th>
            <th class="p-2 border border-slate-300">Purpose</th>
            <th class="p-2 border border-slate-300">Request Date</th>
            <th class="p-2 border border-slate-300">Expected Return</th>
            <th class="p-2 border border-slate-300">Status</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-slate-200">
        @foreach($borrowings as $index => $b)
        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50' }}">
            <td class="p-2 border border-slate-300 font-mono font-bold text-slate-900">{{ $b->borrow_code }}</td>
            <td class="p-2 border border-slate-300 font-bold text-slate-900">{{ $b->borrower->name }}</td>
            <td class="p-2 border border-slate-300">{{ $b->borrower->id_number }} ({{ $b->borrower->department_course }})</td>
            <td class="p-2 border border-slate-300">{{ $b->purpose }}</td>
            <td class="p-2 border border-slate-300">{{ $b->request_date->format('M d, Y') }}</td>
            <td class="p-2 border border-slate-300">{{ $b->expected_return_date->format('M d, Y') }}</td>
            <td class="p-2 border border-slate-300 uppercase font-semibold text-[10px]">{{ $b->status->label() }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
