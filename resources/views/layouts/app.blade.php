<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'AISAT Payroll') }} — High-Level Personnel Management</title>
        <meta name="description" content="Professional-grade Personnel, Attendance, and Payroll management for AISAT College.">

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Serif+Text:ital@0;1&family=Inter:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full bg-[#FDFCF8] text-slate-800 antialiased institutional-gradient" 
          x-data="{ 
              sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false', 
              mobileSidebar: false,
              toggleSidebar() {
                  this.sidebarOpen = !this.sidebarOpen;
                  localStorage.setItem('sidebarOpen', this.sidebarOpen);
              }
          }"
          @toggle-sidebar.window="toggleSidebar()">
        <div class="flex h-full overflow-hidden relative">
            <!-- Sidebar Navigation -->
            @include('layouts.navigation')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0 overflow-hidden transition-all duration-300"
                 :class="sidebarOpen ? 'lg:pl-64' : 'lg:pl-0'">
                <!-- Top Header Bar -->
                <header class="h-16 flex items-center justify-between px-4 md:px-8 bg-white/70 backdrop-blur-md border-b border-[#101D33]/5 sticky top-0 z-30">
                    <div class="flex items-center gap-6">
                        <!-- Hamburger Button (Desktop) -->
                        <button @click="toggleSidebar()" class="hidden lg:flex p-2 text-[#101D33]/40 hover:text-[#101D33] hover:bg-[#101D33]/5 rounded-xl transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        
                        <!-- Hamburger Button (Mobile) -->
                        <button @click="mobileSidebar = true" class="lg:hidden p-2 text-[#101D33]/40 hover:text-[#101D33] hover:bg-[#101D33]/5 rounded-xl transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>

                        @isset($header)
                            <div class="flex items-center gap-2">
                                <h1 class="text-xl font-['DM_Serif_Display'] text-[#101D33] tracking-tight">{{ $header }}</h1>
                            </div>
                        @endisset
                    </div>

                    <div class="flex items-center gap-6">
                        <!-- Neural Alert Bell -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="relative p-2.5 text-[#101D33]/40 hover:text-[#101D33] hover:bg-[#101D33]/5 rounded-xl transition-all group">
                                <svg class="w-5 h-5 transition-transform group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <span class="absolute top-2 right-2 w-2 h-2 bg-[#660000] rounded-full border-2 border-white animate-pulse shadow-[0_0_8px_rgba(102,0,0,0.5)]"></span>
                                @endif
                            </button>

                            <!-- Dropdown -->
                            <div x-show="open" @click.away="open = false" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 class="absolute right-0 mt-3 w-80 bg-white rounded-[2rem] shadow-[0_40px_100px_rgba(16,29,51,0.15)] border border-[#101D33]/5 overflow-hidden z-50">
                                
                                <div class="px-6 py-4 bg-[#FDFCF8] border-b border-[#101D33]/5 flex items-center justify-between">
                                    <span class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em]">Neural Alerts</span>
                                    @if(auth()->user()->unreadNotifications->count() > 0)
                                        <a href="{{ route('notifications.readAll') }}" class="text-[8px] font-bold text-[#660000] uppercase tracking-widest hover:underline">Mark all read</a>
                                    @endif
                                </div>

                                <div class="max-h-96 overflow-y-auto custom-scrollbar">
                                    @forelse(auth()->user()->notifications->take(10) as $notification)
                                        <div class="px-6 py-4 border-b border-[#101D33]/5 hover:bg-slate-50 transition-colors cursor-pointer {{ $notification->unread() ? 'bg-[#D4AF37]/5' : '' }}">
                                            <div class="flex gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-[#101D33] text-[#D4AF37] flex items-center justify-center shrink-0">
                                                    @if(($notification->data['type'] ?? '') === 'payroll_finalized')
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    @else
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    @endif
                                                </div>
                                                <div class="flex-1">
                                                    <p class="text-[11px] font-bold text-[#101D33] leading-tight mb-0.5">{{ $notification->data['title'] ?? 'System Alert' }}</p>
                                                    <p class="text-[10px] text-slate-500 leading-tight">{{ $notification->data['message'] ?? '' }}</p>
                                                    <p class="text-[8px] text-[#D4AF37] font-black uppercase mt-1.5">{{ $notification->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="px-6 py-12 text-center">
                                            <p class="text-[10px] font-['DM_Serif_Text'] italic text-slate-300 uppercase tracking-widest">No active alerts</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- User Profile Action -->
                        <div class="flex items-center gap-4">
                            <div class="text-right hidden sm:block">
                                <div class="text-[10px] font-bold text-[#660000] uppercase tabular-nums tracking-[0.2em] leading-none mb-1.5">{{ Auth::user()->role }}</div>
                                <div class="text-sm font-['DM_Serif_Text'] text-[#101D33] leading-none">{{ Auth::user()->name }}</div>
                            </div>
                            <div class="w-9 h-9 rounded-xl bg-[#101D33] text-white flex items-center justify-center text-sm font-bold shadow-xl shadow-[#101D33]/20 hover:bg-[#101D33]/90 transition-all cursor-pointer border border-white/10 overflow-hidden relative group">
                                <div class="absolute inset-0 bg-gradient-to-tr from-[#660000]/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <span class="relative z-10">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto p-4 md:p-8">
                    <div class="max-w-[140rem] mx-auto space-y-6">
                        <!-- Premium Toast Notifications -->
                        <div x-data="{ 
                            show: {{ session('success') ? 'true' : 'false' }},
                            message: '{{ session('success') }}',
                            type: 'success',
                            init() {
                                if(this.show) setTimeout(() => this.show = false, 5000)
                            }
                        }"
                        x-show="show"
                        x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="translate-y-4 opacity-0 scale-95"
                        x-transition:enter-end="translate-y-0 opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="translate-y-0 opacity-100 scale-100"
                        x-transition:leave-end="translate-y-4 opacity-0 scale-95"
                        class="fixed bottom-10 right-10 z-[100] max-w-md w-full pointer-events-auto"
                        style="display: none;">
                            <div class="bg-[#101D33] text-white border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.3)] rounded-3xl p-5 flex items-center gap-5 overflow-hidden relative">
                                <div class="absolute top-0 right-0 w-24 h-24 bg-[#D4AF37]/10 blur-3xl -mr-12 -mt-12"></div>
                                <div class="w-12 h-12 rounded-2xl bg-[#D4AF37] flex items-center justify-center text-[#101D33] shadow-lg shadow-[#D4AF37]/20 shrink-0 relative z-10">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div class="flex-1 relative z-10">
                                    <h4 class="text-[10px] font-bold text-[#D4AF37] uppercase tracking-[0.2em] mb-1">Administrative Update</h4>
                                    <p class="text-[15px] font-medium leading-tight" x-text="message"></p>
                                </div>
                                <button @click="show = false" class="text-white/40 hover:text-white transition relative z-10 p-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Error Banner -->
                        @if (session('error') || $errors->any())
                            <div class="p-6 bg-[#660000]/5 border border-[#660000]/10 text-[#660000] rounded-3xl space-y-3 shadow-xl shadow-[#660000]/5 animate-in">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-[#660000] flex items-center justify-center text-white flex-shrink-0 shadow-lg shadow-[#660000]/20">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                    </div>
                                    <span class="text-base font-semibold">{{ session('error') ?? 'Action could not be completed.' }}</span>
                                </div>
                                @if($errors->any())
                                    <ul class="list-disc list-inside text-sm font-medium pl-14 text-[#660000]/70 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        @endif

                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
        @stack('scripts')
    </body>
</html>
