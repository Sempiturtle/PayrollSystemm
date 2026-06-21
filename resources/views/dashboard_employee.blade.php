<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="text-slate-400 font-medium text-sm">Personnel Dashboard</span>
            <span class="text-slate-300 text-sm">/</span>
            <span class="font-bold text-slate-800 text-sm">{{ Auth::user()->name }}</span>
        </div>
    </x-slot>

    <div class="space-y-6 animate-in-up">
        <!-- Employee Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-5">
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">System Access</h1>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Active
                    </span>
                </div>
                <p class="text-slate-500 mt-1 font-medium text-sm">
                    Academic Period 2026 <span class="mx-1 text-slate-300">•</span> AISAT Higher Education
                </p>
            </div>
            
            <div class="flex items-center gap-3 bg-white px-4 py-2.5 rounded-xl border border-slate-200/60 shadow-sm">
                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                    </svg>
                </div>
                <div class="text-left">
                    <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-0.5">Authenticated Position</div>
                    <div class="text-sm font-bold text-slate-800 tracking-tight uppercase leading-none">{{ Auth::user()->role }}</div>
                </div>
            </div>
        </div>

        @if($todayHoliday)
            <div class="glass-surface p-5 rounded-2xl flex items-center justify-between border-amber-200/50 bg-amber-50/20 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 shadow-sm shadow-amber-200/50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <div class="text-[9px] font-bold text-amber-600 uppercase tracking-wider">Institutional Break</div>
                        <div class="text-sm font-bold text-slate-800">{{ $todayHoliday->name }} <span class="text-amber-600 font-medium">({{ $todayHoliday->type }})</span></div>
                    </div>
                </div>
                <div class="hidden sm:block text-[10px] font-bold text-amber-600 bg-white shadow-sm border border-amber-100 px-3 py-1 rounded-full uppercase tracking-wider">
                    {{ $todayHoliday->is_paid ? 'Paid Holiday' : 'Non-Paid Suspension' }}
                </div>
            </div>
        @endif

        {{-- Top Stats Row --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
            {{-- Latest Disbursement --}}
            <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm hover:shadow-md hover:border-slate-300/80 transition-all duration-300 flex flex-col justify-between min-h-[130px] group">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Latest Disbursement</span>
                    <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4">
                    @if($myPayrolls->first())
                        <div class="text-2xl font-extrabold tracking-tight text-slate-900">
                            ₱{{ number_format($myPayrolls->first()->net_pay, 2) }}
                        </div>
                        <div class="text-[10px] text-emerald-600 font-semibold mt-1">Processed payout</div>
                    @else
                        <div class="text-sm text-slate-400 italic font-medium">Awaiting sync</div>
                    @endif
                </div>
            </div>

            {{-- LIVE CYCLE Widget --}}
            <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm hover:shadow-md hover:border-slate-300/80 transition-all duration-300 flex flex-col justify-between min-h-[130px] group relative overflow-hidden">
                <div class="absolute top-0 right-0 p-3">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Live Cycle</span>
                    <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-extrabold tracking-tight text-slate-900">
                        {{ number_format($cycleStats['current_hours'], 1) }}h
                    </div>
                    <div class="text-[10px] text-indigo-600 font-semibold mt-1">Hours accumulated</div>
                </div>
            </div>

            {{-- Leave Balances --}}
            <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm hover:shadow-md hover:border-slate-300/80 transition-all duration-300 flex flex-col justify-between min-h-[130px] group">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Leave Credits</span>
                    <div class="w-7 h-7 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-2 border-t border-slate-50 pt-3">
                    <div>
                        <div class="text-[9px] font-bold text-slate-400 uppercase">Sick</div>
                        <div class="text-base font-extrabold text-slate-800">{{ number_format($user->sick_leave_credits, 1) }}</div>
                    </div>
                    <div>
                        <div class="text-[9px] font-bold text-slate-400 uppercase">Vacation</div>
                        <div class="text-base font-extrabold text-slate-800">{{ number_format($user->vacation_leave_credits, 1) }}</div>
                    </div>
                </div>
            </div>

            {{-- Terminal Status --}}
            <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm hover:shadow-md hover:border-slate-300/80 transition-all duration-300 flex flex-col justify-between min-h-[130px] group">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Terminal Status</span>
                    <div class="w-7 h-7 rounded-lg bg-violet-50 text-violet-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    </div>
                </div>
                <div class="mt-4">
                    @if($todayLog)
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50"></span>
                            <span class="text-xl font-extrabold text-slate-900">Active</span>
                        </div>
                        <div class="text-[10px] text-emerald-600 font-semibold mt-1">Logged in today</div>
                    @else
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-slate-300"></span>
                            <span class="text-xl font-extrabold text-slate-400">Inactive</span>
                        </div>
                        <div class="text-[10px] text-slate-400 font-medium mt-1">No attendance yet</div>
                    @endif
                </div>
            </div>

            {{-- Clock --}}
            <div class="bg-slate-900 p-6 rounded-2xl border border-slate-800 shadow-lg flex flex-col justify-between min-h-[130px] relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 to-transparent"></div>
                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Institutional Clock</span>
                        <div class="w-7 h-7 rounded-lg bg-white/10 text-white flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div x-data="{ time: '' }" x-init="setInterval(() => { 
                            time = new Date().toLocaleTimeString('en-US', { timeZone: 'Asia/Manila', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }) 
                        }, 1000)" class="text-2xl font-extrabold tracking-tight text-white font-mono leading-none">
                            <span x-text="time || '00:00:00'"></span>
                        </div>
                        <div class="text-[9px] text-slate-500 font-semibold mt-1">Asia / Manila (PST)</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Weekly Schedule --}}
        @if($mySchedule->count() > 0)
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                <div>
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Work Shift Cycle</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5">Assigned Shift Schedule Matrix</p>
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    Active Roster
                </span>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-4 lg:grid-cols-7 divide-y sm:divide-y-0 lg:divide-x divide-slate-100">
                @php
                    $allDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                    $shortDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                    $today = now()->format('l');
                @endphp
                
                @foreach($allDays as $i => $day)
                    @php
                        $daySchedules = $mySchedule->where('day_of_week', $day);
                        $isToday = ($day === $today);
                    @endphp
                    <div class="p-5 text-center transition-all duration-300 relative group {{ $isToday ? 'bg-indigo-50/50 ring-2 ring-indigo-500/30 ring-inset' : 'hover:bg-slate-50/50' }} flex flex-col justify-between min-h-[140px]">
                        @if($isToday)
                            <div class="absolute top-2 left-1/2 -translate-x-1/2">
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-extrabold bg-indigo-600 text-white uppercase tracking-wider">Today</span>
                            </div>
                        @endif
                        <div class="text-[10px] font-extrabold uppercase tracking-widest {{ $isToday ? 'text-indigo-600 mt-2' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors mb-4">{{ $shortDays[$i] }}</div>
                        
                        @if($daySchedules->count() > 0)
                            <div class="space-y-3 py-1 flex-1 flex flex-col justify-center">
                                @foreach($daySchedules as $sched)
                                    <div class="space-y-0.5">
                                        <div class="font-extrabold text-xs text-slate-800 tracking-tight leading-none">
                                            {{ \Carbon\Carbon::parse($sched->start_time)->format('h:i') }} <span class="text-[8px] font-semibold text-slate-400">{{ \Carbon\Carbon::parse($sched->start_time)->format('A') }}</span>
                                        </div>
                                        <div class="text-[8px] text-slate-400 font-bold uppercase">to</div>
                                        <div class="font-extrabold text-xs text-slate-800 tracking-tight leading-none">
                                            {{ \Carbon\Carbon::parse($sched->end_time)->format('h:i') }} <span class="text-[8px] font-semibold text-slate-400">{{ \Carbon\Carbon::parse($sched->end_time)->format('A') }}</span>
                                        </div>
                                    </div>
                                    @if(!$loop->last)
                                        <div class="border-t border-slate-100/80 my-1.5"></div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="py-5 flex-1 flex items-center justify-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-bold {{ $isToday ? 'bg-indigo-100/50 text-indigo-700' : 'bg-slate-100 text-slate-400' }} uppercase tracking-wider">
                                    Offsite
                                </span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Intelligence Section --}}
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-stretch">
            {{-- Logs --}}
            <div class="xl:col-span-8 bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden flex flex-col h-full">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Personal Authentication Logs</h3>
                        <p class="text-[10px] text-slate-400 mt-0.5">Real-time attendance stream & check-in events</p>
                    </div>
                </div>
                <div class="flex-1 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="px-6 py-4 text-[9px] font-bold text-slate-400 uppercase tracking-wider">Operational Date</th>
                                <th class="px-6 py-4 text-[9px] font-bold text-slate-400 uppercase tracking-wider">Time-In Event</th>
                                <th class="px-6 py-4 text-[9px] font-bold text-slate-400 uppercase tracking-wider">Time-Out Event</th>
                                <th class="px-6 py-4 text-[9px] font-bold text-slate-400 uppercase tracking-wider text-right">Verdict</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($myLogs as $log)
                                <tr class="hover:bg-slate-50/40 transition-colors duration-150">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-slate-800 tracking-tight">{{ \Carbon\Carbon::parse($log->date)->format('M d, Y') }}</div>
                                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">{{ \Carbon\Carbon::parse($log->date)->format('l') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span class="text-sm font-bold text-slate-700 font-mono tracking-tight">{{ $log->time_in ? \Carbon\Carbon::parse($log->time_in)->format('h:i:A') : '--:--' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                            <span class="text-sm font-bold text-slate-700 font-mono tracking-tight">{{ $log->time_out ? \Carbon\Carbon::parse($log->time_out)->format('h:i:A') : '--:--' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider
                                            {{ $log->status == 'On-time' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-amber-50 text-amber-600 border border-amber-100' }}">
                                            {{ $log->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-16 text-center text-slate-400 italic text-xs font-medium uppercase tracking-wider">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            No attendance logs available
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Sidebar Intelligence --}}
            <div class="xl:col-span-4 flex flex-col gap-6">
                {{-- Upcoming Academic Breaks --}}
                <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden flex flex-col">
                    <div class="p-5 border-b border-slate-100">
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Academic Forecast</h3>
                        <p class="text-[10px] text-slate-400 mt-0.5">Upcoming holidays and calendar suspensions</p>
                    </div>
                    <div class="p-5 space-y-4 flex-1">
                        @forelse($upcomingHolidays as $holiday)
                            <div class="flex items-center gap-4 group">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200/60 flex flex-col items-center justify-center shrink-0 group-hover:bg-indigo-50 group-hover:border-indigo-100 transition-all duration-300">
                                    <span class="text-[8px] font-extrabold text-indigo-600 uppercase leading-none">{{ $holiday->date->format('M') }}</span>
                                    <span class="text-xs font-extrabold text-slate-800 leading-none mt-0.5">{{ $holiday->date->format('d') }}</span>
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-slate-800 tracking-tight leading-snug group-hover:text-indigo-600 transition-colors">{{ $holiday->name }}</div>
                                    <div class="text-[9px] font-semibold text-slate-400 uppercase tracking-wide mt-0.5">{{ $holiday->type }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-slate-400 italic text-xs py-6 uppercase tracking-wider font-semibold">
                                Regular Operations Active
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Disbursement history --}}
                <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden border-l-4 border-l-emerald-500 flex flex-col">
                    <div class="p-5 border-b border-slate-100">
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Performance Ledger</h3>
                        <p class="text-[10px] text-slate-400 mt-0.5">Disbursement history & audit trail</p>
                    </div>
                    
                    <div class="p-5 divide-y divide-slate-100 flex-1">
                        @forelse($myPayrolls as $payroll)
                            <div x-data="{ showTrace: false }" class="py-3 first:pt-0 last:pb-0">
                                <div class="flex items-center justify-between cursor-pointer group" @click="showTrace = !showTrace">
                                    <div>
                                        <div class="text-[9px] font-bold text-slate-400 uppercase">Cycle Ending</div>
                                        <div class="text-xs font-bold text-slate-800 tracking-tight group-hover:text-indigo-600 transition-colors">{{ $payroll->period_end->format('M d, Y') }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-extrabold text-emerald-600 tracking-tight">₱{{ number_format($payroll->net_pay, 2) }}</div>
                                        <div class="text-[9px] font-bold text-slate-400 tracking-wider mt-0.5 font-mono uppercase">{{ number_format($payroll->total_hours, 1) }} Hrs</div>
                                    </div>
                                </div>

                                {{-- Formula Trace (FISCAL TRANSPARENCY) --}}
                                <div x-show="showTrace" x-collapse class="mt-3 pt-3 border-t border-slate-100 space-y-2">
                                    <div class="text-[9px] font-bold text-indigo-600 uppercase tracking-wider mb-2">Audit Snapshot</div>
                                    @if($payroll->calculation_snapshot)
                                        <div class="grid grid-cols-1 gap-1 bg-slate-50 p-2.5 rounded-xl border border-slate-200/40">
                                            @foreach($payroll->calculation_snapshot as $key => $val)
                                                <div class="flex justify-between items-center text-[10px]">
                                                    <span class="font-medium text-slate-500 uppercase tracking-wider">{{ str_replace('_', ' ', $key) }}</span>
                                                    <span class="font-extrabold text-slate-850 font-mono">{{ is_numeric($val) ? number_format($val, 2) : $val }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-[9px] font-bold text-slate-400 italic">No snapshot available.</div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-slate-350 italic py-8 text-xs font-bold tracking-wider uppercase">
                                Awaiting Sync
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Support Card (Hardened Dispute Reporting) --}}
                <div x-data="{ open: false }" class="p-5 rounded-2xl bg-indigo-600 text-white shadow-lg relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-950/60 to-transparent"></div>
                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <div>
                            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white mb-4 group-hover:rotate-12 transition-transform duration-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h4 class="text-xs font-extrabold uppercase tracking-wider">Conflict Disclosure</h4>
                            <p class="text-[11px] text-indigo-100 mt-2 leading-relaxed">If you detect any fiscal discrepancies in your verified stream, use our encrypted reporting system.</p>
                        </div>
                        <button @click="open = true" class="mt-5 w-full py-3 bg-white text-slate-900 rounded-xl font-extrabold text-[10px] uppercase tracking-wider hover:bg-slate-50 transition active:scale-[0.98] shadow-md">
                            Initialize Discrepancy Protocol
                        </button>
                    </div>

                    {{-- Discrepancy Reporting Modal --}}
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
                         style="display: none;">
                        <div @click.away="open = false" class="bg-white rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl">
                            <div class="bg-slate-900 p-5 text-white">
                                <h3 class="text-sm font-bold uppercase tracking-wider">Formal Discrepancy Statement</h3>
                                <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-widest">Closed-Loop Resolution Protocol</p>
                            </div>
                            <form action="{{ route('discrepancies.store') }}" method="POST" class="p-5 space-y-5">
                                @csrf
                                <div>
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Target Ledger Cycle</label>
                                    <select name="payroll_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                                        @foreach($myPayrolls as $p)
                                            <option value="{{ $p->id }}">Disbursement #{{ $p->id }} ({{ $p->period_end->format('M Y') }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Nature of Discrepancy</label>
                                    <textarea name="description" rows="4" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none" placeholder="Explain the specific logic or attendance error detected..."></textarea>
                                </div>
                                <div class="flex gap-3 pt-2">
                                    <button type="button" @click="open = false" class="flex-1 py-3 bg-slate-100 text-slate-650 hover:bg-slate-200/60 rounded-xl font-bold text-[10px] uppercase tracking-wider transition">Cancel</button>
                                    <button type="submit" class="flex-1 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-[10px] uppercase tracking-wider shadow-md transition">Submit Report</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
