<x-guest-layout>
    <div class="mb-10">
        <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100 tracking-tighter">Join the Academy</h2>
        <p class="text-sm font-medium text-slate-500 mt-2">Create your personnel credentials.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6 pb-12">
        @csrf

        <!-- Name -->
        <div class="space-y-2">
            <label for="name" class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest pl-1">Full Legal Name</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus 
                       class="block w-full pl-10 pr-4 py-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-bold text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 transition-all outline-none saas-shadow-md" 
                       placeholder="e.g. John Doe">
            </div>
            @if($errors->has('name'))
                <p class="text-[10px] font-bold text-rose-600 dark:text-rose-400 uppercase tracking-tight mt-1 pl-1">{{ $errors->first('name') }}</p>
            @endif
        </div>

        <!-- Email Address -->
        <div class="space-y-2">
            <label for="email" class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest pl-1">Institutional Email</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required 
                       class="block w-full pl-10 pr-4 py-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-bold text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 transition-all outline-none saas-shadow-md" 
                       placeholder="john.doe@aisat.edu.ph">
            </div>
            @if($errors->has('email'))
                <p class="text-[10px] font-bold text-rose-600 dark:text-rose-400 uppercase tracking-tight mt-1 pl-1">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <label for="password" class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest pl-1">New Security Key</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <input id="password" type="password" name="password" required 
                       class="block w-full pl-10 pr-4 py-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-bold text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 transition-all outline-none saas-shadow-md" 
                       placeholder="Min. 8 characters">
            </div>
            @if($errors->has('password'))
                <p class="text-[10px] font-bold text-rose-600 dark:text-rose-400 uppercase tracking-tight mt-1 pl-1">{{ $errors->first('password') }}</p>
            @endif
        </div>

        <!-- Confirm Password -->
        <div class="space-y-2">
            <label for="password_confirmation" class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest pl-1">Repeat Security Key</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <input id="password_confirmation" type="password" name="password_confirmation" required 
                       class="block w-full pl-10 pr-4 py-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-bold text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 transition-all outline-none saas-shadow-md" 
                       placeholder="Verify password">
            </div>
        </div>

        <div class="pt-4">
            <button type="submit" class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-black rounded-xl text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 transition-all saas-shadow-md hover:-translate-y-0.5 active:translate-y-0 uppercase tracking-widest overflow-hidden">
                 <div class="absolute inset-0 z-0 bg-gradient-to-r from-indigo-600 to-violet-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                 <span class="relative z-10 flex items-center gap-2">
                    Initialize Account
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                 </span>
            </button>
        </div>

        <div class="pt-4 text-center">
            <p class="text-xs font-bold text-slate-400">Already a member? <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-500 underline underline-offset-4 tracking-tight">Access Portal</a></p>
        </div>
    </form>
</x-guest-layout>
