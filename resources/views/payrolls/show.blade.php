<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('payrolls.index') }}" class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-indigo-50 hover:text-indigo-600 transition border border-slate-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h2 class="text-lg font-bold text-slate-900 leading-none tracking-tight">Payroll Detail</h2>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $payroll->user->name }} — {{ $payroll->period_start->format('M d') }} to {{ $payroll->period_end->format('M d, Y') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-4">
        {{-- Employee & Period Summary Bar --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div>
                    <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Employee</div>
                    <div class="text-sm font-bold text-slate-900 mt-0.5">{{ $payroll->user->name }}</div>
                </div>
                <div>
                    <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Type</div>
                    <div class="text-sm font-bold text-indigo-600 mt-0.5">{{ ucfirst($payroll->user->employment_type ?? 'Professor') }}</div>
                </div>
                <div>
                    <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Period</div>
                    <div class="text-sm font-bold text-slate-700 mt-0.5 font-mono">{{ $payroll->period_start->format('M d') }} – {{ $payroll->period_end->format('M d') }}</div>
                </div>
                <div>
                    <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Total Hours</div>
                    <div class="text-sm font-bold text-slate-900 mt-0.5 font-mono">{{ number_format($payroll->total_hours, 2) }} hrs</div>
                </div>
                <div>
                    <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Net Pay</div>
                    <div class="text-lg font-black text-emerald-600 mt-0.5">₱{{ number_format($payroll->net_pay, 2) }}</div>
                </div>
            </div>
        </div>

        {{-- HOLIDAY PAY SECTION — The star feature for panelists --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden" id="holiday-pay-section">
            <div class="px-4 py-3 border-b border-slate-100 bg-gradient-to-r from-amber-50 to-orange-50 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 border border-amber-200 flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">Holiday Pay Breakdown</h3>
                        <p class="text-[10px] text-slate-500 mt-0.5">How holidays within this period affect the payroll calculation</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-widest border
                        {{ $holidayPayMethod === 'professor' 
                            ? 'bg-violet-50 text-violet-600 border-violet-200' 
                            : 'bg-blue-50 text-blue-600 border-blue-200' }}">
                        {{ $holidayPayMethod === 'professor' ? 'Hourly Method' : 'Fixed Salary Method' }}
                    </span>
                </div>
            </div>

            {{-- Holiday Pay Method Explanation --}}
            <div class="px-4 py-3 bg-slate-50/50 border-b border-slate-100">
                @if($holidayPayMethod === 'professor')
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-violet-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-[11px] text-slate-600 leading-relaxed">
                            <span class="font-bold text-violet-700">Professor Holiday Pay Rule:</span> 
                            When a professor does not work on a paid holiday, they receive pay based on the 
                            <span class="font-bold text-slate-900">hours they worked on their last working day</span> before the holiday, 
                            multiplied by their hourly rate of <span class="font-bold text-slate-900">₱{{ number_format($payroll->user->hourly_rate ?? 0, 2) }}/hr</span>.
                        </p>
                    </div>
                @else
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-[11px] text-slate-600 leading-relaxed">
                            <span class="font-bold text-blue-700">Staff Holiday Pay Rule:</span> 
                            When a staff employee does not work on a paid holiday, they receive pay based on their 
                            <span class="font-bold text-slate-900">normal scheduled hours</span> for that day.
                        </p>
                    </div>
                @endif
            </div>

            @if(count($holidayDetails) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white border-b border-slate-100">
                                <th class="px-4 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Date</th>
                                <th class="px-4 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Holiday</th>
                                <th class="px-4 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">Pay Type</th>
                                <th class="px-4 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">Worked?</th>
                                <th class="px-4 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right">Hours Credited</th>
                                <th class="px-4 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Calculation Method</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($holidayDetails as $hd)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-4 py-2.5">
                                    <div class="text-xs font-bold text-slate-800 font-mono">{{ \Carbon\Carbon::parse($hd['date'])->format('M d, Y') }}</div>
                                    <div class="text-[9px] text-slate-400 italic">{{ \Carbon\Carbon::parse($hd['date'])->format('l') }}</div>
                                </td>
                                <td class="px-4 py-2.5">
                                    <div class="text-xs font-bold text-slate-800">{{ $hd['name'] }}</div>
                                    <div class="text-[9px] text-slate-400">{{ $hd['type'] }}</div>
                                </td>
                                <td class="px-4 py-2.5 text-center">
                                    @php
                                        $payTypeColor = match($hd['pay_type']) {
                                            'Double Pay' => 'bg-amber-50 text-amber-600 border-amber-200',
                                            'Paid' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                            default => 'bg-slate-50 text-slate-400 border-slate-200',
                                        };
                                    @endphp
                                    <span class="inline-flex px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-widest border {{ $payTypeColor }}">
                                        {{ $hd['pay_type'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5 text-center">
                                    @if($hd['worked'])
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Yes
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-400">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            No
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-2.5 text-right">
                                    <span class="text-sm font-black text-slate-900 font-mono {{ $hd['hours_credited'] > 0 ? '' : 'text-slate-300' }}">
                                        {{ number_format($hd['hours_credited'], 2) }} <span class="text-[9px] font-bold text-slate-400">hrs</span>
                                    </span>
                                    @if(isset($hd['actual_hours']) && isset($hd['multiplier']) && $hd['multiplier'] !== '1x')
                                        <div class="text-[9px] text-amber-600 font-bold">{{ $hd['actual_hours'] }} hrs × {{ $hd['multiplier'] }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-2.5">
                                    <div class="flex items-center gap-1.5">
                                        @if($hd['pay_type'] === 'Unpaid')
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                        @elseif($hd['worked'])
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                        @else
                                            <span class="w-1.5 h-1.5 rounded-full bg-violet-400"></span>
                                        @endif
                                        <span class="text-[10px] font-bold text-slate-600">{{ $hd['method'] }}</span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-slate-50/80 border-t border-slate-200">
                                <td colspan="4" class="px-4 py-2.5 text-right">
                                    <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Total Holiday Hours</span>
                                </td>
                                <td class="px-4 py-2.5 text-right">
                                    @php $totalHolidayHours = collect($holidayDetails)->sum('hours_credited'); @endphp
                                    <span class="text-sm font-black text-indigo-600 font-mono">{{ number_format($totalHolidayHours, 2) }} <span class="text-[9px]">hrs</span></span>
                                </td>
                                <td class="px-4 py-2.5">
                                    @if($holidayPayMethod === 'professor')
                                        @php $holidayPay = $totalHolidayHours * ($payroll->user->hourly_rate ?? 0); @endphp
                                        <span class="text-xs font-black text-indigo-600">≈ ₱{{ number_format($holidayPay, 2) }}</span>
                                    @endif
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="px-4 py-8 text-center">
                    <div class="w-12 h-12 mx-auto rounded-xl bg-slate-100 flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-xs font-bold text-slate-400">No holidays fell within this payroll period.</p>
                    <p class="text-[10px] text-slate-300 mt-1">Holiday pay details appear here when holidays overlap with the pay cycle.</p>

                    @if($periodHolidays->isNotEmpty())
                        <div class="mt-4 p-3 bg-amber-50 rounded-lg border border-amber-100 inline-block">
                            <p class="text-[10px] font-bold text-amber-600">
                                Note: {{ $periodHolidays->count() }} holiday(s) exist in this period, but no snapshot data is stored. 
                                <br>Re-generate payroll to see the holiday breakdown.
                            </p>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        {{-- Earnings & Deductions Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Earnings --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-100 bg-emerald-50/50 flex items-center gap-2">
                    <div class="w-6 h-6 rounded-md bg-emerald-100 flex items-center justify-center">
                        <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">Earnings</h3>
                </div>
                <div class="divide-y divide-slate-50">
                    <div class="px-4 py-3 flex items-center justify-between">
                        <div>
                            <div class="text-xs font-bold text-slate-800">Gross Pay</div>
                            <div class="text-[9px] text-slate-400">{{ number_format($payroll->total_hours, 2) }} hrs total</div>
                        </div>
                        <span class="text-sm font-black text-slate-900 font-mono">₱{{ number_format($payroll->gross_pay, 2) }}</span>
                    </div>
                    @if($payroll->overtime_pay > 0)
                    <div class="px-4 py-3 flex items-center justify-between">
                        <div>
                            <div class="text-xs font-bold text-slate-800">Overtime Pay</div>
                            <div class="text-[9px] text-slate-400">{{ number_format($payroll->overtime_hours, 2) }} hrs × ₱{{ number_format($payroll->user->overtime_rate, 2) }}/hr</div>
                        </div>
                        <span class="text-sm font-bold text-emerald-600 font-mono">+₱{{ number_format($payroll->overtime_pay, 2) }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Deductions --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-100 bg-rose-50/50 flex items-center gap-2">
                    <div class="w-6 h-6 rounded-md bg-rose-100 flex items-center justify-center">
                        <svg class="w-3 h-3 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">Deductions</h3>
                </div>
                <div class="divide-y divide-slate-50">
                    @if($payroll->late_deduction > 0)
                    <div class="px-4 py-2.5 flex items-center justify-between">
                        <div class="text-xs text-slate-700">Late ({{ number_format($payroll->late_minutes, 0) }} min)</div>
                        <span class="text-xs font-bold text-rose-500 font-mono">-₱{{ number_format($payroll->late_deduction, 2) }}</span>
                    </div>
                    @endif
                    @if($payroll->absence_deduction > 0)
                    <div class="px-4 py-2.5 flex items-center justify-between">
                        <div class="text-xs text-slate-700">Absence</div>
                        <span class="text-xs font-bold text-rose-500 font-mono">-₱{{ number_format($payroll->absence_deduction, 2) }}</span>
                    </div>
                    @endif
                    <div class="px-4 py-2.5 flex items-center justify-between">
                        <div class="text-xs text-slate-700">SSS</div>
                        <span class="text-xs font-bold text-rose-500 font-mono">-₱{{ number_format($payroll->sss_deduction, 2) }}</span>
                    </div>
                    <div class="px-4 py-2.5 flex items-center justify-between">
                        <div class="text-xs text-slate-700">PhilHealth</div>
                        <span class="text-xs font-bold text-rose-500 font-mono">-₱{{ number_format($payroll->philhealth_deduction, 2) }}</span>
                    </div>
                    <div class="px-4 py-2.5 flex items-center justify-between">
                        <div class="text-xs text-slate-700">Pag-IBIG</div>
                        <span class="text-xs font-bold text-rose-500 font-mono">-₱{{ number_format($payroll->pagibig_deduction, 2) }}</span>
                    </div>
                    <div class="px-4 py-2.5 flex items-center justify-between">
                        <div class="text-xs text-slate-700">Withholding Tax</div>
                        <span class="text-xs font-bold text-rose-500 font-mono">-₱{{ number_format($payroll->tax_deduction, 2) }}</span>
                    </div>
                    <div class="px-4 py-3 flex items-center justify-between bg-slate-50">
                        <div class="text-xs font-black text-slate-900 uppercase tracking-widest">Total Deductions</div>
                        <span class="text-sm font-black text-rose-600 font-mono">-₱{{ number_format($payroll->total_deductions, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Net Pay Summary --}}
        <div class="bg-gradient-to-r from-slate-900 to-slate-800 rounded-xl p-5 flex items-center justify-between shadow-xl">
            <div>
                <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Net Take-Home Pay</div>
                <div class="text-2xl font-black text-white mt-1 tracking-tight">₱{{ number_format($payroll->net_pay, 2) }}</div>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest {{ strtolower($payroll->status) === 'finalized' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-amber-500/20 text-amber-400 border border-amber-500/30' }}">
                    {{ $payroll->status }}
                </span>
                <a href="{{ route('payrolls.download', $payroll) }}" class="px-4 py-2 bg-white text-slate-900 rounded-lg font-bold text-xs hover:bg-indigo-50 transition">
                    Download PDF
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
