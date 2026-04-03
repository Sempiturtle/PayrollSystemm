<x-app-layout>
    <x-slot name="header">
        <span class="text-glow">Personnel Command Center</span>
    </x-slot>

    <div class="space-y-10 pb-20 relative">
        <!-- Background Decorative Floor -->
        <div class="absolute inset-0 grid-pattern opacity-10 pointer-events-none -z-10 h-full"></div>
        
        <!-- Top Stats: Asymmetric Priority -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            <!-- Featured Stat (Big) -->
            <div class="md:col-span-12 lg:col-span-5">
                <div class="premium-card p-1 bg-gradient-to-br from-indigo-600/20 to-transparent">
                    <div class="bg-slate-900/40 p-8 rounded-2xl h-full flex flex-col justify-between group overflow-hidden relative">
                         <!-- Large Abstract Background Icon -->
                        <div class="absolute -right-8 -bottom-8 opacity-[0.03] rotate-12 transition-transform group-hover:rotate-0 duration-1000">
                            <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path></svg>
                        </div>

                        <div class="flex items-center justify-between mb-12 relative z-10">
                            <div class="w-14 h-14 bg-indigo-500 rounded-2xl flex items-center justify-center text-white shadow-[0_0_40px_rgba(99,102,241,0.4)]">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <div class="text-right">
                                <div class="text-4xl font-black text-white tracking-tighter-lg text-glow transition-all group-hover:scale-110 origin-right">{{ $totalEmployees }}</div>
                                <div class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] mt-1">Total Personnel Base</div>
                            </div>
                        </div>
                        <div class="relative z-10">
                            <h3 class="text-xl font-bold text-white tracking-tight leading-tight mb-2">Total System Engagement</h3>
                            <p class="text-xs text-slate-500 font-medium max-w-xs leading-relaxed">Integrated tracking across {{ count($deptStats) }} core departments with real-time biometric synchronization.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Smaller Stats Grid -->
            <div class="md:col-span-12 lg:col-span-7 grid grid-cols-1 sm:grid-cols-3 gap-6">
                 <x-stats-card title="Operational Today" :value="$presentToday" icon="check" color="emerald" trend="8" />
                 <x-stats-card title="Late Sync Logs" :value="$lateToday" icon="clock" color="amber" trend="-3" />
                 <x-stats-card title="Critical Absences" :value="$absentToday" icon="alert" color="rose" />
            </div>
        </div>

        <!-- Analytics Dashboard Layer -->
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-10">
            <!-- Main Ledger: Recent Logs -->
            <div class="xl:col-span-8 space-y-10">
                <div class="premium-card bg-slate-900/20 backdrop-blur-3xl border-white/5 relative group">
                    <!-- Tech Header detail -->
                    <div class="absolute -top-1 -left-1 w-8 h-8 border-t-2 border-l-2 border-indigo-500 rounded-tl-xl opacity-20 transition-opacity group-hover:opacity-100"></div>

                    <div class="px-8 py-6 border-b border-white/5 flex flex-col md:flex-row justify-between md:items-center gap-4">
                        <div>
                            <h3 class="text-xs font-black text-white uppercase tracking-[0.4em]">Personnel Activity Ledger</h3>
                            <div class="flex items-center gap-2 mt-2">
                                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Live Biometric Stream</span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                             <a href="{{ route('attendance.scanner') }}" class="px-4 py-2 bg-indigo-600/10 border border-indigo-500/20 text-indigo-400 text-[9px] font-black uppercase tracking-widest rounded-lg hover:bg-indigo-600/20 transition-all shadow-xl">Launch Scanner</a>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-white/5 text-[9px] font-black text-slate-500 uppercase tracking-[0.3em] border-b border-white/5">
                                <tr>
                                    <th class="px-8 py-5">Personnel Hash</th>
                                    <th class="px-8 py-5 text-center">Sync Time</th>
                                    <th class="px-8 py-5 text-right">Verification</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($recentLogs as $log)
                                    <tr class="hover:bg-white/5 group transition-all duration-300">
                                        <td class="px-8 py-5">
                                            <div class="flex items-center gap-4">
                                                <div class="w-9 h-9 rounded-xl border border-white/5 bg-slate-800 text-white flex items-center justify-center font-bold text-xs shadow-pro group-hover:border-indigo-500/30 transition-all">
                                                    {{ substr($log->user->name, 0, 1) }}
                                                </div>
                                                <div class="space-y-0.5">
                                                    <div class="text-xs font-black text-white tracking-widest transition-colors group-hover:text-indigo-400">{{ $log->user->name }}</div>
                                                    <div class="text-[9px] font-mono text-slate-600">ID://{{ $log->user->employee_id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5 text-center">
                                            <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/5 border border-white/5 rounded-lg text-[10px] font-mono text-slate-400 tracking-tighter">
                                                <svg class="w-3 h-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ \Carbon\Carbon::parse($log->time_in)->format('H:i:s') }}
                                            </div>
                                        </td>
                                        <td class="px-8 py-5 text-right">
                                            <x-status-badge :type="$log->status">{{ $log->status }}</x-status-badge>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-8 py-20 text-center text-slate-700 text-[10px] font-black uppercase tracking-[0.5em]">Zero Ledger Entries Detected</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Large Analytical Chart -->
                <div class="premium-card p-10 bg-gradient-to-br from-indigo-600/5 to-transparent">
                    <div class="flex items-center justify-between mb-12">
                        <div class="space-y-1">
                             <h3 class="text-xs font-black text-white uppercase tracking-[0.4em]">Financial Disbursement Trends</h3>
                             <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Quarterly Personnel Payout History</p>
                        </div>
                        <div class="text-right">
                             <div class="text-2xl font-black text-emerald-400 tracking-tighter-lg">+12.5%</div>
                             <div class="text-[9px] font-bold text-slate-600 uppercase tracking-widest">Trend Volatility</div>
                        </div>
                    </div>
                    <div id="payrollTrendChart" class="min-h-[400px]"></div>
                </div>
            </div>

            <!-- Sidebar Widgets (1/3) -->
            <div class="xl:col-span-4 space-y-10">
                 <!-- Real-time Temporal Unit -->
                 <div class="premium-card p-1 bg-gradient-to-br from-indigo-500/20 to-violet-500/20 shadow-[0_0_50px_rgba(99,102,241,0.15)] relative overflow-hidden group">
                    <!-- Background pulsing circle -->
                    <div class="absolute -right-20 -top-20 w-64 h-64 bg-indigo-600 rounded-full blur-[100px] opacity-20 animate-pulse duration-5000"></div>
                    
                    <div class="bg-slate-950 p-10 rounded-2xl relative z-10">
                        <div class="text-[9px] font-black text-indigo-400 uppercase tracking-[0.4em] mb-6 border-b border-indigo-500/20 pb-4">Temporal Coordination / Manila</div>
                        <div x-data="{ time: '' }" x-init="setInterval(() => { time = new Date().toLocaleTimeString('en-US', { timeZone: 'Asia/Manila', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }) }, 1000)" class="text-6xl font-black text-white tracking-tighter-lg tabular-nums text-glow mb-4">
                            <span x-text="time || '00:00:00'"></span>
                        </div>
                        <div class="flex items-center justify-between">
                             <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ date('l, F j, Y') }}</div>
                             <div class="w-3 h-3 bg-indigo-500 rounded-full animate-ping"></div>
                        </div>
                    </div>
                 </div>

                 <!-- Personnel Mix Chart -->
                 <div class="premium-card p-8 group">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-10 border-l-2 border-indigo-500/50 pl-4">Personnel Distribution Hash</h3>
                    <div id="attendanceChart" class="min-h-[250px]"></div>
                    <div class="grid grid-cols-2 gap-4 mt-10">
                         <div class="bg-white/3 p-4 rounded-xl border border-white/5 text-center group-hover:border-indigo-500/20 transition-all">
                             <div class="text-xl font-black text-white">96.4%</div>
                             <div class="text-[8px] font-bold text-slate-600 uppercase tracking-widest mt-1">Accuracy</div>
                         </div>
                         <div class="bg-white/3 p-4 rounded-xl border border-white/5 text-center group-hover:border-violet-500/20 transition-all">
                             <div class="text-xl font-black text-white">{{ count($deptStats) }}</div>
                             <div class="text-[8px] font-bold text-slate-600 uppercase tracking-widest mt-1">Clusters</div>
                         </div>
                    </div>
                 </div>

                 <!-- System Quick-Link -->
                 <div class="premium-card bg-indigo-600/10 border-indigo-500/20 p-8 shadow-2xl relative overflow-hidden">
                    <!-- Accent Line -->
                    <div class="absolute left-0 inset-y-0 w-1 bg-indigo-500 shadow-[0_0_20px_rgba(99,102,241,1)]"></div>
                    <h4 class="text-sm font-black text-white uppercase tracking-widest mb-3">Initialize Scanner UI</h4>
                    <p class="text-[10px] text-slate-400 font-medium leading-relaxed mb-8">Access low-level biometric hardware APIs for secure attendance logging and verification.</p>
                    <a href="{{ route('attendance.scanner') }}" class="group block w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-center text-xs font-black uppercase tracking-widest saas-shadow-pro transition-all hover:-translate-y-1 overflow-hidden relative">
                         <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                         Launch Hardware Link
                    </a>
                 </div>
            </div>
        </div>
    </div>

    <!-- Charts integration: High-fidelity overrides -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.Apex = {
                chart: {
                    fontFamily: 'Inter, sans-serif',
                    toolbar: { show: false },
                    foreColor: '#64748b'
                },
                grid: {
                    borderColor: 'rgba(255, 255, 255, 0.05)',
                    strokeDashArray: 2
                },
                tooltip: {
                    theme: 'dark'
                }
            };

            // 1. Attendance Donut (Elite style)
            new ApexCharts(document.querySelector("#attendanceChart"), {
                series: [{{ $presentToday }}, {{ $lateToday }}, {{ $absentToday }}],
                chart: { type: 'donut', height: 280 },
                labels: ['On-time', 'Late Sync', 'Disconnected'],
                colors: ['#10b981', '#f59e0b', '#f43f5e'],
                plotOptions: {
                    pie: {
                        donut: {
                            size: '82%',
                            labels: {
                                show: true,
                                name: { show: true, fontSize: '10px', fontWeight: 900, color: '#64748b', offsetY: -10 },
                                value: { show: true, fontSize: '24px', fontWeight: 900, color: '#ffffff', offsetY: 10 },
                                total: {
                                    show: true,
                                    label: 'Personnel Base',
                                    formatter: () => '{{ $presentToday + $lateToday + $absentToday }}'
                                }
                            }
                        }
                    }
                },
                stroke: { width: 0 },
                legend: { show: false },
                dataLabels: { enabled: false }
            }).render();

            // 2. Payroll Trend (Matrix Flow)
            new ApexCharts(document.querySelector("#payrollTrendChart"), {
                series: [{
                    name: 'Ledger Disbursement',
                    data: [@foreach($payrollTrend as $p) {{ $p->total }}, @endforeach]
                }],
                chart: { type: 'area', height: 400 },
                stroke: { curve: 'smooth', width: 2, colors: ['#6366f1'] },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.3,
                        opacityTo: 0.01,
                        stops: [0, 90, 100]
                    }
                },
                markers: { size: 4, colors: ['#6366f1'], strokeWidth: 0, hover: { size: 6 } },
                xaxis: {
                    categories: [@foreach($payrollTrend as $p) '{{ $p->month }}', @endforeach],
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        formatter: (val) => 'PHP ' + (val/1000).toFixed(0) + 'K'
                    }
                }
            }).render();
        });
    </script>
</x-app-layout>
