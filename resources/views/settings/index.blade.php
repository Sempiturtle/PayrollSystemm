<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="text-slate-400 font-medium">System</span>
            <span class="text-slate-300">/</span>
            <span class="font-bold text-slate-800">System Configurations</span>
        </div>
    </x-slot>

    <div class="space-y-6" x-data="{ activeTab: 'institutional' }">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">System Configurations</h1>
                <p class="text-sm text-slate-500 mt-1">Fine-tune institutional identity variables, statutory tax formulas, and legal guidelines.</p>
            </div>
            
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <form action="{{ route('settings.sync') }}" method="POST" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit" class="btn-action-secondary text-xs py-2.5 px-4 shadow-sm w-full group">
                        <svg class="w-3.5 h-3.5 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Sync Defaults
                    </button>
                </form>
                <button @click="$dispatch('open-modal', 'add-parameter')" class="btn-action-indigo text-xs py-2.5 px-4 shadow-sm w-full sm:w-auto">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    New Parameter
                </button>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="flex items-center gap-2 border-b border-slate-200/60 overflow-x-auto pb-px">
            <button @click="activeTab = 'institutional'" 
                    :class="activeTab === 'institutional' ? 'text-indigo-600 border-indigo-650' : 'text-slate-400 border-transparent hover:text-slate-600'"
                    class="pb-3 text-xs font-bold border-b-2 transition-all whitespace-nowrap px-3 flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5"></path></svg>
                Institutional Identity
            </button>
            <button @click="activeTab = 'statutory'" 
                    :class="activeTab === 'statutory' ? 'text-indigo-600 border-indigo-655' : 'text-slate-400 border-transparent hover:text-slate-600'"
                    class="pb-3 text-xs font-bold border-b-2 transition-all whitespace-nowrap px-3 flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Statutory Parameters
            </button>
            <button @click="activeTab = 'tax'" 
                    :class="activeTab === 'tax' ? 'text-indigo-600 border-indigo-655' : 'text-slate-400 border-transparent hover:text-slate-600'"
                    class="pb-3 text-xs font-bold border-b-2 transition-all whitespace-nowrap px-3 flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Tax Brackets & Legal
            </button>
        </div>

        <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PATCH')

            {{-- Institutional Identity Panel --}}
            <div x-show="activeTab === 'institutional'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="card-reference overflow-hidden bg-white/95 backdrop-blur-xl border border-slate-200/60 shadow-lg">
                    @if(isset($settings['institutional']))
                        <div class="divide-y divide-slate-100">
                            @foreach($settings['institutional'] as $setting)
                                <div class="p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-slate-50/50 transition">
                                    <div class="space-y-1">
                                        <h4 class="text-sm font-extrabold text-slate-800 tracking-tight">{{ $setting->label }}</h4>
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                                            <code class="text-[10px] text-slate-400 font-mono tracking-tighter">{{ $setting->key }}</code>
                                        </div>
                                    </div>
                                    <div class="w-full md:w-80">
                                        <input type="text" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" 
                                               class="w-full bg-slate-50/50 border border-slate-200 rounded-xl py-2.5 px-3.5 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-semibold">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-12 text-center text-slate-400 italic text-xs">No institutional variables found.</div>
                    @endif
                </div>
            </div>

            {{-- Statutory Parameters Panel --}}
            <div x-show="activeTab === 'statutory'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="card-reference overflow-hidden bg-white/95 backdrop-blur-xl border border-slate-200/60 shadow-lg p-3">
                    @if(isset($settings['statutory']))
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($settings['statutory'] as $setting)
                                <div class="p-4 rounded-2xl border border-slate-100 hover:border-slate-200 transition bg-slate-50/20 flex flex-col justify-between gap-4">
                                    <div class="flex items-center justify-between">
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $setting->label }}</label>
                                        <code class="text-[9px] text-slate-450 font-mono tracking-tighter">{{ $setting->key }}</code>
                                    </div>
                                    <div class="relative">
                                        <input type="{{ $setting->type === 'decimal' || $setting->type === 'integer' ? 'number' : 'text' }}" 
                                               step="any" 
                                               name="settings[{{ $setting->key }}]" 
                                               value="{{ $setting->value }}" 
                                               class="w-full bg-slate-50/30 border border-slate-200 rounded-xl py-3 px-4 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-lg font-black text-slate-800">
                                        @if($setting->type === 'decimal' && str_contains(strtolower($setting->label), 'rate'))
                                            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-350 font-bold">%</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-12 text-center text-slate-400 italic text-xs">No statutory metrics found.</div>
                    @endif
                </div>
            </div>

            {{-- Tax Brackets Panel --}}
            <div x-show="activeTab === 'tax'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="card-reference p-6 bg-slate-950 relative overflow-hidden shadow-2xl">
                    <!-- Subtle background lightings -->
                    <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500/10 blur-[80px] rounded-full -mr-24 -mt-24 pointer-events-none"></div>
                    <div class="absolute bottom-0 left-0 w-64 h-64 bg-purple-500/5 blur-[80px] rounded-full -ml-24 -mb-24 pointer-events-none"></div>
                    
                    <div class="relative z-10 space-y-6">
                        <div class="flex items-center gap-4 pb-4 border-b border-white/10">
                            <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-white tracking-tight">Withholding Tax Parameter Matrix</h3>
                                <p class="text-slate-400 text-xs mt-0.5">Define thresholds and percentage rates compliant with statutory policies.</p>
                            </div>
                        </div>

                        @if(isset($settings['tax']))
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($settings['tax'] as $setting)
                                    <div class="bg-white/5 border border-white/10 rounded-2xl p-4 backdrop-blur-md space-y-3">
                                        <div class="flex items-center justify-between">
                                            <span class="text-[9px] font-bold text-indigo-400 uppercase tracking-widest">Tax Bracket</span>
                                            <code class="text-[9px] text-slate-500 font-mono">{{ $setting->key }}</code>
                                        </div>
                                        <label class="block text-xs font-bold text-slate-200">{{ $setting->label }}</label>
                                        <input type="number" step="any" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" 
                                               class="w-full bg-slate-900 border border-slate-800 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 text-xs font-bold text-white transition-all">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-12 text-center text-slate-500 italic text-xs">No tax brackets found.</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Update Submit -->
            <div class="flex justify-end pt-2">
                <button type="submit" class="btn-action-indigo text-xs py-3 px-8 shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    Update Configurations
                </button>
            </div>
        </form>

        <!-- Bottom Audit & Helper Sections Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pt-4">
            <!-- Audit Trail Logs -->
            <div class="card-reference p-5 bg-white/95 backdrop-blur-xl border border-slate-200/60 shadow-lg flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-850 tracking-tight">Security Audit Trail</h3>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">Administrative Action Logs</p>
                        </div>
                        <div class="flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 border border-emerald-100 rounded-lg shrink-0">
                            <span class="relative flex h-1.5 w-1.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                            </span>
                            <span class="text-[9px] font-black text-emerald-600 uppercase tracking-wider">Live Monitor</span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @php
                            $audits = \App\Models\AuditLog::with('user')->latest()->limit(4)->get();
                        @endphp
                        @forelse($audits as $audit)
                            <div class="relative pl-6 pb-2 border-l border-slate-100 last:border-0 last:pb-0">
                                <div class="absolute left-[-4.5px] top-1.5 w-2 h-2 rounded-full bg-white border-2 border-indigo-650 shadow-[0_0_8px_rgba(83,52,246,0.3)]"></div>
                                <div class="flex flex-col gap-0.5">
                                    <div class="text-xs font-bold text-slate-800">
                                        {{ $audit->user->name ?? 'System' }} 
                                        <span class="text-slate-400 font-medium">performed</span> 
                                        <span class="text-indigo-600 font-black uppercase tracking-wider text-[9px]">{{ $audit->event ?? 'Action' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="px-1.5 py-0.5 bg-slate-50 border border-slate-100 rounded text-[9px] font-bold text-slate-450 uppercase">{{ $audit->auditable_type ? class_basename($audit->auditable_type) : 'System' }}</span>
                                        <span class="text-[9px] text-slate-400 font-bold uppercase">{{ $audit->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6 text-slate-400 italic text-xs">No recent administrative logs found.</div>
                        @endforelse
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100">
                    <a href="#" class="text-[10px] font-bold text-indigo-600 hover:text-indigo-850 uppercase tracking-widest flex items-center gap-1.5">
                        View Comprehensive Security Log
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>

            <!-- Parameter Help Card -->
            <div class="card-reference p-6 bg-slate-950 text-white flex flex-col justify-between relative overflow-hidden h-full">
                <!-- Glowing corner -->
                <div class="absolute top-0 right-0 w-48 h-48 bg-indigo-500/10 blur-[60px] rounded-full -mr-16 -mt-16 pointer-events-none"></div>
                
                <div class="relative z-10 space-y-4">
                    <h3 class="text-base font-extrabold tracking-tight">System Parameter Documentation</h3>
                    <p class="text-slate-400 text-xs leading-relaxed font-medium">
                        These settings directly govern the automated payroll engine calculations. Variables such as <code class="text-indigo-400 font-bold font-mono">sss_rate</code> or <code class="text-indigo-400 font-bold font-mono">tax_rate</code> should be saved as decimal values (e.g. 0.04 for 4%). Changes will automatically impact the next compiled payroll stream.
                    </p>
                    <div class="p-4 bg-white/5 border border-white/10 rounded-2xl flex items-start gap-3">
                        <svg class="w-5 h-5 text-indigo-400 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                        <div>
                            <div class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1">PRO-TIP</div>
                            <div class="text-xs text-slate-300 font-medium">Be sure to double check bracket thresholds against updated Philippine tax laws before saving.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Parameter Modal --}}
    <x-modal name="add-parameter" :show="false" focusable>
        <div class="p-6">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-slate-900 tracking-tight">Define Custom Parameter</h3>
                <p class="text-xs text-slate-400 mt-1">Configure and store a new variable parameter in the master registry.</p>
            </div>
            
            <form action="{{ route('settings.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Display Label</label>
                        <input type="text" name="label" placeholder="e.g. PAG-IBIG Rate" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl py-2.5 px-3.5 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-semibold" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Storage Key (Snake Case)</label>
                        <input type="text" name="key" placeholder="e.g. pagibig_rate" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl py-2.5 px-3.5 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-mono font-bold text-indigo-600" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Initial Value</label>
                        <input type="text" name="value" placeholder="0.02" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl py-2.5 px-3.5 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-semibold" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Category Group</label>
                        <select name="group" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl py-2.5 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-bold">
                            <option value="statutory">Statutory Parameters</option>
                            <option value="tax">Tax & Legal</option>
                            <option value="institutional">Institutional Identity</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="type" value="string">

                <div class="mt-6 pt-6 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" @click="$dispatch('close-modal', 'add-parameter')" class="px-5 py-2.5 text-xs font-bold text-slate-500 hover:text-slate-700 transition">Cancel</button>
                    <button type="submit" class="btn-action-indigo text-xs py-2.5 px-6">Define Parameter</button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>
