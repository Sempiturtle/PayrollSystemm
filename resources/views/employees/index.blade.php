<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <span class="text-[#101D33]/40 font-bold uppercase tracking-[0.2em] text-[10px]">Assets</span>
            <span class="text-[#101D33]/20">/</span>
            <span class="font-['DM_Serif_Text'] text-[#101D33] italic">Personnel Registry</span>
        </div>
    </x-slot>

    <div class="space-y-8 animate-in-up">
        <div class="bg-white rounded-[3rem] border border-[#101D33]/5 shadow-[0_30px_100px_rgba(16,29,51,0.06)] overflow-hidden">
            <div class="p-6 border-b border-[#101D33]/5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-5 bg-[#FDFCF8]/30">
                <div>
                    <h1 class="text-2xl font-['DM_Serif_Display'] text-[#101D33] leading-none">Institutional <span class="text-[#660000]">Registry</span></h1>
                    <p class="text-xs text-slate-500 mt-1.5 font-['DM_Serif_Text'] italic opacity-70">Authoritative directory of personnel and operational staff.</p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('employees.create') }}" class="group relative px-6 py-3.5 bg-[#101D33] text-white rounded-[1.5rem] font-bold text-xs shadow-[0_20px_40px_rgba(16,29,51,0.2)] transition-all hover:scale-[1.02] active:scale-[0.98] overflow-hidden flex items-center gap-2.5">
                        <div class="absolute inset-0 bg-gradient-to-tr from-[#660000]/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <svg class="w-4 h-4 relative z-10 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        <span class="relative z-10">Register Member</span>
                    </a>
                </div>
            </div>

            <!-- Search Console -->
            <div class="px-6 py-4 bg-[#FDFCF8]/10 border-b border-[#101D33]/5">
                <form action="{{ route('employees.index') }}" method="GET" class="relative group">
                    <div class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-[#101D33]/20 group-focus-within:text-[#101D33] transition-colors">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Search Identity Registry, Email, or Node ID..." 
                        value="{{ $search ?? '' }}"
                        class="w-full pl-14 pr-6 py-4 bg-white border border-[#101D33]/5 rounded-[1.5rem] text-sm font-['DM_Serif_Text'] text-[#101D33] placeholder-[#101D33]/20 focus:ring-2 focus:ring-[#101D33] focus:border-transparent transition-all shadow-sm"
                    >
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#101D33] text-[9px] font-bold text-white/40 uppercase tracking-[0.3em]">
                            <th class="px-6 py-4 border-r border-white/5">Identity Stream</th>
                            <th class="px-6 py-4 border-r border-white/5">Classification</th>
                            <th class="px-6 py-4 border-r border-white/5 text-center">RFID Authorization</th>
                            <th class="px-6 py-4 border-r border-white/5">Remuneration</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#101D33]/5">
                        @forelse($employees as $employee)
                        <tr class="hover:bg-[#FDFCF8] transition-all group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 rounded-2xl bg-[#101D33] text-white flex items-center justify-center font-['DM_Serif_Display'] text-lg shadow-xl shadow-[#101D33]/10 border border-white/10 overflow-hidden relative">
                                        <div class="absolute inset-0 bg-gradient-to-br from-[#660000]/30 to-transparent opacity-50"></div>
                                        <span class="relative z-10">{{ substr($employee->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-['DM_Serif_Text'] text-[#101D33] tracking-tight leading-none mb-1.5">{{ $employee->name }}</div>
                                        <div class="text-[9px] font-bold text-slate-300 uppercase tracking-[0.2em] leading-none">{{ $employee->employee_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <x-status-badge :type="$employee->role">{{ ucfirst($employee->role) }}</x-status-badge>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <code class="inline-block px-3 py-1.5 bg-[#101D33]/5 rounded-lg text-[10px] font-bold text-[#101D33]/60 tracking-widest tabular-nums border border-[#101D33]/5">
                                    {{ $employee->rfid_card_num ?? 'NOT_REGISTERED' }}
                                </code>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-baseline gap-1">
                                    <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">PHP</span>
                                    <span class="text-base font-['DM_Serif_Display'] text-[#101D33] tabular-nums">{{ number_format($employee->hourly_rate, 2) }}</span>
                                    <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">/ HR</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all transform translate-x-4 group-hover:translate-x-0">
                                    <a href="{{ route('employees.edit', $employee) }}" class="w-9 h-9 flex items-center justify-center text-[#101D33]/40 hover:text-[#101D33] hover:bg-white rounded-xl transition-all shadow-none hover:shadow-lg border border-transparent hover:border-[#101D33]/5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('employees.destroy', $employee) }}" method="POST" onsubmit="return confirm('Archive this institutional asset?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-9 h-9 flex items-center justify-center text-[#660000]/40 hover:text-[#660000] hover:bg-white rounded-xl transition-all shadow-none hover:shadow-lg border border-transparent hover:border-[#660000]/5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-10 py-32 text-center">
                                <div class="flex flex-col items-center gap-4 opacity-40">
                                    <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V19a2 2 0 01-2 2H6a2 2 0 01-2-2v-6m16 0h-4M4 13h4m1.5-4.5L12 6m0 0l2.5 2.5M12 6v12"></path></svg>
                                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">No registry entries matching your criteria</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($employees->hasPages())
            <div class="px-6 py-6 bg-[#FDFCF8]/30 border-t border-[#101D33]/5">
                {{ $employees->links() }}
            </div>
            @endif
        </div>
    </div>

</x-app-layout>
