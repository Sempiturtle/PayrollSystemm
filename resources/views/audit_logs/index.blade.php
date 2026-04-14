<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="text-slate-400 font-medium">System</span>
            <span class="text-slate-300">/</span>
            <span class="font-bold">Security Audit Trail</span>
        </div>
    </x-slot>

    <div class="space-y-8 animate-in-up">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tighter">Event <span class="text-rose-600">Trace</span></h1>
                <p class="text-slate-500 mt-1 font-medium text-sm italic">Immutable record of administrative actions and high-value system changes.</p>
            </div>
            <div class="flex items-center gap-2 px-4 py-2 bg-rose-50 text-rose-600 rounded-full border border-rose-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                <span class="text-[10px] font-black uppercase tracking-widest leading-none">Tamper-Evident Logs</span>
            </div>
        </div>

        <div class="card-modern shadow-xl shadow-slate-200/50 border-none overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Timestamp</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Agent</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">High-Value Target</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Action Vector</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Modifications</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="text-xs font-black text-slate-900 tabular-nums">
                                    {{ $log->created_at->format('M d, Y') }}
                                </div>
                                <div class="text-[10px] font-bold text-slate-400 tabular-nums lowercase italic">
                                    {{ $log->created_at->format('h:i:s A') }}
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-slate-900 text-white flex items-center justify-center font-bold text-[10px]">
                                        {{ substr($log->user?->name ?? 'SYS', 0, 1) }}
                                    </div>
                                    <div class="text-xs font-black text-slate-800 tracking-tight">{{ $log->user?->name ?? 'System Process' }}</div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-md text-[9px] font-black uppercase tracking-widest border border-slate-200">
                                    {{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 rounded-md text-[9px] font-black uppercase tracking-widest border 
                                    {{ $log->event === 'created' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 
                                       ($log->event === 'deleted' ? 'bg-rose-50 text-rose-600 border-rose-100' : 'bg-amber-50 text-amber-600 border-amber-100') }}">
                                    {{ $log->event }}
                                </span>
                            </td>
                            <td class="px-8 py-6 max-w-xs">
                                <div class="text-[10px] font-medium text-slate-500 overflow-hidden text-ellipsis whitespace-nowrap group-hover:whitespace-normal transition-all duration-300">
                                    @if($log->new_values)
                                        <code class="px-1.5 py-0.5 bg-slate-100 rounded text-slate-600">{{ json_encode($log->new_values) }}</code>
                                    @else
                                        <span class="italic text-slate-300">No data payload</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="text-slate-300 font-bold uppercase tracking-widest text-xs italic opacity-40">Security log is currently void of activity signatures.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($logs->hasPages())
                <div class="px-8 py-6 border-t border-slate-50 bg-slate-50/10">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
