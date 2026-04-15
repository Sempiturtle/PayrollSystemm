<nav x-show="sidebarOpen" x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="-translate-x-full"
     x-transition:enter-end="translate-x-0"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="translate-x-0"
     x-transition:leave-end="-translate-x-full"
     class="sidebar-refined w-64 h-screen hidden lg:flex lg:flex-col fixed left-0 top-0 z-40">
    
    <!-- Sidebar Header -->
    <div class="h-16 px-6 border-b border-slate-100 flex items-center gap-3">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center p-1.5 shadow-sm transition-transform group-hover:scale-105 border border-slate-100">
                <img src="{{ asset('images/logo.png') }}" alt="AISAT Logo" class="w-full h-full object-contain">
            </div>
            <div>
                <span class="text-xs font-bold text-slate-900 tracking-tighter uppercase italic">AISAT <span class="text-indigo-600">Payroll</span></span>
            </div>
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 p-4 space-y-7 overflow-y-auto">
        {{-- Overview Section --}}
        <div class="space-y-1">
            <div class="px-3 py-2 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Intelligence</div>
            <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="home">
                Overview
            </x-sidebar-link>
        </div>

        {{-- Management Section --}}
        <div class="space-y-1">
            @if(Auth::user()->isAdmin())
                <div class="px-3 py-2 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Organization</div>
                
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

                <div class="h-4"></div>
                <div class="px-3 py-2 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Policies</div>

                <x-sidebar-link :href="route('leaves.index')" :active="request()->routeIs('leaves.*')" icon="calendar">
                    Leave Requests
                </x-sidebar-link>

                <x-sidebar-link :href="route('holidays.index')" :active="request()->routeIs('holidays.*')" icon="calendar">
                    Academic Calendar
                </x-sidebar-link>

                <x-sidebar-link :href="route('admin.discrepancies.index')" :active="request()->routeIs('admin.discrepancies.*')" icon="alert">
                    Correction Requests
                </x-sidebar-link>
            @else
                <div class="px-3 py-2 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Personal Workspace</div>
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
                <x-sidebar-link :href="route('profile.records')" :active="request()->routeIs('profile.records')" icon="user">
                    My Profile
                </x-sidebar-link>
            @endif
        </div>

        <div class="space-y-1">
            <div class="px-3 py-2 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Configurations</div>
            @if(Auth::user()->isAdmin())
                <x-sidebar-link :href="route('attendance.scanner')" :active="request()->routeIs('attendance.scanner')" icon="scanner">
                    Identity Terminal
                </x-sidebar-link>
                <x-sidebar-link :href="route('admins.index')" :active="request()->routeIs('admins.*')" icon="users">
                    Manage Admins
                </x-sidebar-link>
                <x-sidebar-link :href="route('settings.index')" :active="request()->routeIs('settings.*')" icon="settings">
                    System Configurations
                </x-sidebar-link>
            @endif
        </div>
    </div>

    <!-- Sidebar Footer -->
    <div class="p-4 border-t border-slate-50 mt-auto">
        <div class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50/50 border border-slate-100/50">
            <div class="w-9 h-9 rounded-xl bg-slate-900 text-white flex items-center justify-center text-xs font-bold flex-shrink-0 shadow-sm">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-[11px] font-bold text-slate-900 truncate tracking-tight">{{ Auth::user()->name }}</div>
                <div class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">{{ Auth::user()->role }} Account</div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                @csrf
                <button type="submit" class="p-2 text-slate-300 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-all" title="Sign out">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
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
         class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm"></div>

    <!-- Sidebar Content -->
    <div x-show="mobileSidebar" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="relative w-80 max-w-[calc(100%-3rem)] h-screen bg-white flex flex-col shadow-2xl">
        
        <div class="h-16 px-6 border-b border-slate-50 flex justify-between items-center">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center p-1 border border-slate-100">
                    <img src="{{ asset('images/logo.png') }}" alt="AISAT Logo" class="w-full h-full object-contain">
                </div>
                <span class="text-xs font-bold text-slate-800 tracking-tighter uppercase italic">AISAT <span class="text-indigo-600">Payroll</span></span>
            </div>
            <button @click="mobileSidebar = false" class="p-2 text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="flex-1 p-6 space-y-2 overflow-y-auto">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-white bg-slate-900 rounded-2xl shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </a>
            
            <div class="h-6"></div>
            <div class="px-4 py-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Workspace</div>

            @if(Auth::user()->isAdmin())
                @foreach([
                    'employees.index' => 'Personnel', 
                    'schedules.index' => 'Schedules', 
                    'payrolls.index' => 'Payroll', 
                    'leaves.index' => 'Leaves', 
                    'holidays.index' => 'Holidays',
                    'admin.discrepancies.index' => 'Correction Requests',
                    'admins.index' => 'Manage Admins',
                    'settings.index' => 'System Settings'
                ] as $route => $label)
                    <a href="{{ route($route) }}" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-2xl transition-all">
                        {{ $label }}
                    </a>
                @endforeach
            @else
                @foreach(['attendance.history' => 'My Attendance', 'payrolls.index' => 'My Payouts', 'leaves.index' => 'My Leaves'] as $route => $label)
                    <a href="{{ route($route) }}" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-2xl transition-all">
                        {{ $label }}
                    </a>
                @endforeach
            @endif

            <div class="pt-6 border-t border-slate-50 mt-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-bold text-rose-500 hover:bg-rose-50 rounded-2xl transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
