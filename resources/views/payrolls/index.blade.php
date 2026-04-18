<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 border border-indigo-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-900 leading-none tracking-tight">Payroll Engine</h2>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Disbursement & Settlement Grid</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-4">
        <!-- Command Console (Payroll Generation) -->
        <div class="bg-slate-950 rounded-xl border border-slate-800 shadow-xl relative overflow-hidden flex flex-col md:flex-row items-center justify-between p-1.5 gap-4">
            <!-- Subtle backdrop glow -->
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 to-blue-500/10 pointer-events-none"></div>
            
            <div class="flex items-center gap-6 px-4 py-3 relative z-10 w-full md:w-auto">
                <div class="w-10 h-10 rounded-lg bg-slate-900 border border-slate-800 flex items-center justify-center text-indigo-400 shadow-inner">
                    <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-white tracking-tight">Automated Processing Matrix</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Initialize the computation sequence for current active attendance stream.</p>
                </div>
            </div>

            <div class="w-full md:w-auto flex flex-col sm:flex-row items-center gap-3 bg-slate-900/80 p-2 rounded-lg border border-slate-800 relative z-10 shrink-0">
                <div class="px-4 text-center sm:text-left">
                    <label class="block text-[9px] font-black uppercase tracking-[0.2em] text-slate-500 mb-0.5">Active Cycle</label>
                    @php
                        $period = app(App\Services\PayrollService::class)->getCurrentPeriod();
                    @endphp
                    <div class="text-white font-bold text-xs tracking-tight font-mono">{{ Carbon\Carbon::parse($period['start'])->format('M d') }} <span class="text-slate-600 mx-1">→</span> {{ Carbon\Carbon::parse($period['end'])->format('M d, y') }}</div>
                </div>
                <form action="{{ route('payrolls.generate') }}" method="POST" class="w-full sm:w-auto">
                    @csrf
                    <button class="w-full sm:w-auto px-6 py-2.5 bg-white text-slate-950 rounded-md font-bold text-xs hover:bg-indigo-50 hover:text-indigo-600 transition shadow-[0_0_15px_-3px_rgba(255,255,255,0.3)] whitespace-nowrap tracking-tight">Compile Records</button>
                </form>
            </div>
        </div>

        <!-- High-Density Registry Layout -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">Master Payout Registry</h3>
                </div>
                <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Read-Only Logs</div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white border-b border-slate-100">
                            <th class="px-5 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest w-1/4">Beneficiary</th>
                            <th class="px-5 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Cycle Span</th>
                            <th class="px-5 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right">Logged</th>
                            <th class="px-5 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right">Late Ded.</th>
                            <th class="px-5 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">State</th>
                            <th class="px-5 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right whitespace-nowrap">Net Payout</th>
                            <th class="px-5 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center w-24">Auth</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($payrolls as $payroll)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-md bg-slate-900 text-white flex items-center justify-center font-bold text-xs shadow-inner shrink-0">
                                        {{ substr($payroll->user->name, 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-slate-900 text-xs tracking-tight truncate">{{ $payroll->user->name }}</div>
                                        <div class="text-[9px] text-indigo-600 font-bold uppercase tracking-widest mt-0.5 truncate">{{ $payroll->user->role }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                <div class="text-[11px] font-bold text-slate-600 font-mono tracking-tighter">
                                    {{ $payroll->period_start->format('M d') }} <span class="text-slate-300">-</span> {{ $payroll->period_end->format('M d') }}
                                </div>
                                <div class="text-[8px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-0.5">{{ $payroll->period_start->format('Y') }}</div>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <span class="text-xs font-bold text-slate-800 font-mono bg-slate-50 px-2 py-1 rounded border border-slate-100">{{ number_format($payroll->total_hours, 1) }}<span class="text-[9px] text-slate-400 ml-0.5 uppercase tracking-widest">h</span></span>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <div class="text-xs font-bold text-rose-600 font-mono">-₱{{ number_format($payroll->total_deductions, 2) }}</div>
                                <div class="text-[8px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ $payroll->late_minutes }}m gap</div>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <span class="inline-flex items-center justify-center px-2 py-1 rounded-[4px] text-[9px] font-black uppercase tracking-[0.2em] {{ strtolower($payroll->status) === 'finalized' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-amber-50 text-amber-600 border border-amber-100' }}">
                                    {{ $payroll->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-right whitespace-nowrap">
                                <span class="text-sm font-black {{ strtolower($payroll->status) === 'finalized' ? 'text-slate-900' : 'text-slate-500' }} tabular-nums tracking-tighter">₱{{ number_format($payroll->net_pay, 2) }}</span>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-center gap-1.5 opacity-40 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('payrolls.download', $payroll) }}" class="inline-flex items-center justify-center w-7 h-7 rounded border border-slate-200 bg-white text-slate-500 hover:text-indigo-600 hover:border-indigo-200 transition" title="Export File">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    </a>

                                    @if(!auth()->user()->isAdmin())
                                        {{-- Employee Dispute Button --}}
                                        <div x-data="{ disputeOpen: false }" class="relative">
                                            <button @click="disputeOpen = true" class="inline-flex items-center justify-center w-7 h-7 rounded border border-amber-200 bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white transition" title="File Dispute">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                                            </button>
                                            {{-- Inline Dispute Modal --}}
                                            <div x-show="disputeOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
                                                <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="disputeOpen = false"></div>
                                                <div class="bg-white rounded-xl shadow-2xl w-full max-w-md relative p-5 border border-slate-100">
                                                    <h3 class="text-sm font-bold text-slate-900 mb-1 tracking-tight">File Discrepancy</h3>
                                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Payroll Cycle: {{ $payroll->period_start->format('M d') }} - {{ $payroll->period_end->format('M d, Y') }}</p>
                                                    <form action="{{ route('discrepancies.store') }}" method="POST" class="space-y-4">
                                                        @csrf
                                                        <input type="hidden" name="payroll_id" value="{{ $payroll->id }}">
                                                        <div>
                                                            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Describe the Issue</label>
                                                            <textarea name="description" rows="4" required maxlength="1000" class="w-full bg-slate-50 border-slate-100 rounded-lg text-xs focus:ring-indigo-500" placeholder="e.g. I was present but marked absent on April 15..."></textarea>
                                                        </div>
                                                        <div class="flex gap-3">
                                                            <button type="button" @click="disputeOpen = false" class="flex-1 py-2.5 px-4 bg-slate-100 text-slate-600 rounded-lg font-bold text-xs hover:bg-slate-200 transition">Cancel</button>
                                                            <button type="submit" class="flex-1 py-2.5 px-4 bg-amber-500 text-white rounded-lg font-bold text-xs shadow-lg shadow-amber-100 hover:bg-amber-600 transition">Submit Dispute</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if(auth()->user()->isAdmin() && strtolower($payroll->status) === 'draft')
                                        <form action="{{ route('payrolls.finalize', $payroll) }}" method="POST" onsubmit="return confirm('WARNING: Lock sequence initiated. This permanently finalizes statutory and deduction values. Proceed?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center justify-center w-7 h-7 rounded border border-emerald-200 bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white transition" title="Finalize Core">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                            </button>
                                        </form>
                                    @else
                                        <div class="inline-flex items-center justify-center w-7 h-7 rounded bg-slate-50 text-slate-300" title="Locked">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center">
                                <div class="text-slate-400 text-xs font-bold uppercase tracking-widest">No active payouts generated</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-4 py-3 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
                <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Data dynamically synced via Identity Auth.</div>
                <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest tabular-nums">{{ count($payrolls) }} Registry Entries</div>
            </div>
        </div>
    </div>
</x-app-layout>
