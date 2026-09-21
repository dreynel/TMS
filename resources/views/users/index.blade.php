@extends('layouts.app')

@section('title', 'User Accounts & Borrower Approvals')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-black text-slate-900 tracking-tight">User Account Management & Approvals</h1>
    <p class="text-xs text-slate-500 mt-1">Review student & faculty borrower account registrations and manage system roles.</p>
</div>

<!-- Pending Borrower Approval Queue -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-8">
    <h2 class="text-base font-bold text-slate-900 mb-4 flex items-center">
        <i class="fa-solid fa-user-clock text-amber-500 mr-2"></i> Pending Borrower Approval Queue
    </h2>

    @if(count($pendingUsers) > 0)
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase font-bold">
                    <th class="p-3">Full Name</th>
                    <th class="p-3">Student / Employee ID</th>
                    <th class="p-3">Department / Course</th>
                    <th class="p-3">Email & Contact</th>
                    <th class="p-3 text-right">Approval Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-medium">
                @foreach($pendingUsers as $user)
                <tr class="hover:bg-slate-50">
                    <td class="p-3 font-bold text-slate-900">{{ $user->name }}</td>
                    <td class="p-3 font-mono text-blue-900 font-bold">{{ $user->id_number }}</td>
                    <td class="p-3 text-slate-700">{{ $user->department_course }}</td>
                    <td class="p-3 text-slate-600">{{ $user->email }} @if($user->phone)<br><span class="text-[11px] text-slate-400">{{ $user->phone }}</span>@endif</td>
                    <td class="p-3 text-right space-x-1">
                        <form action="{{ route('users.approve', $user->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] px-3 py-1.5 rounded-lg shadow">
                                <i class="fa-solid fa-check mr-1"></i> Approve Borrower
                            </button>
                        </form>
                        <form action="{{ route('users.reject', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Reject and delete this registration?')">
                            @csrf
                            <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-bold text-[11px] px-3 py-1.5 rounded-lg shadow">
                                <i class="fa-solid fa-xmark mr-1"></i> Reject
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
        <p class="text-xs text-slate-500 py-6 text-center">No pending borrower registrations requiring approval.</p>
    @endif
</div>

<!-- All Registered Users Table -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
    <h2 class="text-base font-bold text-slate-900 mb-4 flex items-center">
        <i class="fa-solid fa-users text-blue-600 mr-2"></i> All Registered Accounts
    </h2>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase font-bold">
                    <th class="p-3">User Name</th>
                    <th class="p-3">ID Number</th>
                    <th class="p-3">Email</th>
                    <th class="p-3">Role</th>
                    <th class="p-3">Approval Status</th>
                    <th class="p-3 text-right">Change Role</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-medium">
                @foreach($allUsers as $u)
                <tr class="hover:bg-slate-50">
                    <td class="p-3 font-bold text-slate-900">{{ $u->name }}</td>
                    <td class="p-3 font-mono text-slate-700">{{ $u->id_number ?? 'N/A' }}</td>
                    <td class="p-3 text-slate-600">{{ $u->email }}</td>
                    <td class="p-3">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-800 border border-blue-200">
                            {{ $u->role->label() }}
                        </span>
                    </td>
                    <td class="p-3">
                        @if($u->is_approved)
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Approved</span>
                        @else
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">Pending</span>
                        @endif
                    </td>
                    <td class="p-3 text-right">
                        @if(auth()->user()->isAdmin())
                        <form action="{{ route('users.update-role', $u->id) }}" method="POST" class="inline-flex items-center space-x-1">
                            @csrf
                            @method('PUT')
                            <select name="role" class="p-1 bg-slate-50 border border-slate-300 rounded text-[11px]">
                                @foreach($roles as $r)
                                    <option value="{{ $r->value }}" {{ $u->role === $r ? 'selected' : '' }}>{{ $r->label() }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded">Save</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
