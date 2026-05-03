<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-['DM_Serif_Display'] text-[#101D33] tracking-tight mb-2 italic leading-none">Authentication <span class="text-[#D4AF37]">Mandate</span></h2>
        <p class="text-[9px] font-black text-[#101D33]/30 uppercase tracking-[0.4em] leading-none">Access Restricted: Personnel Terminal Only</p>
    </div>

    <!-- Institutional Notifications -->
    @if (session('status'))
        <div class="mb-8 p-6 bg-emerald-50 border border-emerald-100 rounded-[2rem] flex items-center gap-4 animate-in fade-in slide-in-from-top-4">
            <div class="w-10 h-10 bg-emerald-500 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div>
                <p class="text-[10px] font-black text-emerald-800 uppercase tracking-widest leading-none mb-1">Authorization Success</p>
                <p class="text-xs font-bold text-emerald-600/70">{{ session('status') }}</p>
            </div>
        </div>
    @endif

    <div class="space-y-8">
        <form method="POST" action="{{ route('login') }}" class="space-y-8">
            @csrf

            <!-- Credential Channel -->
            <div class="space-y-3">
                <label for="username" class="block text-[10px] font-black text-[#101D33] uppercase tracking-[0.3em] ml-2">Credential Channel (Username)</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-[#101D33]/20 group-focus-within:text-[#D4AF37] transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus 
                           class="block w-full pl-14 pr-6 py-4 bg-white border border-[#101D33]/5 rounded-[2rem] text-sm font-['DM_Serif_Text'] text-[#101D33] placeholder-[#101D33]/20 focus:ring-4 focus:ring-[#101D33]/5 focus:border-[#101D33]/10 transition-all outline-none shadow-sm" 
                           placeholder="Personnel Identifier">
                </div>
                @if($errors->has('username'))
                    <p class="text-[9px] font-black text-[#660000] uppercase tracking-widest mt-2 ml-2">{{ $errors->first('username') }}</p>
                @endif
            </div>

            <!-- Security Key -->
            <div class="space-y-3">
                <div class="flex items-center justify-between ml-2">
                    <label for="password" class="block text-[10px] font-black text-[#101D33] uppercase tracking-[0.3em]">Institutional Key</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-[9px] font-black text-[#660000] hover:text-[#D4AF37] uppercase tracking-widest underline underline-offset-4">Forgot Key?</a>
                    @endif
                </div>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-[#101D33]/20 group-focus-within:text-[#D4AF37] transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <input id="password" type="password" name="password" required 
                           class="block w-full pl-14 pr-6 py-4 bg-white border border-[#101D33]/5 rounded-[2rem] text-sm font-['DM_Serif_Text'] text-[#101D33] placeholder-[#101D33]/20 focus:ring-4 focus:ring-[#101D33]/5 focus:border-[#101D33]/10 transition-all outline-none shadow-sm" 
                           placeholder="••••••••">
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="group relative w-full flex justify-center py-5 px-6 border border-transparent text-[11px] font-black rounded-[2.5rem] text-white bg-[#101D33] hover:bg-[#660000] focus:outline-none focus:ring-4 focus:ring-[#101D33]/10 transition-all shadow-2xl shadow-[#101D33]/20 hover:-translate-y-1 active:scale-95 uppercase tracking-[0.4em] overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-tr from-[#660000]/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <span class="relative z-10 flex items-center gap-3">
                        Authorize Access
                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </span>
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
