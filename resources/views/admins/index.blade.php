<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="text-slate-400 font-medium">System</span>
            <span class="text-slate-300">/</span>
            <span class="font-bold text-slate-800">Access Control</span>
        </div>
    </x-slot>

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-sm overflow-hidden">
        {{-- Header --}}
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">Admins & Moderators</h1>
                <p class="text-sm text-slate-500 mt-1">Manage system-level access. Admins have full control; moderators handle daily operations.</p>
            </div>
            <a href="{{ route('admins.create') }}" class="px-5 py-2.5 bg-slate-900 text-white rounded-xl font-bold flex items-center gap-2 hover:bg-slate-800 active:scale-95 transition shadow-sm whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                New Account
            </a>
        </div>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="mx-6 mt-4 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-medium flex items-start gap-2">
                <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mx-6 mt-4 p-4 rounded-xl bg-rose-50 border border-rose-100 text-rose-700 text-sm font-medium flex items-start gap-2">
                <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Legend --}}
        <div class="px-6 pt-4 pb-2 flex items-center gap-4">
            <div class="flex items-center gap-1.5">
                <div class="w-2 h-2 rounded-full bg-indigo-500"></div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Admin — Full system access</span>
            </div>
            <div class="flex items-center gap-1.5">
                <div class="w-2 h-2 rounded-full bg-violet-500"></div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Moderator — Operational access</span>
            </div>
        </div>

        {{-- Desktop Table --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800">
                        <th class="px-6 py-3.5">Identity</th>
                        <th class="px-6 py-3.5">Auth Channel</th>
                        <th class="px-6 py-3.5">Privilege Level</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($admins as $admin)
                    <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-800/40 transition group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-base shadow-sm text-white {{ $admin->role === 'admin' ? 'bg-slate-950' : 'bg-violet-600' }}">
                                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-900 dark:text-slate-100 tracking-tight flex items-center gap-2">
                                        {{ $admin->name }}
                                        @if(Auth::id() == $admin->id)
                                            <span class="text-[8px] font-extrabold text-slate-400 uppercase tracking-widest bg-slate-100 px-1.5 py-0.5 rounded">You</span>
                                        @endif
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-bold tracking-wider uppercase">{{ $admin->employee_id ?? '—' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-xs font-semibold text-slate-700 dark:text-slate-400">{{ $admin->email }}</span>
                                <span class="text-[9px] text-slate-400 font-semibold uppercase tracking-widest mt-0.5">Standard Credentials</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($admin->role === 'admin')
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-[9px] font-extrabold uppercase tracking-widest border border-indigo-100">
                                    <div class="w-1.5 h-1.5 rounded-full bg-indigo-500"></div>
                                    Super Admin
                                </div>
                            @else
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-violet-50 text-violet-600 rounded-lg text-[9px] font-extrabold uppercase tracking-widest border border-violet-100">
                                    <div class="w-1.5 h-1.5 rounded-full bg-violet-500"></div>
                                    Moderator
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-1.5 opacity-60 group-hover:opacity-100 focus-within:opacity-100 transition">
                                <a href="{{ route('admins.edit', $admin) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                @if(Auth::id() != $admin->id)
                                <form action="{{ route('admins.destroy', $admin) }}" method="POST" onsubmit="return confirm('Revoke access for {{ $admin->name }}?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition">
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
                            <span class="text-[8px] font-extrabold text-slate-400 uppercase bg-slate-100 px-1.5 py-0.5 rounded">You</span>
                        @endif
                    </div>
                    <div class="text-[10px] text-slate-500 truncate">{{ $admin->email }}</div>
                    <div class="mt-1">
                        @if($admin->role === 'admin')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded text-[9px] font-bold uppercase tracking-widest border border-indigo-100">
                                <span class="w-1 h-1 rounded-full bg-indigo-500"></span> Super Admin
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-violet-50 text-violet-600 rounded text-[9px] font-bold uppercase tracking-widest border border-violet-100">
                                <span class="w-1 h-1 rounded-full bg-violet-500"></span> Moderator
                            </span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-1 shrink-0">
                    <a href="{{ route('admins.edit', $admin) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </a>
                    @if(Auth::id() != $admin->id)
                    <form action="{{ route('admins.destroy', $admin) }}" method="POST" onsubmit="return confirm('Revoke access?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition">
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

        <div class="px-6 py-3 bg-slate-50/50 border-t border-slate-100 text-[9px] font-bold text-slate-400 uppercase tracking-widest">
            {{ $admins->count() }} account(s) — {{ $admins->where('role', 'admin')->count() }} admin, {{ $admins->where('role', 'moderator')->count() }} moderator
        </div>
    </div>
</x-app-layout>
