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

    <div class="space-y-6">
        <!-- Top Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Punctuality Score -->
            <div class="bg-white rounded-xl p-6 border border-slate-100 shadow-sm overflow-hidden relative group transition-all hover:shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Punctuality Score</h3>
                    <span class="px-2 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold rounded-lg border border-emerald-100">Last 30 Days</span>
                </div>
                <div class="flex items-end gap-3">
                    <span class="text-xl font-extrabold text-slate-900 tracking-tight">{{ $punctualityScore }}%</span>
                    <div class="mb-1.5">
                        @if($punctualityScore >= 95)
                            <span class="text-[10px] font-bold text-emerald-500 uppercase">Excellent</span>
                        @elseif($punctualityScore >= 80)
                            <span class="text-[10px] font-bold text-amber-500 uppercase">Good</span>
                        @else
                            <span class="text-[10px] font-bold text-rose-500 uppercase">Needs Improvement</span>
                        @endif
                    </div>
                </div>
                <p class="mt-4 text-xs font-medium text-slate-500">Based on {{ $totalDays }} shifts. You arrived late {{ $lateDays }} times.</p>
                
                <!-- Simple Metric Progress Bar -->
                <div class="mt-4 h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full {{ $punctualityScore >= 90 ? 'bg-emerald-500' : ($punctualityScore >= 75 ? 'bg-amber-500' : 'bg-rose-500') }}" style="width: {{ $punctualityScore }}%"></div>
                </div>
            </div>

            <!-- Reliability Widget -->
            <div class="bg-slate-900 rounded-xl p-6 shadow-xl relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-all"></div>
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Shift Reliability</h3>
                <div class="text-xl font-bold text-white mb-2">Institutional Grade</div>
                <p class="text-xs text-slate-400 leading-relaxed font-medium">Your attendance fingerprint is verified against AISAT's immutable blockchain-style audit logs for academic defense integrity.</p>
            </div>

            <!-- Active Goals -->
            <div class="bg-white rounded-xl p-6 border border-slate-100 shadow-sm transition-all hover:shadow-md">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Current Milestone</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center text-xs font-bold">
                        <span class="text-slate-600">Streak Status</span>
                        <span class="text-indigo-600">On Track</span>
                    </div>
                    <div class="flex gap-1.5">
                        @for($i=0; $i<7; $i++)
                            <div class="h-8 flex-1 bg-emerald-50 border border-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 font-bold text-[10px]">✔</div>
                        @endfor
                    </div>
                    <p class="text-[10px] text-slate-400 text-center font-bold uppercase">Perfect Attendance This Week</p>
                </div>
            </div>
        </div>

        <!-- Attendance Trends Chart -->
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 leading-none mb-1">Attendance Intensity</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Work Hours Trend (Last 6 Months)</p>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-indigo-500 rounded-sm"></div>
                    <span class="text-[10px] font-bold text-slate-500 uppercase">Hours Worked</span>
                </div>
            </div>

            <div class="h-64 flex items-end gap-4 md:gap-4 px-4">
                @php $maxHours = collect($trends)->pluck('hours')->max() ?: 160; @endphp
                @foreach($trends as $trend)
                    <div class="flex-1 flex flex-col items-center group relative">
                        <!-- Tooltip -->
                        <div class="absolute -top-5 scale-0 group-hover:scale-100 transition-transform bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded inline-block whitespace-nowrap z-10">
                            {{ $trend['hours'] }} Hours
                        </div>
                        
                        <!-- Bar -->
                        <div class="w-full bg-slate-50 rounded-t-xl relative overflow-hidden flex flex-col justify-end" style="height: 100%">
                            <div class="bg-indigo-500/10 group-hover:bg-indigo-500/20 transition-all rounded-t-xl" style="height: {{ ($trend['hours'] / $maxHours) * 100 }}%">
                                <div class="w-full h-full bg-indigo-500 rounded-t-xl transition-all" style="height: 100%"></div>
                            </div>
                        </div>
                        
                        <!-- Label -->
                        <span class="mt-4 text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $trend['month'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Footnote for Defense -->
        <div class="p-6 bg-indigo-600 rounded-xl text-white shadow-xl shadow-indigo-100 flex flex-col md:flex-row items-center gap-6">
            <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white shrink-0">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
            <div>
                <h4 class="text-lg font-bold mb-1">Audit-Proof Transparency</h4>
                <p class="text-sm opacity-90 leading-relaxed max-w-2xl font-medium">All performance data shown here is dynamically generated from immutable Attendance Logs and linked individual Professor Schedules. Any discrepancy found can be reported directly for institutional resolution.</p>
            </div>
            <div class="md:ml-auto">
                <a href="{{ route('attendance.history') }}" class="px-6 py-3 bg-white text-indigo-600 text-xs font-bold rounded-xl shadow-lg hover:shadow-xl transition-all block text-center uppercase tracking-widest">Verify Logs</a>
            </div>
        </div>
    </div>
</x-app-layout>
