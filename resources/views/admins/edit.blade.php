<x-app-layout>
    <x-slot name="header">
        Modify Administrative Access
    </x-slot>

    <div class="max-w-2xl mx-auto mt-8">
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-8 border-b border-slate-50 dark:border-slate-800 text-center">
                <div class="w-16 h-16 bg-indigo-600 text-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xl ring-8 ring-slate-50 dark:ring-slate-800/50">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 italic tracking-tight">Access Control</h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-2">Updating Authority Record</p>
            </div>

            <form action="{{ route('admins.update', $admin) }}" method="POST" class="p-8 space-y-8">
                @csrf
                @method('PATCH')
                
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Authority Name</label>
                        <input type="text" name="name" value="{{ old('name', $admin->name) }}" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-bold uppercase tracking-tight">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Identity Email</label>
                        <input type="email" name="email" value="{{ old('email', $admin->email) }}" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-medium">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">System Identifier</label>
                        <input type="text" name="employee_id" value="{{ old('employee_id', $admin->employee_id) }}" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-black tracking-widest text-indigo-600">
                    </div>
                </div>

                <div class="pt-6 flex justify-between items-center border-t border-slate-50 dark:border-slate-800">
                    <a href="{{ route('admins.index') }}" class="text-sm font-bold text-slate-300 hover:text-rose-500 transition">Discard Modifications</a>
                    <button type="submit" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-xl shadow-indigo-100 dark:shadow-none hover:bg-indigo-700 hover:-translate-y-1 transition duration-300 active:scale-95 flex items-center gap-2">
                        Update Authority
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
