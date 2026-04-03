<x-guest-layout>
    <div class="mb-10">
        <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100 tracking-tighter">Welcome back</h2>
        <p class="text-sm font-medium text-slate-500 mt-2">Log in to your account to continue.</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-800/50 rounded-xl text-xs font-bold text-emerald-600 dark:text-emerald-400 saas-shadow">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="space-y-2">
            <label for="email" class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest pl-1">Email Registry</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"></path></svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                       class="block w-full pl-10 pr-4 py-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-bold text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 transition-all outline-none saas-shadow-md" 
                       placeholder="your@email.com">
            </div>
            @if($errors->has('email'))
                <p class="text-[10px] font-bold text-rose-600 dark:text-rose-400 uppercase tracking-tight mt-1 pl-1">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <div class="flex items-center justify-between pl-1">
                <label for="password" class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest">Access Key</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-[10px] font-bold text-indigo-600 hover:text-indigo-500 uppercase tracking-tight">Forgot Key?</a>
                @endif
            </div>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <input id="password" type="password" name="password" required 
                       class="block w-full pl-10 pr-4 py-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-bold text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 transition-all outline-none saas-shadow-md" 
                       placeholder="••••••••">
            </div>
            @if($errors->has('password'))
                <p class="text-[10px] font-bold text-rose-600 dark:text-rose-400 uppercase tracking-tight mt-1 pl-1">{{ $errors->first('password') }}</p>
            @endif
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-2">
            <label class="flex items-center gap-2 cursor-pointer group">
                <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 dark:border-slate-700 text-indigo-600 focus:ring-indigo-600 transition-all cursor-pointer">
                <span class="text-[11px] font-extrabold text-slate-500 group-hover:text-slate-700 transition-colors uppercase tracking-tight">Persistent Auth</span>
            </label>
        </div>

        <div class="pt-4">
            <button type="submit" class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-black rounded-xl text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 transition-all saas-shadow-md hover:-translate-y-0.5 active:translate-y-0 uppercase tracking-widest overflow-hidden">
                 <div class="absolute inset-0 z-0 bg-gradient-to-r from-indigo-600 to-violet-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                 <span class="relative z-10 flex items-center gap-2">
                    Authorize Entry
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                 </span>
            </button>
        </div>

        <div class="pt-4 text-center">
            <p class="text-xs font-bold text-slate-400">Need access? <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-500 underline underline-offset-4 tracking-tight">Request Credentials</a></p>
        </div>
    </form>
</x-guest-layout>
