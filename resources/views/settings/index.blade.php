<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            System & Institutional Settings
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-100">
                <div class="p-8">
                    <div class="mb-8 border-b border-slate-50 pb-6">
                        <h3 class="text-lg font-bold text-slate-800 tracking-tight">Institutional Configuration</h3>
                        <p class="text-sm text-slate-400">Manage statutory rates, tax brackets, and basic institutional identity.</p>
                    </div>

                    {{-- Manual Parameter Creation (New) --}}
                    <div x-data="{ showForm: false }" class="mb-12">
                        <button @click="showForm = !showForm" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-2">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                            Add New Manual Parameter
                        </button>

                        <div x-show="showForm" x-collapse class="mt-4 p-6 bg-slate-50 rounded-2xl border border-slate-100 space-y-4">
                            <form action="{{ route('settings.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @csrf
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Label (Display Name)</label>
                                    <input type="text" name="label" placeholder="e.g., SSS Rate" class="w-full bg-white border-slate-100 rounded-xl text-sm focus:ring-indigo-500">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Storage Key (Unique)</label>
                                    <input type="text" name="key" placeholder="e.g., sss_rate" class="w-full bg-white border-slate-100 rounded-xl text-sm focus:ring-indigo-500">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Initial Value</label>
                                    <input type="text" name="value" placeholder="0.045" class="w-full bg-white border-slate-100 rounded-xl text-sm focus:ring-indigo-500">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Group</label>
                                    <select name="group" class="w-full bg-white border-slate-100 rounded-xl text-sm focus:ring-indigo-500">
                                        <option value="statutory">Statutory</option>
                                        <option value="tax">Tax</option>
                                        <option value="institutional">Institutional Identity</option>
                                    </select>
                                </div>
                                <input type="hidden" name="type" value="string">
                                <div class="md:col-span-2 flex justify-end pt-2">
                                    <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition">
                                        Define Parameter
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="space-y-12">
                            @foreach($settings as $group => $items)
                                <section class="space-y-6">
                                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] border-l-2 border-indigo-500 pl-3">
                                        {{ ucfirst($group) }} Parameters
                                    </h4>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                                        @foreach($items as $setting)
                                            <div class="flex flex-col gap-1.5">
                                                <label class="text-xs font-bold text-slate-700 tracking-tight">
                                                    {{ $setting->label }}
                                                </label>
                                                
                                                @if($setting->type === 'decimal' || $setting->type === 'integer')
                                                    <input type="number" step="any" 
                                                           name="settings[{{ $setting->key }}]" 
                                                           value="{{ $setting->value }}" 
                                                           class="bg-slate-50 border-slate-100 rounded-xl text-sm focus:ring-indigo-500 w-full transition">
                                                @else
                                                    <input type="text" 
                                                           name="settings[{{ $setting->key }}]" 
                                                           value="{{ $setting->value }}" 
                                                           class="bg-slate-50 border-slate-100 rounded-xl text-sm focus:ring-indigo-500 w-full transition">
                                                @endif
                                                <p class="text-[10px] text-slate-400 italic">Key: {{ $setting->key }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </section>
                            @endforeach
                        </div>

                        <div class="mt-12 pt-8 border-t border-slate-50 flex justify-end">
                            <button type="submit" class="px-8 py-3 bg-slate-900 text-white rounded-xl font-bold text-sm shadow-xl hover:bg-slate-800 hover:-translate-y-0.5 transition-all active:scale-95">
                                Save System Configurations
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Audit Trail Preview (Coming from the new Audit Model) -->
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-100 p-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-base font-bold text-slate-800 tracking-tight">System Integrity Audit</h3>
                        <p class="text-xs text-slate-400 mt-1">Recent administrative actions captured by the audit engine.</p>
                    </div>
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase rounded-full border border-emerald-100">Live Engine Active</span>
                </div>
                
                <div class="space-y-4">
                    @php
                        $audits = \App\Models\AuditLog::with('user')->latest()->limit(5)->get();
                    @endphp
                    @forelse($audits as $audit)
                        <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-50/50 border border-slate-100/50">
                            <div class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 3v8h8V7a10.003 10.003 0 00-10-10"></path></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs font-bold text-slate-700">
                                    {{ $audit->user->name ?? 'System' }} {{ strtolower($audit->event) }} a 
                                    <span class="text-indigo-600 uppercase tracking-tighter">{{ class_basename($audit->auditable_type) }}</span>
                                </div>
                                <div class="text-[10px] text-slate-400 mt-0.5">{{ $audit->created_at->diffForHumans() }} • IP: {{ $audit->ip_address }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-slate-400 italic text-sm">No security logs recorded yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
