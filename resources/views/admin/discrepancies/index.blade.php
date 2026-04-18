<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center text-rose-600 border border-rose-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-900 leading-none tracking-tight">Correction Requests</h2>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Employee-Submitted Discrepancies & Dispute Resolution</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-4">
        <!-- Stats Bar -->
        <div class="flex gap-3">
            @php
                $pending = $reports->where('status', 'Pending')->count();
                $reviewing = $reports->where('status', 'Reviewing')->count();
                $resolved = $reports->where('status', 'Resolved')->count();
            @endphp
            <div class="flex-1 bg-white rounded-xl border border-slate-100 p-3 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600"><span class="text-sm font-black">{{ $pending }}</span></div>
                <div><div class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Pending</div></div>
            </div>
            <div class="flex-1 bg-white rounded-xl border border-slate-100 p-3 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600"><span class="text-sm font-black">{{ $reviewing }}</span></div>
                <div><div class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Under Review</div></div>
            </div>
            <div class="flex-1 bg-white rounded-xl border border-slate-100 p-3 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600"><span class="text-sm font-black">{{ $resolved }}</span></div>
                <div><div class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Resolved</div></div>
            </div>
        </div>

        <!-- Ticket Table -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">Dispute Queue</h3>
                </div>
                <a href="{{ route('attendance.index') }}" class="flex items-center gap-1.5 text-[9px] font-black text-indigo-600 uppercase tracking-widest hover:underline">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    Open Attendance Matrix
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white border-b border-slate-100">
                            <th class="px-5 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Case</th>
                            <th class="px-5 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Employee</th>
                            <th class="px-5 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Target Payroll</th>
                            <th class="px-5 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Description</th>
                            <th class="px-5 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                            <th class="px-5 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Filed</th>
                            <th class="px-5 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($reports as $report)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="px-5 py-3">
                                <span class="text-xs font-black text-slate-900 font-mono">#{{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-md bg-slate-900 text-white flex items-center justify-center font-bold text-[10px] shrink-0">
                                        {{ strtoupper(substr($report->user->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-slate-900 text-xs tracking-tight truncate">{{ $report->user->name }}</div>
                                        <div class="text-[9px] text-indigo-600 font-bold uppercase tracking-widest truncate">{{ $report->user->email }}</div>
                                    </div>
                                </div>
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
                                <div class="text-xs text-slate-600 font-medium max-w-[250px] truncate" title="{{ $report->description }}">{{ $report->description }}</div>
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
                                <div class="text-[11px] font-bold text-slate-500 font-mono">{{ $report->created_at->format('M d') }}</div>
                                <div class="text-[8px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ $report->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <div class="flex items-center justify-end gap-1.5 opacity-40 group-hover:opacity-100 transition-opacity" x-data="{ resolveOpen: false }">
                                    <!-- Quick Adjust: Jump to Attendance for this employee -->
                                    <a href="{{ route('attendance.index', ['user_id' => $report->user_id]) }}" class="inline-flex items-center justify-center w-7 h-7 rounded border border-indigo-200 bg-indigo-50 text-indigo-600 hover:bg-indigo-500 hover:text-white transition" title="Quick Adjust Attendance">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                    </a>

                                    <!-- Resolve/Update -->
                                    <button @click="resolveOpen = true" class="inline-flex items-center justify-center w-7 h-7 rounded border border-emerald-200 bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white transition" title="Resolve Case">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </button>

                                    <!-- Resolution Modal -->
                                    <div x-show="resolveOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
                                        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="resolveOpen = false"></div>
                                        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md relative border border-slate-100 overflow-hidden">
                                            <div class="bg-slate-950 p-4 text-white">
                                                <h3 class="text-xs font-black uppercase tracking-widest">Resolve Case #{{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</h3>
                                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">Filed by {{ $report->user->name }}</p>
                                            </div>
                                            <form action="{{ route('admin.discrepancies.update', $report) }}" method="POST" class="p-4 space-y-4">
                                                @csrf
                                                @method('PATCH')

                                                <div class="bg-slate-50 p-3 rounded-lg border border-slate-100 text-xs text-slate-600 font-medium italic leading-relaxed">
                                                    "{{ $report->description }}"
                                                </div>

                                                <div>
                                                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Resolution Status</label>
                                                    <select name="status" class="w-full bg-slate-50 border-slate-100 rounded-lg text-xs font-bold text-slate-800 focus:ring-indigo-500">
                                                        <option value="Reviewing" {{ $report->status == 'Reviewing' ? 'selected' : '' }}>Under Review</option>
                                                        <option value="Resolved" {{ $report->status == 'Resolved' ? 'selected' : '' }}>Resolved / Corrected</option>
                                                        <option value="Dismissed" {{ $report->status == 'Dismissed' ? 'selected' : '' }}>Dismissed / Accurate</option>
                                                    </select>
                                                </div>

                                                <div>
                                                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Resolution Notes</label>
                                                    <textarea name="admin_notes" rows="3" class="w-full bg-slate-50 border-slate-100 rounded-lg text-xs font-medium focus:ring-indigo-500" placeholder="Explain the resolution or findings...">{{ $report->admin_notes }}</textarea>
                                                </div>

                                                <div class="flex gap-3">
                                                    <button type="button" @click="resolveOpen = false" class="flex-1 py-2.5 bg-slate-100 text-slate-600 rounded-lg font-bold text-xs hover:bg-slate-200 transition">Cancel</button>
                                                    <button type="submit" class="flex-1 py-2.5 bg-indigo-600 text-white rounded-lg font-bold text-xs shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition">Update & Log</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div class="text-slate-400 text-xs font-bold uppercase tracking-widest">No active disputes — all records clean</div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($reports->hasPages())
                <div class="px-4 py-3 border-t border-slate-100">
                    {{ $reports->links() }}
                </div>
            @endif

            <div class="px-4 py-3 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
                <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">All resolutions archived in Security Audit Logs</div>
                <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest tabular-nums">{{ $reports->total() }} Total Cases</div>
            </div>
        </div>
    </div>
</x-app-layout>
