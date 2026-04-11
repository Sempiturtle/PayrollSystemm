<x-app-layout>
    <x-slot name="header">
        Dashboard
    </x-slot>

    <div class="space-y-8">
        {{-- ── Row 1: Welcome + Stats ── --}}
        <div class="space-y-6">
            {{-- Welcome Banner --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Good {{ date('H') < 12 ? 'morning' : (date('H') < 17 ? 'afternoon' : 'evening') }}, {{ Auth::user()->name }}</h2>
                    <p class="text-sm text-slate-500 mt-1">Here's what's happening with your workforce today.</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <div class="text-sm font-semibold text-slate-700">{{ date('l, F j, Y') }}</div>
                        <div class="text-xs text-slate-400" x-data x-text="new Date().toLocaleTimeString('en-US', { timeZone: 'Asia/Manila', hour: '2-digit', minute: '2-digit', hour12: true })"></div>
                    </div>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('attendance.scanner') }}" class="btn-primary flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                            Open Scanner
                        </a>
                    @endif
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Total Employees - Featured --}}
                <div class="stat-card group bg-gradient-to-br from-indigo-600 to-violet-600 border-0 shadow-lg shadow-indigo-200/50">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-xl bg-white/20 text-white flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-white tracking-tight">{{ $totalEmployees }}</div>
                        <h4 class="text-sm font-medium text-indigo-100 mt-1">Total Employees</h4>
                    </div>
                </div>

                <x-stats-card title="Present Today" :value="$presentToday" icon="check" color="emerald" trend="8" />
                <x-stats-card title="Late Today" :value="$lateToday" icon="clock" color="amber" trend="-3" />
                <x-stats-card title="Absent Today" :value="$absentToday" icon="alert" color="rose" />
            </div>
        </div>

        {{-- ── Row 2: Main Content ── --}}
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
            {{-- Left Column: Table + Chart --}}
            <div class="xl:col-span-8 space-y-6">
                {{-- Today's Attendance Table --}}
                <div class="card overflow-hidden">
                    <div class="section-header">
                        <div>
                            <h3 class="section-title">Today's Attendance</h3>
                            <p class="section-subtitle flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Live &middot; Updated just now
                            </p>
                        </div>
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('attendance.scanner') }}" class="btn-secondary text-xs flex items-center gap-2">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                Open Scanner
                            </a>
                        @endif
                    </div>

                    <div class="overflow-x-auto">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Time In</th>
                                    <th class="text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentLogs as $log)
                                    <tr class="group">
                                        <td>
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-semibold text-xs ring-1 ring-slate-200/60 group-hover:ring-indigo-200 transition-all">
                                                    {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold text-slate-800">{{ $log->user->name }}</div>
                                                    <div class="text-xs text-slate-400">{{ $log->user->employee_id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-sm text-slate-600 font-medium">
                                                {{ \Carbon\Carbon::parse($log->time_in)->format('h:i A') }}
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <x-status-badge :type="$log->status">{{ $log->status }}</x-status-badge>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-12">
                                            <div class="flex flex-col items-center gap-2">
                                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                                <span class="text-sm text-slate-400">No attendance records today</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Payroll Trends Chart --}}
                <div class="card p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="section-title">Payroll Trends</h3>
                            <p class="section-subtitle">Monthly disbursement over time</p>
                        </div>
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-lg text-xs font-semibold ring-1 ring-emerald-100">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            +12.5%
                        </div>
                    </div>
                    <div id="payrollTrendChart" class="min-h-[320px]"></div>
                </div>
            </div>

            {{-- Right Column: Widgets --}}
            <div class="xl:col-span-4 space-y-6">
                {{-- Clock Widget --}}
                <div class="card overflow-hidden">
                    <div class="bg-gradient-to-br from-slate-900 to-slate-800 p-6 text-white">
                        <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Local Time — Manila</div>
                        <div x-data="{ time: '' }" x-init="setInterval(() => { time = new Date().toLocaleTimeString('en-US', { timeZone: 'Asia/Manila', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }) }, 1000)" class="text-5xl font-bold tracking-tighter tabular-nums mb-1">
                            <span x-text="time || '00:00:00'"></span>
                        </div>
                        <div class="text-sm text-slate-400">{{ date('l, F j, Y') }}</div>
                    </div>
                </div>

                {{-- Attendance Distribution --}}
                <div class="card p-6">
                    <h3 class="section-title mb-1">Attendance Overview</h3>
                    <p class="section-subtitle mb-6">Today's distribution</p>
                    <div id="attendanceChart" class="min-h-[240px]"></div>
                    <div class="grid grid-cols-3 gap-3 mt-6">
                        <div class="text-center p-3 bg-emerald-50 rounded-xl">
                            <div class="text-lg font-bold text-emerald-700">{{ $presentToday }}</div>
                            <div class="text-[10px] font-semibold text-emerald-600/70 uppercase tracking-wide">On-time</div>
                        </div>
                        <div class="text-center p-3 bg-amber-50 rounded-xl">
                            <div class="text-lg font-bold text-amber-700">{{ $lateToday }}</div>
                            <div class="text-[10px] font-semibold text-amber-600/70 uppercase tracking-wide">Late</div>
                        </div>
                        <div class="text-center p-3 bg-rose-50 rounded-xl">
                            <div class="text-lg font-bold text-rose-700">{{ $absentToday }}</div>
                            <div class="text-[10px] font-semibold text-rose-600/70 uppercase tracking-wide">Absent</div>
                        </div>
                    </div>
                </div>

                {{-- Department Breakdown --}}
                <div class="card p-6">
                    <h3 class="section-title mb-4">Department Breakdown</h3>
                    <div class="space-y-4">
                        @foreach($deptStats as $dept => $count)
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-sm font-medium text-slate-600 capitalize">{{ $dept }}</span>
                                <span class="text-sm font-bold text-slate-800">{{ $count }}</span>
                            </div>
                            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-700 {{ $loop->first ? 'bg-indigo-500' : 'bg-violet-500' }}"
                                     style="width: {{ $totalEmployees > 0 ? ($count / $totalEmployees * 100) : 0 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Quick Action --}}
                @if(Auth::user()->isAdmin())
                    <div class="card p-6 border-indigo-200/60 bg-indigo-50/30">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold text-slate-800">RFID Scanner</h4>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">Open the attendance scanner to log employee check-ins and check-outs.</p>
                            </div>
                        </div>
                        <a href="{{ route('attendance.scanner') }}" class="btn-primary w-full text-center mt-4 block">
                            Launch Scanner
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Global chart defaults for light theme
            window.Apex = {
                chart: {
                    fontFamily: 'Inter, sans-serif',
                    toolbar: { show: false },
                    foreColor: '#94a3b8'
                },
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 0
                },
                tooltip: {
                    theme: 'light',
                    style: { fontSize: '12px' },
                    y: { formatter: (val) => val }
                }
            };

            // Attendance Donut
            new ApexCharts(document.querySelector("#attendanceChart"), {
                series: [{{ $presentToday }}, {{ $lateToday }}, {{ $absentToday }}],
                chart: { type: 'donut', height: 240 },
                labels: ['On-time', 'Late', 'Absent'],
                colors: ['#10b981', '#f59e0b', '#f43f5e'],
                plotOptions: {
                    pie: {
                        donut: {
                            size: '78%',
                            labels: {
                                show: true,
                                name: { show: true, fontSize: '12px', fontWeight: 600, color: '#64748b', offsetY: -8 },
                                value: { show: true, fontSize: '22px', fontWeight: 700, color: '#1e293b', offsetY: 4 },
                                total: {
                                    show: true,
                                    label: 'Total',
                                    color: '#94a3b8',
                                    fontSize: '12px',
                                    fontWeight: 600,
                                    formatter: () => '{{ $presentToday + $lateToday + $absentToday }}'
                                }
                            }
                        }
                    }
                },
                stroke: { width: 2, colors: ['#fff'] },
                legend: { show: false },
                dataLabels: { enabled: false }
            }).render();

            // Payroll Trend Area Chart
            new ApexCharts(document.querySelector("#payrollTrendChart"), {
                series: [{
                    name: 'Net Pay',
                    data: [@foreach($payrollTrend as $p) {{ $p->total }}, @endforeach]
                }],
                chart: { type: 'area', height: 320 },
                stroke: { curve: 'smooth', width: 2.5, colors: ['#6366f1'] },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.15,
                        opacityTo: 0.02,
                        stops: [0, 90, 100],
                        colorStops: [{
                            offset: 0, color: '#6366f1', opacity: 0.15
                        }, {
                            offset: 100, color: '#6366f1', opacity: 0.02
                        }]
                    }
                },
                markers: { size: 4, colors: ['#6366f1'], strokeColors: '#fff', strokeWidth: 2, hover: { size: 6 } },
                xaxis: {
                    categories: [@foreach($payrollTrend as $p) '{{ $p->month }}', @endforeach],
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { style: { fontSize: '12px', fontWeight: 500 } }
                },
                yaxis: {
                    labels: {
                        formatter: (val) => '₱' + (val/1000).toFixed(0) + 'K',
                        style: { fontSize: '12px', fontWeight: 500 }
                    }
                },
                grid: {
                    borderColor: '#f1f5f9',
                    padding: { left: 8, right: 8 }
                }
            }).render();
        });
    </script>
</x-app-layout>
