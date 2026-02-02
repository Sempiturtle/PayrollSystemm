<x-app-layout>
    <x-slot name="header">
        Dashboard
    </x-slot>

    <div class="space-y-8">
        <!-- Overview Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-stats-card title="Total Staff" :value="$totalEmployees" icon="users" color="indigo" />
            <x-stats-card title="Present Today" :value="$presentToday" icon="check" color="emerald" />
            <x-stats-card title="Late Today" :value="$lateToday" icon="clock" color="amber" />
            <x-stats-card title="Absent Today" :value="$absentToday" icon="alert" color="rose" />
        </div>

        <!-- Analytics Charts Layer 1 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Attendance Donut -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 p-8 shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Attendance Distribution</h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Today's Snapshot</p>
                    </div>
                </div>
                <div id="attendanceChart" class="min-h-[300px]"></div>
            </div>

            <!-- Department distribution -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 p-8 shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Personnel Composition</h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Faculty vs Admin Staff</p>
                    </div>
                </div>
                <div id="deptChart" class="min-h-[300px]"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Activity -->
            <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-50 dark:border-slate-800 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 italic">Recent Attendance Logs</h3>
                    <a href="{{ route('attendance.scanner') }}" class="text-xs font-bold text-indigo-600 uppercase tracking-widest hover:underline">View All</a>
                </div>
                <div class="p-0">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                                <th class="px-6 py-4">Employee</th>
                                <th class="px-6 py-4">Time In</th>
                                <th class="px-6 py-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($recentLogs as $log)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs uppercase">
                                                {{ substr($log->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-slate-900 dark:text-slate-100">{{ $log->user->name }}</div>
                                                <div class="text-[10px] text-slate-400 font-medium tracking-tight uppercase">{{ $log->user->employee_id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-semibold text-slate-600 dark:text-slate-400">{{ \Carbon\Carbon::parse($log->time_in)->format('h:i A') }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right sm:text-left">
                                        <x-status-badge :type="$log->status">{{ $log->status }}</x-status-badge>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-10 text-center text-slate-400 text-sm italic">
                                        No logs recorded for today yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Actions / Clock -->
            <div class="space-y-6">
                <!-- Clock Widget -->
                <div class="p-8 bg-slate-900 rounded-3xl text-white shadow-xl shadow-slate-200 dark:shadow-none relative overflow-hidden group">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-500 rounded-full blur-3xl opacity-20 group-hover:opacity-40 transition duration-1000"></div>
                    <div class="relative">
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em] mb-4">Manila Standard Time</div>
                        <div x-data="{ time: '' }" x-init="setInterval(() => { time = new Date().toLocaleTimeString('en-US', { timeZone: 'Asia/Manila', hour: '2-digit', minute: '2-digit', second: '2-digit' }) }, 1000)" class="text-4xl font-bold tracking-tighter mb-1 tabular-nums transition-all">
                            <span x-text="time || '{{ date('h:i:s A') }}'"></span>
                        </div>
                        <div class="text-slate-400 text-sm font-medium">{{ date('l, F j, Y') }}</div>
                    </div>
                </div>

                <!-- Action Card -->
                <div class="p-6 bg-indigo-600 rounded-3xl text-white shadow-lg shadow-indigo-100 dark:shadow-none">
                    <h4 class="font-bold text-lg mb-2">Need to Scan?</h4>
                    <p class="text-indigo-100 text-sm mb-6 leading-relaxed">Access the live scanner to record real-time attendance using RFID or Biometrics.</p>
                    <a href="{{ route('attendance.scanner') }}" class="block w-full py-3 bg-white text-indigo-600 text-center rounded-2xl font-bold shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300">
                        Launch Scanner
                    </a>
                </div>
            </div>
        </div>
        <!-- Payroll Trends (Full Width) -->
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 p-8 shadow-sm">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Financial Disbursement Trends</h3>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Last 6 Months Payouts</p>
                </div>
                <div class="px-4 py-2 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-xl text-xs font-bold">
                    +12.5% from last period
                </div>
            </div>
            <div id="payrollTrendChart" class="min-h-[350px]"></div>
        </div>
    </div>

    <!-- ApexCharts CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Attendance Donut Chart
            const attendanceOptions = {
                series: [{{ $presentToday }}, {{ $lateToday }}, {{ $absentToday }}],
                chart: {
                    type: 'donut',
                    height: 350,
                    fontFamily: 'Outfit, sans-serif'
                },
                labels: ['On-time', 'Late', 'Absent'],
                colors: ['#10b981', '#f59e0b', '#f43f5e'],
                plotOptions: {
                    pie: {
                        donut: {
                            size: '75%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total Expected',
                                    formatter: () => '{{ $presentToday + $lateToday + $absentToday }}'
                                }
                            }
                        }
                    }
                },
                legend: { position: 'bottom' },
                dataLabels: { enabled: false }
            };
            new ApexCharts(document.querySelector("#attendanceChart"), attendanceOptions).render();

            // 2. Department distribution
            const deptOptions = {
                series: [{{ $deptStats['professors'] }}, {{ $deptStats['staff'] }}],
                chart: {
                    type: 'pie',
                    height: 350,
                    fontFamily: 'Outfit, sans-serif'
                },
                labels: ['Professors', 'Admin Staff'],
                colors: ['#6366f1', '#94a3b8'],
                legend: { position: 'bottom' },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) { return val.toFixed(1) + "%" }
                }
            };
            new ApexCharts(document.querySelector("#deptChart"), deptOptions).render();

            // 3. Payroll Trend Chart
            const payrollOptions = {
                series: [{
                    name: 'Net Payout',
                    data: [
                        @foreach($payrollTrend as $p)
                            {{ $p->total }},
                        @endforeach
                    ]
                }],
                chart: {
                    type: 'area',
                    height: 350,
                    toolbar: { show: false },
                    fontFamily: 'Outfit, sans-serif',
                },
                stroke: { curve: 'smooth', width: 3 },
                colors: ['#6366f1'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.45,
                        opacityTo: 0.05,
                        stops: [20, 100]
                    }
                },
                xaxis: {
                    categories: [
                        @foreach($payrollTrend as $p)
                            '{{ $p->month }}',
                        @endforeach
                    ]
                },
                yaxis: {
                    labels: {
                        formatter: (val) => '₱' + val.toLocaleString()
                    }
                },
                dataLabels: { enabled: false }
            };
            new ApexCharts(document.querySelector("#payrollTrendChart"), payrollOptions).render();
        });
    </script>
</x-app-layout>
