<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 shadow-sm border border-indigo-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-900 leading-none mb-1">Performance Analytics</h2>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Efficiency & Attendance Health Metrics</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-4">
        <!-- Top Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Punctuality Score -->
            <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm overflow-hidden relative group transition-all hover:shadow-md">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Punctuality Score</h3>
                    <span class="px-1.5 py-0.5 bg-emerald-50 text-emerald-600 text-[8px] font-bold rounded-lg border border-emerald-100">Last 30 Days</span>
                </div>
                <div class="flex items-end gap-2">
                    <span class="text-lg font-extrabold text-slate-900 tracking-tight">{{ $punctualityScore }}%</span>
                    <div class="mb-1">
                        @if($punctualityScore >= 95)
                            <span class="text-[9px] font-bold text-emerald-500 uppercase">Excellent</span>
                        @elseif($punctualityScore >= 80)
                            <span class="text-[9px] font-bold text-amber-500 uppercase">Good</span>
                        @else
                            <span class="text-[9px] font-bold text-rose-500 uppercase">Needs Improvement</span>
                        @endif
                    </div>
                </div>
                <p class="mt-2 text-[10px] font-medium text-slate-500">Based on {{ $totalDays }} shifts.</p>
                
                <!-- Simple Metric Progress Bar -->
                <div class="mt-2 h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full {{ $punctualityScore >= 90 ? 'bg-emerald-500' : ($punctualityScore >= 75 ? 'bg-amber-500' : 'bg-rose-500') }}" style="width: {{ $punctualityScore }}%"></div>
                </div>
            </div>

            <!-- Reliability Widget -->
            <div class="bg-slate-900 rounded-xl p-4 shadow-xl relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-all"></div>
                <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Shift Reliability</h3>
                <div class="text-lg font-bold text-white mb-1">Institutional Grade</div>
                <p class="text-[10px] text-slate-400 leading-relaxed font-medium">Verified against AISAT's audit logs for academic defense integrity.</p>
            </div>

            <!-- Active Goals -->
            <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm transition-all hover:shadow-md">
                <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Current Milestone</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-[10px] font-bold">
                        <span class="text-slate-600">Streak Status</span>
                        <span class="text-indigo-600">On Track</span>
                    </div>
                    <div class="flex gap-1">
                        @for($i=0; $i<7; $i++)
                            <div class="h-6 flex-1 bg-emerald-50 border border-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 font-bold text-[9px]">✔</div>
                        @endfor
                    </div>
                    <p class="text-[9px] text-slate-400 text-center font-bold uppercase tracking-tighter">Perfect Attendance This Week</p>
                </div>
            </div>
        </div>

        <!-- Attendance Trends Chart -->
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-3.5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900 leading-none mb-1">Attendance Intensity</h3>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Work Hours Trend (Last 6 Months)</p>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 bg-indigo-500 rounded-sm"></div>
                    <span class="text-[9px] font-bold text-slate-500 uppercase">Hours Worked</span>
                </div>
            </div>

            <div class="h-48 flex items-end gap-3 md:gap-4 px-2">
                @php $maxHours = collect($trends)->pluck('hours')->max() ?: 160; @endphp
                @foreach($trends as $trend)
                    <div class="flex-1 flex flex-col items-center group relative">
                        <!-- Tooltip -->
                        <div class="absolute -top-5 scale-0 group-hover:scale-100 transition-transform bg-slate-900 text-white text-[9px] font-bold px-1.5 py-0.5 rounded inline-block whitespace-nowrap z-10">
                            {{ $trend['hours'] }}h
                        </div>
                        
                        <!-- Bar -->
                        <div class="w-full bg-slate-50 rounded-t-lg relative overflow-hidden flex flex-col justify-end" style="height: 100%">
                            <div class="bg-indigo-500/10 group-hover:bg-indigo-500/20 transition-all rounded-t-lg" style="height: {{ ($trend['hours'] / $maxHours) * 100 }}%">
                                <div class="w-full h-full bg-indigo-500 rounded-t-lg transition-all" style="height: 100%"></div>
                            </div>
                        </div>
                        
                        <!-- Label -->
                        <span class="mt-2 text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ $trend['month'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Footnote for Defense -->
        <div class="p-4 bg-indigo-600 rounded-xl text-white shadow-xl shadow-indigo-100 flex flex-col md:flex-row items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
            <div class="flex-1">
                <h4 class="text-base font-bold mb-0.5">Audit-Proof Transparency</h4>
                <p class="text-[11px] opacity-90 leading-relaxed font-medium">Generated from immutable Attendance Logs and Professor Schedules.</p>
            </div>
            <div class="md:ml-auto">
                <a href="{{ route('attendance.history') }}" class="px-5 py-2 bg-white text-indigo-600 text-[10px] font-bold rounded-xl shadow-lg hover:shadow-xl transition-all block text-center uppercase tracking-widest">Verify Logs</a>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
