<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-xl text-slate-900 tracking-tight flex items-center gap-3">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            System Parameters
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ activeTab: 'institutional' }">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 text-[10px] font-bold uppercase tracking-widest rounded-md border border-indigo-100">Configuration</span>
                        <span class="w-1 h-1 rounded-full bg-slate-200"></span>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest leading-none">Global Settings</span>
                    </div>
                    <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">System Configuration</h1>
                    <p class="text-slate-500 text-sm">Fine-tune institutional identity, statutory rates, and legal parameters.</p>
                </div>

                <div class="flex items-center gap-3">
                    <form action="{{ route('settings.sync') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-action-secondary group">
                            <svg class="w-4 h-4 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Sync Basic Defaults
                        </button>
                    </form>
                    <button @click="$dispatch('open-modal', 'add-parameter')" class="btn-action-indigo">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        New Parameter
                    </button>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="flex items-center gap-4 border-b border-slate-200 mb-8 overflow-x-auto pb-px">
                <button @click="activeTab = 'institutional'" 
                        :class="activeTab === 'institutional' ? 'text-indigo-600 border-indigo-600' : 'text-slate-400 border-transparent hover:text-slate-600 hover:border-slate-300'"
                        class="pb-4 text-sm font-bold border-b-2 transition-all whitespace-nowrap px-1">
                    Institutional Identity
                </button>
                <button @click="activeTab = 'statutory'" 
                        :class="activeTab === 'statutory' ? 'text-indigo-600 border-indigo-600' : 'text-slate-400 border-transparent hover:text-slate-600 hover:border-slate-300'"
                        class="pb-4 text-sm font-bold border-b-2 transition-all whitespace-nowrap px-1">
                    Statutory Parameters
                </button>
                <button @click="activeTab = 'tax'" 
                        :class="activeTab === 'tax' ? 'text-indigo-600 border-indigo-600' : 'text-slate-400 border-transparent hover:text-slate-600 hover:border-slate-300'"
                        class="pb-4 text-sm font-bold border-b-2 transition-all whitespace-nowrap px-1">
                    Tax Brackets & Legal
                </button>
            </div>

            <form action="{{ route('settings.update') }}" method="POST">
                @csrf
                @method('PATCH')

                {{-- Institutional Identity Panel --}}
                <div x-show="activeTab === 'institutional'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="grid grid-cols-1 gap-4">
                        @if(isset($settings['institutional']))
                            <div class="card-modern p-4 bg-white/60 backdrop-blur-xl border-white/40">
                                <div class="mb-8 overflow-hidden rounded-2xl border border-slate-100">
                                    <div class="divide-y divide-slate-100">
                                        @foreach($settings['institutional'] as $setting)
                                            <div class="p-4 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-slate-50/50 transition-colors">
                                                <div class="space-y-1">
                                                    <h4 class="text-sm font-bold text-slate-900 tracking-tight">{{ $setting->label }}</h4>
                                                    <div class="flex items-center gap-2">
                                                        <span class="w-1 h-1 rounded-full bg-indigo-500"></span>
                                                        <code class="text-[10px] text-slate-400 font-mono tracking-tighter">{{ $setting->key }}</code>
                                                    </div>
                                                </div>
                                                <div class="w-full md:w-80">
                                                    <input type="text" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" 
                                                           class="w-full bg-white border-slate-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all px-4 py-2.5 outline-none shadow-sm">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="p-12 text-center card-modern">
                                <p class="text-slate-400 text-sm font-medium italic">No institutional settings defined yet.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Statutory Parameters Panel --}}
                <div x-show="activeTab === 'statutory'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="card-modern bg-white/60 backdrop-blur-xl border-white/40">
                        @if(isset($settings['statutory']))
                            <div class="p-2 divide-y divide-slate-50">
                                @foreach($settings['statutory']->chunk(2) as $chunk)
                                    <div class="grid grid-cols-1 md:grid-cols-2">
                                        @foreach($chunk as $setting)
                                            <div class="p-4 border-r border-slate-50 last:border-r-0 hover:bg-white/40 transition-all group">
                                                <div class="flex flex-col gap-4">
                                                    <div class="flex items-center justify-between">
                                                        <div class="w-10 h-10 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform duration-300">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        </div>
                                                        <code class="text-[9px] text-slate-400 font-mono tracking-tighter">{{ $setting->key }}</code>
                                                    </div>
                                                    <div class="space-y-1">
                                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest">{{ $setting->label }}</label>
                                                        <div class="relative">
                                                            <input type="{{ $setting->type === 'decimal' || $setting->type === 'integer' ? 'number' : 'text' }}" 
                                                                   step="any" 
                                                                   name="settings[{{ $setting->key }}]" 
                                                                   value="{{ $setting->value }}" 
                                                                   class="w-full bg-white border-slate-200 rounded-xl text-lg font-bold text-slate-900 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all px-4 py-3 outline-none shadow-sm h-14 pr-12">
                                                            @if($setting->type === 'decimal' && str_contains(strtolower($setting->label), 'rate'))
                                                                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 font-bold">%</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        @else
                           <div class="p-12 text-center">
                                <p class="text-slate-400 text-sm font-medium italic">No statutory parameters defined yet.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Tax Brackets Panel --}}
                <div x-show="activeTab === 'tax'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="card-modern bg-slate-900 p-4 relative overflow-hidden">
                        <!-- Decorative glow -->
                        <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500/10 blur-[100px] rounded-full -mr-32 -mt-32"></div>
                        <div class="absolute bottom-0 left-0 w-64 h-64 bg-emerald-500/5 blur-[100px] rounded-full -ml-32 -mb-32"></div>
                        
                        <div class="relative z-10">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-10 rounded-2xl bg-white/10 flex items-center justify-center backdrop-blur-xl border border-white/10">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white tracking-tight">Tax Configuration</h3>
                                    <p class="text-slate-400 text-xs">Configure monthly withholding tax thresholds and rates.</p>
                                </div>
                            </div>

                            @if(isset($settings['tax']))
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    @foreach($settings['tax'] as $setting)
                                        <div class="bg-white/5 border border-white/10 rounded-2xl p-4 backdrop-blur-md">
                                            <div class="space-y-3">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Bracket Parameter</span>
                                                    <code class="text-[9px] text-slate-500 font-mono">{{ $setting->key }}</code>
                                                </div>
                                                <label class="block text-sm font-bold text-slate-200">{{ $setting->label }}</label>
                                                <input type="number" step="any" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" 
                                                       class="w-full bg-slate-800 border-slate-700 rounded-xl text-base font-bold text-white focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-400 px-4 py-2.5 outline-none transition-all">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-12 text-center text-slate-500 italic text-sm">No tax parameters defined yet.</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex justify-end">
                    <button type="submit" class="px-10 py-4 bg-slate-900 text-white rounded-[1.25rem] font-bold text-sm shadow-xl hover:bg-slate-800 hover:-translate-y-1 transition-all active:scale-95 flex items-center gap-3">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        Update Parameters
                    </button>
                </div>
            </form>

            <!-- Bottom Sections Grid -->
            <div class="mt-16 grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Audit Trail -->
                <div class="card-modern p-4 flex flex-col h-full bg-white/60 backdrop-blur-xl border-white/40">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 tracking-tight">Integrity Audit</h3>
                            <p class="text-xs text-slate-400 mt-1">Real-time administrative security trail.</p>
                        </div>
                        <div class="flex items-center gap-2 px-3 py-1 bg-emerald-50 border border-emerald-100 rounded-full">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            <span class="text-[10px] font-black text-emerald-600 uppercase tracking-tighter">Live Monitor</span>
                        </div>
                    </div>

                    <div class="space-y-6 flex-1">
                        @php
                            $audits = \App\Models\AuditLog::with('user')->latest()->limit(4)->get();
                        @endphp
                        @forelse($audits as $audit)
                            <div class="relative pl-8 pb-2 border-l border-slate-100 last:border-0 last:pb-0">
                                <div class="absolute left-[-5px] top-0 w-2.5 h-2.5 rounded-full bg-white border-2 border-indigo-500 shadow-[0_0_8px_rgba(79,70,229,0.3)]"></div>
                                <div class="flex flex-col gap-1">
                                    <div class="text-xs font-bold text-slate-800">
                                        {{ $audit->user->name ?? 'System' }} 
                                        <span class="text-slate-400 font-medium">performed</span> 
                                        <span class="text-indigo-600 font-black uppercase tracking-tighter">{{ $audit->event ?? 'Action' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="px-2 py-0.5 bg-slate-100 rounded-md text-[9px] font-bold text-slate-500 tracking-tighter">{{ $audit->auditable_type ? class_basename($audit->auditable_type) : 'Event' }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $audit->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10">
                                <p class="text-slate-300 text-sm italic">No recent activity logs found.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-8 pt-6 border-t border-slate-50">
                        <a href="#" class="text-[11px] font-bold text-slate-400 hover:text-indigo-600 transition-colors uppercase tracking-widest flex items-center gap-2">
                            View Comprehensive Security Report
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Documentation/Help Card -->
                <div class="card-modern p-4 bg-slate-900 group relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4">
                        <svg class="w-24 h-24 text-white/5 group-hover:text-indigo-500/10 transition-colors duration-700" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z"/></svg>
                    </div>
                    <div class="relative z-10 h-full flex flex-col">
                        <h3 class="text-xl font-bold text-white tracking-tight mb-4">Parameter Help</h3>
                        <p class="text-slate-400 text-sm leading-relaxed mb-8 flex-1">
                            Use these configurations to determine how the payroll engine calculates net pay. Rates like <span class="text-white font-medium italic">sss_rate</span> should be stored as decimals (e.g., 0.045 for 4.5%). Changes take effect immediately across all pending payroll batches.
                        </p>
                        <div class="flex items-center gap-4">
                            <div class="p-4 bg-white/5 rounded-2xl border border-white/10 flex-1">
                                <div class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1">PRO TIP</div>
                                <div class="text-xs text-slate-300">Always backup parameters before changing tax law brackets.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Manual Parameter Creation Modal (Using existing x-modal if available or custom Alpine) --}}
    <x-modal name="add-parameter" :show="false" focusable>
        <div class="p-4">
            <div class="mb-8">
                <h3 class="text-xl font-bold text-slate-900 tracking-tight">Define New Parameter</h3>
                <p class="text-sm text-slate-500">Add a custom administrative field to the database.</p>
            </div>
            
            <form action="{{ route('settings.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Display Label</label>
                        <input type="text" name="label" placeholder="e.g., PAG-IBIG Rate" class="w-full bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 px-4 py-3 outline-none" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Storage Key (Snake Case)</label>
                        <input type="text" name="key" placeholder="e.g., pagibig_rate" class="w-full bg-slate-50 border-slate-200 rounded-xl text-sm font-mono focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 px-4 py-3 outline-none" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Initial Value</label>
                        <input type="text" name="value" placeholder="0.02" class="w-full bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 px-4 py-3 outline-none" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Category Group</label>
                        <select name="group" class="w-full bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 px-4 py-3 outline-none">
                            <option value="statutory">Statutory Parameters</option>
                            <option value="tax">Tax & Legal</option>
                            <option value="institutional">Institutional Identity</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="type" value="string">

                <div class="mt-8 pt-8 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" @click="$dispatch('close-modal', 'add-parameter')" class="px-6 py-3 text-sm font-bold text-slate-500 hover:text-slate-800 transition">Cancel</button>
                    <button type="submit" class="btn-action-indigo px-8">Define Parameter</button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>
