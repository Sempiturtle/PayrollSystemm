<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <span class="text-[#101D33]/40 font-bold uppercase tracking-[0.2em] text-[10px]">Operations</span>
            <span class="text-[#101D33]/20">/</span>
            <span class="font-['DM_Serif_Text'] text-[#101D33] italic">Presence Registry</span>
        </div>
    </x-slot>

    <div class="space-y-6 animate-in-up" x-data="{ 
        editModal: false, 
        addModal: false,
        activeLog: { id: '', user_id: '', date: '', time_in: '', time_out: '', status: '' },
        openEdit(log) {
            this.activeLog = { ...log };
            this.editModal = true;
        }
    }">
        <!-- Filters & Actions Header -->
        <div class="bg-white rounded-2xl border border-[#101D33]/5 shadow-[0_20px_60px_rgba(16,29,51,0.04)] p-4 lg:p-5 flex flex-col xl:flex-row xl:items-center justify-between gap-4 overflow-hidden relative group">
            <div class="absolute inset-0 bg-gradient-to-tr from-[#660000]/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            
            <form action="{{ route('attendance.index') }}" method="GET" class="flex flex-wrap items-center gap-4 flex-1 relative z-10">
                <div class="flex items-center gap-2">
                    <label class="text-[8px] font-black text-[#101D33]/30 uppercase tracking-[0.2em]">Temporal Range</label>
                    <div class="flex items-center bg-[#FDFCF8] rounded-xl border border-[#101D33]/5 overflow-hidden">
                        <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="bg-transparent border-none text-[10px] font-bold text-[#101D33] focus:ring-0 py-2 pl-3 pr-1">
                        <span class="text-slate-300 mx-0.5">—</span>
                        <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="bg-transparent border-none text-[10px] font-bold text-[#101D33] focus:ring-0 py-2 pr-3 pl-1">
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <label class="text-[8px] font-black text-[#101D33]/30 uppercase tracking-[0.2em]">Asset</label>
                    <select name="user_id" class="bg-[#FDFCF8] border-[#101D33]/5 rounded-xl text-[10px] font-bold text-[#101D33] focus:ring-[#101D33] min-w-[180px] py-2 px-4">
                        <option value="">All Institutional Assets</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ ($filters['user_id'] ?? '') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <label class="text-[8px] font-black text-[#101D33]/30 uppercase tracking-[0.2em]">Stream</label>
                    <select name="status" class="bg-[#FDFCF8] border-[#101D33]/5 rounded-xl text-[10px] font-bold text-[#101D33] focus:ring-[#101D33] py-2 px-4">
                        <option value="">All Protocols</option>
                        <option value="On-time" {{ ($filters['status'] ?? '') == 'On-time' ? 'selected' : '' }}>On-time Only</option>
                        <option value="Late" {{ ($filters['status'] ?? '') == 'Late' ? 'selected' : '' }}>Late Only</option>
                    </select>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="w-10 h-10 flex items-center justify-center bg-[#101D33] text-white rounded-xl hover:bg-[#660000] transition-all shadow-xl shadow-[#101D33]/10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                    @if(!empty($filters))
                        <a href="{{ route('attendance.index') }}" class="text-[9px] font-black text-[#660000] uppercase tracking-widest hover:opacity-70 transition-all underline underline-offset-4 decoration-1">Reset Grid</a>
                    @endif
                </div>
            </form>

            <div class="flex items-center gap-3 relative z-10 xl:border-l border-[#101D33]/5 xl:pl-5">
                <a href="{{ route('attendance.export', request()->all()) }}" class="flex items-center gap-2 px-4 py-2.5 bg-white text-[#101D33] border border-[#101D33]/10 rounded-xl font-black text-[9px] uppercase tracking-[0.2em] hover:bg-[#FDFCF8] transition-all shadow-sm">
                    <svg class="w-3.5 h-3.5 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export Ledger
                </a>
                <button @click="addModal = true" class="flex items-center gap-2 px-4 py-2.5 bg-[#101D33] text-white rounded-xl font-black text-[9px] uppercase tracking-[0.2em] shadow-xl shadow-[#101D33]/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                    <svg class="w-3.5 h-3.5 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Manual Registry
                </button>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-3xl border border-[#101D33]/5 shadow-[0_30px_100px_rgba(16,29,51,0.06)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white border-b border-[#101D33]/5">
                            <th class="px-5 py-3 text-[8px] font-black text-slate-400 uppercase tracking-[0.3em]">Asset Identity</th>
                            <th class="px-5 py-3 text-[8px] font-black text-slate-400 uppercase tracking-[0.3em]">Temporal Point</th>
                            <th class="px-5 py-3 text-[8px] font-black text-slate-400 uppercase tracking-[0.3em]">Authentication In</th>
                            <th class="px-5 py-3 text-[8px] font-black text-slate-400 uppercase tracking-[0.3em]">Authentication Out</th>
                            <th class="px-5 py-3 text-[8px] font-black text-slate-400 uppercase tracking-[0.3em]">Protocol State</th>
                            <th class="px-5 py-3 text-[8px] font-black text-slate-400 uppercase tracking-[0.3em]">Labor Value</th>
                            <th class="px-5 py-3 text-[8px] font-black text-slate-400 uppercase tracking-[0.3em] text-right">Registry Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#101D33]/5">
                        @forelse($logs as $log)
                            <tr class="hover:bg-[#FDFCF8] transition-all group relative">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-[#101D33] text-white flex items-center justify-center font-['DM_Serif_Display'] text-xs relative overflow-hidden shrink-0 shadow-lg">
                                            <div class="absolute inset-0 bg-gradient-to-br from-[#660000]/30 to-transparent"></div>
                                            <span class="relative z-10">{{ strtoupper(substr($log->user->name ?? '?', 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-['DM_Serif_Text'] text-[#101D33] tracking-tight leading-none mb-1">{{ $log->user->name ?? 'Unknown Asset' }}</div>
                                            <div class="text-[8px] text-slate-300 font-bold uppercase tracking-[0.2em]">{{ $log->user->employee_id ?? 'UNREGISTERED' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8">
                                    <div class="text-xs font-bold text-[#101D33] uppercase tracking-widest leading-none mb-2">{{ $log->date->format('M d, Y') }}</div>
                                    <div class="text-[9px] text-slate-300 font-bold uppercase tracking-[0.3em] italic">{{ $log->date->format('l') }}</div>
                                </td>
                                <td class="px-10 py-8">
                                    <div class="inline-flex items-center gap-3 px-4 py-2 bg-[#FDFCF8] rounded-xl text-xs font-bold text-[#101D33] border border-[#101D33]/5 tabular-nums">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                                        {{ $log->time_in }}
                                    </div>
                                </td>
                                <td class="px-10 py-8">
                                    @if($log->time_out)
                                        <div class="inline-flex items-center gap-3 px-4 py-2 bg-[#FDFCF8] rounded-xl text-xs font-bold text-[#101D33] border border-[#101D33]/5 tabular-nums">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                            {{ $log->time_out }}
                                        </div>
                                    @else
                                        <span class="text-[9px] font-black text-emerald-500 uppercase tracking-[0.4em] animate-pulse">Session Active</span>
                                    @endif
                                </td>
                                <td class="px-10 py-8">
                                    <x-status-badge :type="$log->status">{{ $log->status }}</x-status-badge>
                                </td>
                                <td class="px-10 py-8">
                                    @if($log->time_in && $log->time_out)
                                        @php
                                            $in = \Carbon\Carbon::parse($log->date->toDateString() . ' ' . $log->time_in);
                                            $out = \Carbon\Carbon::parse($log->date->toDateString() . ' ' . $log->time_out);
                                            $hrs = number_format($in->diffInMinutes($out) / 60, 2);
                                        @endphp
                                        <div class="flex items-baseline gap-1">
                                            <span class="text-base font-['DM_Serif_Display'] text-[#101D33] tabular-nums">{{ $hrs }}</span>
                                            <span class="text-[9px] text-slate-300 font-bold uppercase tracking-widest">HR</span>
                                        </div>
                                    @else
                                        <span class="text-slate-200">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-all transform translate-x-4 group-hover:translate-x-0">
                                        <button @click="openEdit({{ $log->toJson() }})" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white text-[#101D33]/40 hover:text-[#101D33] border border-[#101D33]/5 hover:shadow-lg transition-all" title="Modify Presence">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                        </button>
                                        <form action="{{ route('attendance.destroy', $log) }}" method="POST" onsubmit="return confirm('Excise attendance record from ledger? This affects fiscal settlements.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white text-[#660000]/40 hover:text-[#660000] border border-[#660000]/5 hover:shadow-lg transition-all" title="Purge Log">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-10 py-24 text-center">
                                    <div class="w-20 h-20 bg-[#FDFCF8] rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-slate-100">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div class="text-[11px] font-black text-[#101D33]/20 uppercase tracking-[0.4em] italic">No attendance records localized in current criteria</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Manual Log Modal -->
        <div x-show="addModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-6">
            <div class="fixed inset-0 bg-[#101D33]/40 backdrop-blur-md" @click="addModal = false"></div>
            <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md relative overflow-hidden border border-white/20">
                <div class="bg-[#101D33] p-8 text-white">
                    <h3 class="text-xl font-['DM_Serif_Display'] mb-1">Manual <span class="text-[#D4AF37]">Registry</span></h3>
                    <p class="text-[9px] font-bold text-white/30 uppercase tracking-[0.3em]">Institutional Presence Override</p>
                </div>
                <form action="{{ route('attendance.store') }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    <div>
                        <label class="block text-[9px] font-black text-[#101D33]/30 uppercase tracking-[0.3em] mb-3">Institutional Asset</label>
                        <select name="user_id" required class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl text-sm focus:ring-[#101D33] py-4">
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[9px] font-black text-[#101D33]/30 uppercase tracking-[0.3em] mb-3">Temporal Date</label>
                        <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl text-sm focus:ring-[#101D33] py-4">
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[9px] font-black text-[#101D33]/30 uppercase tracking-[0.3em] mb-3">Check-In</label>
                            <input type="time" name="time_in_input" step="1" required class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl text-sm focus:ring-[#101D33] py-4" @change="$nextTick(() => { $el.parentElement.querySelector('input[name=time_in]').value = $el.value + ':00' })">
                            <input type="hidden" name="time_in">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-[#101D33]/30 uppercase tracking-[0.3em] mb-3">Check-Out</label>
                            <input type="time" name="time_out_input" step="1" class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl text-sm focus:ring-[#101D33] py-4" @change="$nextTick(() => { $el.parentElement.querySelector('input[name=time_out]').value = $el.value ? $el.value + ':00' : '' })">
                            <input type="hidden" name="time_out">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[9px] font-black text-[#101D33]/30 uppercase tracking-[0.3em] mb-3">Protocol Status</label>
                        <select name="status" required class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl text-sm focus:ring-[#101D33] py-4">
                            <option value="On-time">Institutional Standard (On-time)</option>
                            <option value="Late">Temporal Variance (Late)</option>
                        </select>
                    </div>
                    <input type="hidden" name="source" value="Admin Manual">
                    <div class="flex gap-4 pt-4">
                        <button type="button" @click="addModal = false" class="flex-1 py-4 px-6 bg-slate-100 text-slate-500 rounded-2xl font-bold text-xs hover:bg-slate-200 transition-all uppercase tracking-widest">Cancel</button>
                        <button type="submit" class="flex-1 py-4 px-6 bg-[#101D33] text-white rounded-2xl font-bold text-xs shadow-xl shadow-[#101D33]/20 hover:bg-[#660000] transition-all uppercase tracking-widest">Commit Log</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div x-show="editModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-6">
            <div class="fixed inset-0 bg-[#101D33]/40 backdrop-blur-md" @click="editModal = false"></div>
            <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md relative overflow-hidden border border-white/20">
                <div class="bg-[#101D33] p-8 text-white">
                    <h3 class="text-xl font-['DM_Serif_Display'] mb-1">Adjust <span class="text-[#D4AF37]">Presence</span></h3>
                    <p class="text-[9px] font-bold text-white/30 uppercase tracking-[0.3em]">Temporal Point Rectification</p>
                </div>
                <form :action="'{{ url('attendance') }}/' + activeLog.id" method="POST" class="p-8 space-y-6">
                    @csrf
                    @method('PATCH')
                    
                    <div>
                        <label class="block text-[9px] font-black text-[#101D33]/30 uppercase tracking-[0.3em] mb-3">Validated Asset</label>
                        <div class="p-5 bg-[#FDFCF8] rounded-2xl text-sm font-bold text-[#101D33]/40 border border-[#101D33]/5" x-text="activeLog.user?.name"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[9px] font-black text-[#101D33]/30 uppercase tracking-[0.3em] mb-3">Authentic In</label>
                            <input type="time" step="1" name="time_in_raw" x-model="activeLog.time_in" class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl text-sm focus:ring-[#101D33] py-4 tabular-nums" @change="$nextTick(() => { $el.parentElement.querySelector('input[name=time_in]').value = $el.value.includes(':') && $el.value.split(':').length == 2 ? $el.value + ':00' : $el.value })">
                            <input type="hidden" name="time_in" x-model="activeLog.time_in">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-[#101D33]/30 uppercase tracking-[0.3em] mb-3">Authentic Out</label>
                            <input type="time" step="1" name="time_out_raw" x-model="activeLog.time_out" class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl text-sm focus:ring-[#101D33] py-4 tabular-nums" @change="$nextTick(() => { $el.parentElement.querySelector('input[name=time_out]').value = $el.value && $el.value.split(':').length == 2 ? $el.value + ':00' : $el.value })">
                            <input type="hidden" name="time_out" x-model="activeLog.time_out">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[9px] font-black text-[#101D33]/30 uppercase tracking-[0.3em] mb-3">Protocol Classification</label>
                        <select name="status" x-model="activeLog.status" class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl text-sm focus:ring-[#101D33] py-4">
                            <option value="On-time">Institutional Standard</option>
                            <option value="Late">Temporal Variance</option>
                        </select>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="button" @click="editModal = false" class="flex-1 py-4 px-6 bg-slate-100 text-slate-500 rounded-2xl font-bold text-xs hover:bg-slate-200 transition-all uppercase tracking-widest">Discard</button>
                        <button type="submit" class="flex-1 py-4 px-6 bg-[#101D33] text-white rounded-2xl font-bold text-xs shadow-xl shadow-[#101D33]/20 hover:bg-[#660000] transition-all uppercase tracking-widest">Commit Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Institutional Intelligence Tooltip -->
    <div class="fixed bottom-12 right-12 group z-40">
        <div class="absolute bottom-full right-0 mb-6 w-80 p-8 bg-[#101D33] text-white text-[11px] rounded-[2rem] shadow-[0_30px_60px_rgba(0,0,0,0.4)] opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-500 pointer-events-none border border-white/10">
            <div class="font-['DM_Serif_Display'] text-lg text-[#D4AF37] mb-3 border-b border-white/10 pb-3">Institutional Intelligence</div>
            <p class="leading-relaxed font-['DM_Serif_Text'] italic opacity-60">"Manual entries are categorized under 'Institutional Override'. Any modifications to these streams will automatically re-calibrate associated disbursement vectors upon next fiscal synchronization."</p>
        </div>
        <div class="w-16 h-16 bg-[#101D33] text-white rounded-full flex items-center justify-center shadow-2xl cursor-help hover:scale-110 hover:bg-[#660000] transition-all duration-500 border border-white/10">
            <svg class="w-6 h-6 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>
</x-app-layout>
