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
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 pb-3 border-b border-[#101D33]/5">
            <div>
                <h1 class="text-3xl font-['DM_Serif_Display'] text-[#101D33] leading-none mb-2">Institutional <span class="text-[#D4AF37]">Command</span> Center</h1>
                <p class="text-[10px] text-[#101D33]/30 font-black uppercase tracking-[0.4em]">Real-time Executive Oversight & Fiscal Governance</p>
            </div>
            
            <div class="flex items-center gap-4">
                <a href="{{ route('exports.executive-summary') }}" class="px-6 py-3 bg-[#101D33] text-[#D4AF37] rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-[#660000] hover:text-white transition-all shadow-xl flex items-center gap-3 group">
                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Institutional Export
                </a>
                <div class="hidden xl:flex items-center gap-4 px-6 py-3 bg-white rounded-[1.5rem] border border-[#101D33]/5 shadow-[0_10px_30px_rgba(16,29,51,0.03)]">
                    <div class="text-right">
                        <div class="text-[8px] font-black text-[#101D33]/30 uppercase tracking-[0.3em] mb-1 leading-none">Central Terminal Time</div>
                        <div class="text-base font-bold text-[#101D33] tabular-nums leading-none tracking-tight">
                            <span x-data="{ time: '' }" x-init="setInterval(() => { 
                                time = new Date().toLocaleTimeString('en-US', { timeZone: 'Asia/Manila', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }) 
                            }, 1000)" x-text="time || '00:00:00'"></span>
                        </div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-[#101D33]/5 flex items-center justify-center text-[#101D33]/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('attendance.scanner') }}" class="group relative px-6 py-3.5 bg-[#101D33] text-white rounded-[1.5rem] font-bold text-xs shadow-[0_20px_40px_rgba(16,29,51,0.2)] transition-all hover:scale-[1.02] active:scale-[0.98] overflow-hidden flex items-center gap-3">
                        <div class="absolute inset-0 bg-gradient-to-tr from-[#660000]/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <svg class="w-4 h-4 relative z-10 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        <span class="relative z-10">Initialize Terminal</span>
                    </a>
                @endif
            </div>
        </div>

        @if($todayHoliday)
            <div class="bg-[#101D33] text-white p-5 rounded-[2rem] flex items-center justify-between border border-white/10 shadow-2xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-64 h-64 bg-[#660000]/20 blur-3xl -mr-32 -mt-32 transition-transform group-hover:scale-110"></div>
                <div class="flex items-center gap-5 relative z-10">
                    <div class="w-12 h-12 rounded-2xl bg-[#D4AF37] flex items-center justify-center text-[#101D33] shadow-xl shadow-[#D4AF37]/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <div class="text-[9px] font-bold text-[#D4AF37] uppercase tracking-[0.3em] mb-1">Academic Observance</div>
                        <div class="text-lg font-['DM_Serif_Display'] tracking-tight">{{ $todayHoliday->name }} <span class="text-white/40 italic font-['DM_Serif_Text'] text-xs">({{ $todayHoliday->type }})</span></div>
                    </div>
                </div>
                <div class="hidden sm:block relative z-10 text-[9px] font-black text-[#101D33] bg-[#D4AF37] px-5 py-2 rounded-xl italic uppercase tracking-[0.2em] shadow-lg shadow-[#D4AF37]/10">Holiday Remuneration Active</div>
            </div>
        @endif

        @if($incompleteProfilesCount > 0)
            <div class="bg-[#660000]/5 border border-[#660000]/10 p-5 rounded-[2rem] flex items-center justify-between group">
                <div class="flex items-center gap-5">
                    <div class="w-12 h-12 rounded-2xl bg-[#660000] flex items-center justify-center text-white shadow-xl shadow-[#660000]/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <div class="text-[9px] font-bold text-[#660000] uppercase tracking-[0.3em] mb-1">Integrity Breach</div>
                        <div class="text-base font-['DM_Serif_Text'] text-[#101D33] leading-none">{{ $incompleteProfilesCount }} Personnel Records Require Statutory Auditing</div>
                    </div>
                </div>
                <a href="{{ route('employees.index') }}" class="hidden sm:block text-[9px] font-bold text-white bg-[#101D33] px-5 py-2.5 rounded-xl uppercase tracking-[0.2em] hover:bg-[#660000] transition-all shadow-lg shadow-[#101D33]/10">Execute Audit</a>
            </div>
        @endif

        {{-- Executive Command Center --}}
        @if($pendingLeaves > 0 || $pendingDiscrepancies > 0 || $unfinalizedPayrolls > 0)
        <div class="bg-white rounded-[3rem] overflow-hidden border border-[#101D33]/5 shadow-[0_30px_100px_rgba(16,29,51,0.06)] relative group">
            <div class="absolute top-0 right-0 w-96 h-96 bg-[#101D33]/5 blur-[120px] -mr-48 -mt-48 transition-opacity group-hover:opacity-100 opacity-50"></div>
            <div class="p-6 border-b border-[#101D33]/5 flex items-center justify-between relative z-10">
                <div>
                    <h3 class="text-xs font-bold text-[#660000] uppercase tracking-[0.3em] mb-1">Administrative Priority</h3>
                    <p class="text-[10px] text-slate-400 font-['DM_Serif_Text'] italic">High-impact tasks awaiting authorization.</p>
                </div>
                <div class="flex items-center gap-2.5 px-4 py-2 bg-[#660000]/5 rounded-full border border-[#660000]/10">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#660000] animate-pulse"></span>
                    <span class="text-[9px] font-bold text-[#660000] uppercase tracking-[0.2em]">{{ $pendingLeaves + $pendingDiscrepancies + $unfinalizedPayrolls }} Urgent Actions</span>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 relative z-10">
                {{-- Pending Leaves --}}
                <a href="{{ route('leaves.index') }}" class="p-6 flex flex-col items-center text-center gap-4 group hover:bg-[#FDFCF8] transition-all border-b sm:border-b-0 sm:border-r border-[#101D33]/5">
                    <div class="w-12 h-12 rounded-[1.5rem] {{ $pendingLeaves > 0 ? 'bg-[#D4AF37] shadow-[0_15px_40px_rgba(212,175,55,0.3)]' : 'bg-slate-50' }} flex items-center justify-center text-[#101D33] transition-transform group-hover:-translate-y-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <div class="text-2xl font-['DM_Serif_Display'] text-[#101D33] tabular-nums leading-none">{{ $pendingLeaves }}</div>
                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1.5">Leave Authorizations</div>
                    </div>
                </a>

                {{-- Open Disputes --}}
                <a href="{{ route('admin.discrepancies.index') }}" class="p-6 flex flex-col items-center text-center gap-4 group hover:bg-[#FDFCF8] transition-all border-b sm:border-b-0 sm:border-r border-[#101D33]/5">
                    <div class="w-12 h-12 rounded-[1.5rem] {{ $pendingDiscrepancies > 0 ? 'bg-[#660000] text-white shadow-[0_15px_40px_rgba(102,0,0,0.3)]' : 'bg-slate-50 text-slate-300' }} flex items-center justify-center transition-transform group-hover:-translate-y-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <div class="text-2xl font-['DM_Serif_Display'] text-[#101D33] tabular-nums leading-none">{{ $pendingDiscrepancies }}</div>
                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1.5">Fiscal Disputes</div>
                    </div>
                </a>

                {{-- Unfinalized Payrolls --}}
                <a href="{{ route('payrolls.index') }}" class="p-6 flex flex-col items-center text-center gap-4 group hover:bg-[#FDFCF8] transition-all border-[#101D33]/5">
                    <div class="w-12 h-12 rounded-[1.5rem] {{ $unfinalizedPayrolls > 0 ? 'bg-[#101D33] text-white shadow-[0_15px_40px_rgba(16,29,51,0.3)]' : 'bg-slate-50 text-slate-300' }} flex items-center justify-center transition-transform group-hover:-translate-y-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <div>
                        <div class="text-2xl font-['DM_Serif_Display'] text-[#101D33] tabular-nums leading-none">{{ $unfinalizedPayrolls }}</div>
                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1.5">Unfinalized Ledgers</div>
                    </div>
                </a>
            </div>
        </div>
        @endif

        {{-- Top Metrics --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-[2rem] p-6 border border-[#101D33]/5 shadow-[0_20px_60px_rgba(16,29,51,0.04)] flex flex-col justify-between h-48 relative overflow-hidden group">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-[#101D33]/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
                <div class="flex items-center justify-between relative z-10">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em]">Personnel Capital</span>
                    <div class="w-9 h-9 rounded-xl bg-[#101D33]/5 flex items-center justify-center text-[#101D33]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-2 relative z-10">
                    <div class="text-3xl font-['DM_Serif_Display'] text-[#101D33] tracking-tighter tabular-nums">{{ $totalEmployees }}</div>
                    <div class="mt-1 flex items-center gap-2">
                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded text-[8px] font-bold uppercase tracking-widest">+1 (30d)</span>
                        <span class="text-[8px] font-bold text-slate-300 uppercase tracking-widest">Growth Vector</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] p-6 border border-[#101D33]/5 shadow-[0_20px_60px_rgba(16,29,51,0.04)] flex flex-col justify-between h-48 relative overflow-hidden group border-b-4 border-b-emerald-600">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-emerald-500/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
                <div class="flex items-center justify-between relative z-10">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em]">Presence Index</span>
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                </div>
                <div class="mt-2 relative z-10">
                    <div class="text-3xl font-['DM_Serif_Display'] text-emerald-600 tracking-tighter tabular-nums">{{ $totalEmployees > 0 ? round(($presentToday / $totalEmployees) * 100) : 0 }}%</div>
                    <div class="mt-1 flex items-center gap-2">
                        <span class="text-[10px] font-['DM_Serif_Text'] text-[#101D33] italic opacity-60">{{ $presentToday }} Authenticated</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] p-6 border border-[#101D33]/5 shadow-[0_20px_60px_rgba(16,29,51,0.04)] flex flex-col justify-between h-48 relative overflow-hidden group">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-[#660000]/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
                <div class="flex items-center justify-between relative z-10">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em]">Cycle Latency</span>
                    <div class="w-9 h-9 rounded-xl bg-[#660000]/5 flex items-center justify-center text-[#660000]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-2 relative z-10">
                    <div class="text-3xl font-['DM_Serif_Display'] {{ $pendingLeaves > 0 ? 'text-[#660000]' : 'text-slate-200' }} tracking-tighter tabular-nums">{{ $pendingLeaves }}</div>
                    <div class="mt-1 flex items-center gap-2">
                        <span class="text-[8px] font-bold text-[#660000] uppercase tracking-[0.2em]">Pending Auth</span>
                    </div>
                </div>
            </div>

            <div class="bg-[#101D33] text-white rounded-[2rem] p-6 shadow-[0_30px_60px_rgba(16,29,51,0.2)] flex flex-col justify-between h-48 relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-tr from-[#660000]/30 to-transparent opacity-50"></div>
                <div class="flex items-center justify-between relative z-10">
                    <span class="text-[9px] font-bold text-[#D4AF37] uppercase tracking-[0.3em]">Institutional Fiscal Pulse</span>
                    <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center text-[#D4AF37]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-2 relative z-10">
                    <div class="flex items-baseline gap-2">
                        <div class="text-3xl font-['DM_Serif_Display'] text-white tabular-nums leading-none">₱{{ number_format($institutionalPayrollPulse, 2) }}</div>
                    </div>
                    <div class="mt-3 flex items-center gap-2.5">
                        <div class="flex-1 bg-white/10 h-1 rounded-full overflow-hidden">
                            @php 
                                $totalRows = ($statStats->finalized_count + $statStats->draft_count) ?: 1;
                                $percent = ($statStats->finalized_count / $totalRows) * 100;
                            @endphp
                            <div class="bg-[#D4AF37] h-full transition-all duration-1000" style="width: {{ $percent }}%"></div>
                        </div>
                        <span class="text-[9px] font-black text-white tabular-nums tracking-tighter">{{ round($percent) }}%</span>
                    </div>
                    <div class="mt-1.5 text-[8px] font-bold text-white/30 uppercase tracking-[0.2em]">{{ $statStats->finalized_count }} Finalized Ledgers This Cycle</div>
                </div>
            </div>
        </div>


        {{-- Row 2: Operational & Visual Intelligence --}}
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-stretch">
            {{-- Attendance Distribution (Donut) --}}
            <div class="xl:col-span-4 bg-white rounded-[2.5rem] p-6 border border-[#101D33]/5 shadow-[0_20px_60px_rgba(16,29,51,0.04)] flex flex-col h-full overflow-hidden">
                <div class="mb-6">
                    <h3 class="text-xs font-bold text-[#101D33] uppercase tracking-[0.3em] mb-1 leading-none">Authentication Flux</h3>
                    <p class="text-[10px] text-slate-400 font-['DM_Serif_Text'] italic leading-none">Real-time presence distribution.</p>
                </div>
                <div class="flex-1 flex flex-col justify-between">
                    <div id="attendanceDonut" class="flex justify-center py-4"></div>
                    <div class="grid grid-cols-3 gap-4 border-t border-[#101D33]/5 pt-6 mt-3">
                        <div class="text-center">
                            <div class="text-lg font-['DM_Serif_Display'] text-[#101D33] tabular-nums">{{ $presentToday }}</div>
                            <div class="text-[8px] font-bold text-emerald-500 uppercase tracking-widest mt-1 leading-none">Standard</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-['DM_Serif_Display'] text-[#101D33] tabular-nums">{{ $lateToday }}</div>
                            <div class="text-[8px] font-bold text-[#D4AF37] uppercase tracking-widest mt-1 leading-none">Latency</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-['DM_Serif_Display'] text-[#101D33] tabular-nums">{{ $absentToday }}</div>
                            <div class="text-[8px] font-bold text-[#660000] uppercase tracking-widest mt-1 leading-none">Deficit</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Institutional Pulse (Hourly Flow Area Chart) --}}
            <div class="xl:col-span-8 bg-white rounded-[2.5rem] p-6 border border-[#101D33]/5 shadow-[0_20px_60px_rgba(16,29,51,0.04)] flex flex-col h-full overflow-hidden">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xs font-bold text-[#101D33] uppercase tracking-[0.3em] mb-1 leading-none">Institutional Pulse</h3>
                        <p class="text-[10px] text-slate-400 font-['DM_Serif_Text'] italic leading-none">Hourly authentication stream.</p>
                    </div>
                    <div class="flex items-center gap-3 px-4 py-1.5 bg-[#101D33]/5 rounded-full border border-[#101D33]/10">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#101D33] animate-pulse"></span>
                        <span class="text-[8px] font-bold text-[#101D33] uppercase tracking-[0.2em]">Live Stream</span>
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
            <div class="xl:col-span-4 bg-white rounded-[2.5rem] p-6 border border-[#101D33]/5 shadow-[0_20px_60px_rgba(16,29,51,0.04)] flex flex-col h-full overflow-hidden">
                <div class="mb-6">
                    <h3 class="text-xs font-bold text-[#101D33] uppercase tracking-[0.3em] mb-1 leading-none">Institutional Apex</h3>
                    <p class="text-[10px] text-slate-400 font-['DM_Serif_Text'] italic leading-none">Distinguished faculty presence rankings.</p>
                </div>
                <div class="flex-1 space-y-4">
                    @forelse($performerStats as $performer)
                        <div class="flex items-center justify-between p-2 rounded-2xl hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-[#101D33] text-white flex items-center justify-center font-['DM_Serif_Display'] text-xs">{{ substr($performer->user->name, 0, 1) }}</div>
                                <div>
                                    <div class="text-[11px] font-['DM_Serif_Text'] text-[#101D33] leading-none mb-1">{{ $performer->user->name }}</div>
                                    <div class="text-[7px] font-bold text-slate-300 uppercase tracking-widest leading-none">{{ $performer->user->role }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs font-['DM_Serif_Display'] text-emerald-600 tabular-nums">{{ $performer->count }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-300 italic text-[10px] uppercase font-bold tracking-[0.3em]">Compiling...</div>
                    @endforelse
                </div>

                <div class="mt-6 pt-6 border-t border-[#101D33]/5">
                    <h3 class="text-[9px] font-bold text-[#660000] uppercase tracking-[0.3em] mb-4 leading-none">Live Audit Stream</h3>
                    <div class="space-y-4">
                        @foreach($recentAuditStream as $audit)
                        <div class="flex gap-3 relative">
                            <div class="w-0.5 bg-[#101D33]/5 absolute left-2 top-4 bottom-0"></div>
                            <div class="w-4 h-4 rounded-full bg-white border-2 border-[#101D33] flex-shrink-0 z-10"></div>
                            <div>
                                <div class="text-[9px] font-['DM_Serif_Text'] text-[#101D33] leading-none mb-1">{{ $audit->user->name ?? 'System' }}</div>
                                <div class="text-[8px] font-bold text-slate-400 uppercase tracking-widest leading-tight">
                                    {{ $audit->event }}
                                </div>
                                <div class="text-[7px] font-bold text-[#D4AF37] uppercase tracking-tighter mt-1">{{ $audit->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Authentication Logs --}}
            <div class="xl:col-span-8 bg-white rounded-[2.5rem] overflow-hidden border border-[#101D33]/5 shadow-[0_30px_100px_rgba(16,29,51,0.06)] flex flex-col h-full">
                <div class="p-6 border-b border-[#101D33]/5 flex items-center justify-between">
                    <div>
                        <h3 class="text-xs font-bold text-[#101D33] uppercase tracking-[0.3em] mb-1 leading-none">Authentication Log</h3>
                        <p class="text-[10px] text-slate-400 font-['DM_Serif_Text'] italic leading-none">Live stream of access events.</p>
                    </div>
                    <div class="flex items-center gap-2.5 px-4 py-1.5 bg-emerald-50 rounded-full border border-emerald-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                        <span class="text-[8px] font-bold text-emerald-600 uppercase tracking-[0.2em]">Uplink Active</span>
                    </div>
                </div>
                <div class="flex-1 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#FDFCF8]">
                                <th class="px-8 py-4 text-[9px] font-bold text-[#101D33]/30 uppercase tracking-[0.3em]">Identity Node</th>
                                <th class="px-8 py-4 text-[9px] font-bold text-[#101D33]/30 uppercase tracking-[0.3em]">Auth Event</th>
                                <th class="px-8 py-4 text-[9px] font-bold text-[#101D33]/30 uppercase tracking-[0.3em] text-right">Verification</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#101D33]/5">
                            @forelse($recentLogs as $log)
                                <tr class="group hover:bg-[#FDFCF8] transition-colors">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-[#101D33] text-white flex items-center justify-center font-['DM_Serif_Display'] text-base shadow-xl shadow-[#101D33]/10 border border-white/10 overflow-hidden relative">
                                                <div class="absolute inset-0 bg-gradient-to-br from-[#660000]/20 to-transparent opacity-50"></div>
                                                <span class="relative z-10">{{ substr($log->user->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-['DM_Serif_Text'] text-[#101D33] tracking-tight leading-none mb-1">{{ $log->user->name }}</div>
                                                <div class="text-[8px] font-bold text-[#D4AF37] uppercase tracking-[0.2em] leading-none">{{ $log->user->role }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-10 py-8">
                                        <div class="text-sm font-bold text-[#101D33] tabular-nums leading-none tracking-tight">
                                            {{ \Carbon\Carbon::parse($log->time_in)->format('H:i:s') }} <span class="text-[10px] text-slate-300 font-bold uppercase ml-1">{{ \Carbon\Carbon::parse($log->time_in)->format('T') }}</span>
                                        </div>
                                        <div class="text-[9px] font-bold text-slate-300 uppercase tracking-widest mt-1">Terminal {{ rand(100,999) }}</div>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <span class="inline-flex px-4 py-1.5 rounded-xl text-[8px] font-bold uppercase tracking-[0.2em] shadow-sm
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

        {{-- Row 4: Institutional Continuity Monitor --}}
        <div x-data="{ 
            modalOpen: false, 
            suggestions: [], 
            absentUser: null, 
            activeBlock: null,
            loading: false,
            fetchRelief(userId) {
                this.loading = true;
                this.modalOpen = true;
                fetch(`/admin/substitutions/suggestions/${userId}`)
                    .then(res => res.json())
                    .then(data => {
                        this.suggestions = data.suggestions;
                        this.absentUser = data.absent_user;
                        this.activeBlock = data.block;
                        this.loading = false;
                    });
            }
        }" class="space-y-4">
            <div class="flex items-center justify-between px-2">
                <h3 class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.3em] flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-[#660000] animate-pulse"></span>
                    Continuity Monitor
                </h3>
                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">{{ count($continuityAlerts) }} Active Latency Events</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($continuityAlerts as $alert)
                    <div class="bg-white rounded-[2rem] border border-[#660000]/10 p-6 shadow-xl shadow-[#660000]/5 relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-[#660000]/5 rounded-bl-full -mr-12 -mt-12 transition-transform group-hover:scale-110"></div>
                        
                        <div class="flex items-start gap-4 relative z-10">
                            <div class="w-12 h-12 rounded-2xl bg-[#660000] text-white flex items-center justify-center shadow-lg shadow-[#660000]/20 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-['DM_Serif_Text'] text-[#101D33] leading-tight mb-1 truncate">{{ $alert->name }}</h4>
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="px-2 py-0.5 bg-[#660000]/5 text-[#660000] text-[8px] font-black uppercase tracking-widest rounded-lg">Absent Node</span>
                                    <span class="text-[9px] text-slate-400 font-medium">Late by 20+ mins</span>
                                </div>
                                
                                @php $currentBlock = $alert->schedules->first(); @endphp
                                @if($currentBlock)
                                    <div class="p-3 bg-[#101D33]/5 rounded-xl border border-[#101D33]/5 mb-4">
                                        <div class="text-[8px] text-[#101D33]/40 font-bold uppercase tracking-widest mb-1">Active Block</div>
                                        <div class="text-[10px] font-bold text-[#101D33]">{{ $currentBlock->start_time }} — {{ $currentBlock->end_time }}</div>
                                    </div>
                                @endif

                                <button @click="fetchRelief({{ $alert->id }})" class="w-full py-2.5 bg-[#101D33] text-[#D4AF37] text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-[#660000] hover:text-white transition-all shadow-lg flex items-center justify-center gap-2">
                                    Identify Relief Staff
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="lg:col-span-3 py-12 text-center bg-white rounded-[3rem] border border-[#101D33]/5 border-dashed">
                        <p class="text-[10px] font-['DM_Serif_Text'] italic text-slate-300 uppercase tracking-widest">Institutional Continuity Perfected — No Disruptions Detected</p>
                    </div>
                @endforelse
            </div>

            <!-- Relief Suggestion Modal -->
            <div x-show="modalOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 md:p-12">
                <div @click="modalOpen = false" class="absolute inset-0 bg-[#101D33]/60 backdrop-blur-md transition-opacity"></div>
                
                <div x-show="modalOpen" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     class="bg-white w-full max-w-2xl rounded-[3rem] shadow-[0_40px_100px_rgba(16,29,51,0.3)] border border-white/20 relative z-10 overflow-hidden flex flex-col max-h-[80vh]">
                    
                    <div class="p-8 border-b border-[#101D33]/5 flex justify-between items-center bg-[#FDFCF8]">
                        <div>
                            <h2 class="text-2xl font-['DM_Serif_Display'] text-[#101D33] leading-none mb-2">Relief <span class="text-[#D4AF37]">Intelligence</span></h2>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-[0.2em]">Institutional Matching Algorithm</p>
                        </div>
                        <button @click="modalOpen = false" class="p-3 text-[#101D33]/20 hover:text-[#101D33] hover:bg-[#101D33]/5 rounded-2xl transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                        <div x-show="loading" class="py-20 text-center">
                            <div class="w-12 h-12 border-4 border-[#D4AF37]/20 border-t-[#D4AF37] rounded-full animate-spin mx-auto mb-6"></div>
                            <p class="text-[10px] font-black text-[#101D33] uppercase tracking-widest">Querying Institutional Pulse...</p>
                        </div>

                        <div x-show="!loading" class="space-y-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-4 bg-[#660000]/5 rounded-2xl border border-[#660000]/10">
                                    <div class="text-[8px] font-black text-[#660000] uppercase tracking-widest mb-1">Absent Node</div>
                                    <div class="text-sm font-bold text-[#101D33]" x-text="absentUser?.name"></div>
                                </div>
                                <div class="p-4 bg-[#101D33]/5 rounded-2xl border border-[#101D33]/10">
                                    <div class="text-[8px] font-black text-[#101D33] uppercase tracking-widest mb-1">Block Target</div>
                                    <div class="text-sm font-bold text-[#101D33]" x-text="activeBlock ? `${activeBlock.start_time} - ${activeBlock.end_time}` : 'N/A'"></div>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <h4 class="text-[9px] font-black text-[#101D33] uppercase tracking-[0.3em] mb-4">Available Relief Candidates (On-Site)</h4>
                                
                                <template x-for="staff in suggestions" :key="staff.id">
                                    <form action="{{ route('substitutions.store') }}" method="POST" class="p-5 bg-white border border-[#101D33]/5 rounded-3xl hover:border-[#D4AF37] transition-all group shadow-sm hover:shadow-xl">
                                        @csrf
                                        <input type="hidden" name="absent_user_id" :value="absentUser?.id">
                                        <input type="hidden" name="relief_user_id" :value="staff.id">
                                        <input type="hidden" name="date" value="{{ date('Y-m-d') }}">
                                        <input type="hidden" name="start_time" :value="activeBlock?.start_time">
                                        <input type="hidden" name="end_time" :value="activeBlock?.end_time">

                                        <div class="flex items-center justify-between gap-6">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 rounded-2xl bg-[#D4AF37] text-[#101D33] flex items-center justify-center font-bold text-lg shadow-lg shadow-[#D4AF37]/10" x-text="staff.name.charAt(0)"></div>
                                                <div>
                                                    <div class="text-sm font-['DM_Serif_Text'] text-[#101D33]" x-text="staff.name"></div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Present & Available</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-4">
                                                <div class="text-right">
                                                    <div class="text-[8px] font-black text-[#101D33]/40 uppercase tracking-widest mb-1">Relief Premium</div>
                                                    <div class="text-xs font-bold text-emerald-600">₱500.00</div>
                                                </div>
                                                <button type="submit" class="p-3 bg-[#101D33] text-[#D4AF37] rounded-2xl hover:bg-[#660000] hover:text-white transition-all shadow-lg group-hover:scale-105">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </template>

                                <template x-if="suggestions.length === 0">
                                    <div class="py-12 text-center bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">No available relief staff localized on-site.</p>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Row 5: Modern Intelligence (Radials) --}}
        <div class="bg-white rounded-[3rem] p-8 border border-[#101D33]/5 shadow-[0_40px_120px_rgba(16,29,51,0.05)] relative overflow-hidden group">
            <div class="absolute -right-24 -bottom-24 w-96 h-96 bg-[#D4AF37]/5 rounded-full blur-[100px] transition-transform duration-1000 group-hover:scale-125"></div>
            <div class="mb-8 relative z-10">
                <h3 class="text-xs font-bold text-[#101D33] uppercase tracking-[0.3em] mb-1 leading-none">Personnel Allocation</h3>
                <p class="text-[10px] text-slate-400 font-['DM_Serif_Text'] italic leading-none">Departmental flow and capacity audit.</p>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-20 relative z-10">
                <div id="capacityRadial" class="flex justify-center scale-110"></div>
                <div class="space-y-8">
                    <p class="text-[14px] font-['DM_Serif_Text'] text-slate-500 italic leading-relaxed opacity-80">Autonomous analysis of faculty and administrative distribution, providing real-time visibility into operational density.</p>
                    <div class="grid grid-cols-2 gap-6">
                        @foreach($deptStats as $role => $count)
                            <div class="group/stat">
                                <div class="text-[9px] font-bold text-[#101D33]/30 uppercase tracking-[0.3em] mb-2 group-hover/stat:text-[#101D33] transition-colors leading-none">{{ $role }} Stream</div>
                                <div class="text-xl font-['DM_Serif_Display'] text-[#101D33] tabular-nums tracking-tighter leading-none">{{ $count }} <span class="text-[9px] text-[#D4AF37] font-bold uppercase ml-2 italic tracking-widest">Active</span></div>
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

