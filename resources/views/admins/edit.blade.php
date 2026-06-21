<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admins.index') }}" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 hover:bg-indigo-50 dark:hover:bg-indigo-950 hover:text-indigo-600 transition border border-slate-200 dark:border-slate-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <span class="text-slate-400 font-medium">Admin Management</span>
            <span class="text-slate-300">/</span>
            <span class="font-bold text-slate-800">Edit Admin</span>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto mt-8 px-4">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 bg-indigo-600 text-white rounded-xl flex items-center justify-center shadow-sm shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">Access Control</h1>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Updating Authority Record</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admins.update', $admin) }}" method="POST" class="p-6 space-y-5">
                @csrf
                @method('PATCH')
                
                <div class="space-y-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Authority Name</label>
                        <input type="text" name="name" value="{{ old('name', $admin->name) }}" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm font-semibold text-slate-800 dark:text-slate-100 transition">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Identity Email</label>
                        <input type="email" name="email" value="{{ old('email', $admin->email) }}" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm font-medium text-slate-800 dark:text-slate-100 transition">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">System Identifier</label>
                        <input type="text" name="employee_id" value="{{ old('employee_id', $admin->employee_id) }}" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm font-bold tracking-widest text-indigo-600 dark:text-indigo-400 transition">
                    </div>
                </div>

                <div class="pt-5 flex justify-end items-center gap-4 border-t border-slate-100 dark:border-slate-800">
                    <a href="{{ route('admins.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition">Cancel</a>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 active:scale-95 transition flex items-center gap-1.5 shadow-sm">
                        <span>Update Authority</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
