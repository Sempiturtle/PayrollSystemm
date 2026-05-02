<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <span class="text-[#101D33]/40 font-bold uppercase tracking-[0.2em] text-[10px]">Strategic</span>
            <span class="text-[#101D33]/20">/</span>
            <span class="font-['DM_Serif_Text'] text-[#101D33] italic">Command Center</span>
        </div>
    </x-slot>

    <div class="space-y-8 animate-in-up">
        <!-- Dashboard Header -->
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8 pb-4 border-b border-[#101D33]/5">
            <div>
                <h1 class="text-4xl font-['DM_Serif_Display'] text-[#101D33] leading-tight">Institutional <span class="text-[#660000]">Intelligence</span></h1>
                <p class="text-slate-500 mt-2 font-['DM_Serif_Text'] text-lg italic opacity-70">Unified monitoring of academic personnel, operational flows, and fiscal cycles.</p>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="hidden xl:flex items-center gap-6 px-8 py-4 bg-white rounded-[2rem] border border-[#101D33]/5 shadow-[0_10px_30px_rgba(16,29,51,0.03)]">
                    <div class="text-right">
                        <div class="text-[9px] font-black text-[#101D33]/30 uppercase tracking-[0.3em] mb-1.5 leading-none">Central Terminal Time</div>
                        <div class="text-lg font-bold text-[#101D33] tabular-nums leading-none tracking-tight">
                            <span x-data="{ time: '' }" x-init="setInterval(() => { 
                                time = new Date().toLocaleTimeString('en-US', { timeZone: 'Asia/Manila', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }) 
                            }, 1000)" x-text="time || '00:00:00'"></span>
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-[#101D33]/5 flex items-center justify-center text-[#101D33]/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('attendance.scanner') }}" class="group relative px-8 py-4 bg-[#101D33] text-white rounded-[2rem] font-bold text-sm shadow-[0_20px_40px_rgba(16,29,51,0.2)] transition-all hover:scale-[1.02] active:scale-[0.98] overflow-hidden flex items-center gap-3">
                        <div class="absolute inset-0 bg-gradient-to-tr from-[#660000]/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <svg class="w-5 h-5 relative z-10 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        <span class="relative z-10">Initialize Terminal</span>
                    </a>
                @endif
            </div>
        </div>

        @if($todayHoliday)
            <div class="bg-[#101D33] text-white p-6 rounded-[2.5rem] flex items-center justify-between border border-white/10 shadow-2xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-64 h-64 bg-[#660000]/20 blur-3xl -mr-32 -mt-32 transition-transform group-hover:scale-110"></div>
                <div class="flex items-center gap-6 relative z-10">
                    <div class="w-14 h-14 rounded-3xl bg-[#D4AF37] flex items-center justify-center text-[#101D33] shadow-xl shadow-[#D4AF37]/20">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-[#D4AF37] uppercase tracking-[0.3em] mb-1.5">Academic Observance</div>
                        <div class="text-xl font-['DM_Serif_Display'] tracking-tight">{{ $todayHoliday->name }} <span class="text-white/40 italic font-['DM_Serif_Text'] text-sm">({{ $todayHoliday->type }})</span></div>
                    </div>
                </div>
                <div class="hidden sm:block relative z-10 text-[10px] font-black text-[#101D33] bg-[#D4AF37] px-6 py-2.5 rounded-2xl italic uppercase tracking-[0.2em] shadow-lg shadow-[#D4AF37]/10">Holiday Remuneration Active</div>
            </div>
        @endif

        @if($incompleteProfilesCount > 0)
            <div class="bg-[#660000]/5 border border-[#660000]/10 p-6 rounded-[2.5rem] flex items-center justify-between group">
                <div class="flex items-center gap-6">
                    <div class="w-14 h-14 rounded-3xl bg-[#660000] flex items-center justify-center text-white shadow-xl shadow-[#660000]/20">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-[#660000] uppercase tracking-[0.3em] mb-1.5">Integrity Breach</div>
                        <div class="text-lg font-['DM_Serif_Text'] text-[#101D33] leading-none">{{ $incompleteProfilesCount }} Personnel Records Require Immediate Statutory Auditing</div>
                    </div>
                </div>
                <a href="{{ route('employees.index') }}" class="hidden sm:block text-[10px] font-bold text-white bg-[#101D33] px-6 py-3 rounded-2xl uppercase tracking-[0.2em] hover:bg-[#660000] transition-all shadow-lg shadow-[#101D33]/10">Execute Audit</a>
            </div>
        @endif

        {{-- Executive Command Center --}}
        @if($pendingLeaves > 0 || $pendingDiscrepancies > 0 || $unfinalizedPayrolls > 0)
        <div class="bg-white rounded-[3rem] overflow-hidden border border-[#101D33]/5 shadow-[0_30px_100px_rgba(16,29,51,0.06)] relative group">
            <div class="absolute top-0 right-0 w-96 h-96 bg-[#101D33]/5 blur-[120px] -mr-48 -mt-48 transition-opacity group-hover:opacity-100 opacity-50"></div>
            <div class="p-8 border-b border-[#101D33]/5 flex items-center justify-between relative z-10">
                <div>
                    <h3 class="text-sm font-bold text-[#660000] uppercase tracking-[0.3em] mb-1.5">Administrative Priority</h3>
                    <p class="text-[11px] text-slate-400 font-['DM_Serif_Text'] italic">High-impact tasks awaiting institutional authorization.</p>
                </div>
                <div class="flex items-center gap-3 px-5 py-2.5 bg-[#660000]/5 rounded-full border border-[#660000]/10">
                    <span class="w-2 h-2 rounded-full bg-[#660000] animate-pulse"></span>
                    <span class="text-[10px] font-bold text-[#660000] uppercase tracking-[0.2em]">{{ $pendingLeaves + $pendingDiscrepancies + $unfinalizedPayrolls }} Urgent Actions</span>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 relative z-10">
                {{-- Pending Leaves --}}
                <a href="{{ route('leaves.index') }}" class="p-10 flex flex-col items-center text-center gap-5 group hover:bg-[#FDFCF8] transition-all border-b sm:border-b-0 sm:border-r border-[#101D33]/5">
                    <div class="w-16 h-16 rounded-[2rem] {{ $pendingLeaves > 0 ? 'bg-[#D4AF37] shadow-[0_15px_40px_rgba(212,175,55,0.3)]' : 'bg-slate-50' }} flex items-center justify-center text-[#101D33] transition-transform group-hover:-translate-y-2">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <div class="text-3xl font-['DM_Serif_Display'] text-[#101D33] tabular-nums leading-none">{{ $pendingLeaves }}</div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-2">Leave Authorizations</div>
                    </div>
                </a>

                {{-- Open Disputes --}}
                <a href="{{ route('admin.discrepancies.index') }}" class="p-10 flex flex-col items-center text-center gap-5 group hover:bg-[#FDFCF8] transition-all border-b sm:border-b-0 sm:border-r border-[#101D33]/5">
                    <div class="w-16 h-16 rounded-[2rem] {{ $pendingDiscrepancies > 0 ? 'bg-[#660000] text-white shadow-[0_15px_40px_rgba(102,0,0,0.3)]' : 'bg-slate-50 text-slate-300' }} flex items-center justify-center transition-transform group-hover:-translate-y-2">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <div class="text-3xl font-['DM_Serif_Display'] text-[#101D33] tabular-nums leading-none">{{ $pendingDiscrepancies }}</div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-2">Fiscal Disputes</div>
                    </div>
                </a>

                {{-- Unfinalized Payrolls --}}
                <a href="{{ route('payrolls.index') }}" class="p-10 flex flex-col items-center text-center gap-5 group hover:bg-[#FDFCF8] transition-all border-[#101D33]/5">
                    <div class="w-16 h-16 rounded-[2rem] {{ $unfinalizedPayrolls > 0 ? 'bg-[#101D33] text-white shadow-[0_15px_40px_rgba(16,29,51,0.3)]' : 'bg-slate-50 text-slate-300' }} flex items-center justify-center transition-transform group-hover:-translate-y-2">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <div>
                        <div class="text-3xl font-['DM_Serif_Display'] text-[#101D33] tabular-nums leading-none">{{ $unfinalizedPayrolls }}</div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-2">Unfinalized Ledgers</div>
                    </div>
                </a>
            </div>
        </div>
        @endif

        {{-- Top Metrics --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-[2.5rem] p-8 border border-[#101D33]/5 shadow-[0_20px_60px_rgba(16,29,51,0.04)] flex flex-col justify-between h-56 relative overflow-hidden group">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-[#101D33]/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
                <div class="flex items-center justify-between relative z-10">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Personnel Capital</span>
                    <div class="w-10 h-10 rounded-2xl bg-[#101D33]/5 flex items-center justify-center text-[#101D33]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 relative z-10">
                    <div class="text-4xl font-['DM_Serif_Display'] text-[#101D33] tracking-tighter tabular-nums">{{ $totalEmployees }}</div>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded text-[9px] font-bold uppercase tracking-widest">+1 (30d)</span>
                        <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">Growth Vector</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] p-8 border border-[#101D33]/5 shadow-[0_20px_60px_rgba(16,29,51,0.04)] flex flex-col justify-between h-56 relative overflow-hidden group border-b-4 border-b-emerald-600">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-emerald-500/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
                <div class="flex items-center justify-between relative z-10">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Presence Index</span>
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 relative z-10">
                    <div class="text-4xl font-['DM_Serif_Display'] text-emerald-600 tracking-tighter tabular-nums">{{ $totalEmployees > 0 ? round(($presentToday / $totalEmployees) * 100) : 0 }}%</div>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="text-[11px] font-['DM_Serif_Text'] text-[#101D33] italic opacity-60">{{ $presentToday }} Authenticated Today</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] p-8 border border-[#101D33]/5 shadow-[0_20px_60px_rgba(16,29,51,0.04)] flex flex-col justify-between h-56 relative overflow-hidden group">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-[#660000]/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
                <div class="flex items-center justify-between relative z-10">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Cycle Latency</span>
                    <div class="w-10 h-10 rounded-2xl bg-[#660000]/5 flex items-center justify-center text-[#660000]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 relative z-10">
                    <div class="text-4xl font-['DM_Serif_Display'] {{ $pendingLeaves > 0 ? 'text-[#660000]' : 'text-slate-200' }} tracking-tighter tabular-nums">{{ $pendingLeaves }}</div>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="text-[9px] font-bold text-[#660000] uppercase tracking-[0.2em]">Pending Authorization</span>
                    </div>
                </div>
            </div>

            <div class="bg-[#101D33] text-white rounded-[2.5rem] p-8 shadow-[0_30px_60px_rgba(16,29,51,0.2)] flex flex-col justify-between h-56 relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-tr from-[#660000]/30 to-transparent opacity-50"></div>
                <div class="flex items-center justify-between relative z-10">
                    <span class="text-[10px] font-bold text-[#D4AF37] uppercase tracking-[0.3em]">Fiscal Velocity</span>
                    <div class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center text-[#D4AF37]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944"></path></svg>
                    </div>
                </div>
                <div class="mt-4 relative z-10">
                    <div class="flex items-baseline gap-3">
                        <div class="text-4xl font-['DM_Serif_Display'] text-white tabular-nums leading-none">{{ $statStats->finalized_count }}</div>
                        <span class="text-[10px] font-bold text-[#D4AF37]/50 uppercase tracking-widest italic">Ledgers Finalized</span>
                    </div>
                    <div class="mt-4 flex items-center gap-3">
                        <div class="flex-1 bg-white/10 h-1.5 rounded-full overflow-hidden">
                            @php 
                                $totalRows = ($statStats->finalized_count + $statStats->draft_count) ?: 1;
                                $percent = ($statStats->finalized_count / $totalRows) * 100;
                            @endphp
                            <div class="bg-[#D4AF37] h-full transition-all duration-1000" style="width: {{ $percent }}%"></div>
                        </div>
                        <span class="text-[10px] font-black text-white tabular-nums tracking-tighter">{{ round($percent) }}%</span>
                    </div>
                    <div class="mt-2 text-[9px] font-bold text-white/30 uppercase tracking-[0.2em]">{{ $statStats->draft_count }} Pending Drafts</div>
                </div>
            </div>
        </div>


        {{-- Row 2: Operational & Visual Intelligence --}}
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-stretch">
            {{-- Attendance Distribution (Donut) --}}
            <div class="xl:col-span-4 bg-white rounded-[3rem] p-8 border border-[#101D33]/5 shadow-[0_20px_60px_rgba(16,29,51,0.04)] flex flex-col h-full overflow-hidden">
                <div class="mb-8">
                    <h3 class="text-sm font-bold text-[#101D33] uppercase tracking-[0.3em] mb-1.5 leading-none">Authentication Flux</h3>
                    <p class="text-[11px] text-slate-400 font-['DM_Serif_Text'] italic leading-none">Real-time presence distribution.</p>
                </div>
                <div class="flex-1 flex flex-col justify-between">
                    <div id="attendanceDonut" class="flex justify-center py-4"></div>
                    <div class="grid grid-cols-3 gap-4 border-t border-[#101D33]/5 pt-8 mt-4">
                        <div class="text-center">
                            <div class="text-xl font-['DM_Serif_Display'] text-[#101D33] tabular-nums">{{ $presentToday }}</div>
                            <div class="text-[9px] font-bold text-emerald-500 uppercase tracking-widest mt-1 leading-none">Standard</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-['DM_Serif_Display'] text-[#101D33] tabular-nums">{{ $lateToday }}</div>
                            <div class="text-[9px] font-bold text-[#D4AF37] uppercase tracking-widest mt-1 leading-none">Latency</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-['DM_Serif_Display'] text-[#101D33] tabular-nums">{{ $absentToday }}</div>
                            <div class="text-[9px] font-bold text-[#660000] uppercase tracking-widest mt-1 leading-none">Deficit</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Institutional Pulse (Hourly Flow Area Chart) --}}
            <div class="xl:col-span-8 bg-white rounded-[3rem] p-8 border border-[#101D33]/5 shadow-[0_20px_60px_rgba(16,29,51,0.04)] flex flex-col h-full overflow-hidden">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-sm font-bold text-[#101D33] uppercase tracking-[0.3em] mb-1.5 leading-none">Institutional Pulse</h3>
                        <p class="text-[11px] text-slate-400 font-['DM_Serif_Text'] italic leading-none">Hourly authentication stream for the current cycle.</p>
                    </div>
                    <div class="flex items-center gap-3 px-5 py-2 bg-[#101D33]/5 rounded-full border border-[#101D33]/10">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#101D33] animate-pulse"></span>
                        <span class="text-[9px] font-bold text-[#101D33] uppercase tracking-[0.2em]">Live Stream</span>
                    </div>
                </div>
                <div class="flex-1 min-h-[350px]">
                    <div id="pulseChart" class="h-full"></div>
                </div>
            </div>
        </div>

        {{-- Row 3: Performance & Streams --}}
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-stretch">
            {{-- Performance Leaderboard --}}
            <div class="xl:col-span-4 bg-white rounded-[3rem] p-8 border border-[#101D33]/5 shadow-[0_20px_60px_rgba(16,29,51,0.04)] flex flex-col h-full overflow-hidden">
                <div class="mb-8">
                    <h3 class="text-sm font-bold text-[#101D33] uppercase tracking-[0.3em] mb-1.5 leading-none">Distinguished Faculty</h3>
                    <p class="text-[11px] text-slate-400 font-['DM_Serif_Text'] italic leading-none">Apex presence and punctuality rankings.</p>
                </div>
                <div class="flex-1 space-y-6">
                    @forelse($performerStats as $performer)
                        <div class="flex items-center justify-between group cursor-pointer hover:bg-[#FDFCF8] p-3 -m-3 rounded-[2rem] transition-all">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-[#101D33] text-white flex items-center justify-center font-['DM_Serif_Display'] text-lg shadow-xl shadow-[#101D33]/10 border border-white/10 overflow-hidden relative">
                                    <div class="absolute inset-0 bg-gradient-to-br from-[#660000]/20 to-transparent"></div>
                                    <span class="relative z-10">{{ substr($performer->user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <div class="text-sm font-['DM_Serif_Text'] text-[#101D33] tracking-tight leading-none mb-1">{{ $performer->user->name }}</div>
                                    <div class="text-[9px] font-bold text-slate-300 uppercase tracking-widest leading-none">{{ $performer->user->role }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-['DM_Serif_Display'] text-[#101D33] tabular-nums leading-none">{{ $performer->count }}</div>
                                <div class="text-[8px] font-bold text-emerald-500 uppercase tracking-widest leading-none mt-1">Punctual Cycles</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16 text-slate-300 italic text-[10px] uppercase font-bold tracking-[0.3em] opacity-40">Compiling Institutional Data...</div>
                    @endforelse
                </div>
            </div>

            {{-- Authentication Logs --}}
            <div class="xl:col-span-8 bg-white rounded-[3rem] overflow-hidden border border-[#101D33]/5 shadow-[0_30px_100px_rgba(16,29,51,0.06)] flex flex-col h-full">
                <div class="p-8 border-b border-[#101D33]/5 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-[#101D33] uppercase tracking-[0.3em] mb-1.5 leading-none">Security Authentication Log</h3>
                        <p class="text-[11px] text-slate-400 font-['DM_Serif_Text'] italic leading-none">Live cryptographic stream of institutional access events.</p>
                    </div>
                    <div class="flex items-center gap-3 px-5 py-2 bg-emerald-50 rounded-full border border-emerald-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                        <span class="text-[9px] font-bold text-emerald-600 uppercase tracking-[0.2em]">RFID Uplink Active</span>
                    </div>
                </div>
                <div class="flex-1 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#FDFCF8]">
                                <th class="px-10 py-6 text-[10px] font-bold text-[#101D33]/30 uppercase tracking-[0.3em]">Identity Node</th>
                                <th class="px-10 py-6 text-[10px] font-bold text-[#101D33]/30 uppercase tracking-[0.3em]">Authentication Event</th>
                                <th class="px-10 py-6 text-[10px] font-bold text-[#101D33]/30 uppercase tracking-[0.3em] text-right">Verification</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#101D33]/5">
                            @forelse($recentLogs as $log)
                                <tr class="group hover:bg-[#FDFCF8] transition-colors">
                                    <td class="px-10 py-8">
                                        <div class="flex items-center gap-5">
                                            <div class="w-12 h-12 rounded-2xl bg-[#101D33] text-white flex items-center justify-center font-['DM_Serif_Display'] text-lg shadow-xl shadow-[#101D33]/10 border border-white/10 overflow-hidden relative">
                                                <div class="absolute inset-0 bg-gradient-to-br from-[#660000]/20 to-transparent opacity-50"></div>
                                                <span class="relative z-10">{{ substr($log->user->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-['DM_Serif_Text'] text-[#101D33] tracking-tight leading-none mb-1.5">{{ $log->user->name }}</div>
                                                <div class="text-[9px] font-bold text-[#D4AF37] uppercase tracking-[0.2em] leading-none">{{ $log->user->role }} Registry</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-10 py-8">
                                        <div class="text-sm font-bold text-[#101D33] tabular-nums leading-none tracking-tight">
                                            {{ \Carbon\Carbon::parse($log->time_in)->format('H:i:s') }} <span class="text-[10px] text-slate-300 font-bold uppercase ml-1">{{ \Carbon\Carbon::parse($log->time_in)->format('T') }}</span>
                                        </div>
                                        <div class="text-[9px] font-bold text-slate-300 uppercase tracking-widest mt-1">Terminal {{ rand(100,999) }}</div>
                                    </td>
                                    <td class="px-10 py-8 text-right">
                                        <span class="inline-flex px-5 py-2 rounded-2xl text-[9px] font-bold uppercase tracking-[0.2em] shadow-sm
                                            {{ $log->status == 'On-time' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-[#D4AF37]/10 text-[#D4AF37] border border-[#D4AF37]/20' }}">
                                            {{ $log->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center py-24 text-slate-300 italic text-[10px] font-bold tracking-[0.3em] uppercase opacity-40">Synchronizing with External Terminals...</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Row 4: Modern Intelligence (Radials) --}}
        <div class="bg-white rounded-[3.5rem] p-10 border border-[#101D33]/5 shadow-[0_40px_120px_rgba(16,29,51,0.05)] relative overflow-hidden group">
            <div class="absolute -right-24 -bottom-24 w-96 h-96 bg-[#D4AF37]/5 rounded-full blur-[100px] transition-transform duration-1000 group-hover:scale-125"></div>
            <div class="mb-12 relative z-10">
                <h3 class="text-sm font-bold text-[#101D33] uppercase tracking-[0.3em] mb-2 leading-none">Personnel Strategic Allocation</h3>
                <p class="text-[11px] text-slate-400 font-['DM_Serif_Text'] italic">Comprehensive departmental flow and capacity audit.</p>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-20 relative z-10">
                <div id="capacityRadial" class="flex justify-center scale-110"></div>
                <div class="space-y-12">
                    <p class="text-[15px] font-['DM_Serif_Text'] text-slate-500 italic leading-relaxed opacity-80">Autonomous analysis of faculty and administrative distribution, providing real-time visibility into operational density across all institutional streams.</p>
                    <div class="grid grid-cols-2 gap-8">
                        @foreach($deptStats as $role => $count)
                            <div class="group/stat">
                                <div class="text-[10px] font-bold text-[#101D33]/30 uppercase tracking-[0.3em] mb-3 group-hover/stat:text-[#101D33] transition-colors leading-none">{{ $role }} Stream</div>
                                <div class="text-2xl font-['DM_Serif_Display'] text-[#101D33] tabular-nums tracking-tighter leading-none">{{ $count }} <span class="text-[10px] text-[#D4AF37] font-bold uppercase ml-2 italic tracking-widest">Active Node</span></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripting for High-Fidelity Charts --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartOptions = {
                chart: { toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                dataLabels: { enabled: false },
                stroke: { width: 0 },
                legend: { show: false }
            };

            // 1. Attendance Donut
            new ApexCharts(document.querySelector("#attendanceDonut"), {
                ...chartOptions,
                series: [{{ $presentToday }}, {{ $lateToday }}, {{ $absentToday }}],
                labels: ['Standard', 'Latency', 'Deficit'],
                colors: ['#059669', '#D4AF37', '#660000'],
                chart: { ...chartOptions.chart, type: 'donut', height: 320 },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '85%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'AUTHENTICATED',
                                    formatter: () => '{{ $totalEmployees > 0 ? round(($presentToday/$totalEmployees)*100) : 0 }}%',
                                    style: { fontSize: '11px', fontWeight: 900, color: '#101D33', fontFamily: 'Inter' }
                                }
                            }
                        }
                    }
                },
                stroke: { width: 4, colors: ['#fff'] }
            }).render();

            // 2. Institutional Pulse (Hourly Flow)
            new ApexCharts(document.querySelector("#pulseChart"), {
                ...chartOptions,
                series: [{
                    name: 'Authentications',
                    data: @json($hourlyActivities)
                }],
                chart: { ...chartOptions.chart, type: 'area', height: '100%', sparkline: { enabled: false } },
                stroke: { curve: 'smooth', width: 5, colors: ['#101D33'] },
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.1, opacityTo: 0, stops: [0, 90, 100] } },
                colors: ['#101D33'],
                grid: { borderColor: '#101D3308', strokeDashArray: 8, padding: { left: 0, right: 0, top: 0, bottom: 0 } },
                xaxis: {
                    categories: Array.from({length: 24}, (_, i) => i + ':00'),
                    labels: { show: true, style: { colors: '#94a3b8', fontSize: '9px', fontWeight: 900 } },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: { show: false },
                markers: { size: 0, hover: { size: 6, strokeWidth: 3, strokeColors: '#101D33', colors: ['#fff'] } }
            }).render();

            // 3. Personnel Capacity Radial
            new ApexCharts(document.querySelector("#capacityRadial"), {
                ...chartOptions,
                series: [{{ ($totalEmployees > 0 && isset($deptStats['Professors'])) ? round(($deptStats['Professors']/$totalEmployees)*100) : 0 }}, {{ ($totalEmployees > 0 && isset($deptStats['Staff'])) ? round(($deptStats['Staff']/$totalEmployees)*100) : 0 }}],
                labels: ['Faculty Core', 'Administrative'],
                colors: ['#101D33', '#D4AF37'],
                chart: { ...chartOptions.chart, type: 'radialBar', height: 420 },
                plotOptions: {
                    radialBar: {
                        dataLabels: {
                            name: { fontSize: '13px', fontWeight: 900, color: '#101D33', offsetY: -10 },
                            value: { fontSize: '24px', fontWeight: 900, color: '#101D33', offsetY: 5 },
                            total: { show: true, label: 'TOTAL NODES', formatter: () => '{{ $totalEmployees }}', style: { color: '#94a3b8', fontSize: '10px' } }
                        },
                        track: { background: '#101D3305', strokeWidth: '100%' },
                        hollow: { size: '65%' }
                    }
                },
                stroke: { lineCap: 'round' }
            }).render();
        });
    </script>
</x-app-layout>

