<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="text-slate-400 font-medium">Organization</span>
            <span class="text-slate-300">/</span>
            <span class="font-bold text-slate-800">Leave Requests</span>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Leave Management</h1>
                <p class="text-sm text-slate-500 mt-1">
                    {{ auth()->user()->isStaffOrAdmin() ? 'Review, approve, and track employee time-off requests.' : 'Submit leave requests and monitor authorization status.' }}
                </p>
            </div>
            
            @if(!auth()->user()->isStaffOrAdmin())
            <div x-data="{ showModal: false }" class="w-full sm:w-auto">
                <button @click="showModal = true" class="btn-action-indigo text-xs py-2.5 px-4 shadow-sm w-full sm:w-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    Request Leave
                </button>

                <!-- New Leave Modal -->
                <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4">
                    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showModal = false"></div>
                    <div class="bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl w-full sm:max-w-md relative p-6 max-h-[90vh] overflow-y-auto">
                        <form action="{{ route('leaves.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <h3 class="text-lg font-bold text-slate-900 mb-5">Request Leave</h3>
                            
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Leave Type</label>
                                <select name="type" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-bold">
                                    <option value="Sick">Sick Leave</option>
                                    <option value="Vacation">Vacation</option>
                                    <option value="Personal">Personal Leave</option>
                                </select>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Start Date</label>
                                    <input type="date" name="start_date" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-semibold">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">End Date</label>
                                    <input type="date" name="end_date" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-semibold">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Reason (Optional)</label>
                                <textarea name="reason" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-semibold" placeholder="Briefly describe the reason..."></textarea>
                            </div>

                            <div class="pt-3 flex gap-3">
                                <button type="button" @click="showModal = false" class="flex-1 btn-action-secondary py-3 text-xs">Cancel</button>
                                <button type="submit" class="flex-1 btn-action-indigo py-3 text-xs">Submit Request</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div x-data="{ 
            selected: [],
            allSelected: false,
            toggleAll() {
                this.allSelected = !this.allSelected;
                if (this.allSelected) {
                    this.selected = Array.from(document.querySelectorAll('input[name=\'leave_ids[]\']')).map(el => el.value);
                } else {
                    this.selected = [];
                }
            }
        }" class="space-y-6">
            
            {{-- Bulk Actions Toolbar --}}
            @if(auth()->user()->isStaffOrAdmin())
            <div x-show="selected.length > 0" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-cloak
                 class="fixed bottom-8 left-1/2 -translate-x-1/2 z-[100] bg-slate-950/95 backdrop-blur-xl px-6 py-3.5 rounded-2xl shadow-2xl flex items-center gap-4 border border-slate-800">
                <div class="flex items-center gap-2 pr-4 border-r border-slate-800">
                    <span class="w-6 h-6 rounded-lg bg-indigo-600 flex items-center justify-center text-xs font-black text-white" x-text="selected.length"></span>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Selected</span>
                </div>
                <div class="flex items-center gap-2">
                    <form action="{{ route('leaves.bulk') }}" method="POST" class="inline">
                        @csrf
                        <template x-for="id in selected" :key="id">
                            <input type="hidden" name="leave_ids[]" :value="id">
                        </template>
                        <input type="hidden" name="status" value="Approved">
                        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition">
                            Approve
                        </button>
                    </form>
                    <form action="{{ route('leaves.bulk') }}" method="POST" class="inline">
                        @csrf
                        <template x-for="id in selected" :key="id">
                            <input type="hidden" name="leave_ids[]" :value="id">
                        </template>
                        <input type="hidden" name="status" value="Rejected">
                        <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition">
                            Reject
                        </button>
                    </form>
                    <button @click="selected = []; allSelected = false" class="text-xs font-bold text-slate-400 hover:text-white transition uppercase tracking-wider ml-2">
                        Cancel
                    </button>
                </div>
            </div>
            @endif

            <!-- Leaves Registry Card -->
            <div class="card-reference overflow-hidden bg-white/95 backdrop-blur-xl border border-slate-200/60 shadow-lg">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                        <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Time-off Registry</h3>
                    </div>
                    <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg uppercase tracking-wider">Active List</span>
                </div>

                {{-- Desktop Table --}}
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                @if(auth()->user()->isStaffOrAdmin())
                                    <th class="px-6 py-4 w-4">
                                        <input type="checkbox" @click="toggleAll" :checked="allSelected" class="rounded border-slate-200 text-indigo-600 focus:ring-indigo-500/20">
                                    </th>
                                    <th class="px-6 py-4">Employee</th>
                                @endif
                                <th class="px-6 py-4">Type</th>
                                <th class="px-6 py-4">Duration</th>
                                <th class="px-6 py-4">Status</th>
                                @if(auth()->user()->isStaffOrAdmin())
                                    <th class="px-6 py-4 text-right">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($leaves as $leave)
                                <tr class="hover:bg-indigo-50/10 transition group" :class="selected.includes('{{ $leave->id }}') ? 'bg-indigo-50/20' : ''">
                                    @if(auth()->user()->isStaffOrAdmin())
                                        <td class="px-6 py-4">
                                            @if($leave->status === 'Pending')
                                                <input type="checkbox" name="leave_ids[]" value="{{ $leave->id }}" x-model="selected" class="rounded border-slate-200 text-indigo-600 focus:ring-indigo-500/20">
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center font-black text-sm text-indigo-600 shadow-sm shrink-0">
                                                    {{ substr($leave->user->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-extrabold text-slate-900 tracking-tight">{{ $leave->user->name }}</div>
                                                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $leave->user->employee_id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                    @endif
                                    <td class="px-6 py-4">
                                        @if($leave->type === 'Sick')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-100 uppercase tracking-wider">Sick Leave</span>
                                        @elseif($leave->type === 'Vacation')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-indigo-50 text-indigo-600 border border-indigo-100 uppercase tracking-wider">Vacation</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-violet-50 text-violet-600 border border-violet-100 uppercase tracking-wider">Personal</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-extrabold text-slate-700">{{ $leave->start_date->format('M d') }} – {{ $leave->end_date->format('M d, Y') }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold uppercase mt-0.5 tracking-wider">{{ $leave->start_date->diffInDays($leave->end_date) + 1 }} day(s)</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($leave->status === 'Approved')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-wider">Approved</span>
                                        @elseif($leave->status === 'Rejected')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-100 uppercase tracking-wider">Rejected</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-amber-50 text-amber-650 border border-amber-100 uppercase tracking-wider animate-pulse">Pending</span>
                                        @endif
                                    </td>
                                    @if(auth()->user()->isStaffOrAdmin())
                                        <td class="px-6 py-4 text-right">
                                            @if($leave->status === 'Pending')
                                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition duration-200">
                                                    <form action="{{ route('leaves.update', $leave) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="Approved">
                                                        <button type="submit" class="p-2 text-emerald-600 hover:bg-emerald-50 border border-transparent hover:border-emerald-100 rounded-xl transition-all" title="Approve">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('leaves.update', $leave) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="Rejected">
                                                        <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-100 rounded-xl transition-all" title="Reject">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic text-sm">No leave requests found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Card List --}}
                <div class="sm:hidden divide-y divide-slate-100">
                    @forelse($leaves as $leave)
                    <div class="p-4 space-y-3" :class="selected.includes('{{ $leave->id }}') ? 'bg-indigo-50/20' : ''">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex items-center gap-2.5">
                                @if(auth()->user()->isStaffOrAdmin() && $leave->status === 'Pending')
                                    <input type="checkbox" name="leave_ids[]" value="{{ $leave->id }}" x-model="selected" class="rounded border-slate-200 text-indigo-600 focus:ring-indigo-500/20 mt-0.5">
                                @endif
                                <div>
                                    @if(auth()->user()->isStaffOrAdmin())
                                        <div class="text-sm font-extrabold text-slate-900 leading-tight">{{ $leave->user->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $leave->user->employee_id }}</div>
                                    @else
                                        <div class="text-sm font-extrabold text-slate-900 leading-tight">Leave Request</div>
                                    @endif
                                </div>
                            </div>
                            @if($leave->status === 'Approved')
                                <span class="px-2 py-0.5 rounded-lg text-[9px] font-bold border border-emerald-100 bg-emerald-50 text-emerald-600 uppercase tracking-wider">Approved</span>
                            @elseif($leave->status === 'Rejected')
                                <span class="px-2 py-0.5 rounded-lg text-[9px] font-bold border border-rose-100 bg-rose-50 text-rose-600 uppercase tracking-wider">Rejected</span>
                            @else
                                <span class="px-2 py-0.5 rounded-lg text-[9px] font-bold border border-amber-100 bg-amber-50 text-amber-650 uppercase tracking-wider animate-pulse">Pending</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2 flex-wrap text-[11px] bg-slate-50 border border-slate-100 p-2.5 rounded-xl">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[9px] font-bold
                                {{ $leave->type === 'Sick' ? 'bg-rose-50 text-rose-600 border border-rose-100' : '' }}
                                {{ $leave->type === 'Vacation' ? 'bg-indigo-50 text-indigo-650 border border-indigo-100' : '' }}
                                {{ $leave->type === 'Personal' ? 'bg-purple-50 text-purple-600 border border-purple-100' : '' }} uppercase tracking-wider">
                                {{ $leave->type }}
                            </span>
                            <span class="font-bold text-slate-700">{{ $leave->start_date->format('M d') }} – {{ $leave->end_date->format('M d, Y') }}</span>
                            <span class="text-[10px] text-slate-400 font-bold uppercase">({{ $leave->start_date->diffInDays($leave->end_date) + 1 }}d)</span>
                        </div>
                        @if(auth()->user()->isStaffOrAdmin() && $leave->status === 'Pending')
                        <div class="flex gap-2 pt-1">
                            <form action="{{ route('leaves.update', $leave) }}" method="POST" class="flex-1">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="Approved">
                                <button type="submit" class="w-full py-2 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-xl text-xs font-bold hover:bg-emerald-100 transition duration-200">Approve</button>
                            </form>
                            <form action="{{ route('leaves.update', $leave) }}" method="POST" class="flex-1">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="Rejected">
                                <button type="submit" class="w-full py-2 bg-rose-50 border border-rose-100 text-rose-600 rounded-xl text-xs font-bold hover:bg-rose-100 transition duration-200">Reject</button>
                            </form>
                        </div>
                        @endif
                    </div>
                    @empty
                    <div class="p-12 text-center text-slate-400 italic text-sm">No leave requests found.</div>
                    @endforelse
                </div>
                @if($leaves->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $leaves->links() }}
                    </div>
                @endif

                <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 text-[10px] font-bold text-slate-450 uppercase tracking-widest flex justify-between items-center">
                    <span>Data dynamically synced via leave manager.</span>
                    <span class="tabular-nums">{{ $leaves->total() }} Total Entries</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
