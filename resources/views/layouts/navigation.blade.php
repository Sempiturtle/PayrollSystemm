<nav x-show="sidebarOpen" x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="-translate-x-full"
     x-transition:enter-end="translate-x-0"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="translate-x-0"
     x-transition:leave-end="-translate-x-full"
     class="sidebar-shell w-64 min-h-screen hidden lg:flex lg:flex-col fixed left-0 top-0 z-40">
    <!-- Sidebar Header -->
    <div class="h-16 px-5 border-b border-white/[0.06] flex items-center gap-3">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-indigo-600/30 transition-transform group-hover:scale-105">A</div>
            <div>
                <span class="text-sm font-bold text-white tracking-tight">AISAT College</span>
                <div class="text-[10px] text-slate-500 font-medium -mt-0.5">Payroll System</div>
            </div>
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 p-3 space-y-6 overflow-y-auto">
        <div class="space-y-1">
            <div class="px-3 py-2 text-[10px] font-semibold text-slate-500 uppercase tracking-widest">Overview</div>
            <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="home">
                Dashboard
            </x-sidebar-link>
        </div>

        <div class="space-y-1">
            @if(Auth::user()->isAdmin())
                <div class="px-3 py-2 text-[10px] font-semibold text-slate-500 uppercase tracking-widest">Management</div>
                
                <x-sidebar-link :href="route('employees.index')" :active="request()->routeIs('employees.*')" icon="users">
                    Employees
                </x-sidebar-link>

                <x-sidebar-link :href="route('schedules.index')" :active="request()->routeIs('schedules.*')" icon="calendar">
                    Schedules
                </x-sidebar-link>

                <x-sidebar-link :href="route('payrolls.index')" :active="request()->routeIs('payrolls.*')" icon="currency">
                    Payroll History
                </x-sidebar-link>

                <x-sidebar-link :href="route('leaves.index')" :active="request()->routeIs('leaves.*')" icon="calendar">
                    Leave Approvals
                </x-sidebar-link>
            @else
                <div class="px-3 py-2 text-[10px] font-semibold text-slate-500 uppercase tracking-widest">Personal</div>
                <x-sidebar-link :href="route('attendance.history')" :active="request()->routeIs('attendance.history')" icon="calendar">
                    My Attendance
                </x-sidebar-link>
                <x-sidebar-link :href="route('payrolls.index')" :active="request()->routeIs('payrolls.index')" icon="currency">
                    My Payouts
                </x-sidebar-link>

                <x-sidebar-link :href="route('leaves.index')" :active="request()->routeIs('leaves.*')" icon="calendar">
                    My Leaves
                </x-sidebar-link>
            @endif
        </div>

        <div class="space-y-1">
            <div class="px-3 py-2 text-[10px] font-semibold text-slate-500 uppercase tracking-widest">Tools</div>
            @if(Auth::user()->isAdmin())
                <x-sidebar-link :href="route('attendance.scanner')" :active="request()->routeIs('attendance.scanner')" icon="scanner">
                    Live Scanner
                </x-sidebar-link>
            @endif
            <x-sidebar-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')" icon="settings">
                Settings
            </x-sidebar-link>
        </div>
    </div>

    <!-- Sidebar Footer: User + Logout -->
    <div class="p-3 border-t border-white/[0.06]">
        <div class="flex items-center gap-3 px-3 py-3 rounded-xl bg-white/[0.04]">
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-xs font-semibold text-white truncate">{{ Auth::user()->name }}</div>
                <div class="text-[10px] text-slate-500 capitalize">{{ Auth::user()->role }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-1.5 text-slate-500 hover:text-rose-400 rounded-lg transition-colors" title="Sign out">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </button>
            </form>
        </div>
    </div>
</nav>

<!-- Mobile Sidebar -->
<nav x-show="mobileSidebar" x-cloak
     class="fixed inset-0 z-50 lg:hidden">
    <!-- Overlay -->
    <div x-show="mobileSidebar" @click="mobileSidebar = false" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

    <!-- Sidebar Content -->
    <div x-show="mobileSidebar" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="relative w-80 max-w-[calc(100%-3rem)] min-h-screen sidebar-shell flex flex-col shadow-2xl">
        <div class="h-16 px-5 border-b border-white/[0.06] flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-sm">A</div>
                <div>
                    <span class="text-sm font-bold text-white tracking-tight">AISAT College</span>
                    <div class="text-[10px] text-slate-500 font-medium -mt-0.5">Payroll System</div>
                </div>
            </div>
            <button @click="mobileSidebar = false" class="p-2 text-slate-500 hover:text-white transition-colors rounded-lg hover:bg-white/[0.06]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="flex-1 p-4 space-y-2 overflow-y-auto">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold text-white bg-indigo-600/20 rounded-xl">
                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </a>
            @if(Auth::user()->isAdmin())
                <a href="{{ route('employees.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-slate-400 hover:text-white hover:bg-white/[0.06] rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Employees
                </a>
                <a href="{{ route('schedules.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-slate-400 hover:text-white hover:bg-white/[0.06] rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Schedules
                </a>
                <a href="{{ route('payrolls.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-slate-400 hover:text-white hover:bg-white/[0.06] rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Payroll History
                </a>
                <a href="{{ route('leaves.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-slate-400 hover:text-white hover:bg-white/[0.06] rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Leave Approvals
                </a>
            @else
                <a href="{{ route('leaves.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-slate-400 hover:text-white hover:bg-white/[0.06] rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    My Leaves
                </a>
            @endif
            @if(Auth::user()->isAdmin())
                <a href="{{ route('attendance.scanner') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-slate-400 hover:text-white hover:bg-white/[0.06] rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    Live Scanner
                </a>
            @endif
            <div class="pt-4 border-t border-white/[0.06] mt-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-rose-400 hover:text-rose-300 hover:bg-white/[0.04] rounded-xl transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
