<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <span class="text-[#101D33]/40 font-bold uppercase tracking-[0.2em] text-[10px]">Academic</span>
            <span class="text-[#101D33]/20">/</span>
            <span class="font-['DM_Serif_Text'] text-[#101D33] italic">Timetable Command</span>
        </div>
    </x-slot>

    <div x-data="{ 
        selected: [],
        allSelected: false,
        showBulkUpload: false,
        toggleAll() {
            this.allSelected = !this.allSelected;
            if (this.allSelected) {
                this.selected = Array.from(document.querySelectorAll('input[name=\'user_ids[]\']')).map(el => el.value);
            } else {
                this.selected = [];
            }
        }
    }" class="space-y-8 animate-in-up">
        
        {{-- Bulk Actions Toolbar --}}
        <div x-show="selected.length > 0" 
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             class="fixed bottom-12 left-1/2 -translate-x-1/2 z-[100] bg-[#101D33] text-white px-10 py-6 rounded-[3rem] shadow-[0_40px_80px_rgba(16,29,51,0.4)] flex items-center gap-10 border border-white/10 backdrop-blur-2xl">
            <div class="flex items-center gap-5 pr-10 border-r border-white/10">
                <div class="w-10 h-10 rounded-2xl bg-[#D4AF37] flex items-center justify-center text-[#101D33] text-sm font-black shadow-lg shadow-[#D4AF37]/20" x-text="selected.length"></div>
                <div>
                    <div class="text-[10px] font-black uppercase tracking-[0.2em] text-white/40 leading-none mb-1">Batch selection</div>
                    <div class="text-sm font-['DM_Serif_Text'] italic text-white/90 leading-none">Awaiting Temporal Assignment</div>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <button @click="showBulkUpload = true" class="px-8 py-3 bg-[#D4AF37] hover:bg-yellow-600 text-[#101D33] rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all hover:scale-[1.05] active:scale-95 shadow-xl shadow-[#D4AF37]/20">
                    Apply Template
                </button>
                <form action="{{ route('schedules.bulkDestroy') }}" method="POST" class="inline" onsubmit="return confirm('Clear institutional schedules for selected assets?')">
                    @csrf
                    @method('DELETE')
                    <template x-for="id in selected" :key="id">
                        <input type="hidden" name="user_ids[]" :value="id">
                    </template>
                    <button type="submit" class="px-8 py-3 bg-[#660000] hover:bg-rose-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all hover:scale-[1.05] active:scale-95 shadow-xl shadow-rose-900/20 border border-white/5">
                        Clear Batch
                    </button>
                </form>
                <button @click="selected = []; allSelected = false" class="text-[10px] font-black text-white/30 hover:text-white transition-colors uppercase tracking-[0.2em] ml-4">
                    Cancel
                </button>
            </div>
        </div>

        {{-- Bulk Upload Modal --}}
        <div x-show="showBulkUpload" class="fixed inset-0 z-[150] overflow-y-auto" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-[#101D33]/90 backdrop-blur-xl" @click="showBulkUpload = false"></div>
                <div class="relative bg-white rounded-[3rem] shadow-[0_50px_100px_rgba(0,0,0,0.5)] w-full max-w-xl overflow-hidden border border-white/20" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-12 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                    <form action="{{ route('schedules.bulkUpload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <template x-for="id in selected" :key="id">
                            <input type="hidden" name="user_ids[]" :value="id">
                        </template>
                        <div class="px-10 pt-12 pb-8">
                            <div class="flex items-center justify-between mb-10">
                                <div>
                                    <h3 class="text-3xl font-['DM_Serif_Display'] text-[#101D33] leading-none mb-3">Temporal <span class="text-[#660000]">Import</span></h3>
                                    <p class="text-[11px] text-slate-400 font-bold uppercase tracking-[0.2em]">Institutional schedule synchronization</p>
                                </div>
                                <div class="w-14 h-14 rounded-[1.5rem] bg-[#101D33]/5 flex items-center justify-center text-[#101D33]/20">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                </div>
                            </div>
                            
                            <p class="text-sm font-['DM_Serif_Text'] text-slate-500 italic mb-8">Assigning an official master schedule image to <span class="text-[#101D33] font-bold" x-text="selected.length"></span> institutional nodes.</p>
                            
                            <div class="mt-8">
                                <label class="block text-[10px] font-black text-[#101D33]/30 uppercase tracking-[0.3em] mb-4">Official Schedule Reference</label>
                                <div class="relative group">
                                    <input type="file" name="schedule_image" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    <div class="px-8 py-12 bg-[#FDFCF8] border-2 border-dashed border-[#101D33]/10 rounded-[2rem] text-center transition-all group-hover:border-[#101D33]/20 group-hover:bg-white">
                                        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm text-[#101D33]/30 group-hover:text-[#101D33] transition-colors">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <p class="text-[10px] font-black text-[#101D33]/40 uppercase tracking-widest">Drop Master Template or Click to Browse</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="px-10 py-8 bg-[#FDFCF8]/50 border-t border-[#101D33]/5 flex items-center justify-end gap-6">
                            <button type="button" @click="showBulkUpload = false" class="text-[10px] font-black text-slate-400 hover:text-[#660000] transition-colors uppercase tracking-[0.2em]">Abort Sync</button>
                            <button type="submit" class="px-10 py-4 bg-[#101D33] text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] hover:bg-[#660000] transition-all shadow-xl shadow-[#101D33]/10 active:scale-95">Execute Temporal Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Header Bar -->
        <div class="bg-white rounded-[3rem] border border-[#101D33]/5 shadow-[0_30px_100px_rgba(16,29,51,0.06)] overflow-hidden">
            <div class="p-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 bg-[#FDFCF8]/30 border-b border-[#101D33]/5">
                <div>
                    <h3 class="text-3xl font-['DM_Serif_Display'] text-[#101D33] leading-none">Timetable <span class="text-[#660000]">Matrix</span></h3>
                    <p class="text-sm text-slate-500 mt-2 font-['DM_Serif_Text'] italic opacity-70">
                        Synchronized temporal oversight of <span class="font-bold text-[#101D33]">{{ $totalScheduled }}</span> institutional nodes and <span class="font-bold text-[#101D33]">{{ $totalEntries }}</span> cycle entries.
                    </p>
                </div>
                <div class="flex items-center gap-4">
                    <button @click="toggleAll" class="px-6 py-3 bg-[#101D33]/5 text-[#101D33] rounded-2xl font-bold text-[10px] uppercase tracking-[0.2em] flex items-center gap-3 hover:bg-[#101D33] hover:text-white transition-all shadow-sm">
                        <span x-text="allSelected ? 'De-Select All Nodes' : 'Select All Nodes'"></span>
                    </button>
                    <!-- Factory Reset -->
                    <form action="{{ route('schedules.destroy') }}" method="POST" onsubmit="return confirm('WARNING: This will clear ALL institutional timetables. Proceed with caution?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-6 py-3 bg-[#660000]/5 text-[#660000] rounded-2xl font-bold text-[10px] uppercase tracking-[0.2em] flex items-center gap-3 hover:bg-[#660000] hover:text-white transition-all shadow-sm border border-[#660000]/10">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Institutional Reset
                        </button>
                    </form>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="px-8 py-6">
                <form action="{{ route('schedules.index') }}" method="GET" class="relative group">
                    <div class="absolute left-6 top-1/2 -translate-y-1/2 w-6 h-6 text-[#101D33]/20 group-focus-within:text-[#101D33] transition-colors">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Search Identity Registry or Node ID..." 
                        value="{{ $search ?? '' }}"
                        class="w-full pl-16 pr-8 py-5 bg-[#FDFCF8] border border-[#101D33]/5 rounded-[2rem] text-sm font-['DM_Serif_Text'] text-[#101D33] placeholder-[#101D33]/20 focus:ring-2 focus:ring-[#101D33] focus:border-transparent transition-all shadow-sm"
                    >
                </form>
            </div>
        </div>

        <!-- Employees Schedule Cards -->
        <div class="space-y-6">
            @forelse($employees as $employee)
            <div class="bg-white rounded-[2.5rem] border border-[#101D33]/5 shadow-[0_20px_60px_rgba(16,29,51,0.04)] overflow-hidden transition-all duration-500 hover:shadow-[0_40px_80px_rgba(16,29,51,0.08)]" 
                 :class="selected.includes('{{ $employee->id }}') ? 'ring-4 ring-[#D4AF37]/30 border-[#D4AF37]/20 bg-[#FDFCF8]' : ''"
                 x-data="{ open: true }">
                
                <!-- Employee Header -->
                <div class="flex items-center">
                    <div class="pl-10 flex items-center">
                        <input type="checkbox" name="user_ids[]" value="{{ $employee->id }}" x-model="selected" class="rounded-[0.5rem] border-[#101D33]/10 text-[#101D33] focus:ring-[#101D33] w-6 h-6">
                    </div>
                    <button @click="open = !open" class="flex-1 p-8 flex items-center justify-between hover:bg-[#FDFCF8] transition-colors group">
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 rounded-[1.5rem] bg-[#101D33] text-white flex items-center justify-center font-['DM_Serif_Display'] text-2xl shadow-xl shadow-[#101D33]/10 border border-white/10 overflow-hidden relative">
                                <div class="absolute inset-0 bg-gradient-to-br from-[#660000]/30 to-transparent opacity-50"></div>
                                <span class="relative z-10">{{ substr($employee->name, 0, 1) }}</span>
                            </div>
                            <div class="text-left">
                                <div class="text-xl font-['DM_Serif_Text'] text-[#101D33] tracking-tight leading-none mb-2">{{ $employee->name }}</div>
                                <div class="flex items-center gap-4">
                                    <span class="text-[10px] font-bold text-slate-300 uppercase tracking-[0.2em] leading-none">{{ $employee->employee_id }} Registry</span>
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest leading-none border
                                        {{ $employee->role === 'professor' ? 'bg-[#101D33]/5 text-[#101D33] border-[#101D33]/10' : 'bg-[#D4AF37]/10 text-[#D4AF37] border-[#D4AF37]/20' }}">
                                        {{ $employee->role }}
                                    </span>
                                    <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest leading-none">
                                        {{ $employee->schedules->count() }} Weekly Cycles
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-8">
                            <div class="text-right">
                                <div class="text-2xl font-['DM_Serif_Display'] text-[#101D33] tabular-nums leading-none">
                                    {{ $employee->schedules->sum(fn($s) => round(\Carbon\Carbon::parse($s->end_time)->diffInMinutes(\Carbon\Carbon::parse($s->start_time)) / 60, 1)) }}<span class="text-xs text-slate-300 ml-1 italic font-['DM_Serif_Text']">h/wk</span>
                                </div>
                                <div class="text-[9px] font-bold text-slate-300 uppercase tracking-[0.2em] mt-1.5">Load Velocity</div>
                            </div>
                            <div class="w-10 h-10 rounded-full border border-[#101D33]/5 flex items-center justify-center text-[#101D33]/20 group-hover:text-[#101D33] transition-all" :class="{ 'rotate-180 bg-[#101D33] text-white': open }">
                                <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </button>
                </div>

                <!-- Weekly Timetable Grid -->
                <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    <div class="border-t border-[#101D33]/5">
                        @if($employee->schedule_image)
                            <div class="p-10 bg-[#FDFCF8]/50 flex flex-col items-center gap-6 border-b border-[#101D33]/5">
                                <div class="max-w-3xl w-full rounded-[2.5rem] overflow-hidden border border-[#101D33]/10 shadow-[0_30px_60px_rgba(16,29,51,0.1)] bg-white relative group/img">
                                    <div class="absolute inset-0 bg-[#101D33]/60 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm z-20">
                                        <a href="{{ asset($employee->schedule_image) }}" target="_blank" class="px-8 py-3 bg-[#D4AF37] text-[#101D33] rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] shadow-xl shadow-[#D4AF37]/20 hover:scale-[1.05] transition-all">Expand Reference Matrix</a>
                                    </div>
                                    <img src="{{ asset($employee->schedule_image) }}" alt="Schedule Reference" class="w-full h-auto object-contain max-h-[600px] relative z-10 transition-transform duration-700 group-hover/img:scale-[1.02]">
                                </div>
                                <div class="text-[10px] font-bold text-slate-300 uppercase tracking-[0.3em] italic">Official Institutional Reference Matrix</div>
                            </div>
                        @endif
                        <div class="grid grid-cols-7 divide-x divide-[#101D33]/5">
                            @php
                                $allDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                $shortDays = ['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'];
                                $today = now()->format('l');
                            @endphp
                            
                            @foreach($allDays as $i => $day)
                                @php
                                    $sched = $employee->schedules->firstWhere('day_of_week', $day);
                                    $isToday = ($day === $today);
                                @endphp
                                <div class="p-8 text-center transition-all {{ $isToday ? 'bg-[#101D33]/5' : 'hover:bg-[#FDFCF8]' }}">
                                    <div class="text-[10px] font-black uppercase tracking-[0.3em] mb-6 {{ $isToday ? 'text-[#D4AF37]' : 'text-[#101D33]/20' }}">
                                        {{ $shortDays[$i] }}
                                    </div>
                                    @if($sched)
                                        <div class="space-y-1">
                                            <div class="text-sm font-black text-[#101D33] tabular-nums tracking-tighter leading-none">
                                                {{ \Carbon\Carbon::parse($sched->start_time)->format('h:i A') }}
                                            </div>
                                            <div class="text-[10px] text-slate-300 font-bold uppercase tracking-widest my-2 italic">↓ Cycle ↓</div>
                                            <div class="text-sm font-black text-[#101D33] tabular-nums tracking-tighter leading-none">
                                                {{ \Carbon\Carbon::parse($sched->end_time)->format('h:i A') }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex flex-col items-center gap-2 py-4">
                                            <div class="w-1.5 h-1.5 rounded-full bg-slate-200"></div>
                                            <div class="text-[10px] text-slate-300 font-black uppercase tracking-widest italic leading-none">OFF</div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-[3rem] border border-[#101D33]/5 shadow-[0_20px_60px_rgba(16,29,51,0.04)] p-24 text-center">
                <div class="w-20 h-20 bg-[#FDFCF8] rounded-[2rem] flex items-center justify-center text-[#101D33]/20 mx-auto mb-8 border border-[#101D33]/5">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h4 class="text-2xl font-['DM_Serif_Display'] text-[#101D33] mb-4">No Temporal Data Found</h4>
                <p class="text-sm font-['DM_Serif_Text'] text-slate-400 italic">The institutional timetable registry is currently vacant. Please upload master templates to synchronize nodes.</p>
            </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
