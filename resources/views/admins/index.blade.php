<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="text-slate-400 font-medium">System</span>
            <span class="text-slate-300">/</span>
            <span class="font-bold text-slate-800">Access Control</span>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Admins & Moderators</h1>
                <p class="text-sm text-slate-500 mt-1">Manage system-level access. Admins have full control; moderators handle daily operations.</p>
            </div>
            <a href="{{ route('admins.create') }}" class="btn-action-indigo text-xs py-2.5 px-4 shadow-sm w-full sm:w-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                New Account
            </a>
        </div>

        {{-- Legend Indicators --}}
        <div class="flex items-center gap-4 flex-wrap">
            <div class="flex items-center gap-1.5 bg-slate-50 border border-slate-100 px-3 py-1.5 rounded-xl">
                <div class="w-2.5 h-2.5 rounded-full bg-slate-950"></div>
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Admin — Full System Access</span>
            </div>
            <div class="flex items-center gap-1.5 bg-slate-50 border border-slate-100 px-3 py-1.5 rounded-xl">
                <div class="w-2.5 h-2.5 rounded-full bg-violet-600"></div>
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Moderator — Operational Access</span>
            </div>
        </div>

        <!-- Directory Card -->
        <div class="card-reference overflow-hidden bg-white/95 backdrop-blur-xl border border-slate-200/60 shadow-lg">
            {{-- Desktop Table --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            <th class="px-6 py-4">Identity</th>
                            <th class="px-6 py-4">Auth Channel</th>
                            <th class="px-6 py-4">Privilege Level</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($admins as $admin)
                        <tr class="hover:bg-indigo-50/10 transition group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-base shadow-sm text-white group-hover:scale-105 transition-all duration-300 {{ $admin->role === 'admin' ? 'bg-slate-950' : 'bg-violet-600' }}">
                                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                                            {{ $admin->name }}
                                            @if(Auth::id() == $admin->id)
                                                <span class="text-[8px] font-extrabold text-slate-400 uppercase tracking-widest bg-slate-100 border border-slate-200 px-1.5 py-0.5 rounded-lg">You</span>
                                            @endif
                                        </div>
                                        <div class="text-[10px] text-slate-400 font-bold tracking-wider uppercase mt-0.5">{{ $admin->employee_id ?? '—' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-xs font-extrabold text-slate-700">{{ $admin->email }}</span>
                                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Standard Credentials</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($admin->role === 'admin')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-950 text-white rounded-lg text-[9px] font-extrabold uppercase tracking-wider">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                        Super Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-violet-50 text-violet-600 border border-violet-100 rounded-lg text-[9px] font-extrabold uppercase tracking-wider">
                                        <span class="w-1.5 h-1.5 rounded-full bg-violet-500"></span>
                                        Moderator
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition duration-200">
                                    <a href="{{ route('admins.edit', $admin) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 border border-transparent hover:border-indigo-100 rounded-xl transition-all" title="Edit Admin">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    @if(Auth::id() != $admin->id)
                                    <form action="{{ route('admins.destroy', $admin) }}" method="POST" onsubmit="return confirm('Revoke access for {{ $admin->name }}?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-100 rounded-xl transition-all" title="Revoke Access">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400 text-sm font-bold uppercase tracking-widest">No administrative accounts found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile Card List --}}
            <div class="md:hidden divide-y divide-slate-100">
                @forelse($admins as $admin)
                <div class="p-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-white shrink-0 {{ $admin->role === 'admin' ? 'bg-slate-950' : 'bg-violet-600' }}">
                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span class="text-sm font-bold text-slate-900 truncate">{{ $admin->name }}</span>
                            @if(Auth::id() == $admin->id)
                                <span class="text-[8px] font-extrabold text-slate-400 uppercase bg-slate-100 px-1.5 py-0.5 rounded-lg border border-slate-200">You</span>
                            @endif
                        </div>
                        <div class="text-[10px] text-slate-500 truncate mt-0.5">{{ $admin->email }}</div>
                        <div class="mt-2">
                            @if($admin->role === 'admin')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-slate-950 text-white rounded text-[9px] font-bold uppercase tracking-wider">
                                    Super Admin
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-violet-50 text-violet-600 border border-violet-100 rounded text-[9px] font-bold uppercase tracking-wider">
                                    Moderator
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 shrink-0">
                        <a href="{{ route('admins.edit', $admin) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 border border-slate-100/50 rounded-xl transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        @if(Auth::id() != $admin->id)
                        <form action="{{ route('admins.destroy', $admin) }}" method="POST" onsubmit="return confirm('Revoke access?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 border border-slate-100/50 rounded-xl transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-10 text-center text-slate-400 text-sm font-bold uppercase tracking-widest">No accounts found.</div>
                @endforelse
            </div>

            @if($admins->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $admins->links() }}
                </div>
            @endif

            <!-- Footer summary -->
            <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-widest flex justify-between items-center">
                <span>Total Control Registry: {{ $admins->total() }} accounts</span>
                <span class="tabular-nums">Page {{ $admins->currentPage() }} of {{ $admins->lastPage() }}</span>
            </div>
        </div>
    </div>
</x-app-layout>
