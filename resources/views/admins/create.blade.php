<x-app-layout>
    <x-slot name="header">
        Elevate Authority
    </x-slot>

    <div class="max-w-2xl mx-auto mt-8">
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-8 border-b border-slate-50 dark:border-slate-800 text-center">
                <div class="w-16 h-16 bg-slate-900 text-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xl ring-8 ring-slate-50 dark:ring-slate-800/50">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 3v1c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 3v1m0 0c.851 0 1.673.1 2.459.29m0 0A10.016 10.016 0 0115.353 10H14a3 3 0 00-2.828 4M12 11c0 3.517 1.009 6.799 2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 3v1m0 0c.851 0 1.673.1 2.459.29m0 0a10.016 10.016 0 011.094 9.71m3.44 2.04l-.054-.09a10.003 10.003 0 010-19.142m0 19.142A10.002 10.002 0 0120 13v-1M12 11c0 3.517 1.009 6.799 2.753 9.571m0 0c.851 0 1.673.1 2.459.29m0 0a10.016 10.016 0 011.094 9.71m-1.094-9.71A10.016 10.016 0 0018 11.29"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 italic tracking-tight">Admin Assignment</h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-2">Granting Full System Access</p>
            </div>

            <form action="{{ route('admins.store') }}" method="POST" class="p-8 space-y-8">
                @csrf
                
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Identity Name</label>
                        <input type="text" name="name" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-slate-900/10 text-sm font-bold uppercase tracking-tight" placeholder="ADMINISTRATOR FULL NAME">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Institutional Email</label>
                        <input type="email" name="email" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-slate-900/10 text-sm font-medium" placeholder="admin@aisat.edu.ph">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">System ID / Employee number</label>
                        <input type="text" name="employee_id" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-slate-900/10 text-sm font-black tracking-widest text-slate-900" placeholder="ADM-0000">
                        <p class="text-[10px] text-slate-400 italic">Default passkey will be generated based on this ID (e.g. AISAT-ADM-0000)</p>
                    </div>
                </div>

                <div class="pt-6 flex justify-between items-center border-t border-slate-50 dark:border-slate-800">
                    <a href="{{ route('admins.index') }}" class="text-sm font-bold text-slate-300 hover:text-rose-500 transition">Cancel Elevation</a>
                    <button type="submit" class="px-8 py-4 bg-slate-900 text-white rounded-2xl font-bold shadow-xl hover:bg-black hover:-translate-y-1 transition duration-300 active:scale-95 flex items-center gap-2">
                        Generate Account
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
