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
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full bg-slate-50 text-slate-800 antialiased" 
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
                <header class="h-16 flex items-center justify-between px-4 md:px-8 glass-header sticky top-0 z-30">
                    <div class="flex items-center gap-4">
                        <!-- Hamburger Button (Desktop) -->
                        <button @click="toggleSidebar()" class="hidden lg:flex p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        
                        <!-- Hamburger Button (Mobile) -->
                        <button @click="mobileSidebar = true" class="lg:hidden p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>

                        @isset($header)
                            <div class="flex items-center gap-2">
                                <h1 class="text-base font-semibold text-slate-800 tracking-tight">{{ $header }}</h1>
                            </div>
                        @endisset
                    </div>

                    <div class="flex items-center gap-4">
                        <!-- User Profile Action -->
                        <div class="flex items-center gap-3">
                            <div class="text-right">
                                <div class="text-[11px] font-bold text-slate-400 capitalize tabular-nums tracking-widest leading-none mb-1">{{ Auth::user()->role }} Account</div>
                                <div class="text-sm font-bold text-slate-900 tracking-tight leading-none">{{ Auth::user()->name }}</div>
                            </div>
                            <div class="w-9 h-9 rounded-xl bg-slate-900 text-white flex items-center justify-center text-xs font-bold shadow-md hover:bg-slate-800 transition-colors cursor-pointer border border-slate-800">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-slate-50">
                    <div class="max-w-7xl mx-auto space-y-6">
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
                        x-transition:enter="transition ease-out duration-300 transform"
                        x-transition:enter-start="translate-y-[-20px] opacity-0"
                        x-transition:enter-end="translate-y-0 opacity-100"
                        x-transition:leave="transition ease-in duration-200 transform"
                        x-transition:leave-start="translate-y-0 opacity-100"
                        x-transition:leave-end="translate-y-[-20px] opacity-0"
                        class="fixed top-6 right-6 z-[100] max-w-sm w-full pointer-events-auto"
                        style="display: none;">
                            <div class="bg-white/90 backdrop-blur-xl border border-emerald-100 shadow-2xl shadow-emerald-200/50 rounded-2xl p-4 flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-200 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-0.5">Notification</h4>
                                    <p class="text-sm font-bold text-slate-800" x-text="message"></p>
                                </div>
                                <button @click="show = false" class="text-slate-300 hover:text-slate-500 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Error Banner (Kept static for priority) -->
                        @if (session('error') || $errors->any())
                            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl space-y-2 shadow-soft animate-in">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center text-rose-600 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                    </div>
                                    <span class="text-sm font-medium">{{ session('error') ?? 'Action could not be completed.' }}</span>
                                </div>
                                @if($errors->any())
                                    <ul class="list-disc list-inside text-xs font-medium pl-11 text-rose-600/80 space-y-1">
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
    </body>
</html>
