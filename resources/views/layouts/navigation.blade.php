<nav x-show="sidebarOpen" x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="-translate-x-full"
     x-transition:enter-end="translate-x-0"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="translate-x-0"
     x-transition:leave-end="-translate-x-full"
     class="bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 w-64 min-h-screen hidden lg:block fixed left-0 top-0 z-40 shadow-xl lg:shadow-none">
    <!-- Sidebar Header -->
    <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-indigo-200 dark:shadow-none">A</div>
            <span class="text-xl font-bold tracking-tight text-slate-800 dark:text-slate-100">AISAT</span>
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="p-4 space-y-1.5 overflow-y-auto h-[calc(100vh-80px)]">
        <div class="px-4 py-3 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Main Menu</div>
        
        <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="home">
            Dashboard
        </x-sidebar-link>

        @if(Auth::user()->isAdmin())
            <div class="px-4 py-3 pt-6 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Management</div>
            
            <x-sidebar-link :href="route('employees.index')" :active="request()->routeIs('employees.*')" icon="users">
                Employees
            </x-sidebar-link>

            <x-sidebar-link :href="route('schedules.index')" :active="request()->routeIs('schedules.*')" icon="calendar">
                Schedules
            </x-sidebar-link>

            <x-sidebar-link :href="route('payrolls.index')" :active="request()->routeIs('payrolls.*')" icon="currency">
                Payroll
            </x-sidebar-link>
        @else
            <div class="px-4 py-3 pt-6 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Personal</div>
            
            <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="calendar">
                My Attendance
            </x-sidebar-link>

            <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="currency">
                My Payouts
            </x-sidebar-link>
        @endif

        <div class="px-4 py-3 pt-6 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Attendance</div>
        
        <x-sidebar-link :href="route('attendance.scanner')" :active="request()->routeIs('attendance.scanner')" icon="scanner">
            Live Scanner
        </x-sidebar-link>

        <div class="px-4 py-3 pt-6 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Account</div>
        <x-sidebar-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')" icon="settings">
            Settings
        </x-sidebar-link>

        <!-- Authentication -->
        <form method="POST" action="{{ route('logout') }}" class="pt-4">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-rose-600 rounded-xl transition group">
                <svg class="w-5 h-5 opacity-60 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Log Out
            </button>
        </form>
    </div>
</nav>

<!-- Mobile Overlay -->
<div x-show="mobileSidebar" 
     x-transition:opacity
     class="fixed inset-0 bg-slate-900/50 z-40 lg:hidden" 
     @click="mobileSidebar = false"></div>

<!-- Mobile Sidebar -->
<nav x-show="mobileSidebar" 
     x-transition:enter="transition ease-out duration-300 transform"
     x-transition:enter-start="-translate-x-full"
     x-transition:enter-end="translate-x-0"
     x-transition:leave="transition ease-in duration-300 transform"
     x-transition:leave-start="translate-x-0"
     x-transition:leave-end="-translate-x-full"
     class="fixed inset-y-0 left-0 w-72 bg-white dark:bg-slate-900 z-50 shadow-2xl lg:hidden border-r border-slate-100 dark:border-slate-800">
    <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-lg">A</div>
            <span class="font-bold text-slate-800 dark:text-slate-100 italic">AISAT Menu</span>
        </div>
        <button @click="mobileSidebar = false" class="p-2 text-slate-400 hover:text-rose-600 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    <div class="p-4 space-y-2 overflow-y-auto h-[calc(100vh-80px)]">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition">Dashboard</a>
        @if(Auth::user()->isAdmin())
            <div class="px-4 py-3 pt-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Management</div>
            <a href="{{ route('employees.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition">Employees</a>
            <a href="{{ route('schedules.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition">Schedules</a>
            <a href="{{ route('payrolls.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition">Payroll</a>
        @endif
        <div class="px-4 py-3 pt-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Attendance</div>
        <a href="{{ route('attendance.scanner') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition">Live Scanner</a>
        
        <hr class="my-4 border-slate-100 dark:border-slate-800">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left px-4 py-3 text-sm font-bold text-rose-600 hover:bg-rose-50 rounded-xl transition">Sign Out</button>
        </form>
    </div>
</nav>
