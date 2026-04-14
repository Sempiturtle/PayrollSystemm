<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Presence Master List
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ 
        editModal: false, 
        addModal: false,
        activeLog: { id: '', user_id: '', date: '', time_in: '', time_out: '', status: '' },
        openEdit(log) {
            this.activeLog = { ...log };
            this.editModal = true;
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Filters & Actions Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <form action="{{ route('attendance.index') }}" method="GET" class="flex flex-wrap items-center gap-4 flex-1">
                    <div class="flex items-center gap-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">From</label>
                        <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="bg-slate-50 border-slate-100 rounded-xl text-xs focus:ring-indigo-500">
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">To</label>
                        <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="bg-slate-50 border-slate-100 rounded-xl text-xs focus:ring-indigo-500">
                    </div>
                    <select name="user_id" class="bg-slate-50 border-slate-100 rounded-xl text-xs focus:ring-indigo-500 min-w-[200px]">
                        <option value="">All Employees</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ ($filters['user_id'] ?? '') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                        @endforeach
                    </select>
                    <select name="status" class="bg-slate-50 border-slate-100 rounded-xl text-xs focus:ring-indigo-500">
                        <option value="">All Statuses</option>
                        <option value="On-time" {{ ($filters['status'] ?? '') == 'On-time' ? 'selected' : '' }}>On-time</option>
                        <option value="Late" {{ ($filters['status'] ?? '') == 'Late' ? 'selected' : '' }}>Late Only</option>
                    </select>
                    <button type="submit" class="p-2.5 bg-slate-900 text-white rounded-xl hover:bg-slate-800 transition shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                    @if(!empty($filters))
                        <a href="{{ route('attendance.index') }}" class="text-xs font-bold text-slate-400 hover:text-rose-500 underline decoration-slate-200">Clear</a>
                    @endif
                </form>

                <div class="flex items-center gap-3 border-l border-slate-100 pl-4">
                    <a href="{{ route('attendance.export', request()->all()) }}" class="flex items-center gap-2 px-4 py-2.5 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-xl font-bold text-xs hover:bg-emerald-100 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Export CSV
                    </a>
                    <button @click="addModal = true" class="flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white rounded-xl font-bold text-xs shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition active:scale-[0.98]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Add Manual Log
                    </button>
                </div>
            </div>

            <!-- Table Section -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50/50">
                            <tr class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">
                                <th class="px-6 py-4">Employee</th>
                                <th class="px-6 py-4">Date / Day</th>
                                <th class="px-6 py-4">Check In</th>
                                <th class="px-6 py-4">Check Out</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Hrs Worked</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($logs as $log)
                                <tr class="hover:bg-slate-50/50 transition relative group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-slate-900 text-white flex items-center justify-center text-[10px] font-bold">
                                                {{ strtoupper(substr($log->user->name ?? '?', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-slate-700">{{ $log->user->name ?? 'Unknown' }}</div>
                                                <div class="text-[10px] text-slate-400 font-bold tracking-wider uppercase">{{ $log->user->employee_id ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-slate-600">{{ $log->date->format('M d, Y') }}</div>
                                        <div class="text-[10px] text-slate-400 font-medium italic">{{ $log->date->format('l') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 rounded-lg text-xs font-bold text-slate-600 border border-slate-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            {{ $log->time_in }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($log->time_out)
                                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 rounded-lg text-xs font-bold text-slate-600 border border-slate-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                {{ $log->time_out }}
                                            </div>
                                        @else
                                            <span class="text-[10px] font-bold text-slate-300 uppercase italic tracking-widest">Active</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded text-[10px] font-bold border uppercase tracking-wider
                                            {{ $log->status === 'Late' ? 'bg-rose-50 text-rose-600 border-rose-100' : 'bg-emerald-50 text-emerald-600 border-emerald-100' }}">
                                            {{ $log->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($log->time_in && $log->time_out)
                                            @php
                                                $in = \Carbon\Carbon::parse($log->date->toDateString() . ' ' . $log->time_in);
                                                $out = \Carbon\Carbon::parse($log->date->toDateString() . ' ' . $log->time_out);
                                                $hrs = number_format($in->diffInMinutes($out) / 60, 2);
                                            @endphp
                                            <div class="text-sm font-bold text-slate-600">{{ $hrs }} <small class="text-[10px] text-slate-400 uppercase">Hrs</small></div>
                                        @else
                                            <span class="text-slate-300">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button @click="openEdit({{ $log->toJson() }})" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                            </button>
                                            <form action="{{ route('attendance.destroy', $log) }}" method="POST" onsubmit="return confirm('Secure delete attendance record? This will affect payroll.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-20 text-center text-slate-400 italic text-sm">
                                        No attendance logs matching the current criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add Manual Log Modal -->
        <div x-show="addModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="addModal = false"></div>
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md relative p-8 border border-slate-100">
                <h3 class="text-xl font-bold text-slate-800 mb-6 font-outfit">Manual Entry</h3>
                <form action="{{ route('attendance.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Employee</label>
                        <select name="user_id" required class="w-full bg-slate-50 border-slate-100 rounded-xl text-sm focus:ring-indigo-500">
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Date</label>
                        <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border-slate-100 rounded-xl text-sm focus:ring-indigo-500">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Time In</label>
                            <input type="time" name="time_in_input" step="1" required class="w-full bg-slate-50 border-slate-100 rounded-xl text-sm focus:ring-indigo-500" @change="$nextTick(() => { $el.parentElement.querySelector('input[name=time_in]').value = $el.value + ':00' })">
                            <input type="hidden" name="time_in">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Time Out</label>
                            <input type="time" name="time_out_input" step="1" class="w-full bg-slate-50 border-slate-100 rounded-xl text-sm focus:ring-indigo-500" @change="$nextTick(() => { $el.parentElement.querySelector('input[name=time_out]').value = $el.value ? $el.value + ':00' : '' })">
                            <input type="hidden" name="time_out">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Override Status</label>
                        <select name="status" required class="w-full bg-slate-50 border-slate-100 rounded-xl text-sm focus:ring-indigo-500">
                            <option value="On-time">On-time</option>
                            <option value="Late">Late</option>
                        </select>
                    </div>
                    <input type="hidden" name="source" value="Admin Manual">
                    <div class="pt-4 flex gap-3">
                        <button type="button" @click="addModal = false" class="flex-1 py-3 px-4 bg-slate-100 text-slate-600 rounded-xl font-bold transition hover:bg-slate-200">Cancel</button>
                        <button type="submit" class="flex-1 py-3 px-4 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition">Save Log</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div x-show="editModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="editModal = false"></div>
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md relative p-8 border border-slate-100">
                <h3 class="text-xl font-bold text-slate-800 mb-6 font-outfit">Adjust Presence</h3>
                <form :action="'{{ url('attendance') }}/' + activeLog.id" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Employee (Read-only)</label>
                        <div class="p-3 bg-slate-50 rounded-xl text-xs font-bold text-slate-500" x-text="activeLog.user?.name"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Time In</label>
                            <input type="time" step="1" name="time_in_raw" x-model="activeLog.time_in" class="w-full bg-slate-50 border-slate-100 rounded-xl text-sm focus:ring-indigo-500" @change="$nextTick(() => { $el.parentElement.querySelector('input[name=time_in]').value = $el.value.includes(':') && $el.value.split(':').length == 2 ? $el.value + ':00' : $el.value })">
                            <input type="hidden" name="time_in" x-model="activeLog.time_in">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Time Out</label>
                            <input type="time" step="1" name="time_out_raw" x-model="activeLog.time_out" class="w-full bg-slate-50 border-slate-100 rounded-xl text-sm focus:ring-indigo-500" @change="$nextTick(() => { $el.parentElement.querySelector('input[name=time_out]').value = $el.value && $el.value.split(':').length == 2 ? $el.value + ':00' : $el.value })">
                            <input type="hidden" name="time_out" x-model="activeLog.time_out">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Status</label>
                        <select name="status" x-model="activeLog.status" class="w-full bg-slate-50 border-slate-100 rounded-xl text-sm focus:ring-indigo-500">
                            <option value="On-time">On-time</option>
                            <option value="Late">Late</option>
                        </select>
                    </div>

                    <div class="pt-4 flex gap-3">
                        <button type="button" @click="editModal = false" class="flex-1 py-3 px-4 bg-slate-100 text-slate-600 rounded-xl font-bold transition hover:bg-slate-200">Cancel</button>
                        <button type="submit" class="flex-1 py-3 px-4 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Help Tooltip -->
    <div class="fixed bottom-8 right-8 group">
        <div class="absolute bottom-full right-0 mb-4 w-64 p-4 bg-slate-900 text-white text-[11px] rounded-2xl shadow-2xl opacity-0 translate-y-2 group-hover:opacity-100 group-hover:translate-y-0 transition-all pointer-events-none">
            <div class="font-bold text-white uppercase tracking-widest mb-2 border-b border-white/10 pb-2">Admin Guide</div>
            <p class="leading-relaxed opacity-80">Manual logs are marked as "Admin Manual". Adjusting logs here will automatically recalculate associated payroll records on the next sync.</p>
        </div>
        <div class="w-12 h-12 bg-slate-900 text-white rounded-full flex items-center justify-center shadow-2xl cursor-help hover:scale-110 transition active:scale-95 border border-white/10">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>
</x-app-layout>
