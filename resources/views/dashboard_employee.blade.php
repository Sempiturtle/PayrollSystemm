<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="text-slate-400 font-medium">Personnel Dashboard</span>
            <span class="text-slate-300">/</span>
            <span class="font-bold">{{ Auth::user()->name }}</span>
        </div>
    </x-slot>

    <div class="space-y-4 animate-in-up">
        <!-- Employee Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">System Access <span class="text-indigo-600">Active</span></h1>
                <p class="text-slate-500 mt-1 font-medium text-sm italic">Academic Period 2026 �?AISAT Higher Education</p>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="text-right">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Authenticated Position</div>
                    <div class="text-xl font-bold text-slate-900 tracking-tighter uppercase leading-none">{{ Auth::user()->role }}</div>
                </div>
            </div>
        </div>

        @if($todayHoliday)
            <div class="glass-surface p-5 rounded-xl flex items-center justify-between border-amber-200/50 bg-amber-50/20">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-10 rounded-2xl bg-amber-100 flex items-center justify-center text-amber-600 shadow-sm shadow-amber-200/50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-amber-500 uppercase tracking-[0.2em]">Institutional Break</div>
                        <div class="text-base font-bold text-slate-800">{{ $todayHoliday->name }} <span class="text-amber-600 italic">({{ $todayHoliday->type }})</span></div>
                    </div>
                </div>
                <div class="hidden sm:block text-xs font-bold text-amber-600 bg-white shadow-sm border border-amber-100 px-4 py-1.5 rounded-full italic uppercase tracking-wider">
                    {{ $todayHoliday->is_paid ? 'Paid Holiday' : 'Non-Paid Suspension' }}
                </div>
            </div>
        @endif

        {{-- Top Stats Row --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            {{-- Latest Disbursement --}}
            <div class="stat-high-level group p-5 bg-white shadow-xl shadow-slate-100/10 rounded-2xl">
                <div class="stat-label flex items-center justify-between font-black text-[10px] uppercase tracking-widest text-slate-400">
                    <span class="italic font-black">Latest Disbursement</span>
                    <svg class="w-4 h-4 text-slate-300 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                @if($myPayrolls->first())
                    <div class="mt-6 flex items-baseline gap-2">
                        <div class="stat-value text-xl font-black tracking-tighter tabular-nums text-slate-900 border-l-4 border-l-emerald-500 pl-4">₱{{ number_format($myPayrolls->first()->net_pay, 2) }}</div>
                    </div>
                @else
                    <div class="mt-6 text-slate-300 italic text-sm">Awaiting sync.</div>
                @endif
            </div>

            {{-- LIVE CYCLE Hardening Widget --}}
            <div class="stat-high-level group p-5 bg-indigo-50 border border-indigo-100 overflow-hidden relative rounded-2xl">
                <div class="absolute top-0 right-0 p-4">
                    <div class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                    </div>
                </div>
                <div class="stat-label flex items-center justify-between font-black text-[10px] uppercase tracking-widest text-indigo-500">
                    <span class="italic font-black text-indigo-500">Live Cycle</span>
                </div>
                <div class="mt-6">
                    <div class="stat-value text-xl font-black tracking-tighter tabular-nums text-slate-900">{{ number_format($cycleStats['current_hours'], 1) }}h</div>
                </div>
            </div>

            {{-- Leave Balances --}}
            <div class="stat-high-level group p-5 bg-white shadow-xl shadow-slate-100/10 rounded-2xl relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 w-12 h-10 bg-slate-50 rounded-full flex items-center justify-center text-slate-200">
                    <svg class="w-8 h-8 opacity-20" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 11-2 0 1 1 0 012 0zm-1 9a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                </div>
                <div class="stat-label font-black text-[10px] uppercase tracking-widest text-slate-400 italic mb-6">Leave Credits</div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-[10px] font-black text-emerald-600 uppercase tracking-widest leading-none mb-1">Sick</div>
                        <div class="text-xl font-black text-slate-900 tracking-tighter tabular-nums">{{ number_format($user->sick_leave_credits, 1) }}</div>
                    </div>
                    <div>
                        <div class="text-[10px] font-black text-indigo-600 uppercase tracking-widest leading-none mb-1">Vacation</div>
                        <div class="text-xl font-black text-slate-900 tracking-tighter tabular-nums">{{ number_format($user->vacation_leave_credits, 1) }}</div>
                    </div>
                </div>
            </div>

            {{-- Attendance --}}
            <div class="stat-high-level group p-5 bg-white rounded-2xl">
                <div class="stat-label font-black text-[10px] uppercase tracking-widest text-slate-400 italic">Terminal Status</div>
                @if($todayLog)
                    <div class="mt-6 flex items-center gap-3">
                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-lg shadow-emerald-500/50"></div>
                        <div class="stat-value text-xl font-black tracking-tighter text-slate-900 italic underline decoration-emerald-100 underline-offset-8">Active</div>
                    </div>
                @else
                    <div class="mt-6 flex items-center gap-3 opacity-50">
                        <div class="w-2.5 h-2.5 rounded-full bg-slate-200"></div>
                        <div class="stat-value text-xl font-black tracking-tighter text-slate-300 italic">Inactive</div>
                    </div>
                @endif
            </div>

            <div class="stat-high-level group p-5 bg-slate-950 !text-white overflow-hidden relative rounded-2xl">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 to-transparent"></div>
                <div class="relative z-10">
                    <div class="stat-label !text-slate-500 italic uppercase tracking-widest font-black text-[10px]">Institutional Clock</div>
                    <div x-data="{ time: '' }" x-init="setInterval(() => { 
                        time = new Date().toLocaleTimeString('en-US', { timeZone: 'Asia/Manila', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }) 
                    }, 1000)" class="text-xl font-black tracking-tighter tabular-nums mt-6 text-white drop-shadow-2xl font-mono">
                        <span x-text="time || '00:00:00'"></span>
                    </div>
                </div>
            </div>
        </div>

                {{-- Official Schedule Image --}}
        <div class="card-modern bg-white overflow-hidden flex flex-col shadow-xl shadow-indigo-100/20">
            <div class="p-5 border-b border-slate-50 bg-slate-50/10 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-black text-indigo-600 uppercase tracking-[0.2em] italic">Work Shift Cycle</h3>
                    <p class="text-[10px] text-slate-400 mt-1 font-bold uppercase tracking-widest leading-none">Official Institutional Schedule</p>
                </div>
                @if(Auth::user()->schedule_image)
                    <a href="{{ asset(Auth::user()->schedule_image) }}" target="_blank" class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl font-black text-[9px] uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition shadow-sm">
                        View Full Size
                    </a>
                @endif
            </div>
            
            <div class="p-4 flex items-center justify-center bg-slate-50/30">
                @if(Auth::user()->schedule_image)
                    <div class="w-full max-w-4xl rounded-2xl overflow-hidden border border-slate-100 shadow-inner bg-white p-2">
                        <img src="{{ asset(Auth::user()->schedule_image) }}" alt="Official Schedule" class="w-full h-auto object-contain max-h-[700px] rounded-xl">
                    </div>
                @elseif($mySchedule->count() > 0)
                    {{-- Fallback to structured schedule if no image yet --}}
                    <div class="w-full grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 divide-x divide-slate-100 border border-slate-50 rounded-2xl overflow-hidden">
                        @php
                            $allDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                            $shortDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                            $today = now()->format('l');
                        @endphp
                        
                        @foreach($allDays as $i => $day)
                            @php
                                $daySchedule = $mySchedule->firstWhere('day_of_week', $day);
                                $isToday = ($day === $today);
                            @endphp
                            <div class="p-4 text-center group transition-colors duration-500 {{ $isToday ? 'bg-indigo-600 !text-white' : 'bg-white hover:bg-slate-50' }}">
                                <div class="text-[10px] font-black uppercase tracking-[0.2em] {{ $isToday ? 'text-indigo-200' : 'text-slate-400 group-hover:text-indigo-600' }} mb-6 transition-colors">{{ $shortDays[$i] }}</div>
                                
                                @if($daySchedule)
                                    <div class="font-black text-lg tracking-tighter tabular-nums leading-none">{{ \Carbon\Carbon::parse($daySchedule->start_time)->format('h:i') }} <span class="text-[10px] uppercase opacity-40 italic">{{ \Carbon\Carbon::parse($daySchedule->start_time)->format('A') }}</span></div>
                                    <div class="h-6 flex items-center justify-center">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $isToday ? 'bg-indigo-300 animate-pulse' : 'bg-slate-100' }}"></span>
                                    </div>
                                    <div class="font-black text-lg tracking-tighter tabular-nums leading-none">{{ \Carbon\Carbon::parse($daySchedule->end_time)->format('h:i') }} <span class="text-[10px] uppercase opacity-40 italic">{{ \Carbon\Carbon::parse($daySchedule->end_time)->format('A') }}</span></div>
                                @else
                                    <div class="text-[10px] font-black {{ $isToday ? 'text-indigo-300' : 'text-slate-200' }} italic py-10 uppercase tracking-[0.2em]">Offsite</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-20 text-center w-full">
                        <svg class="w-12 h-12 text-slate-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <p class="text-sm font-black text-slate-300 uppercase tracking-widest italic">Signal Offline: No Schedule Protocol Loaded</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Intelligence Section --}}
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-4 items-stretch">
            {{-- Logs --}}
            <div class="xl:col-span-8 card-modern flex flex-col h-full bg-white overflow-hidden shadow-xl shadow-slate-100/10">
                <div class="p-5 border-b border-slate-50">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] italic">Personal Authentication Logs</h3>
                    <p class="text-[10px] text-slate-400 mt-1 font-bold uppercase tracking-widest leading-none">Institutional Attendance stream</p>
                </div>
                <div class="flex-1 overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Operational Date</th>
                                <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Time-In Event</th>
                                <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Time-Out Event</th>
                                <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Verdict</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($myLogs as $log)
                                <tr class="group hover:bg-slate-50 transition-colors">
                                    <td class="px-10 py-6">
                                        <div class="text-sm font-black text-slate-900 tracking-tighter">{{ \Carbon\Carbon::parse($log->date)->format('M d, Y') }}</div>
                                        <div class="text-[9px] font-bold text-slate-400 uppercase italic">{{ \Carbon\Carbon::parse($log->date)->format('l') }}</div>
                                    </td>
                                    <td class="px-10 py-6">
                                        <div class="text-sm font-black text-slate-700 tabular-nums">{{ $log->time_in ? \Carbon\Carbon::parse($log->time_in)->format('h:i:A') : '--:--' }}</div>
                                    </td>
                                    <td class="px-10 py-6">
                                        <div class="text-sm font-black text-slate-700 tabular-nums">{{ $log->time_out ? \Carbon\Carbon::parse($log->time_out)->format('h:i:A') : '--:--' }}</div>
                                    </td>
                                    <td class="px-10 py-6 text-right">
                                        <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest
                                            {{ $log->status == 'On-time' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                            {{ $log->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-20 text-center text-slate-300 italic text-sm font-black tracking-widest uppercase opacity-40 italic font-mono">Terminal Signal: No Records Found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Sidebar Intelligence --}}
            <div class="xl:col-span-4 flex flex-col gap-4">
                {{-- Upcoming Academic Breaks --}}
                <div class="card-modern flex flex-col bg-white overflow-hidden shadow-xl shadow-slate-100/10">
                    <div class="p-5 border-b border-slate-50">
                        <h3 class="text-xs font-black text-indigo-600 uppercase tracking-[0.2em] italic">Academic Forecast</h3>
                        <p class="text-[10px] text-slate-400 mt-1 font-bold uppercase tracking-widest leading-none">Institutional Milestone Stream</p>
                    </div>
                    <div class="p-5 space-y-4 flex-1">
                        @forelse($upcomingHolidays as $holiday)
                            <div class="flex gap-5 group">
                                <div class="w-12 h-10 rounded-2xl bg-slate-50 border border-slate-100/50 flex flex-col items-center justify-center shrink-0 group-hover:bg-indigo-50 group-hover:border-indigo-100 transition-all">
                                    <span class="text-[10px] font-black text-indigo-600 uppercase leading-none">{{ $holiday->date->format('M') }}</span>
                                    <span class="text-sm font-black text-slate-900 leading-none mt-1">{{ $holiday->date->format('d') }}</span>
                                </div>
                                <div>
                                    <div class="text-sm font-black text-slate-800 tracking-tight leading-none mb-1.5">{{ $holiday->name }}</div>
                                    <div class="text-[9px] font-bold text-slate-400 uppercase italic tracking-widest">{{ $holiday->type }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-slate-200 italic text-sm py-4 uppercase font-bold tracking-widest">Regular Operations</div>
                        @endforelse
                    </div>
                </div>

                {{-- Disbursement history --}}
                <div class="card-modern flex flex-col bg-white overflow-hidden shadow-xl shadow-slate-100/10 border-l-4 border-l-emerald-500">
                    <div class="p-5 border-b border-slate-50">
                        <h3 class="text-xs font-black text-emerald-600 uppercase tracking-[0.2em] italic">Performance Ledger</h3>
                        <p class="text-[10px] text-slate-400 mt-1 font-bold uppercase tracking-widest leading-none">Verified Payout History</p>
                    </div>
                    
                    <div class="p-5 space-y-4 flex-1">
                        @forelse($myPayrolls as $payroll)
                            <div x-data="{ showTrace: false }" class="flex flex-col group p-4 -m-4 rounded-[2rem] transition-all hover:bg-slate-50">
                                <div class="flex items-center justify-between cursor-pointer" @click="showTrace = !showTrace">
                                    <div>
                                        <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1.5">Cycle Ending</div>
                                        <div class="text-sm font-black text-slate-800 tracking-tight">{{ $payroll->period_end->format('M d, Y') }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-base font-black text-emerald-600 tracking-tighter">₱{{ number_format($payroll->net_pay, 2) }}</div>
                                        <div class="text-[9px] font-bold text-slate-400 tabular-nums font-mono">{{ number_format($payroll->total_hours, 1) }} HRS</div>
                                    </div>
                                </div>

                                {{-- Formula Trace (FISCAL TRANSPARENCY) --}}
                                <div x-show="showTrace" x-collapse class="mt-4 pt-4 border-t border-slate-100 space-y-3">
                                    <div class="text-[9px] font-black text-indigo-600 uppercase tracking-widest mb-2">Audit-Proof Snapshot</div>
                                    @if($payroll->calculation_snapshot)
                                        @foreach($payroll->calculation_snapshot as $key => $val)
                                            <div class="flex justify-between items-center bg-white px-3 py-2 rounded-xl border border-slate-100 shadow-sm">
                                                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ str_replace('_', ' ', $key) }}</span>
                                                <span class="text-[10px] font-black text-slate-900 tabular-nums">{{ is_numeric($val) ? number_format($val, 2) : $val }}</span>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="text-[9px] font-bold text-slate-400 italic">No snapshot available for legacy record.</div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-slate-300 italic py-8 text-sm font-bold tracking-widest uppercase opacity-40">Awaiting Sync</div>
                        @endforelse
                    </div>
                </div>

                {{-- Support Card (Hardened Dispute Reporting) --}}
                <div x-data="{ open: false }" class="glass-surface p-5 rounded-2xl bg-indigo-600 text-white shadow-2xl shadow-indigo-200 relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/50 to-transparent"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-10 rounded-2xl bg-white/10 flex items-center justify-center text-white mb-8 group-hover:rotate-12 transition-transform duration-500 rotate-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h4 class="text-sm font-black uppercase italic tracking-[0.1em]">Conflict Disclosure</h4>
                        <p class="text-[11px] text-indigo-100 mt-4 leading-relaxed font-bold italic">If you detect any fiscal discrepancies in your verified stream, use our encrypted reporting system.</p>
                        <button @click="open = true" class="mt-8 w-full py-4 bg-white text-slate-900 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-50 transition active:scale-95 shadow-xl shadow-white/10">
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
                         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm"
                         style="display: none;">
                        <div @click.away="open = false" class="bg-white rounded-[2rem] w-full max-w-lg overflow-hidden shadow-2xl">
                            <div class="bg-slate-900 p-4 text-white">
                                <h3 class="text-sm font-black uppercase tracking-widest italic">Formal Discrepancy Statement</h3>
                                <p class="text-[10px] text-slate-500 mt-2 font-black uppercase tracking-widest">Closed-Loop Resolution Protocol</p>
                            </div>
                            <form action="{{ route('discrepancies.store') }}" method="POST" class="p-4 space-y-6">
                                @csrf
                                <div>
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic block mb-3">Target Ledger Cycle</label>
                                    <select name="payroll_id" class="w-full bg-slate-50 border-none rounded-2xl text-sm font-black text-slate-800 p-4 focus:ring-2 focus:ring-indigo-500 transition-all">
                                        @foreach($myPayrolls as $p)
                                            <option value="{{ $p->id }}">Disbursement #{{ $p->id }} ({{ $p->period_end->format('M Y') }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic block mb-3">Nature of Discrepancy</label>
                                    <textarea name="description" rows="5" class="w-full bg-slate-50 border-none rounded-2xl text-sm font-black text-slate-800 p-4 focus:ring-2 focus:ring-indigo-500 transition-all" placeholder="Explain the specific logic or attendance error detected..."></textarea>
                                </div>
                                <div class="flex gap-4">
                                    <button type="button" @click="open = false" class="flex-1 py-4 bg-slate-100 text-slate-600 rounded-2xl font-black text-[10px] uppercase tracking-widest">Cancel</button>
                                    <button type="submit" class="flex-1 py-4 bg-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-indigo-100">Submit Report</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
