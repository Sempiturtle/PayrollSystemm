<x-app-layout>
    <x-slot name="header">
        Payroll Management
    </x-slot>

    <div class="space-y-8">
        <!-- Generate Payroll Section -->
        <div class="bg-indigo-600 rounded-[2.5rem] p-10 text-white shadow-xl shadow-indigo-100 dark:shadow-none relative overflow-hidden">
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-8">
                <div class="max-w-md">
                    <h3 class="text-3xl font-bold tracking-tight mb-2">Process Payroll</h3>
                    <p class="text-indigo-100 text-sm leading-relaxed">Select a date range to automatically compute hours, late deductions, and total net pay for all active employees.</p>
                </div>
                <form action="{{ route('payrolls.generate') }}" method="POST" class="w-full lg:w-auto flex flex-wrap items-end gap-4 bg-white/10 p-6 rounded-3xl border border-white/20">
                    @csrf
                    <div class="flex-1 min-w-[150px]">
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-indigo-200 mb-2">Start Period</label>
                        <input name="start_date" type="date" required class="w-full bg-indigo-700/50 border-white/30 rounded-xl text-white focus:ring-white">
                    </div>
                    <div class="flex-1 min-w-[150px]">
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-indigo-200 mb-2">End Period</label>
                        <input name="end_date" type="date" required class="w-full bg-indigo-700/50 border-white/30 rounded-xl text-white focus:ring-white">
                    </div>
                    <button class="px-8 py-3 bg-white text-indigo-600 rounded-xl font-bold hover:bg-slate-50 transition shadow-lg">Generate</button>
                </form>
            </div>
        </div>

        <!-- Payroll History -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-8 border-b border-slate-50 dark:border-slate-800">
                <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100 italic tracking-tight text-center sm:text-left">Payout History Registry</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                            <th class="px-8 py-4">Beneficiary</th>
                            <th class="px-8 py-4">Cycle Period</th>
                            <th class="px-8 py-4">Logged Hours</th>
                            <th class="px-8 py-4">Late Deduc.</th>
                            <th class="px-8 py-4">Status</th>
                            <th class="px-8 py-4 text-right">Net Payout</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($payrolls as $payroll)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 font-bold">
                                        {{ substr($payroll->user->name, 0, 1) }}
                                    </div>
                                    <div class="font-bold text-slate-900 dark:text-slate-100 text-sm tracking-tight">{{ $payroll->user->name }}</div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-xs font-bold text-slate-600 dark:text-slate-400">
                                    {{ $payroll->period_start->format('M d') }} - {{ $payroll->period_end->format('M d, Y') }}
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-sm font-bold text-slate-800 dark:text-slate-200 tabular-nums">{{ number_format($payroll->total_hours, 1) }}h</span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-sm font-bold text-rose-500 tabular-nums">-₱{{ number_format($payroll->total_deductions, 2) }}</span>
                                <div class="text-[10px] text-slate-400 font-medium tracking-tight uppercase">{{ $payroll->late_minutes }} mins late</div>
                            </td>
                            <td class="px-8 py-6">
                                <x-status-badge :type="strtolower($payroll->status)">{{ $payroll->status }}</x-status-badge>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <span class="text-xl font-bold text-emerald-600 tabular-nums tracking-tighter">₱{{ number_format($payroll->net_pay, 2) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center text-slate-400 text-sm italic">No payroll records discovered in the registry.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
