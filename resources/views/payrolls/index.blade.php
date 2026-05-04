<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <span class="text-[#101D33]/40 font-bold uppercase tracking-[0.2em] text-[10px]">Financials</span>
            <span class="text-[#101D33]/20">/</span>
            <span class="font-['DM_Serif_Text'] text-[#101D33] italic">Disbursement Hub</span>
        </div>
    </x-slot>

    <div class="space-y-6 animate-in-up">
        <!-- Command Console (Payroll Generation) -->
        <div class="bg-[#101D33] rounded-2xl border border-white/5 shadow-[0_40px_100px_rgba(16,29,51,0.2)] relative overflow-hidden flex flex-col lg:flex-row items-center justify-between p-1 lg:p-2 gap-4">
            <!-- Institutional Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-tr from-[#660000]/20 to-transparent pointer-events-none opacity-50"></div>
            
            <div class="flex items-center gap-6 px-6 py-4 relative z-10 w-full lg:w-auto">
                <div class="w-10 h-10 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-[#D4AF37] shadow-2xl relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#D4AF37]/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <svg class="w-5 h-5 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-['DM_Serif_Display'] text-white leading-none">Automated <span class="text-[#D4AF37]">Computation</span> Matrix</h3>
                    <p class="text-[9px] font-bold text-white/30 uppercase tracking-[0.3em] mt-2">Synchronize attendance streams into fiscal settlements.</p>
                </div>
            </div>

            <div class="w-full lg:w-auto flex flex-col sm:flex-row items-center gap-4 bg-white/5 p-3 rounded-xl border border-white/10 relative z-10 shrink-0">
                <div class="px-4 text-center sm:text-left">
                    <label class="block text-[8px] font-black uppercase tracking-[0.4em] text-white/20 mb-1.5">Active Fiscal Cycle</label>
                    @php
                        $period = app(App\Services\PayrollService::class)->getCurrentPeriod();
                    @endphp
                    <div class="text-white font-['DM_Serif_Display'] text-base tracking-tight">
                        {{ Carbon\Carbon::parse($period['start'])->format('M d') }} 
                        <span class="text-[#D4AF37] mx-1.5">→</span> 
                        {{ Carbon\Carbon::parse($period['end'])->format('M d, Y') }}
                    </div>
                </div>
                <form action="{{ route('payrolls.generate') }}" method="POST" class="w-full sm:w-auto">
                    @csrf
                    <button class="w-full sm:w-auto px-8 py-3 bg-[#D4AF37] text-[#101D33] rounded-xl font-black text-[11px] hover:bg-white transition-all shadow-[0_15px_30px_rgba(212,175,55,0.2)] uppercase tracking-[0.2em] hover:scale-[1.02] active:scale-[0.98]">
                        Compile Registry
                    </button>
                </form>
            </div>
        </div>

        <!-- High-Density Registry Layout -->
        <div class="bg-white rounded-3xl border border-[#101D33]/5 shadow-[0_30px_100px_rgba(16,29,51,0.06)] overflow-hidden flex flex-col">
            <div class="px-5 py-3 border-b border-[#101D33]/5 flex items-center justify-between bg-[#FDFCF8]/50">
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></span>
                    <h3 class="text-[9px] font-black text-[#101D33] uppercase tracking-[0.4em]">Institutional Settlement Ledger</h3>
                </div>
                <div class="text-[8px] font-bold text-slate-300 uppercase tracking-[0.2em]">Validated Assets & Remunerations</div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white border-b border-[#101D33]/5">
                            <th class="px-5 py-3 text-[8px] font-black text-slate-400 uppercase tracking-[0.3em] w-1/4">Recipient Identity</th>
                            <th class="px-5 py-3 text-[8px] font-black text-slate-400 uppercase tracking-[0.3em]">Temporal Span</th>
                            <th class="px-5 py-3 text-[8px] font-black text-slate-400 uppercase tracking-[0.3em] text-center">Labor Log</th>
                            <th class="px-5 py-3 text-[8px] font-black text-slate-400 uppercase tracking-[0.3em] text-right">Tardy Penalty</th>
                            <th class="px-5 py-3 text-[8px] font-black text-slate-400 uppercase tracking-[0.3em] text-center">Protocol State</th>
                            <th class="px-5 py-3 text-[8px] font-black text-slate-400 uppercase tracking-[0.3em] text-right">Net Disbursement</th>
                            <th class="px-5 py-3 text-[8px] font-black text-slate-400 uppercase tracking-[0.3em] text-center w-32">Auth</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#101D33]/5">
                        @forelse($payrolls as $payroll)
                        <tr class="hover:bg-[#FDFCF8] transition-all group">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-[#101D33] text-white flex items-center justify-center font-['DM_Serif_Display'] text-xs shadow-xl relative overflow-hidden shrink-0">
                                        <div class="absolute inset-0 bg-gradient-to-br from-[#660000]/30 to-transparent"></div>
                                        <span class="relative z-10">{{ substr($payroll->user->name, 0, 1) }}</span>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-['DM_Serif_Text'] text-[#101D33] text-sm tracking-tight truncate leading-none mb-1.5">{{ $payroll->user->name }}</div>
                                        <div class="text-[8px] text-slate-300 font-bold uppercase tracking-[0.2em] truncate">{{ $payroll->user->role }} Registry</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-8">
                                <div class="text-xs font-bold text-[#101D33] tabular-nums tracking-widest uppercase">
                                    {{ $payroll->period_start->format('M d') }} <span class="text-slate-300 mx-1">—</span> {{ $payroll->period_end->format('M d') }}
                                </div>
                                <div class="text-[8px] text-slate-300 font-bold uppercase tracking-[0.3em] mt-2">Fiscal Year {{ $payroll->period_start->format('Y') }}</div>
                            </td>
                            <td class="px-10 py-8 text-center">
                                <span class="inline-block text-xs font-bold text-[#101D33] tabular-nums bg-[#101D33]/5 px-4 py-2 rounded-xl border border-[#101D33]/5">
                                    {{ number_format($payroll->total_hours, 1) }}<span class="text-[9px] text-[#101D33]/40 ml-1 uppercase tracking-widest">HR Logged</span>
                                </span>
                            </td>
                            <td class="px-10 py-8 text-right">
                                <div class="text-xs font-bold text-[#660000] tabular-nums">— ₱{{ number_format($payroll->late_deduction, 2) }}</div>
                                <div class="text-[8px] text-slate-300 font-bold uppercase tracking-widest mt-2">{{ number_format($payroll->late_minutes, 0) }}m Temporal Offset</div>
                            </td>
                            <td class="px-10 py-8 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <x-status-badge :type="strtolower($payroll->status)">{{ $payroll->status }}</x-status-badge>
                                    <div class="text-[7px] font-bold text-slate-200 uppercase tracking-[0.2em]">Compliance Verified</div>
                                </div>
                            </td>
                            <td class="px-10 py-8 text-right">
                                <div class="flex flex-col items-end">
                                    <span class="text-lg font-['DM_Serif_Display'] {{ strtolower($payroll->status) === 'finalized' ? 'text-[#101D33]' : 'text-slate-300' }} tabular-nums leading-none mb-1">₱{{ number_format($payroll->net_pay, 2) }}</span>
                                    <span class="text-[8px] font-bold text-[#D4AF37] uppercase tracking-widest">Remuneration Total</span>
                                </div>
                                                    <td class="px-5 py-3">
                                <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-all transform translate-x-4 group-hover:translate-x-0">
                                    <a href="{{ route('payrolls.download', $payroll) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white text-[#101D33]/40 hover:text-[#101D33] border border-[#101D33]/5 hover:shadow-lg transition-all" title="Institutional Export">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    </a>

                                    @if(!auth()->user()->isAdmin())
                                        {{-- Employee Dispute Button --}}
                                        <div x-data="{ disputeOpen: false }" class="relative">
                                            <button @click="disputeOpen = true" class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#660000]/5 text-[#660000]/40 hover:text-[#660000] border border-[#660000]/5 hover:shadow-lg transition-all" title="File Discrepancy">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                                            </button>
                                            {{-- Inline Dispute Modal --}}
                                            <div x-show="disputeOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
                                                <div class="fixed inset-0 bg-[#101D33]/40 backdrop-blur-md" @click="disputeOpen = false"></div>
                                                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md relative overflow-hidden border border-white/20">
                                                    <div class="bg-[#101D33] p-6 text-white">
                                                        <h3 class="text-xl font-['DM_Serif_Display'] mb-1">File <span class="text-[#D4AF37]">Discrepancy</span></h3>
                                                        <p class="text-[8px] font-bold text-white/30 uppercase tracking-[0.3em]">Cycle: {{ $payroll->period_start->format('M d') }} — {{ $payroll->period_end->format('M d, Y') }}</p>
                                                    </div>
                                                    <form action="{{ route('discrepancies.store') }}" method="POST" class="p-6 space-y-4">
                                                        @csrf
                                                        <input type="hidden" name="payroll_id" value="{{ $payroll->id }}">
                                                        <div>
                                                            <label class="block text-[8px] font-black text-[#101D33]/30 uppercase tracking-[0.3em] mb-2">Institutional Statement</label>
                                                            <textarea name="description" rows="4" required maxlength="1000" class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-xl text-sm focus:ring-[#101D33] placeholder:italic" placeholder="Provide detailed explanation of the discrepancy..."></textarea>
                                                        </div>
                                                        <div class="flex gap-3">
                                                            <button type="button" @click="disputeOpen = false" class="flex-1 py-3 px-4 bg-slate-100 text-slate-500 rounded-xl font-bold text-xs hover:bg-slate-200 transition-all uppercase tracking-widest">Cancel</button>
                                                            <button type="submit" class="flex-1 py-3 px-4 bg-[#660000] text-white rounded-xl font-bold text-xs shadow-xl shadow-[#660000]/20 hover:bg-[#800000] transition-all uppercase tracking-widest">Submit Registry</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Adjustments Button (Admin Only, Draft Only) --}}
                                    @if(auth()->user()->isAdmin() && strtolower($payroll->status) === 'draft')
                                        <div x-data="{ adjOpen: false }" class="relative">
                                            <button @click="adjOpen = true" class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#101D33]/5 text-[#101D33]/40 hover:text-[#101D33] border border-[#101D33]/5 hover:shadow-lg transition-all" title="Modify Adjustments">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                            </button>
                                            {{-- Adjustments Modal --}}
                                            <div x-show="adjOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
                                                <div class="fixed inset-0 bg-[#101D33]/60 backdrop-blur-xl" @click="adjOpen = false"></div>
                                                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl relative overflow-hidden border border-white/20 max-h-[90vh] flex flex-col">
                                                    <div class="bg-[#101D33] p-6 text-white flex items-center justify-between shrink-0">
                                                        <div>
                                                            <h3 class="text-xl font-['DM_Serif_Display'] leading-none">Fiscal <span class="text-[#D4AF37]">Adjustments</span></h3>
                                                            <p class="text-[8px] font-bold text-white/30 uppercase tracking-[0.3em] mt-3">{{ $payroll->user->name }} &bull; {{ $payroll->period_start->format('M d') }} — {{ $payroll->period_end->format('M d, Y') }}</p>
                                                        </div>
                                                        <button @click="adjOpen = false" class="w-10 h-10 rounded-xl bg-white/5 text-white/20 hover:text-white hover:bg-white/10 flex items-center justify-center transition-all">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        </button>
                                                    </div>

                                                    <div class="p-6 overflow-y-auto flex-1">
                                                        {{-- Existing Adjustments --}}
                                                        @if($payroll->adjustments->count() > 0)
                                                            <div class="mb-6 space-y-3">
                                                                <label class="block text-[8px] font-black text-[#101D33]/30 uppercase tracking-[0.4em] mb-3">Active Line Items</label>
                                                                @foreach($payroll->adjustments as $adj)
                                                                    <div class="flex items-center justify-between p-4 rounded-xl {{ $adj->type === 'bonus' ? 'bg-emerald-50/50 border border-emerald-100' : 'bg-[#660000]/5 border border-[#660000]/10' }}">
                                                                        <div>
                                                                            <div class="text-sm font-['DM_Serif_Text'] {{ $adj->type === 'bonus' ? 'text-emerald-700' : 'text-[#660000]' }}">{{ $adj->description }}</div>
                                                                            <div class="text-[8px] font-bold text-slate-300 uppercase tracking-[0.2em] mt-1.5">{{ ucfirst($adj->type) }} {{ $adj->remarks ? '— '.$adj->remarks : '' }}</div>
                                                                        </div>
                                                                        <div class="flex items-center gap-4">
                                                                            <span class="text-base font-['DM_Serif_Display'] tabular-nums {{ $adj->type === 'bonus' ? 'text-emerald-600' : 'text-[#660000]' }}">{{ $adj->type === 'bonus' ? '+' : '−' }} ₱{{ number_format($adj->amount, 2) }}</span>
                                                                            <form action="{{ route('payroll.adjustments.destroy', $adj) }}" method="POST" onsubmit="return confirm('Excise this adjustment from registry?')">
                                                                                @csrf @method('DELETE')
                                                                                <button class="w-8 h-8 rounded-lg bg-white border border-[#101D33]/5 text-[#101D33]/20 hover:text-[#660000] hover:shadow-lg flex items-center justify-center transition-all">
                                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                                                </button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <div class="mb-6 py-8 text-center text-slate-200 text-[9px] font-bold uppercase tracking-[0.4em] italic border border-dashed border-[#101D33]/5 rounded-xl">No adjustments in system memory</div>
                                                        @endif

                                                        {{-- Add Adjustment Form --}}
                                                        <div class="border-t border-[#101D33]/5 pt-6">
                                                            <div class="text-[8px] font-black text-[#101D33]/30 uppercase tracking-[0.4em] mb-4">Append New Line Item</div>
                                                            <form action="{{ route('payroll.adjustments.store', $payroll) }}" method="POST" class="space-y-4">
                                                                @csrf
                                                                <div class="grid grid-cols-2 gap-4">
                                                                    <div>
                                                                        <label class="block text-[8px] font-black text-[#101D33]/30 uppercase tracking-[0.3em] mb-2">Classification</label>
                                                                        <select name="type" required class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-xl text-xs focus:ring-[#101D33] py-3">
                                                                            <option value="deduction">Deduction</option>
                                                                            <option value="bonus">Bonus</option>
                                                                        </select>
                                                                    </div>
                                                                    <div>
                                                                        <label class="block text-[8px] font-black text-[#101D33]/30 uppercase tracking-[0.3em] mb-2">Amount (PHP)</label>
                                                                        <input type="number" name="amount" step="0.01" min="0.01" required class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-xl text-xs focus:ring-[#101D33] py-3 tabular-nums" placeholder="0.00">
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <label class="block text-[8px] font-black text-[#101D33]/30 uppercase tracking-[0.3em] mb-2">Official Description</label>
                                                                    <input type="text" name="description" required maxlength="255" class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-xl text-xs focus:ring-[#101D33] py-3" placeholder="e.g. Merit-based Performance Bonus">
                                                                </div>
                                                                <div>
                                                                    <label class="block text-[8px] font-black text-[#101D33]/30 uppercase tracking-[0.3em] mb-2">Institutional Remarks</label>
                                                                    <input type="text" name="remarks" maxlength="500" class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-xl text-xs focus:ring-[#101D33] py-3" placeholder="Internal audit trail notes...">
                                                                </div>
                                                                <div class="flex gap-3 pt-3">
                                                                    <button type="button" @click="adjOpen = false" class="flex-1 py-3 px-4 bg-slate-100 text-slate-500 rounded-xl font-bold text-xs hover:bg-slate-200 transition-all uppercase tracking-widest">Discard</button>
                                                                    <button type="submit" class="flex-1 py-3 px-4 bg-[#101D33] text-white rounded-xl font-bold text-xs shadow-xl shadow-[#101D33]/20 hover:bg-[#D4AF37] hover:text-[#101D33] transition-all uppercase tracking-widest">Apply To Ledger</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif              @endif

                                    @if(auth()->user()->isAdmin() && strtolower($payroll->status) === 'draft')
                                        <form action="{{ route('payrolls.finalize', $payroll) }}" method="POST" onsubmit="return confirm('CRITICAL: Initiating permanent ledger lock. This action cannot be reversed. Finalize settlement?')">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-xl bg-emerald-50 text-emerald-400 hover:text-emerald-600 border border-emerald-100 hover:shadow-lg transition-all" title="Finalize Settlement">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                            </button>
                                        </form>
                                    @else
                                        <div class="w-9 h-9 flex items-center justify-center rounded-xl bg-[#FDFCF8] text-slate-200 border border-[#101D33]/5" title="Settlement Locked">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-10 py-24 text-center">
                                <div class="w-20 h-20 bg-[#FDFCF8] rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-slate-100">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <div class="text-[11px] font-black text-[#101D33]/20 uppercase tracking-[0.4em] italic">No active disbursements in current memory stream</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-10 py-6 bg-[#FDFCF8]/50 border-t border-[#101D33]/5 flex items-center justify-between">
                <div class="text-[9px] font-bold text-slate-300 uppercase tracking-[0.3em]">Data verified via Institutional Identity Authentication Streams.</div>
                <div class="text-[9px] font-bold text-slate-300 uppercase tracking-[0.3em] tabular-nums">{{ count($payrolls) }} Registry Entries Reconciled</div>
            </div>
        </div>
    </div>
</x-app-layout>
