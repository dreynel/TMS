@extends('layouts.app')

@section('title', 'User Accounts & Borrower Approvals')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-black text-slate-900 tracking-tight">User Account Management & Approvals</h1>
    <p class="text-xs text-slate-500 mt-1">Review student & faculty borrower account registrations and manage system roles.</p>
</div>

<!-- Pending Borrower Approval Queue -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-6 mb-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm sm:text-base font-bold text-slate-900 flex items-center">
            <i class="fa-solid fa-user-clock text-amber-500 mr-2"></i> Pending Borrower Approval Queue
        </h2>
        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800">
            {{ count($pendingUsers) }} Pending
        </span>
    </div>

    @if(count($pendingUsers) > 0)
    <!-- Mobile Cards View -->
    <div class="block md:hidden space-y-4">
        @foreach($pendingUsers as $user)
        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="font-bold text-slate-900 text-sm">{{ $user->name }}</h3>
                    <span class="font-mono text-blue-900 font-bold text-xs">{{ $user->id_number }}</span>
                </div>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                    Pending
                </span>
            </div>

            <div class="text-xs text-slate-600 space-y-1">
                <div><span class="text-slate-400 font-bold uppercase text-[9px] block">Course / Dept:</span> {{ $user->department_course }}</div>
                <div><span class="text-slate-400 font-bold uppercase text-[9px] block">Contact:</span> {{ $user->email }} @if($user->phone) • {{ $user->phone }}@endif</div>
            </div>

            <div class="pt-2 border-t border-slate-200 flex gap-2">
                <form action="{{ route('users.approve', $user->id) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" onclick="confirmApproveUser(event, '{{ addslashes($user->name) }}')" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs py-2 rounded-xl shadow flex items-center justify-center">
                        <i class="fa-solid fa-check mr-1.5"></i> Approve
                    </button>
                </form>
                <form action="{{ route('users.reject', $user->id) }}" method="POST" class="shrink-0">
                    @csrf
                    <button type="submit" onclick="confirmRejectUser(event, '{{ addslashes($user->name) }}')" class="bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs px-3 py-2 rounded-xl shadow flex items-center justify-center">
                        <i class="fa-solid fa-xmark mr-1"></i> Reject
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block table-responsive">
        <table class="w-full text-left text-xs border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase font-bold sticky top-0 z-10">
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
                            <button type="submit" onclick="confirmApproveUser(event, '{{ addslashes($user->name) }}')" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] px-3 py-1.5 rounded-lg shadow">
                                <i class="fa-solid fa-check mr-1"></i> Approve Borrower
                            </button>
                        </form>
                        <form action="{{ route('users.reject', $user->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" onclick="confirmRejectUser(event, '{{ addslashes($user->name) }}')" class="bg-rose-600 hover:bg-rose-700 text-white font-bold text-[11px] px-3 py-1.5 rounded-lg shadow">
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
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm sm:text-base font-bold text-slate-900 flex items-center">
            <i class="fa-solid fa-users text-blue-600 mr-2"></i> All Registered Accounts
        </h2>
        <span class="text-xs text-slate-400 font-semibold">{{ count($allUsers) }} Total</span>
    </div>

    <!-- Mobile Registered Users Cards -->
    <div class="block md:hidden space-y-4">
        @foreach($allUsers as $u)
        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-2.5">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="font-bold text-slate-900 text-sm">{{ $u->name }}</h3>
                    <p class="text-[11px] text-slate-500 font-mono">{{ $u->id_number ?? 'No ID Number' }}</p>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-800 border border-blue-200">
                        {{ $u->role->label() }}
                    </span>
                    @if($u->is_approved)
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Approved</span>
                    @else
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">Pending</span>
                    @endif
                </div>
            </div>

            <p class="text-xs text-slate-600">{{ $u->email }}</p>

            @if(auth()->user()->isAdmin())
            <div class="pt-2 border-t border-slate-200">
                <span class="text-slate-400 font-bold uppercase text-[9px] block mb-1">Update System Role:</span>
                <form action="{{ route('users.update-role', $u->id) }}" method="POST" class="flex items-center gap-2">
                    @csrf
                    @method('PUT')
                    <select name="role" class="flex-1 py-1.5 px-2 bg-white border border-slate-300 rounded-lg text-xs font-semibold">
                        @foreach($roles as $r)
                            <option value="{{ $r->value }}" {{ $u->role === $r ? 'selected' : '' }}>{{ $r->label() }}</option>
                        @endforeach
                    </select>
                    <button type="submit" onclick="confirmRoleChange(event, '{{ addslashes($u->name) }}')" class="bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow shrink-0">
                        Save
                    </button>
                </form>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <!-- Desktop Registered Users Table -->
    <div class="hidden md:block table-responsive">
        <table class="w-full text-left text-xs border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase font-bold sticky top-0 z-10">
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
                            <select name="role" class="p-1 bg-slate-50 border border-slate-300 rounded text-[11px] font-semibold">
                                @foreach($roles as $r)
                                    <option value="{{ $r->value }}" {{ $u->role === $r ? 'selected' : '' }}>{{ $r->label() }}</option>
                                @endforeach
                            </select>
                            <button type="submit" onclick="confirmRoleChange(event, '{{ addslashes($u->name) }}')" class="bg-slate-800 hover:bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded">Save</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    function confirmApproveUser(event, userName) {
        event.preventDefault();
        const form = event.target.closest('form');
        Swal.fire({
            title: 'Approve Borrower Profile?',
            text: `Grant full borrowing access to ${userName}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#059669',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Approve',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed && form) {
                form.submit();
            }
        });
    }

    function confirmRejectUser(event, userName) {
        event.preventDefault();
        const form = event.target.closest('form');
        Swal.fire({
            title: 'Reject Registration?',
            text: `Reject and remove ${userName}'s registration request? This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Reject & Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed && form) {
                form.submit();
            }
        });
    }

    function confirmRoleChange(event, userName) {
        event.preventDefault();
        const form = event.target.closest('form');
        const select = form.querySelector('select[name="role"]');
        const roleName = select.options[select.selectedIndex].text;

        Swal.fire({
            title: 'Update Account Role?',
            text: `Change ${userName}'s role to "${roleName}"?`,
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#002B49',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Update Role',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed && form) {
                form.submit();
            }
        });
    }
</script>
@endpush

@endsection
