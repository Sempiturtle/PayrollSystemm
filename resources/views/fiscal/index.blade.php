<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-sm border border-emerald-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-900 leading-none mb-1">Fiscal Summary (YTD {{ $currentYear }})</h2>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Year-to-Date Statutory Contributions & Earnings</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- YTD Summary Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
                $metrics = [
                    ['label' => 'Total SSS', 'value' => $totals['sss'], 'icon' => 'shield', 'color' => 'indigo'],
                    ['label' => 'Total PhilHealth', 'value' => $totals['philhealth'], 'icon' => 'heart', 'color' => 'rose'],
                    ['label' => 'Total Pag-IBIG', 'value' => $totals['pagibig'], 'icon' => 'home', 'color' => 'blue'],
                    ['label' => 'Total WHT Tax', 'value' => $totals['tax'], 'icon' => 'currency', 'color' => 'amber'],
                ];
            @endphp

            @foreach($metrics as $m)
                <div class="bg-white rounded-xl p-6 border border-slate-100 shadow-sm overflow-hidden relative group hover:shadow-md transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xs font-bold uppercase tracking-widest text-slate-500">{{ $m['label'] }}</h3>
                        <div class="shrink-0 w-8 h-8 rounded-lg bg-{{ $m['color'] }}-50 flex items-center justify-center text-{{ $m['color'] }}-600">
                            @if($m['icon'] == 'shield')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            @elseif($m['icon'] == 'heart')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            @elseif($m['icon'] == 'home')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @endif
                        </div>
                    </div>
                    <div class="text-xl font-extrabold text-slate-900 tracking-tight">₱ {{ number_format($m['value'], 2) }}</div>
                    <p class="mt-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Accumulated {{ $currentYear }}</p>
                </div>
            @endforeach
        </div>

        <!-- Net/Gross Earnings Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-slate-900 rounded-xl p-4 text-white shadow-xl relative overflow-hidden group">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total Gross Earnings</h3>
                        <div class="text-xl font-extrabold tracking-tight">₱ {{ number_format($totals['gross'], 2) }}</div>
                    </div>
                    <div class="w-12 h-10 rounded-2xl bg-white/10 flex items-center justify-center backdrop-blur-md text-emerald-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                </div>
                <div class="h-1.5 w-full bg-white/10 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500" style="width: 100%"></div>
                </div>
                <p class="mt-4 text-xs font-medium text-slate-400 italic">Total value before any statutory or institutional deductions.</p>
            </div>

            <div class="bg-emerald-600 rounded-xl p-4 text-white shadow-xl relative overflow-hidden group">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xs font-bold text-emerald-200 uppercase tracking-widest mb-1">Total Net Take-Home</h3>
                        <div class="text-xl font-extrabold tracking-tight">₱ {{ number_format($totals['net'], 2) }}</div>
                    </div>
                    <div class="w-12 h-10 rounded-2xl bg-white/20 flex items-center justify-center backdrop-blur-md text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
                <div class="h-1.5 w-full bg-white/10 rounded-full overflow-hidden">
                    <div class="h-full bg-white" style="width: {{ $totals['gross'] > 0 ? ($totals['net'] / $totals['gross']) * 100 : 0 }}%"></div>
                </div>
                <p class="mt-4 text-xs font-medium text-emerald-100 italic">Actual amount disbursed into your account this year.</p>
            </div>
        </div>

        <!-- History Table -->
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-50 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Fiscal Period Records</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Comparison of deductions per cycle</p>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pay Period</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">SSS</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">PH</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">PI</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">WHT</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Net Pay</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($ytdData as $p)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-xs font-bold text-slate-900">{{ \Carbon\Carbon::parse($p->period_start)->format('M d') }} - {{ \Carbon\Carbon::parse($p->period_end)->format('M d') }}</div>
                                    <div class="text-[10px] text-slate-400 font-medium">Ref: #PAY-{{ $p->id }}</div>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-600 text-right">₱ {{ number_format($p->sss_deduction, 2) }}</td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-600 text-right">₱ {{ number_format($p->philhealth_deduction, 2) }}</td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-600 text-right">₱ {{ number_format($p->pagibig_deduction, 2) }}</td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-600 text-right">₱ {{ number_format($p->tax_deduction, 2) }}</td>
                                <td class="px-6 py-4 text-right">
                                    <span class="px-2.5 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-bold rounded-lg border border-indigo-100">₱ {{ number_format($p->net_pay, 2) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="text-slate-400 font-medium italic">No fiscal records found for {{ $currentYear }}.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Defense Note -->
        <div class="flex items-start gap-4 p-4 bg-amber-50 border border-amber-100 rounded-2xl">
            <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center text-amber-600 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <h5 class="text-xs font-bold text-amber-900 uppercase tracking-widest mb-1">Notice for Statutory Verification</h5>
                <p class="text-[11px] text-amber-800 leading-relaxed font-medium">Values shown here are aggregated from finalized payroll records. For institutional verification of SSS or PhilHealth contributions, please coordinate with the HR Disbursement Unit or refer to the Digital Service Records tab.</p>
            </div>
        </div>
    </div>
</x-app-layout>
