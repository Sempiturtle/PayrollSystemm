<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admins.index') }}" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 hover:bg-indigo-50 dark:hover:bg-indigo-950 hover:text-indigo-600 transition border border-slate-200 dark:border-slate-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <span class="text-slate-400 font-medium">Access Control</span>
            <span class="text-slate-300">/</span>
            <span class="font-bold text-slate-800">Edit Account</span>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto mt-8 px-4">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 {{ $admin->role === 'admin' ? 'bg-indigo-600' : 'bg-violet-600' }} text-white rounded-xl flex items-center justify-center shadow-sm shrink-0 text-lg font-bold">
                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">{{ $admin->name }}</h1>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Editing Access Account</p>
                    </div>
                </div>
            </div>

            @if($errors->any())
                <div class="mx-6 mt-4 p-4 rounded-xl bg-rose-50 border border-rose-100 text-rose-700 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <div class="flex items-center gap-2"><span class="text-rose-400">•</span> {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('admins.update', $admin) }}" method="POST" class="p-6 space-y-5">
                @csrf
                @method('PATCH')

                {{-- Role Picker — hidden for self to prevent self-demotion --}}
                @if(Auth::id() != $admin->id)
                    <div class="space-y-2" x-data="{ role: '{{ old('role', $admin->role) }}' }">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Privilege Level</label>
                        <div class="grid grid-cols-2 gap-3">
                            {{-- Admin --}}
                            <label class="relative cursor-pointer">
                                <input type="radio" name="role" value="admin" class="sr-only peer" x-model="role" {{ old('role', $admin->role) === 'admin' ? 'checked' : '' }}>
                                <div class="p-4 rounded-xl border-2 transition peer-checked:border-indigo-500 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20 border-slate-200 dark:border-slate-700 hover:border-indigo-300">
                                    <div class="flex items-center gap-2.5 mb-2">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                        </div>
                                        <span class="text-sm font-extrabold text-slate-900 dark:text-slate-100">Super Admin</span>
                                    </div>
                                    <p class="text-[10px] text-slate-500 leading-relaxed">Full system access — payroll, settings, audit logs, all configurations.</p>
                                </div>
                            </label>

                            {{-- Moderator --}}
                            <label class="relative cursor-pointer">
                                <input type="radio" name="role" value="moderator" class="sr-only peer" x-model="role" {{ old('role', $admin->role) === 'moderator' ? 'checked' : '' }}>
                                <div class="p-4 rounded-xl border-2 transition peer-checked:border-violet-500 peer-checked:bg-violet-50 dark:peer-checked:bg-violet-900/20 border-slate-200 dark:border-slate-700 hover:border-violet-300">
                                    <div class="flex items-center gap-2.5 mb-2">
                                        <div class="w-8 h-8 rounded-lg bg-violet-600 text-white flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </div>
                                        <span class="text-sm font-extrabold text-slate-900 dark:text-slate-100">Moderator</span>
                                    </div>
                                    <p class="text-[10px] text-slate-500 leading-relaxed">Operational access — attendance, schedules, leaves, discrepancies. No financials.</p>
                                </div>
                            </label>
                        </div>
                    </div>
                @else
                    {{-- Self-edit: show role as read-only badge --}}
                    <div class="p-3 rounded-xl bg-amber-50 border border-amber-100 flex items-center gap-2 text-xs text-amber-700 font-medium">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                        You cannot change your own role. Current role: <strong class="capitalize ml-1">{{ $admin->role }}</strong>
                    </div>
                @endif

                <div class="space-y-4 pt-2">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Authority Name</label>
                        <input type="text" name="name" value="{{ old('name', $admin->name) }}" required
                            class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm font-semibold text-slate-800 dark:text-slate-100 transition">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Identity Email</label>
                        <input type="email" name="email" value="{{ old('email', $admin->email) }}" required
                            class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm font-medium text-slate-800 dark:text-slate-100 transition">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">System Identifier</label>
                        <input type="text" name="employee_id" value="{{ old('employee_id', $admin->employee_id) }}" required
                            class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 rounded-xl p-3 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm font-bold tracking-widest text-indigo-600 dark:text-indigo-400 transition">
                    </div>
                </div>

                <div class="pt-5 flex justify-end items-center gap-4 border-t border-slate-100 dark:border-slate-800">
                    <a href="{{ route('admins.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition">Cancel</a>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 active:scale-95 transition flex items-center gap-1.5 shadow-sm">
                        <span>Save Changes</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
