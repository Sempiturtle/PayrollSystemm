<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <span class="text-[#101D33]/40 font-bold uppercase tracking-[0.2em] text-[10px]">Security</span>
            <span class="text-[#101D33]/20">/</span>
            <span class="font-['DM_Serif_Text'] text-[#101D33] italic">Higher Authority Registry</span>
        </div>
    </x-slot>

    <div class="space-y-8 animate-in-up">
        <div class="bg-white rounded-[3rem] border border-[#101D33]/5 shadow-[0_30px_100px_rgba(16,29,51,0.06)] overflow-hidden">
            <div class="p-8 border-b border-[#101D33]/5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 bg-[#FDFCF8]/30">
                <div>
                    <h1 class="text-3xl font-['DM_Serif_Display'] text-[#101D33] leading-none">Access <span class="text-[#660000]">Control</span></h1>
                    <p class="text-sm text-slate-500 mt-2 font-['DM_Serif_Text'] italic opacity-70">Authoritative directory of institutional overseers and system administrators.</p>
                </div>
                <a href="{{ route('admins.create') }}" class="group relative px-8 py-4 bg-[#101D33] text-white rounded-[2rem] font-bold text-sm shadow-[0_20px_40px_rgba(16,29,51,0.2)] transition-all hover:scale-[1.02] active:scale-[0.98] overflow-hidden flex items-center gap-3">
                    <div class="absolute inset-0 bg-gradient-to-tr from-[#660000]/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <svg class="w-5 h-5 relative z-10 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    <span class="relative z-10">Delegate New Authority</span>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#101D33] text-[10px] font-bold text-white/40 uppercase tracking-[0.3em]">
                            <th class="px-10 py-6 border-r border-white/5">Authority Stream</th>
                            <th class="px-10 py-6 border-r border-white/5">Credential Channel</th>
                            <th class="px-10 py-6 border-r border-white/5">Privilege Matrix</th>
                            <th class="px-10 py-6 text-right">Registry Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#101D33]/5">
                        @foreach($admins as $admin)
                        <tr class="hover:bg-[#FDFCF8] transition-all group">
                            <td class="px-10 py-8">
                                <div class="flex items-center gap-5">
                                    <div class="w-14 h-14 rounded-3xl bg-[#101D33] text-white flex items-center justify-center font-['DM_Serif_Display'] text-xl shadow-xl shadow-[#101D33]/10 border border-white/10 overflow-hidden relative">
                                        <div class="absolute inset-0 bg-gradient-to-br from-[#660000]/30 to-transparent opacity-50"></div>
                                        <span class="relative z-10">{{ substr($admin->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <div class="text-base font-['DM_Serif_Text'] text-[#101D33] tracking-tight leading-none mb-2">{{ $admin->name }}</div>
                                        <div class="text-[10px] font-bold text-slate-300 uppercase tracking-[0.2em] leading-none">{{ $admin->employee_id }} Overseer</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-8">
                                <div class="flex flex-col">
                                    <span class="text-sm font-['DM_Serif_Text'] text-[#101D33]">{{ $admin->email }}</span>
                                    <span class="text-[9px] text-slate-300 font-bold uppercase tracking-[0.2em] mt-1 italic">Authorized Signal</span>
                                </div>
                            </td>
                            <td class="px-10 py-8">
                                <div class="inline-flex items-center gap-3 px-4 py-1.5 bg-[#D4AF37]/10 text-[#D4AF37] rounded-xl text-[9px] font-black uppercase tracking-[0.2em] border border-[#D4AF37]/20 shadow-sm">
                                    <div class="w-1.5 h-1.5 rounded-full bg-[#D4AF37] shadow-[0_0_8px_rgba(212,175,55,0.6)] animate-pulse"></div>
                                    Higher Authority
                                </div>
                            </td>
                            <td class="px-10 py-8 text-right">
                                <div class="flex justify-end gap-4 opacity-0 group-hover:opacity-100 transition-all transform translate-x-4 group-hover:translate-x-0">
                                    <a href="{{ route('admins.edit', $admin) }}" class="w-10 h-10 flex items-center justify-center text-[#101D33]/40 hover:text-[#101D33] hover:bg-white rounded-xl transition-all shadow-none hover:shadow-lg border border-transparent hover:border-[#101D33]/5">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    @if(Auth::id() != $admin->id)
                                    <form action="{{ route('admins.destroy', $admin) }}" method="POST" onsubmit="return confirm('Revoke institutional authority?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-10 h-10 flex items-center justify-center text-[#660000]/40 hover:text-[#660000] hover:bg-white rounded-xl transition-all shadow-none hover:shadow-lg border border-transparent hover:border-[#660000]/5">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

