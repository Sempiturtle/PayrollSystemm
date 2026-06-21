<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="text-slate-400 font-medium">System</span>
            <span class="text-slate-300">/</span>
            <span class="font-bold text-slate-800">Admin Management</span>
        </div>
    </x-slot>

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">System Administrators</h1>
                <p class="text-sm text-slate-500 mt-1">Manage core system access and administrative credentials.</p>
            </div>
            <a href="{{ route('admins.create') }}" class="px-5 py-2.5 bg-slate-900 text-white rounded-xl font-bold flex items-center gap-2 hover:bg-slate-800 active:scale-95 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                New Admin
            </a>
        </div>

        <div class="overflow-x-auto">
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
                    @foreach($admins as $admin)
                    <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-800/40 transition group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-xl bg-slate-950 text-white flex items-center justify-center font-bold text-base shadow-sm">
                                    {{ substr($admin->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-900 dark:text-slate-100 tracking-tight">{{ $admin->name }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold tracking-wider uppercase">{{ $admin->employee_id }}</div>
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
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-lg text-[9px] font-extrabold uppercase tracking-widest border border-indigo-100 dark:border-indigo-800/50">
                                <div class="w-1.5 h-1.5 rounded-full bg-indigo-500"></div>
                                Higher Authority
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-1.5 opacity-60 group-hover:opacity-100 focus-within:opacity-100 transition duration-200">
                                <a href="{{ route('admins.edit', $admin) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-950/50 rounded-lg transition" title="Edit Admin">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                @if(Auth::id() != $admin->id)
                                <form action="{{ route('admins.destroy', $admin) }}" method="POST" onsubmit="return confirm('Revoke administrative access?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/50 rounded-lg transition" title="Delete Admin">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
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
</x-app-layout>
