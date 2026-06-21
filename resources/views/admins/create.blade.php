<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admins.index') }}" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 hover:bg-indigo-50 dark:hover:bg-indigo-950 hover:text-indigo-600 transition border border-slate-200 dark:border-slate-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <span class="text-slate-400 font-medium">Admin Management</span>
            <span class="text-slate-300">/</span>
            <span class="font-bold text-slate-800">New Admin</span>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto mt-8 px-4">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 bg-slate-900 text-white rounded-xl flex items-center justify-center shadow-sm shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 3v1c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 3v1m0 0c.851 0 1.673.1 2.459.29m0 0A10.016 10.016 0 0115.353 10H14a3 3 0 00-2.828 4"></path></svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">Admin Assignment</h1>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Granting Full System Access</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admins.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                
                <div class="space-y-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Identity Name</label>
                        <input type="text" name="name" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 text-sm font-semibold text-slate-800 dark:text-slate-100 transition" placeholder="Enter Full Name">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Institutional Email</label>
                        <input type="email" name="email" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 text-sm font-medium text-slate-800 dark:text-slate-100 transition" placeholder="admin@aisat.edu.ph">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">System ID / Employee number</label>
                        <input type="text" name="employee_id" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 text-sm font-bold tracking-widest text-slate-900 dark:text-white transition" placeholder="ADM-0000">
                        <p class="text-[10px] text-slate-400 italic mt-1">Default passkey will be generated based on this ID (e.g. AISAT-ADM-0000)</p>
                    </div>
                </div>

                <div class="pt-5 flex justify-end items-center gap-4 border-t border-slate-100 dark:border-slate-800">
                    <a href="{{ route('admins.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition">Cancel</a>
                    <button type="submit" class="px-5 py-2.5 bg-slate-900 text-white rounded-xl font-bold hover:bg-slate-800 active:scale-95 transition flex items-center gap-1.5 shadow-sm">
                        <span>Generate Account</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
