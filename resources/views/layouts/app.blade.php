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
                <header class="h-20 flex items-center justify-between px-4 md:px-12 bg-white/70 backdrop-blur-md border-b border-[#101D33]/5 sticky top-0 z-30">
                    <div class="flex items-center gap-6">
                        <!-- Hamburger Button (Desktop) -->
                        <button @click="toggleSidebar()" class="hidden lg:flex p-2.5 text-[#101D33]/40 hover:text-[#101D33] hover:bg-[#101D33]/5 rounded-xl transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        
                        <!-- Hamburger Button (Mobile) -->
                        <button @click="mobileSidebar = true" class="lg:hidden p-2.5 text-[#101D33]/40 hover:text-[#101D33] hover:bg-[#101D33]/5 rounded-xl transition-all">
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
                        <!-- User Profile Action -->
                        <div class="flex items-center gap-4">
                            <div class="text-right hidden sm:block">
                                <div class="text-[10px] font-bold text-[#660000] uppercase tabular-nums tracking-[0.2em] leading-none mb-1.5">{{ Auth::user()->role }}</div>
                                <div class="text-sm font-['DM_Serif_Text'] text-[#101D33] leading-none">{{ Auth::user()->name }}</div>
                            </div>
                            <div class="w-11 h-11 rounded-2xl bg-[#101D33] text-white flex items-center justify-center text-sm font-bold shadow-xl shadow-[#101D33]/20 hover:bg-[#101D33]/90 transition-all cursor-pointer border border-white/10 overflow-hidden relative group">
                                <div class="absolute inset-0 bg-gradient-to-tr from-[#660000]/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <span class="relative z-10">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto p-4 md:p-12">
                    <div class="max-w-[140rem] mx-auto space-y-8">
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
