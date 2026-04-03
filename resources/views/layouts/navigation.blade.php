<nav x-show="sidebarOpen" x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="-translate-x-full"
     x-transition:enter-end="translate-x-0"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="translate-x-0"
     x-transition:leave-end="-translate-x-full"
     class="bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 w-64 min-h-screen hidden lg:block fixed left-0 top-0 z-40 saas-shadow lg:shadow-none">
    <!-- Sidebar Header -->
    <div class="h-14 px-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
            <div class="w-7 h-7 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-xs shadow-md shadow-indigo-200 dark:shadow-none transition-transform group-hover:rotate-12">A</div>
            <span class="text-sm font-bold tracking-tighter text-slate-800 dark:text-slate-100 uppercase italic">Aisat College</span>
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="p-3 space-y-4 overflow-y-auto h-[calc(100vh-56px)]">
        <div class="space-y-1">
            <div class="px-3 py-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Main Menu</div>
            <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="home">
                Dashboard
            </x-sidebar-link>
        </div>

        <div class="space-y-1">
            @if(Auth::user()->isAdmin())
                <div class="px-3 py-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Management</div>
                
                <x-sidebar-link :href="route('employees.index')" :active="request()->routeIs('employees.*')" icon="users">
                    Employees
                </x-sidebar-link>

                <x-sidebar-link :href="route('schedules.index')" :active="request()->routeIs('schedules.*')" icon="calendar">
                    Schedules
                </x-sidebar-link>

                <x-sidebar-link :href="route('payrolls.index')" :active="request()->routeIs('payrolls.*')" icon="currency">
                    Payroll Histoy
                </x-sidebar-link>
            @else
                <div class="px-3 py-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Personal</div>
                <x-sidebar-link :href="route('dashboard')" :active="false" icon="calendar">
                    My Attendance
                </x-sidebar-link>
                <x-sidebar-link :href="route('dashboard')" :active="false" icon="currency">
                    My Payouts
                </x-sidebar-link>
            @endif
        </div>

        <div class="space-y-1">
            <div class="px-3 py-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tools</div>
            <x-sidebar-link :href="route('attendance.scanner')" :active="request()->routeIs('attendance.scanner')" icon="scanner">
                Live Scanner
            </x-sidebar-link>
            <x-sidebar-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')" icon="settings">
                Account Settings
            </x-sidebar-link>
        </div>

        <!-- Authentication -->
        <div class="pt-4 mt-4 border-t border-slate-100 dark:border-slate-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-xs font-bold text-slate-400 hover:text-rose-600 transition-colors uppercase tracking-tight group">
                    <svg class="w-4 h-4 opacity-70 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Sign Out
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
         x-transition:opacity
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

    <!-- Sidebar Content -->
    <div x-show="mobileSidebar" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="relative w-80 max-w-[calc(100%-3rem)] min-h-screen bg-white dark:bg-slate-900 shadow-2xl">
        <div class="h-14 px-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-xs">A</div>
                <span class="text-sm font-bold tracking-tight text-slate-800 dark:text-slate-100 italic uppercase">Aisat College</span>
            </div>
            <button @click="mobileSidebar = false" class="p-2 text-slate-400 hover:text-rose-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-4 space-y-4">
            <!-- Mobile Links replicate the structure of Desktop if needed or simplified -->
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-sm font-bold text-indigo-600 bg-indigo-50/50 rounded-lg">Dashboard</a>
            @if(Auth::user()->isAdmin())
                <a href="{{ route('employees.index') }}" class="block px-3 py-2 text-sm font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg">Employees</a>
            @endif
            <a href="{{ route('attendance.scanner') }}" class="block px-3 py-2 text-sm font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg">Live Scanner</a>
            <hr class="border-slate-100 dark:border-slate-800 my-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 text-sm font-bold text-rose-600 uppercase tracking-widest">Sign Out</button>
            </form>
        </div>
    </div>
</nav>
