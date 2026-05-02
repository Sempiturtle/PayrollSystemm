<nav x-show="sidebarOpen" x-cloak
     x-transition:enter="transition ease-out duration-500"
     x-transition:enter-start="-translate-x-full opacity-0"
     x-transition:enter-end="translate-x-0 opacity-100"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="translate-x-0 opacity-100"
     x-transition:leave-end="-translate-x-full opacity-0"
     class="w-64 h-screen hidden lg:flex lg:flex-col fixed left-0 top-0 z-40 bg-[#101D33] text-white shadow-[10px_0_40px_rgba(0,0,0,0.15)] border-r border-white/5">
    
    <!-- Sidebar Header -->
    <div class="h-20 px-8 flex items-center gap-4 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-[#660000]/20 to-transparent"></div>
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3.5 group relative z-10">
            <div class="w-11 h-11 bg-white rounded-2xl flex items-center justify-center p-2 shadow-[0_8px_20px_rgba(255,255,255,0.1)] transition-transform group-hover:scale-105 group-hover:rotate-3">
                <img src="{{ asset('images/logo.png') }}" alt="AISAT Logo" class="w-full h-full object-contain">
            </div>
            <div class="flex flex-col">
                <span class="text-xs font-bold text-white tracking-[0.2em] uppercase leading-none mb-1 italic">AISAT</span>
                <span class="text-[10px] font-bold text-[#D4AF37] tracking-[0.3em] uppercase leading-none opacity-80">Institutional</span>
            </div>
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 px-4 py-8 space-y-9 overflow-y-auto custom-scrollbar">
        {{-- Overview Section --}}
        <div class="space-y-1.5">
            <div class="px-4 py-2 text-[9px] font-bold text-white/30 uppercase tracking-[0.3em] mb-1">Intelligence Hub</div>
            <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="home">
                Executive Overview
            </x-sidebar-link>
        </div>

        {{-- Management Section --}}
        <div class="space-y-1.5">
            @if(Auth::user()->isAdmin())
                <div class="px-4 py-2 text-[9px] font-bold text-white/30 uppercase tracking-[0.3em] mb-1">Institutional Assets</div>
                
                <x-sidebar-link :href="route('employees.index')" :active="request()->routeIs('employees.*')" icon="users">
                    Personnel Registry
                </x-sidebar-link>

                <x-sidebar-link :href="route('attendance.index')" :active="request()->routeIs('attendance.*')" icon="attendance">
                    Presence Master List
                </x-sidebar-link>

                <x-sidebar-link :href="route('schedules.index')" :active="request()->routeIs('schedules.*')" icon="calendar">
                    Schedule Matrix
                </x-sidebar-link>

                <x-sidebar-link :href="route('payrolls.index')" :active="request()->routeIs('payrolls.*')" icon="currency">
                    Fiscal Disbursements
                </x-sidebar-link>

                <div class="h-2"></div>
                <div class="px-4 py-2 text-[9px] font-bold text-white/30 uppercase tracking-[0.3em] mb-1">Regulatory Policies</div>

                <x-sidebar-link :href="route('leaves.index')" :active="request()->routeIs('leaves.*')" icon="calendar">
                    Absence Registry
                </x-sidebar-link>

                <x-sidebar-link :href="route('holidays.index')" :active="request()->routeIs('holidays.*')" icon="calendar">
                    Academic Calendar Master
                </x-sidebar-link>

                <x-sidebar-link :href="route('admin.discrepancies.index')" :active="request()->routeIs('admin.discrepancies.*')" icon="alert">
                    Correction Requests
                </x-sidebar-link>
            @else
                <div class="px-4 py-2 text-[9px] font-bold text-white/30 uppercase tracking-[0.3em] mb-1">Faculty Portal</div>
                <x-sidebar-link :href="route('attendance.history')" :active="request()->routeIs('attendance.history')" icon="calendar">
                    Presence History
                </x-sidebar-link>
                <x-sidebar-link :href="route('performance.index')" :active="request()->routeIs('performance.index')" icon="chart">
                    Performance Analytics
                </x-sidebar-link>
                <x-sidebar-link :href="route('payrolls.index')" :active="request()->routeIs('payrolls.index')" icon="currency">
                    My Disbursements
                </x-sidebar-link>
                <x-sidebar-link :href="route('fiscal.index')" :active="request()->routeIs('fiscal.index')" icon="document">
                    Fiscal Summary (YTD)
                </x-sidebar-link>
                <x-sidebar-link :href="route('leaves.index')" :active="request()->routeIs('leaves.*')" icon="calendar">
                    Leave Management
                </x-sidebar-link>
                <x-sidebar-link :href="route('discrepancies.mine')" :active="request()->routeIs('discrepancies.mine')" icon="alert">
                    My Disputes
                </x-sidebar-link>
                <x-sidebar-link :href="route('profile.records')" :active="request()->routeIs('profile.records')" icon="user">
                    My Profile
                </x-sidebar-link>
            @endif
        </div>

        <div class="space-y-1.5">
            <div class="px-4 py-2 text-[9px] font-bold text-white/30 uppercase tracking-[0.3em] mb-1">System Control</div>
            @if(Auth::user()->isAdmin())
                <x-sidebar-link :href="route('attendance.scanner')" :active="request()->routeIs('attendance.scanner')" icon="scanner">
                    Identity Terminal
                </x-sidebar-link>
                <x-sidebar-link :href="route('admins.index')" :active="request()->routeIs('admins.*')" icon="users">
                    Access Control
                </x-sidebar-link>
                <x-sidebar-link :href="route('audit-logs.index')" :active="request()->routeIs('audit-logs.*')" icon="document">
                    Security Audits
                </x-sidebar-link>
                <x-sidebar-link :href="route('settings.index')" :active="request()->routeIs('settings.*')" icon="settings">
                    Core Configurations
                </x-sidebar-link>
            @endif
        </div>
    </div>

    <!-- Sidebar Footer -->
    <div class="p-6 border-t border-white/5 bg-black/20">
        <div class="flex items-center gap-4 group">
            <div class="w-10 h-10 rounded-2xl bg-[#D4AF37] text-[#101D33] flex items-center justify-center text-xs font-bold flex-shrink-0 shadow-lg shadow-[#D4AF37]/10 transition-transform group-hover:scale-105 border border-white/10">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-[12px] font-['DM_Serif_Text'] text-white truncate leading-none mb-1">{{ Auth::user()->name }}</div>
                <div class="text-[8px] text-[#D4AF37] font-bold uppercase tracking-[0.2em] leading-none">{{ Auth::user()->role }} Auth</div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                @csrf
                <button type="submit" class="p-2 text-white/20 hover:text-[#660000] hover:bg-[#660000]/10 rounded-xl transition-all" title="Terminate Session">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </button>
            </form>
        </div>
    </div>
</nav>

<!-- Mobile Sidebar Overlay -->
<nav x-show="mobileSidebar" x-cloak class="fixed inset-0 z-50 lg:hidden">
    <!-- Blur Overlay -->
    <div x-show="mobileSidebar" @click="mobileSidebar = false" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-[#101D33]/60 backdrop-blur-md"></div>

    <!-- Sidebar Content -->
    <div x-show="mobileSidebar" 
         x-transition:enter="transition ease-out duration-500 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-400 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed left-0 top-0 w-80 max-w-[calc(100%-3rem)] h-[100dvh] bg-[#101D33] text-white flex flex-col shadow-2xl z-50">
        
        <div class="h-20 px-8 border-b border-white/5 flex justify-between items-center relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-[#660000]/20 to-transparent"></div>
            <div class="flex items-center gap-3.5 relative z-10">
                <div class="w-9 h-9 bg-white rounded-xl flex items-center justify-center p-1.5">
                    <img src="{{ asset('images/logo.png') }}" alt="AISAT Logo" class="w-full h-full object-contain">
                </div>
                <div class="flex flex-col">
                    <span class="text-[11px] font-bold text-white tracking-[0.2em] uppercase italic leading-none">AISAT</span>
                    <span class="text-[8px] font-bold text-[#D4AF37] tracking-[0.3em] uppercase leading-none opacity-80">Institutional</span>
                </div>
            </div>
            <button @click="mobileSidebar = false" class="p-2 text-white/40 hover:text-white transition-colors relative z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="flex-1 px-6 py-8 space-y-9 overflow-y-auto">
            {{-- Mobile Links --}}
            <div class="space-y-1.5">
                <div class="px-4 py-2 text-[9px] font-bold text-white/30 uppercase tracking-[0.3em] mb-1">Intelligence Hub</div>
                <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="home">
                    Executive Overview
                </x-sidebar-link>
            </div>

            <div class="space-y-1.5">
                @if(Auth::user()->isAdmin())
                    <div class="px-4 py-2 text-[9px] font-bold text-white/30 uppercase tracking-[0.3em] mb-1">Institutional Assets</div>
                    <x-sidebar-link :href="route('employees.index')" :active="request()->routeIs('employees.*')" icon="users">
                        Personnel Directory
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('attendance.index')" :active="request()->routeIs('attendance.*')" icon="attendance">
                        Presence Master List
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('schedules.index')" :active="request()->routeIs('schedules.*')" icon="calendar">
                        Schedule Matrix
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('payrolls.index')" :active="request()->routeIs('payrolls.*')" icon="currency">
                        Disbursement History
                    </x-sidebar-link>
                @endif
            </div>
        </div>

        <div class="p-8 border-t border-white/5 bg-black/20 mt-auto">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-4 px-6 py-4 text-[13px] font-bold text-[#660000] bg-[#660000]/5 border border-[#660000]/10 rounded-2xl transition-all active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Terminate Session
                </button>
            </form>
        </div>
    </div>
</nav>
