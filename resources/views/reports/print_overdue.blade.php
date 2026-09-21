@extends('layouts.print')

@section('title', 'Overdue Unreturned Items Official Report')

@section('content')

<div class="mb-6">
    <h2 class="text-xl font-black text-rose-900 uppercase">Overdue Unreturned Items Report</h2>
    <p class="text-xs text-slate-500">Official Warning & Follow-Up List - BIND-Tech Department</p>
</div>

<table class="w-full text-left text-xs border border-slate-300 border-collapse mb-8">
    <thead>
        <tr class="bg-rose-100 text-rose-900 border-b border-slate-300 uppercase font-bold">
            <th class="p-2 border border-slate-300">#</th>
            <th class="p-2 border border-slate-300">Borrow Code</th>
            <th class="p-2 border border-slate-300">Borrower Name</th>
            <th class="p-2 border border-slate-300">ID & Dept</th>
            <th class="p-2 border border-slate-300">Contact Number</th>
            <th class="p-2 border border-slate-300">DueDate</th>
            <th class="p-2 border border-slate-300">Status</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-slate-200">
        @foreach($overdues as $index => $o)
        <tr class="bg-rose-50">
            <td class="p-2 border border-slate-300 text-center font-mono">{{ $index + 1 }}</td>
            <td class="p-2 border border-slate-300 font-mono font-bold text-rose-900">{{ $o->borrow_code }}</td>
            <td class="p-2 border border-slate-300 font-bold text-slate-900">{{ $o->borrower->name }}</td>
            <td class="p-2 border border-slate-300">{{ $o->borrower->id_number }} ({{ $o->borrower->department_course }})</td>
            <td class="p-2 border border-slate-300">{{ $o->borrower->phone ?? 'N/A' }}</td>
            <td class="p-2 border border-slate-300 font-bold text-rose-800">{{ $o->expected_return_date->format('M d, Y') }}</td>
            <td class="p-2 border border-slate-300 font-bold text-rose-700 uppercase">OVERDUE</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
