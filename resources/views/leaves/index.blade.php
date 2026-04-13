<x-app-layout>
    <x-slot name="header">
        Leave Management
    </x-slot>

    <div class="space-y-6">
        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-green-900/30 dark:text-green-400" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-red-900/30 dark:text-red-400" role="alert">
                {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-red-900/30 dark:text-red-400">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

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
        }" class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden relative">
            
            {{-- Bulk Actions Toolbar --}}
            @if(auth()->user()->isAdmin())
            <div x-show="selected.length > 0" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="fixed bottom-8 left-1/2 -translate-x-1/2 z-[100] bg-slate-900 text-white px-8 py-4 rounded-[2rem] shadow-2xl flex items-center gap-6 border border-slate-700/50 backdrop-blur-xl">
                <div class="flex items-center gap-3 pr-6 border-r border-slate-700">
                    <span class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-xs font-bold" x-text="selected.length"></span>
                    <span class="text-sm font-bold uppercase tracking-widest text-slate-400">Selected</span>
                </div>
                <div class="flex items-center gap-3">
                    <form action="{{ route('leaves.bulk') }}" method="POST" class="inline">
                        @csrf
                        <template x-for="id in selected" :key="id">
                            <input type="hidden" name="leave_ids[]" :value="id">
                        </template>
                        <input type="hidden" name="status" value="Approved">
                        <button type="submit" class="px-5 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-xs font-bold uppercase tracking-widest transition active:scale-95 shadow-lg shadow-emerald-500/20">
                            Approve Batch
                        </button>
                    </form>
                    <form action="{{ route('leaves.bulk') }}" method="POST" class="inline">
                        @csrf
                        <template x-for="id in selected" :key="id">
                            <input type="hidden" name="leave_ids[]" :value="id">
                        </template>
                        <input type="hidden" name="status" value="Rejected">
                        <button type="submit" class="px-5 py-2 bg-rose-500 hover:bg-rose-600 text-white rounded-xl text-xs font-bold uppercase tracking-widest transition active:scale-95 shadow-lg shadow-rose-500/20">
                            Reject Batch
                        </button>
                    </form>
                    <button @click="selected = []; allSelected = false" class="text-xs font-bold text-slate-400 hover:text-white transition uppercase tracking-widest ml-2">
                        Cancel
                    </button>
                </div>
            </div>
            @endif

            <div class="p-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100 italic tracking-tight">Leave Requests</h3>
                    <p class="text-sm text-slate-500 mt-1">
                        {{ auth()->user()->isAdmin() ? 'Manage employee time-off requests.' : 'View and request your time off.' }}
                    </p>
                </div>
                @if(!auth()->user()->isAdmin())
                <div x-data="{ showModal: false }">
                    <button @click="showModal = true" class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold flex items-center gap-2 hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 dark:shadow-none hover:-translate-y-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Request Leave
                    </button>

                    <!-- New Leave Modal -->
                    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                            <!-- Overlay -->
                            <div class="fixed inset-0 transition-opacity bg-slate-900/75 dark:bg-slate-900/90 backdrop-blur-sm" @click="showModal = false"></div>

                            <div class="relative inline-block w-full text-left align-bottom transition-all transform bg-white dark:bg-slate-900 rounded-3xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg overflow-hidden border border-slate-100 dark:border-slate-800" @click.away="showModal = false">
                                <form action="{{ route('leaves.store') }}" method="POST">
                                    @csrf
                                    <div class="px-8 pt-8 pb-6">
                                        <h3 class="text-2xl font-black text-slate-900 dark:text-slate-100 italic tracking-tight mb-6">New Request</h3>
                                        
                                        <div class="space-y-5">
                                            <div>
                                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Leave Type</label>
                                                <select name="type" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border-0 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-indigo-500">
                                                    <option value="Sick">Sick Leave</option>
                                                    <option value="Vacation">Vacation</option>
                                                    <option value="Personal">Personal Leave</option>
                                                </select>
                                            </div>
                                            
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Start Date</label>
                                                    <input type="date" name="start_date" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border-0 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-indigo-500">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">End Date</label>
                                                    <input type="date" name="end_date" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border-0 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-indigo-500">
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Reason (Optional)</label>
                                                <textarea name="reason" rows="3" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border-0 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-indigo-500"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-8 py-6 bg-slate-50 dark:bg-slate-800/50 flex items-center justify-end gap-3 mt-4">
                                        <button type="button" @click="showModal = false" class="px-5 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition">Cancel</button>
                                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 dark:shadow-none">Submit Request</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                        <tr>
                            @if(auth()->user()->isAdmin())
                                <th class="px-8 py-4 w-4">
                                    <input type="checkbox" @click="toggleAll" :checked="allSelected" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                </th>
                                <th class="px-8 py-4">Employee</th>
                            @endif
                            <th class="px-8 py-4">Type</th>
                            <th class="px-8 py-4">Duration</th>
                            <th class="px-8 py-4">Reason</th>
                            <th class="px-8 py-4">Status</th>
                            @if(auth()->user()->isAdmin())
                                <th class="px-8 py-4 text-right">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                        @forelse($leaves as $leave)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition shadow-sm" :class="selected.includes('{{ $leave->id }}') ? 'bg-indigo-50/30 dark:bg-indigo-900/10' : ''">
                                @if(auth()->user()->isAdmin())
                                    <td class="px-8 py-4">
                                        @if($leave->status === 'Pending')
                                            <input type="checkbox" name="leave_ids[]" value="{{ $leave->id }}" x-model="selected" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                        @endif
                                    </td>
                                    <td class="px-8 py-4">
                                        <div class="font-bold text-slate-900 dark:text-slate-100">{{ $leave->user->name }}</div>
                                        <div class="text-xs text-slate-400">{{ $leave->user->employee_id }}</div>
                                    </td>
                                @endif
                                <td class="px-8 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                                        {{ $leave->type === 'Sick' ? 'bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-400' : '' }}
                                        {{ $leave->type === 'Vacation' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                                        {{ $leave->type === 'Personal' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400' : '' }}
                                    ">
                                        {{ $leave->type }}
                                    </span>
                                </td>
                                <td class="px-8 py-4">
                                    <div class="font-medium text-slate-700 dark:text-slate-300">
                                        {{ $leave->start_date->format('M d, Y') }} - {{ $leave->end_date->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-slate-400">
                                        {{ $leave->start_date->diffInDays($leave->end_date) + 1 }} day(s)
                                    </div>
                                </td>
                                <td class="px-8 py-4 text-slate-500 dark:text-slate-400">
                                    {{ Str::limit($leave->reason, 30) ?? 'N/A' }}
                                </td>
                                <td class="px-8 py-4">
                                    <x-status-badge :status="$leave->status" />
                                </td>
                                @if(auth()->user()->isAdmin())
                                    <td class="px-8 py-4 text-right">
                                        @if($leave->status === 'Pending')
                                            <div class="flex items-center justify-end gap-2">
                                                <form action="{{ route('leaves.update', $leave) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="Approved">
                                                    <button type="submit" class="p-2 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-400 dark:hover:bg-emerald-900/40 transition tooltip" title="Approve">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    </button>
                                                </form>
                                                <form action="{{ route('leaves.update', $leave) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="Rejected">
                                                    <button type="submit" class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 dark:bg-rose-900/20 dark:text-rose-400 dark:hover:bg-rose-900/40 transition tooltip" title="Reject">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->isAdmin() ? 7 : 5 }}" class="px-8 py-12 text-center text-slate-500">
                                    No leave requests found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
