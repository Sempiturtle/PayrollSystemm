<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600 border border-amber-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-900 leading-none tracking-tight">My Correction Requests</h2>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Dispute Tracking & Resolution Status</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-4" x-data="{ fileModal: false }">

        <!-- File New Dispute Button -->
        <div class="flex justify-end">
            <button @click="fileModal = true" class="flex items-center gap-2 px-4 py-2.5 bg-amber-500 text-white rounded-xl font-bold text-xs shadow-lg shadow-amber-100 hover:bg-amber-600 transition active:scale-[0.98]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                File New Dispute
            </button>
        </div>

        <!-- Ticket Registry -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">Active Tickets</h3>
                </div>
                <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest tabular-nums">{{ count($reports) }} Total</div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white border-b border-slate-100">
                            <th class="px-5 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Case #</th>
                            <th class="px-5 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Target Payroll</th>
                            <th class="px-5 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Description</th>
                            <th class="px-5 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                            <th class="px-5 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Admin Response</th>
                            <th class="px-5 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Filed On</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($reports as $report)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-5 py-3">
                                <span class="text-xs font-black text-slate-900 font-mono">#{{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="px-5 py-3">
                                @if($report->payroll)
                                    <div class="text-[11px] font-bold text-slate-600 font-mono tracking-tighter">
                                        {{ $report->payroll->period_start->format('M d') }} - {{ $report->payroll->period_end->format('M d') }}
                                    </div>
                                    <div class="text-[8px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-0.5">₱{{ number_format($report->payroll->net_pay, 2) }}</div>
                                @else
                                    <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">General</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <div class="text-xs text-slate-600 font-medium max-w-xs truncate">{{ $report->description }}</div>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <span class="inline-flex items-center justify-center px-2 py-1 rounded-[4px] text-[9px] font-black uppercase tracking-[0.2em]
                                    @if($report->status == 'Pending') bg-amber-50 text-amber-600 border border-amber-100
                                    @elseif($report->status == 'Reviewing') bg-indigo-50 text-indigo-600 border border-indigo-100
                                    @elseif($report->status == 'Resolved') bg-emerald-50 text-emerald-600 border border-emerald-100
                                    @else bg-slate-100 text-slate-400 border border-slate-200 @endif">
                                    {{ $report->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3">
                                @if($report->admin_notes)
                                    <div class="text-xs text-slate-600 font-medium max-w-xs truncate italic">{{ $report->admin_notes }}</div>
                                @else
                                    <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest italic">Awaiting</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <div class="text-[11px] font-bold text-slate-500 font-mono">{{ $report->created_at->format('M d, Y') }}</div>
                                <div class="text-[8px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ $report->created_at->diffForHumans() }}</div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div class="text-slate-400 text-xs font-bold uppercase tracking-widest">No disputes filed — all records clear</div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- File Dispute Modal -->
        <div x-show="fileModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="fileModal = false"></div>
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md relative p-5 border border-slate-100">
                <h3 class="text-sm font-bold text-slate-900 mb-4 tracking-tight">File a Correction Request</h3>
                <form action="{{ route('discrepancies.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Target Payroll (Optional)</label>
                        <select name="payroll_id" class="w-full bg-slate-50 border-slate-100 rounded-lg text-xs focus:ring-indigo-500">
                            <option value="">General / Non-Payroll Issue</option>
                            @php
                                $myPayrolls = \App\Models\Payroll::where('user_id', auth()->id())->latest()->take(10)->get();
                            @endphp
                            @foreach($myPayrolls as $p)
                                <option value="{{ $p->id }}">{{ $p->period_start->format('M d') }} - {{ $p->period_end->format('M d, Y') }} (₱{{ number_format($p->net_pay, 2) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Describe the Issue</label>
                        <textarea name="description" rows="4" required maxlength="1000" class="w-full bg-slate-50 border-slate-100 rounded-lg text-xs focus:ring-indigo-500" placeholder="e.g. I was present on April 15 but was marked as Absent. Please check CCTV records or the physical logbook for verification."></textarea>
                    </div>
                    <div class="pt-2 flex gap-3">
                        <button type="button" @click="fileModal = false" class="flex-1 py-2.5 px-4 bg-slate-100 text-slate-600 rounded-lg font-bold text-xs transition hover:bg-slate-200">Cancel</button>
                        <button type="submit" class="flex-1 py-2.5 px-4 bg-amber-500 text-white rounded-lg font-bold text-xs shadow-lg shadow-amber-100 hover:bg-amber-600 transition">Submit Dispute</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
