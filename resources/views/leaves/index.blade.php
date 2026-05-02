<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <span class="text-[#101D33]/40 font-bold uppercase tracking-[0.2em] text-[10px]">Operations</span>
            <span class="text-[#101D33]/20">/</span>
            <span class="font-['DM_Serif_Text'] text-[#101D33] italic">Absence Registry</span>
        </div>
    </x-slot>

    <div class="space-y-8 animate-in-up">
        @if(session('success'))
            <div class="p-6 bg-emerald-50 border border-emerald-100 rounded-[2rem] flex items-center gap-4 text-emerald-800 shadow-sm animate-in-fade">
                <div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="font-['DM_Serif_Text'] italic">{{ session('success') }}</span>
            </div>
        @endif

        <div x-data="{ 
            selected: [],
            allSelected: false,
            toggleAll() {
                this.allSelected = !this.allSelected;
                if (this.allSelected) {
                    this.selected = Array.from(document.querySelectorAll('input[name=\'leave_ids[]\']')).map(el => el.value);
                } else {
                    this.selected = [];
                }
            }
        }" class="bg-white rounded-[3rem] border border-[#101D33]/5 shadow-[0_30px_100px_rgba(16,29,51,0.06)] overflow-hidden relative">
            
            {{-- Bulk Actions Toolbar --}}
            @if(auth()->user()->isAdmin())
            <div x-show="selected.length > 0" 
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 class="fixed bottom-12 left-1/2 -translate-x-1/2 z-[100] bg-[#101D33] text-white px-10 py-6 rounded-[3rem] shadow-[0_40px_80px_rgba(16,29,51,0.4)] flex items-center gap-10 border border-white/10 backdrop-blur-2xl">
                <div class="flex items-center gap-5 pr-10 border-r border-white/10">
                    <div class="w-10 h-10 rounded-2xl bg-[#D4AF37] flex items-center justify-center text-[#101D33] text-sm font-black shadow-lg shadow-[#D4AF37]/20" x-text="selected.length"></div>
                    <div>
                        <div class="text-[10px] font-black uppercase tracking-[0.2em] text-white/40 leading-none mb-1">Batch selection</div>
                        <div class="text-sm font-['DM_Serif_Text'] italic text-white/90 leading-none">Awaiting Institutional Decision</div>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <form action="{{ route('leaves.bulk') }}" method="POST" class="inline">
                        @csrf
                        <template x-for="id in selected" :key="id">
                            <input type="hidden" name="leave_ids[]" :value="id">
                        </template>
                        <input type="hidden" name="status" value="Approved">
                        <button type="submit" class="px-8 py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all hover:scale-[1.05] active:scale-95 shadow-xl shadow-emerald-500/20">
                            Approve Batch
                        </button>
                    </form>
                    <form action="{{ route('leaves.bulk') }}" method="POST" class="inline">
                        @csrf
                        <template x-for="id in selected" :key="id">
                            <input type="hidden" name="leave_ids[]" :value="id">
                        </template>
                        <input type="hidden" name="status" value="Rejected">
                        <button type="submit" class="px-8 py-3 bg-[#660000] hover:bg-rose-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all hover:scale-[1.05] active:scale-95 shadow-xl shadow-rose-900/20 border border-white/5">
                            Reject Batch
                        </button>
                    </form>
                    <button @click="selected = []; allSelected = false" class="text-[10px] font-black text-white/30 hover:text-white transition-colors uppercase tracking-[0.2em] ml-4">
                        Cancel
                    </button>
                </div>
            </div>
            @endif

            <div class="p-8 border-b border-[#101D33]/5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 bg-[#FDFCF8]/30">
                <div>
                    <h1 class="text-3xl font-['DM_Serif_Display'] text-[#101D33] leading-none">Absence <span class="text-[#660000]">Registry</span></h1>
                    <p class="text-sm text-slate-500 mt-2 font-['DM_Serif_Text'] italic opacity-70">
                        {{ auth()->user()->isAdmin() ? 'Institutional oversight of faculty and administrative absence protocols.' : 'Personal chronicle of authorized institutional absences.' }}
                    </p>
                </div>
                @if(!auth()->user()->isAdmin())
                <div x-data="{ showModal: false }">
                    <button @click="showModal = true" class="group relative px-8 py-4 bg-[#101D33] text-white rounded-[2rem] font-bold text-sm shadow-[0_20px_40px_rgba(16,29,51,0.2)] transition-all hover:scale-[1.02] active:scale-[0.98] overflow-hidden flex items-center gap-3">
                        <div class="absolute inset-0 bg-gradient-to-tr from-[#660000]/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <svg class="w-5 h-5 relative z-10 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span class="relative z-10">Request Authorization</span>
                    </button>

                    <!-- New Leave Modal -->
                    <div x-show="showModal" class="fixed inset-0 z-[150] overflow-y-auto" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                            <!-- Overlay -->
                            <div class="fixed inset-0 transition-opacity bg-[#101D33]/90 backdrop-blur-xl" @click="showModal = false"></div>

                            <div class="relative inline-block w-full text-left align-bottom transition-all transform bg-white rounded-[3rem] shadow-[0_50px_100px_rgba(0,0,0,0.5)] sm:my-8 sm:align-middle sm:max-w-xl overflow-hidden border border-white/20" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-12 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                                <form action="{{ route('leaves.store') }}" method="POST">
                                    @csrf
                                    <div class="px-10 pt-12 pb-8">
                                        <div class="flex items-center justify-between mb-10">
                                            <div>
                                                <h3 class="text-3xl font-['DM_Serif_Display'] text-[#101D33] leading-none mb-3">Leave <span class="text-[#660000]">Petition</span></h3>
                                                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-[0.2em]">Institutional absence request protocol</p>
                                            </div>
                                            <div class="w-14 h-14 rounded-[1.5rem] bg-[#101D33]/5 flex items-center justify-center text-[#101D33]/20">
                                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        </div>
                                        
                                        <div class="space-y-8">
                                            <div>
                                                <label class="block text-[10px] font-black text-[#101D33]/30 uppercase tracking-[0.3em] mb-4">Leave Classification</label>
                                                <div class="grid grid-cols-3 gap-4">
                                                    @foreach(['Sick', 'Vacation', 'Personal'] as $type)
                                                        <label class="relative cursor-pointer group">
                                                            <input type="radio" name="type" value="{{ $type }}" required class="peer hidden">
                                                            <div class="px-6 py-4 bg-[#FDFCF8] border border-[#101D33]/5 rounded-2xl text-center transition-all peer-checked:bg-[#101D33] peer-checked:text-white peer-checked:border-transparent group-hover:border-[#101D33]/20 shadow-sm peer-checked:shadow-xl peer-checked:shadow-[#101D33]/10">
                                                                <span class="text-[10px] font-bold uppercase tracking-widest">{{ $type }}</span>
                                                            </div>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                            
                                            <div class="grid grid-cols-2 gap-8">
                                                <div>
                                                    <label class="block text-[10px] font-black text-[#101D33]/30 uppercase tracking-[0.3em] mb-4">Initial Cycle</label>
                                                    <input type="date" name="start_date" required class="w-full px-6 py-4 bg-[#FDFCF8] border border-[#101D33]/5 rounded-2xl text-sm font-['DM_Serif_Text'] text-[#101D33] focus:ring-2 focus:ring-[#101D33] transition-all shadow-sm">
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-[#101D33]/30 uppercase tracking-[0.3em] mb-4">Final Cycle</label>
                                                    <input type="date" name="end_date" required class="w-full px-6 py-4 bg-[#FDFCF8] border border-[#101D33]/5 rounded-2xl text-sm font-['DM_Serif_Text'] text-[#101D33] focus:ring-2 focus:ring-[#101D33] transition-all shadow-sm">
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-[10px] font-black text-[#101D33]/30 uppercase tracking-[0.3em] mb-4">Justification (Institutional Record)</label>
                                                <textarea name="reason" rows="3" placeholder="Specify the critical rationale for this institutional absence..." class="w-full px-6 py-4 bg-[#FDFCF8] border border-[#101D33]/5 rounded-2xl text-sm font-['DM_Serif_Text'] text-[#101D33] focus:ring-2 focus:ring-[#101D33] transition-all shadow-sm placeholder:text-slate-300"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-10 py-8 bg-[#FDFCF8]/50 border-t border-[#101D33]/5 flex items-center justify-end gap-6">
                                        <button type="button" @click="showModal = false" class="text-[10px] font-black text-slate-400 hover:text-[#660000] transition-colors uppercase tracking-[0.2em]">Withdraw Petition</button>
                                        <button type="submit" class="px-10 py-4 bg-[#101D33] text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] hover:bg-[#660000] transition-all shadow-xl shadow-[#101D33]/10 active:scale-95">Submit for Authorization</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#101D33] text-[10px] font-bold text-white/40 uppercase tracking-[0.3em]">
                            @if(auth()->user()->isAdmin())
                                <th class="px-10 py-6 w-4 border-r border-white/5">
                                    <input type="checkbox" @click="toggleAll" :checked="allSelected" class="rounded border-white/20 bg-transparent text-[#D4AF37] focus:ring-[#D4AF37]">
                                </th>
                                <th class="px-10 py-6 border-r border-white/5">Identity Node</th>
                            @endif
                            <th class="px-10 py-6 border-r border-white/5">Classification</th>
                            <th class="px-10 py-6 border-r border-white/5">Temporal Duration</th>
                            <th class="px-10 py-6 border-r border-white/5">Justification</th>
                            <th class="px-10 py-6 border-r border-white/5">Verification</th>
                            @if(auth()->user()->isAdmin())
                                <th class="px-10 py-6 text-right">Registry Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#101D33]/5">
                        @forelse($leaves as $leave)
                            <tr class="hover:bg-[#FDFCF8] transition-all group" :class="selected.includes('{{ $leave->id }}') ? 'bg-[#D4AF37]/5' : ''">
                                @if(auth()->user()->isAdmin())
                                    <td class="px-10 py-8">
                                        @if($leave->status === 'Pending')
                                            <input type="checkbox" name="leave_ids[]" value="{{ $leave->id }}" x-model="selected" class="rounded border-[#101D33]/10 bg-[#FDFCF8] text-[#101D33] focus:ring-[#101D33]">
                                        @endif
                                    </td>
                                    <td class="px-10 py-8">
                                        <div class="flex items-center gap-5">
                                            <div class="w-12 h-12 rounded-2xl bg-[#101D33] text-white flex items-center justify-center font-['DM_Serif_Display'] text-lg shadow-xl shadow-[#101D33]/10 border border-white/10 overflow-hidden relative">
                                                <div class="absolute inset-0 bg-gradient-to-br from-[#660000]/20 to-transparent opacity-50"></div>
                                                <span class="relative z-10">{{ substr($leave->user->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-['DM_Serif_Text'] text-[#101D33] tracking-tight leading-none mb-1.5">{{ $leave->user->name }}</div>
                                                <div class="text-[9px] font-bold text-slate-300 uppercase tracking-[0.2em] leading-none">{{ $leave->user->employee_id }} Registry</div>
                                            </div>
                                        </div>
                                    </td>
                                @endif
                                <td class="px-10 py-8">
                                    <span class="inline-flex px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-[0.2em] border shadow-sm
                                        {{ $leave->type === 'Sick' ? 'bg-rose-50 text-rose-700 border-rose-100' : '' }}
                                        {{ $leave->type === 'Vacation' ? 'bg-[#101D33]/5 text-[#101D33] border-[#101D33]/10' : '' }}
                                        {{ $leave->type === 'Personal' ? 'bg-[#D4AF37]/10 text-[#D4AF37] border-[#D4AF37]/20' : '' }}
                                    ">
                                        {{ $leave->type }}
                                    </span>
                                </td>
                                <td class="px-10 py-8">
                                    <div class="text-sm font-bold text-[#101D33] tabular-nums tracking-tight leading-none mb-1.5">
                                        {{ $leave->start_date->format('M d, Y') }} — {{ $leave->end_date->format('M d, Y') }}
                                    </div>
                                    <div class="text-[9px] font-bold text-slate-300 uppercase tracking-widest leading-none">
                                        Temporal Span: {{ $leave->start_date->diffInDays($leave->end_date) + 1 }} Cycle(s)
                                    </div>
                                </td>
                                <td class="px-10 py-8">
                                    <p class="text-sm font-['DM_Serif_Text'] text-slate-500 italic leading-snug">
                                        {{ Str::limit($leave->reason, 40) ?? 'Formal Justification Omitted' }}
                                    </p>
                                </td>
                                <td class="px-10 py-8">
                                    <x-status-badge :status="$leave->status" />
                                </td>
                                @if(auth()->user()->isAdmin())
                                    <td class="px-10 py-8 text-right">
                                        @if($leave->status === 'Pending')
                                            <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all transform translate-x-4 group-hover:translate-x-0">
                                                <form action="{{ route('leaves.update', $leave) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="Approved">
                                                    <button type="submit" class="w-10 h-10 flex items-center justify-center bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-500 hover:text-white transition-all shadow-none hover:shadow-lg border border-emerald-100">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    </button>
                                                </form>
                                                <form action="{{ route('leaves.update', $leave) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="Rejected">
                                                    <button type="submit" class="w-10 h-10 flex items-center justify-center bg-rose-50 text-rose-600 rounded-xl hover:bg-[#660000] hover:text-white transition-all shadow-none hover:shadow-lg border border-rose-100">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <div class="text-[10px] font-bold text-slate-200 uppercase tracking-[0.2em] italic">Finalized State</div>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->isAdmin() ? 7 : 5 }}" class="px-10 py-24 text-center">
                                    <div class="flex flex-col items-center gap-4 opacity-40">
                                        <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                        <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">Institutional absence stream is currently vacant</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

