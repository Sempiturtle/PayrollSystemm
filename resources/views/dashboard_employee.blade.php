<x-app-layout>
    <x-slot name="header">
        Employee Dashboard
    </x-slot>

    <div class="space-y-8">
        <!-- Welcome Card -->
        <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-[2.5rem] p-10 text-white shadow-xl relative overflow-hidden">
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="relative z-10">
                <h3 class="text-3xl font-bold tracking-tight mb-2">Welcome back, {{ $user->name }}!</h3>
                <p class="text-indigo-100 text-sm max-w-md leading-relaxed">AISAT Personnel Portal. Track your daily attendance, view schedules, and monitor your payroll payouts.</p>
                
                <div class="mt-8 flex items-center gap-6">
                    <div class="text-center">
                        <div class="text-xs font-bold text-indigo-200 uppercase tracking-widest mb-1">Today Status</div>
                        @if($todayLog)
                            <x-status-badge :type="$todayLog->status">{{ $todayLog->status }}</x-status-badge>
                        @else
                            <span class="px-3 py-1 bg-white/20 text-white text-xs font-bold rounded-full border border-white/30 italic">Not Logged Yet</span>
                        @endif
                    </div>
                    <div class="w-px h-10 bg-white/20"></div>
                    <div>
                        <div class="text-xs font-bold text-indigo-200 uppercase tracking-widest mb-1">Employee ID</div>
                        <div class="text-lg font-bold tracking-tighter">{{ $user->employee_id }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Attendance -->
            <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-50 dark:border-slate-800">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 italic">Your Recent Logs</h3>
                </div>
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Time In</th>
                            <th class="px-6 py-4">Time Out</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($myLogs as $log)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition">
                            <td class="px-6 py-4 text-sm font-bold text-slate-700 dark:text-slate-300">
                                {{ \Carbon\Carbon::parse($log->date)->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-600 dark:text-slate-400">
                                {{ $log->time_in ? \Carbon\Carbon::parse($log->time_in)->format('h:i A') : '--:--' }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-600 dark:text-slate-400">
                                {{ $log->time_out ? \Carbon\Carbon::parse($log->time_out)->format('h:i A') : '--:--' }}
                            </td>
                            <td class="px-6 py-4">
                                <x-status-badge :type="$log->status">{{ $log->status }}</x-status-badge>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="p-8 text-center italic text-slate-400">No attendance history found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Recent Payroll -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-50 dark:border-slate-800">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 italic">Payout History</h3>
                </div>
                <div class="p-6 space-y-4">
                    @forelse($myPayrolls as $payroll)
                    <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-800 hover:shadow-md transition group">
                        <div class="flex justify-between items-start mb-2">
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $payroll->period_end->format('M d, Y') }}</div>
                            <div class="text-emerald-600 font-bold tracking-tighter">₱{{ number_format($payroll->net_pay, 2) }}</div>
                        </div>
                        <div class="text-sm font-bold text-slate-700 dark:text-slate-300">Net Payout Received</div>
                        <div class="mt-2 flex items-center justify-between text-[10px] uppercase font-bold text-slate-400">
                            <span>Hours: {{ number_format($payroll->total_hours, 1) }}h</span>
                            <span class="text-rose-400">Lates: {{ $payroll->late_minutes }}m</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center italic text-slate-400 py-8">No payroll records yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
