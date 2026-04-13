<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'AISAT Payroll') }} — High-Level Personnel Management</title>
        <meta name="description" content="Professional-grade Personnel, Attendance, and Payroll management for AISAT College.">

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
                            <div class="text-right hidden sm:block">
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
                        <!-- Session Messages -->
                        @if (session('success'))
                            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3 shadow-soft animate-in">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 flex-shrink-0">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                </div>
                                <span class="text-sm font-medium">{{ session('success') }}</span>
                            </div>
                        @endif

                        @if (session('error') || $errors->any())
                            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl space-y-2 shadow-soft">
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
