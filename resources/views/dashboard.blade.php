<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="text-slate-400 font-medium">System</span>
            <span class="text-slate-300">/</span>
            <span class="font-bold">Dashboard</span>
        </div>
    </x-slot>

    <div class="space-y-8 animate-in-up">
        <!-- Dashboard Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tighter">Executive <span class="text-indigo-600">Overview</span></h1>
                    <p class="text-slate-500 mt-1 font-medium text-sm italic">Intelligence-driven indicators for campus personnel and cycles.</p>
                </div>
                
                <div class="flex items-center gap-6">
                    <div class="hidden lg:flex items-center gap-4 px-6 py-3 bg-white rounded-2xl border border-slate-100 shadow-sm">
                        <div class="text-right">
                            <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Manila Terminal</div>
                            <div class="text-sm font-black text-slate-900 tabular-nums leading-none">
                                <span x-data="{ time: '' }" x-init="setInterval(() => { 
                                    time = new Date().toLocaleTimeString('en-US', { timeZone: 'Asia/Manila', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }) 
                                }, 1000)" x-text="time || '00:00:00'"></span>
                            </div>
                        </div>
                        <div class="w-8 h-8 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('attendance.scanner') }}" class="btn-action-indigo text-xs py-3 px-6 rounded-2xl shadow-xl shadow-indigo-100 ring-4 ring-indigo-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        Launch Identity Terminal
                    </a>
                @endif
            </div>
        </div>

        @if($todayHoliday)
            <div class="glass-surface p-5 rounded-[2rem] flex items-center justify-between border-indigo-200/50 bg-indigo-50/10">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-indigo-400 uppercase tracking-[0.2em] mb-1">Active Holiday</div>
                        <div class="text-base font-bold text-slate-800 tracking-tight">{{ $todayHoliday->name }} <span class="text-slate-400 italic font-medium">({{ $todayHoliday->type }})</span></div>
                    </div>
                </div>
                <div class="hidden sm:block text-[10px] font-black text-indigo-600 bg-white border border-indigo-100 px-4 py-2 rounded-xl italic uppercase tracking-widest">Double Pay Logic Engaged</div>
            </div>
        @endif

        {{-- Top Metrics --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="stat-high-level group p-10 bg-white shadow-xl shadow-slate-100/10 flex flex-col justify-between overflow-hidden relative">
                <div class="stat-label flex items-center justify-between relative z-10">
                    <span class="italic text-slate-400">Total Personnel</span>
                    <svg class="w-4 h-4 text-slate-300 group-hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div class="mt-8 flex items-baseline gap-2 relative z-10">
                    <div class="stat-value text-5xl tracking-tighter">{{ $totalEmployees }}</div>
                    <span class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest">+1 (30d)</span>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-16 opacity-30 group-hover:opacity-100 transition-opacity duration-700">
                    <div id="sparklinePersonnel"></div>
                </div>
            </div>

            <div class="stat-high-level group p-10 bg-white shadow-xl shadow-slate-100/10 flex flex-col justify-between overflow-hidden relative border-l-4 border-l-emerald-500">
                <div class="stat-label flex items-center justify-between relative z-10">
                    <span class="italic text-slate-400">Operational Presence</span>
                    <svg class="w-4 h-4 text-slate-300 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div class="mt-8 flex items-baseline gap-2 relative z-10">
                    <div class="stat-value text-5xl tracking-tighter text-emerald-600">{{ $totalEmployees > 0 ? round(($presentToday / $totalEmployees) * 100) : 0 }}%</div>
                    <span class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest italic font-mono">{{ $presentToday }} Active</span>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-16 opacity-30 group-hover:opacity-100 transition-opacity duration-700">
                    <div id="sparklineEfficiency"></div>
                </div>
            </div>

            <div class="stat-high-level group p-10 bg-white shadow-xl shadow-slate-100/10 flex flex-col justify-between overflow-hidden relative">
                <div class="stat-label flex items-center justify-between relative z-10">
                    <span class="italic text-slate-400">Strategic Workflow</span>
                    <svg class="w-4 h-4 text-slate-300 group-hover:text-rose-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <div class="mt-8 flex items-baseline gap-2 relative z-10">
                    <div class="stat-value text-5xl tracking-tighter {{ $pendingLeaves > 0 ? 'text-rose-600' : 'text-slate-300' }}">{{ $pendingLeaves }}</div>
                    <span class="text-[10px] font-bold text-rose-400 uppercase tracking-widest italic font-mono">Pending Cycle</span>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-16 opacity-30 group-hover:opacity-100 transition-opacity duration-700">
                    <div id="sparklineWorkflow"></div>
                </div>
            </div>

            <div class="stat-high-level group p-10 bg-slate-950 text-white shadow-2xl overflow-hidden relative border-none flex flex-col justify-between">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/30 to-transparent"></div>
                <div class="stat-label flex items-center justify-between relative z-10">
                    <span class="italic text-indigo-400 font-black">Fiscal Processing</span>
                    <svg class="w-4 h-4 text-indigo-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <div class="mt-8 relative z-10">
                    <div class="flex items-baseline gap-2">
                        <div class="stat-value text-4xl tracking-tighter tabular-nums">{{ $statStats->finalized_count }}</div>
                        <span class="text-[10px] font-bold text-indigo-400/60 uppercase tracking-widest italic font-mono">Finalized</span>
                    </div>
                    <div class="mt-1 flex items-center gap-2">
                        <div class="w-full bg-indigo-900/50 h-1 rounded-full overflow-hidden">
                            @php 
                                $totalRows = ($statStats->finalized_count + $statStats->draft_count) ?: 1;
                                $percent = ($statStats->finalized_count / $totalRows) * 100;
                            @endphp
                            <div class="bg-indigo-400 h-full" style="width: {{ $percent }}%"></div>
                        </div>
                        <span class="text-[9px] font-black tabular-nums">{{ round($percent) }}%</span>
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between relative z-10">
                    <div class="text-[9px] font-bold text-indigo-300/40 uppercase tracking-widest">{{ $statStats->draft_count }} Pending Drafts</div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-10 opacity-40 group-hover:opacity-100 transition-opacity duration-700">
                    <div id="sparklineVelocity"></div>
                </div>
            </div>
        </div>


        {{-- Row 2: Operational & Visual Intelligence --}}
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-stretch">
            {{-- Attendance Distribution (Donut) --}}
            <div class="xl:col-span-4 card-modern flex flex-col h-full overflow-hidden shadow-xl shadow-slate-100/10">
                <div class="p-10 border-b border-slate-50">
                    <h3 class="text-xs font-black text-emerald-600 uppercase tracking-[0.2em] italic">Operational Snapshot</h3>
                    <p class="text-[10px] text-slate-400 mt-1 font-bold uppercase tracking-widest leading-none">Live Presence Flow</p>
                </div>
                <div class="p-10 flex-1 flex flex-col justify-between">
                    <div id="attendanceDonut" class="flex justify-center"></div>
                    <div class="grid grid-cols-3 gap-2 border-t border-slate-50 pt-8 mt-6">
                        <div class="text-center">
                            <div class="text-[10px] font-black text-slate-900">{{ $presentToday }}</div>
                            <div class="text-[8px] font-black text-slate-400 uppercase tracking-tighter">On-time</div>
                        </div>
                        <div class="text-center">
                            <div class="text-[10px] font-black text-slate-900">{{ $lateToday }}</div>
                            <div class="text-[8px] font-black text-slate-400 uppercase tracking-tighter">Late</div>
                        </div>
                        <div class="text-center">
                            <div class="text-[10px] font-black text-slate-900">{{ $absentToday }}</div>
                            <div class="text-[8px] font-black text-slate-400 uppercase tracking-tighter">Absent</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Institutional Pulse (Hourly Flow Area Chart) --}}
            <div class="xl:col-span-8 card-modern flex flex-col h-full overflow-hidden shadow-xl shadow-slate-100/10 border-l border-slate-50/50">
                <div class="p-10 border-b border-slate-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-xs font-black text-indigo-600 uppercase tracking-[0.2em] italic">Institutional Pulse</h3>
                        <p class="text-[10px] text-slate-400 mt-1 font-bold uppercase tracking-widest leading-none">Hourly Authentication Stream (Today)</p>
                    </div>
                    <div class="flex items-center gap-1.5 px-4 py-2 bg-indigo-50 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
                        <span class="text-[9px] font-black text-indigo-600 uppercase tracking-widest">Live Flow</span>
                    </div>
                </div>
                <div class="p-4 flex-1 bg-slate-50/5 relative">
                    <div id="pulseChart" class="h-full min-h-[300px]"></div>
                </div>
            </div>
        </div>

        {{-- Row 3: Performance & Streams --}}
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-stretch">
            {{-- Performance Leaderboard --}}
            <div class="xl:col-span-4 card-modern flex flex-col h-full overflow-hidden shadow-xl shadow-slate-100/10">
                <div class="p-10 border-b border-slate-50">
                    <h3 class="text-xs font-black text-indigo-600 uppercase tracking-[0.2em] italic">Elite Performers</h3>
                    <p class="text-[10px] text-slate-400 mt-1 font-bold uppercase tracking-widest leading-none">Attendance Consistency</p>
                </div>
                <div class="p-10 flex-1 space-y-8">
                    @forelse($performerStats as $performer)
                        <div class="flex items-center justify-between group cursor-pointer hover:bg-slate-50 p-2 -m-2 rounded-xl transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-bold text-xs shadow-lg shadow-indigo-100 ring-4 ring-indigo-50">
                                    {{ substr($performer->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-800 tracking-tight">{{ $performer->user->name }}</div>
                                    <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $performer->user->role }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs font-bold text-indigo-600 tabular-nums">{{ $performer->count }}</div>
                                <div class="text-[8px] font-bold text-slate-300 uppercase tracking-tighter italic">On-time Count</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-slate-300 italic text-xs uppercase font-bold tracking-widest opacity-40 italic">Establishing Rankings...</div>
                    @endforelse
                </div>
            </div>

            {{-- Authentication Logs --}}
            <div class="xl:col-span-8 card-modern flex flex-col h-full overflow-hidden shadow-xl shadow-slate-100/10">
                <div class="p-10 border-b border-slate-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] italic">Live Authentication Stream</h3>
                        <p class="text-[10px] text-slate-400 mt-1 font-bold uppercase tracking-widest leading-none">Institutional Security Log</p>
                    </div>
                    <div class="flex items-center gap-2 px-4 py-2 bg-slate-50 rounded-full">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Active RFID Sync</span>
                    </div>
                </div>
                <div class="flex-1 overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Identity Stream</th>
                                <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Auth Event</th>
                                <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Verdict</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($recentLogs as $log)
                                <tr class="group hover:bg-slate-50 transition-colors">
                                    <td class="px-10 py-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-2xl bg-slate-900 border-4 border-slate-900/10 text-white flex items-center justify-center font-bold text-xs shadow-xl">
                                                {{ substr($log->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-black text-slate-900 tracking-tight">{{ $log->user->name }}</div>
                                                <div class="text-[9px] font-bold text-indigo-500 uppercase tracking-widest leading-none mt-1">{{ $log->user->role }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-10 py-6">
                                        <div class="text-sm font-black text-slate-600 tabular-nums">
                                            {{ \Carbon\Carbon::parse($log->time_in)->format('h:i:s') }} <span class="text-[10px] text-slate-400 uppercase italic">{{ \Carbon\Carbon::parse($log->time_in)->format('A') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-10 py-6 text-right">
                                        <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest
                                            {{ $log->status == 'On-time' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                            {{ $log->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center py-20 text-slate-300 italic text-sm font-bold tracking-widest uppercase opacity-40 italic">Awaiting Terminal Signal...</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Row 4: Modern Intelligence (Radials) --}}
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-stretch pt-4">
            <div class="xl:col-span-12 card-modern flex flex-col h-full overflow-hidden shadow-xl shadow-slate-100/10">
                <div class="p-10 border-b border-slate-100 bg-slate-50/20">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] italic">Personnel Dynamic Analysis</h3>
                    <p class="text-[10px] text-slate-400 mt-1 font-bold uppercase tracking-widest leading-none">Departmental Capacity & Flux</p>
                </div>
                <div class="p-10 grid grid-cols-1 md:grid-cols-2 items-center gap-16">
                    <div id="capacityRadial" class="flex justify-center"></div>
                    <div class="space-y-12">
                        <p class="text-sm font-medium text-slate-500 italic leading-relaxed">Automated mapping of personnel distribution across academic and operational units based on role-based access streams.</p>
                        <div class="grid grid-cols-2 gap-8">
                            @foreach($deptStats as $role => $count)
                                <div class="group">
                                    <div class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 group-hover:text-indigo-600 transition-colors">{{ $role }} Stream</div>
                                    <div class="text-3xl font-black text-slate-900 tabular-nums tracking-tighter">{{ $count }} <span class="text-xs text-slate-300 font-bold uppercase ml-1 italic">Active</span></div>
                                </div>
                            @endforeach
                        </div>
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

            const sparklineOptions = {
                chart: { type: 'area', height: 80, sparkline: { enabled: true }, animations: { enabled: true, easing: 'easeinout', speed: 800 } },
                stroke: { curve: 'smooth', width: 2 },
                fill: { type: 'gradient', gradient: { opacityFrom: 0.3, opacityTo: 0 } },
                tooltip: { fixed: { enabled: false }, x: { show: false }, y: { title: { formatter: (s) => '' } }, marker: { show: false } }
            };

            // 1. Attendance Donut
            new ApexCharts(document.querySelector("#attendanceDonut"), {
                ...chartOptions,
                series: [{{ $presentToday }}, {{ $lateToday }}, {{ $absentToday }}],
                labels: ['On-time', 'Late', 'Absent'],
                colors: ['#10b981', '#f59e0b', '#f43f5e'],
                chart: { ...chartOptions.chart, type: 'donut', height: 260 },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '88%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'PRESENCE',
                                    formatter: () => '{{ $totalEmployees > 0 ? round(($presentToday/$totalEmployees)*100) : 0 }}%',
                                    style: { fontSize: '12px', fontWeight: 900, color: '#1e293b' }
                                }
                            }
                        }
                    }
                }
            }).render();

            // 2. Institutional Pulse (Hourly Flow)
            new ApexCharts(document.querySelector("#pulseChart"), {
                ...chartOptions,
                series: [{
                    name: 'Authentications',
                    data: @json($hourlyActivities)
                }],
                chart: { ...chartOptions.chart, type: 'area', height: '100%', sparkline: { enabled: false } },
                stroke: { curve: 'smooth', width: 4, colors: ['#4f46e5'] },
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.2, opacityTo: 0, stops: [0, 90, 100] } },
                colors: ['#4f46e5'],
                grid: { borderColor: '#f1f5f9', strokeDashArray: 4, padding: { left: 0, right: 0, top: 0, bottom: 0 } },
                xaxis: {
                    categories: Array.from({length: 24}, (_, i) => i + ':00'),
                    labels: { show: true, style: { colors: '#94a3b8', fontSize: '9px', fontWeight: 700 } },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: { show: false }
            }).render();

            // 3. Personnel Capacity Radial
            new ApexCharts(document.querySelector("#capacityRadial"), {
                ...chartOptions,
                series: [{{ $deptStats['Professors'] > 0 ? round(($deptStats['Professors']/$totalEmployees)*100) : 0 }}, {{ $deptStats['Staff'] > 0 ? round(($deptStats['Staff']/$totalEmployees)*100) : 0 }}],
                labels: ['Professors', 'Administrative Staff'],
                colors: ['#4f46e5', '#94a3b8'],
                chart: { ...chartOptions.chart, type: 'radialBar', height: 350 },
                plotOptions: {
                    radialBar: {
                        dataLabels: {
                            name: { fontSize: '14px', fontWeight: 900, color: '#1e293b' },
                            value: { fontSize: '20px', fontWeight: 900, color: '#4f46e5' },
                            total: { show: true, label: 'TOTAL CORE', formatter: () => '{{ $totalEmployees }}' }
                        },
                        track: { background: '#f1f5f9', strokeWidth: '100%' }
                    }
                }
            }).render();

            // 4. Sparklines
            new ApexCharts(document.querySelector("#sparklinePersonnel"), { ...sparklineOptions, series: [{ data: @json($sparklineData) }], colors: ['#4f46e5'] }).render();
            new ApexCharts(document.querySelector("#sparklineEfficiency"), { ...sparklineOptions, series: [{ data: [65, 78, 82, 75, 89, 92, 85] }], colors: ['#10b981'] }).render();
            new ApexCharts(document.querySelector("#sparklineWorkflow"), { ...sparklineOptions, series: [{ data: [12, 15, 10, 8, 14, 11, 9] }], colors: ['#f43f5e'] }).render();
            new ApexCharts(document.querySelector("#sparklineVelocity"), { ...sparklineOptions, series: [{ data: [45, 52, 60, 48, 70, 65, 80] }], colors: ['#6366f1'] }).render();
        });
    </script>
</x-app-layout>
