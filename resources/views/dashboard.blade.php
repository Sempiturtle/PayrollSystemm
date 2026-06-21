<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="text-slate-400 font-medium">System</span>
            <span class="text-slate-300">/</span>
            <span class="font-bold text-slate-800">Dashboard</span>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Dashboard Header & Greeting -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Dashboard</h1>
                <p class="text-sm text-slate-500 mt-1">welcome back, {{ Auth::user()->name }}</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <!-- Campus Time Clock Widget -->
                <div class="flex items-center gap-2.5 px-4 py-2 bg-white rounded-xl border border-slate-200 shadow-sm">
                    <div class="text-right">
                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Campus Time (Manila)</div>
                        <div class="text-xs font-bold text-slate-900 tabular-nums leading-none">
                            <span x-data="{ time: '' }" x-init="setInterval(() => { 
                                time = new Date().toLocaleTimeString('en-US', { timeZone: 'Asia/Manila', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }) 
                            }, 1000)" x-text="time || '00:00:00'"></span>
                        </div>
                    </div>
                    <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 shadow-sm shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

                <!-- Sort Dropdown selector matching reference -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 px-4 py-2 bg-white rounded-xl border border-slate-200 shadow-sm text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                        <span>Sort: <span class="text-indigo-600">Last week</span></span>
                        <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-40 bg-white border border-slate-100 rounded-xl shadow-lg z-50 py-1.5" style="display: none;">
                        <a href="#" class="block px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">Today</a>
                        <a href="#" class="block px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">Last week</a>
                        <a href="#" class="block px-4 py-2 text-xs font-semibold text-indigo-600 bg-indigo-50/50">Month-to-date</a>
                    </div>
                </div>

                @if(Auth::user()->isAdmin())
                    <a href="{{ route('attendance.scanner') }}" class="btn-action-indigo text-xs py-2 px-4 rounded-xl shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        Terminal Scanner
                    </a>
                @endif
            </div>
        </div>

        @if($todayHoliday)
            <div class="bg-indigo-600/5 backdrop-blur-md p-4 rounded-2xl flex items-center justify-between border border-indigo-500/10 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-md shadow-indigo-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest mb-0.5">Academic Holiday</div>
                        <div class="text-sm font-bold text-slate-800 tracking-tight">{{ $todayHoliday->name }} <span class="text-slate-500 font-normal">({{ $todayHoliday->type }})</span></div>
                    </div>
                </div>
                <div class="hidden sm:block text-[9px] font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg uppercase tracking-wider">Premium Active</div>
            </div>
        @endif

        {{-- Premium Enriched Cards Layout matching reference --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Card 1: Payroll Cost & Cycles --}}
            <div class="card-reference p-6 flex flex-col justify-between min-h-[220px]">
                <div class="flex items-center justify-between text-slate-400">
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Payroll Cost</span>
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"></path></svg>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="text-3xl font-extrabold text-slate-900 tracking-tight">₱{{ number_format(($totalEmployees * 16400.50), 2) }}</div>
                    <div class="text-[10px] text-slate-400 mt-1 font-semibold uppercase tracking-wider">
                        Cycles: {{ $statStats->finalized_count }} Finalized / {{ $statStats->draft_count }} Draft
                    </div>
                </div>
                <div class="mt-4 flex-1 flex items-end">
                    <div id="costBarChart" class="w-full h-16"></div>
                </div>
            </div>

            {{-- Card 2: Presence Activity & Leaves --}}
            <div class="card-reference p-6 flex flex-col justify-between min-h-[220px]">
                <div class="flex items-center justify-between text-slate-400">
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Daily Presence</span>
                    <div class="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center text-rose-500 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $totalEmployees > 0 ? round(($presentToday / $totalEmployees) * 100) : 0 }}%</div>
                    <div class="text-[10px] text-slate-400 mt-1 font-semibold uppercase tracking-wider">
                        Leaves: {{ $pendingLeaves }} Pending Request(s)
                    </div>
                </div>
                <div class="mt-4 flex-1 flex items-end">
                    <div id="presenceBarChart" class="w-full h-16"></div>
                </div>
            </div>

            {{-- Card 3: Staff Active & Growth --}}
            <div class="card-reference p-6 flex flex-col justify-between min-h-[220px]">
                <div class="flex items-center justify-between text-slate-400">
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Staff Active</span>
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $totalEmployees }} Active</div>
                    <div class="text-[10px] text-slate-400 mt-1 font-semibold uppercase tracking-wider">
                        Checked-in: {{ $presentToday }} today
                    </div>
                </div>
                <div class="mt-4 flex-1 flex items-end">
                    <div id="salesLineChart" class="w-full h-16"></div>
                </div>
            </div>
        </div>

        {{-- Mid-section dashboard features --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
            {{-- Column 1 & 2: Recent Logs & Hourly Flow (Pulse Chart) --}}
            <div class="lg:col-span-2 space-y-6">
                <!-- Recent Logs (Mocked like Recent Messages) -->
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-slate-900 tracking-tight">Recent activity</h3>
                    
                    <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm divide-y divide-slate-100/80">
                        @forelse($recentLogs->take(4) as $log)
                            <div class="flex items-center justify-between py-4 first:pt-0 last:pb-0 group transition-all duration-200">
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center font-extrabold text-indigo-600 text-xs shadow-sm group-hover:scale-105 transition-transform">
                                        {{ strtoupper(substr($log->user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 text-sm tracking-tight">{{ $log->user->name }}</div>
                                        <div class="text-xs text-slate-400 mt-0.5 font-medium">Checked in via terminal ({{ $log->user->role }})</div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-8">
                                    <span class="text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wider
                                        {{ $log->status == 'On-time' ? 'text-indigo-600 bg-indigo-50' : 'text-amber-600 bg-amber-50' }}">
                                        {{ $log->status }}
                                    </span>
                                    <span class="text-xs font-bold text-slate-400 tabular-nums">
                                        {{ \Carbon\Carbon::parse($log->time_in)->format('H:i') }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-slate-400 text-sm italic">No logs recorded today</div>
                        @endforelse
                    </div>
                </div>

                <!-- Hourly Activity (Pulse Chart) -->
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-slate-900 tracking-tight">Hourly activity pulse</h3>
                    
                    <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm">
                        <div id="pulseChart" class="w-full"></div>
                    </div>
                </div>
            </div>

            {{-- Column 3: Donut chart, streaks, capacity --}}
            <div class="space-y-6">
                <!-- Attendance Distribution (Donut Chart) -->
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-slate-900 tracking-tight">Attendance breakdown</h3>
                    
                    <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm flex flex-col justify-between min-h-[300px]">
                        <div id="attendanceDonut" class="flex justify-center my-auto"></div>
                        <div class="grid grid-cols-3 gap-1 border-t border-slate-100 pt-4 text-center text-xs font-medium text-slate-600">
                            <div>
                                <div class="font-bold text-slate-900 text-sm">{{ $presentToday }}</div>
                                <div class="text-[10px] text-slate-400 uppercase font-semibold">Present</div>
                            </div>
                            <div>
                                <div class="font-bold text-slate-900 text-sm">{{ $lateToday }}</div>
                                <div class="text-[10px] text-slate-400 uppercase font-semibold">Late</div>
                            </div>
                            <div>
                                <div class="font-bold text-slate-900 text-sm">{{ $absentToday }}</div>
                                <div class="text-[10px] text-slate-400 uppercase font-semibold">Absent</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Streaks & Capacity -->
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-slate-900 tracking-tight">Campus overview</h3>
                    
                    <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm space-y-6">
                        <div>
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Top Streaks (On-Time)</h4>
                            <div class="space-y-2">
                                @forelse($performerStats->take(3) as $performer)
                                    <div class="flex items-center justify-between p-2 hover:bg-slate-50 rounded-xl transition-all text-xs font-medium text-slate-700">
                                        <div class="flex items-center gap-3">
                                            <div class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-[10px]">
                                                {{ substr($performer->user->name, 0, 1) }}
                                            </div>
                                            <span class="font-bold text-slate-800">{{ $performer->user->name }}</span>
                                        </div>
                                        <span class="text-indigo-600 font-extrabold tabular-nums bg-indigo-50 px-2.5 py-0.5 rounded-lg">{{ $performer->count }} days</span>
                                    </div>
                                @empty
                                    <div class="text-center py-2 text-slate-400 text-xs italic">No streak stats</div>
                                @endforelse
                            </div>
                        </div>

                        <div class="border-t border-slate-100 pt-4">
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Campus Capacity</h4>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($deptStats as $role => $count)
                                    <div class="p-3 rounded-2xl bg-slate-50/50 border border-slate-100 flex items-center justify-between">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">{{ $role }}</span>
                                        <span class="text-xs font-bold text-slate-900">{{ $count }}</span>
                                    </div>
                                @endforeach
                            </div>
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
            // General Chart Settings
            const commonOptions = {
                chart: {
                    sparkline: { enabled: true },
                    animations: { enabled: true, easing: 'easeinout', speed: 800 }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 3,
                        columnWidth: '55%',
                    }
                },
                dataLabels: { enabled: false },
                tooltip: { enabled: false }
            };

            // 1. Cost Bar Chart (Revenue style)
            new ApexCharts(document.querySelector("#costBarChart"), {
                ...commonOptions,
                chart: { ...commonOptions.chart, type: 'bar', height: 60 },
                series: [{
                    name: 'Disbursements',
                    data: [35, 45, 30, 60, 50, 75, 40, 95, 80, 55, 65, 85]
                }],
                colors: ['#5334F6'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        type: "vertical",
                        shadeIntensity: 0.5,
                        gradientToColors: ['#3F29C3'],
                        inverseColors: true,
                        opacityFrom: 1,
                        opacityTo: 0.8,
                        stops: [0, 100]
                    }
                }
            }).render();

            // 2. Presence Bar Chart (Expenses style: one highlighted bar)
            new ApexCharts(document.querySelector("#presenceBarChart"), {
                ...commonOptions,
                chart: { ...commonOptions.chart, type: 'bar', height: 60 },
                series: [{
                    name: 'Presence Rate',
                    data: [50, 48, 55, 42, 60, 85, 45, 52, 49, 58, 62, 50]
                }],
                colors: [function({ value, seriesIndex, dataPointIndex, w }) {
                    if (dataPointIndex === 5) {
                        return '#F43F5E'; 
                    } else {
                        return '#FCA5A5'; 
                    }
                }],
                fill: {
                    opacity: [0.35]
                }
            }).render();

            // 3. Sales Line Chart (Sales style)
            new ApexCharts(document.querySelector("#salesLineChart"), {
                ...commonOptions,
                chart: { ...commonOptions.chart, type: 'area', height: 60 },
                series: [{
                    name: 'Trends',
                    data: [15, 25, 20, 35, 28, 42, 38, 55, 45, 65, 75, 90]
                }],
                stroke: {
                    curve: 'smooth',
                    width: 3,
                    colors: ['#F43F5E']
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.15,
                        opacityTo: 0,
                        stops: [0, 90, 100],
                        colorStops: [
                            { offset: 0, color: '#F43F5E', opacity: 0.15 },
                            { offset: 100, color: '#F43F5E', opacity: 0 }
                        ]
                    }
                }
            }).render();

            // 4. Attendance Donut Chart
            new ApexCharts(document.querySelector("#attendanceDonut"), {
                chart: {
                    type: 'donut',
                    height: 180,
                    fontFamily: 'Inter, sans-serif'
                },
                dataLabels: { enabled: false },
                stroke: { width: 0 },
                legend: { show: false },
                series: [{{ $presentToday }}, {{ $lateToday }}, {{ $absentToday }}],
                labels: ['On-time', 'Late', 'Absent'],
                colors: ['#5334F6', '#9CA3AF', '#F43F5E'],
                plotOptions: {
                    pie: {
                        donut: {
                            size: '80%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'PRESENCE',
                                    formatter: () => '{{ $totalEmployees > 0 ? round(($presentToday/$totalEmployees)*100) : 0 }}%',
                                    style: { fontSize: '12px', fontWeight: 800, color: '#1E293B' }
                                }
                            }
                        }
                    }
                }
            }).render();

            // 5. Hourly Activity Pulse Chart
            new ApexCharts(document.querySelector("#pulseChart"), {
                chart: {
                    type: 'area',
                    height: 200,
                    toolbar: { show: false },
                    fontFamily: 'Inter, sans-serif'
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3, colors: ['#5334F6'] },
                series: [{
                    name: 'Authentications',
                    data: @json($hourlyActivities)
                }],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.2,
                        opacityTo: 0,
                        stops: [0, 90, 100]
                    }
                },
                colors: ['#5334F6'],
                grid: {
                    borderColor: '#F1F5F9',
                    strokeDashArray: 4,
                    padding: { left: 10, right: 10, top: 0, bottom: 0 }
                },
                xaxis: {
                    categories: Array.from({length: 24}, (_, i) => i + ':00'),
                    labels: { show: true, style: { colors: '#94A3B8', fontSize: '10px' } },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: { style: { colors: '#94A3B8', fontSize: '10px' } }
                }
            }).render();
        });
    </script>
</x-app-layout>
