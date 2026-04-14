<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="text-slate-400 font-medium">Administration</span>
            <span class="text-slate-300">/</span>
            <span class="font-bold">Discrepancy Reports</span>
        </div>
    </x-slot>

    <div class="space-y-8 animate-in-up">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tighter">Conflict <span class="text-indigo-600">Resolution</span></h1>
                <p class="text-slate-500 mt-1 font-bold text-xs uppercase tracking-widest leading-none">Employee-Submitted Fiscal Discrepancies</p>
            </div>
        </div>

        <div class="card-modern bg-white overflow-hidden shadow-xl shadow-slate-100/10">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Employee</th>
                            <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Target Payroll</th>
                            <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Description</th>
                            <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                            <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($reports as $report)
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="px-10 py-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-[10px] font-black uppercase">
                                            {{ substr($report->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-black text-slate-900 tracking-tight">{{ $report->user->name }}</div>
                                            <div class="text-[9px] font-bold text-slate-400 uppercase italic">{{ $report->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-6">
                                    <div class="text-sm font-black text-slate-700">#{{ $report->payroll_id }}</div>
                                    <div class="text-[9px] font-bold text-slate-400 uppercase italic">{{ $report->payroll->period_end->format('M Y') }}</div>
                                </td>
                                <td class="px-10 py-6">
                                    <div class="text-xs text-slate-600 font-medium max-w-xs truncate">{{ $report->description }}</div>
                                </td>
                                <td class="px-10 py-6">
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest
                                        @if($report->status == 'Pending') bg-amber-50 text-amber-600
                                        @elseif($report->status == 'Reviewing') bg-indigo-50 text-indigo-600
                                        @elseif($report->status == 'Resolved') bg-emerald-50 text-emerald-600
                                        @else bg-slate-100 text-slate-400 @endif">
                                        {{ $report->status }}
                                    </span>
                                </td>
                                <td class="px-10 py-6 text-right">
                                    <div x-data="{ open: false }">
                                        <button @click="open = true" class="text-[10px] font-black text-indigo-600 uppercase tracking-widest hover:underline">
                                            Resolve
                                        </button>

                                        {{-- Resolution Modal --}}
                                        <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm" style="display: none;">
                                            <div @click.away="open = false" class="bg-white rounded-[2rem] w-full max-w-lg overflow-hidden text-left shadow-2xl">
                                                <div class="bg-slate-900 p-8 text-white">
                                                    <h3 class="text-sm font-black uppercase tracking-widest italic leading-none">Review Statement</h3>
                                                    <p class="text-[10px] text-slate-500 mt-2 font-black uppercase tracking-widest">Discrepancy Case #{{ $report->id }}</p>
                                                </div>
                                                <form action="{{ route('admin.discrepancies.update', $report) }}" method="POST" class="p-8 space-y-6">
                                                    @csrf
                                                    @method('PATCH')
                                                    
                                                    <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 italic text-xs text-slate-600 leading-relaxed font-medium">
                                                        "{{ $report->description }}"
                                                    </div>

                                                    <div>
                                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-3">Resolution Status</label>
                                                        <select name="status" class="w-full bg-slate-50 border-none rounded-2xl text-sm font-black text-slate-800 p-4 focus:ring-2 focus:ring-indigo-500 transition-all">
                                                            <option value="Reviewing" {{ $report->status == 'Reviewing' ? 'selected' : '' }}>Under Review</option>
                                                            <option value="Resolved" {{ $report->status == 'Resolved' ? 'selected' : '' }}>Resolved / Corrected</option>
                                                            <option value="Dismissed" {{ $report->status == 'Dismissed' ? 'selected' : '' }}>Dismissed / Accurate</option>
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-3">Resolution Notes</label>
                                                        <textarea name="admin_notes" rows="4" class="w-full bg-slate-50 border-none rounded-2xl text-sm font-black text-slate-800 p-4 focus:ring-2 focus:ring-indigo-500 transition-all font-medium" placeholder="Explain the resolution or findings...">{{ $report->admin_notes }}</textarea>
                                                    </div>

                                                    <div class="flex gap-4">
                                                        <button type="button" @click="open = false" class="flex-1 py-4 bg-slate-100 text-slate-600 rounded-2xl font-black text-[10px] uppercase tracking-widest">Cancel</button>
                                                        <button type="submit" class="flex-1 py-4 bg-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-indigo-100">Update Case</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-20 text-center text-slate-300 italic text-sm font-black tracking-widest uppercase opacity-40 italic font-mono">No active conflicts detected in the stream.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($reports->hasPages())
                <div class="px-10 py-6 border-t border-slate-50">
                    {{ $reports->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
