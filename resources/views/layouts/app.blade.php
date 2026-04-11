<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} | AISAT College</title>
        <meta name="description" content="AISAT College Personnel & Payroll Management System">

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

                    <div class="flex items-center gap-3">
                        <!-- Search Hint -->
                        <div class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-slate-100 text-slate-400 rounded-lg text-xs cursor-pointer hover:bg-slate-200/80 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            <span>Search...</span>
                            <kbd class="text-[10px] font-medium bg-white px-1.5 py-0.5 rounded border border-slate-200 text-slate-400 ml-4">⌘K</kbd>
                        </div>

                        <!-- Notification Bell -->
                        <button class="relative p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-indigo-500 rounded-full ring-2 ring-white"></span>
                        </button>

                        <!-- Divider -->
                        <div class="w-px h-6 bg-slate-200 hidden sm:block"></div>

                        <!-- User Info -->
                        <div class="flex items-center gap-3">
                            <div class="text-right hidden sm:block">
                                <div class="text-sm font-semibold text-slate-700 leading-tight">{{ Auth::user()->name }}</div>
                                <div class="text-[11px] text-slate-400 capitalize">{{ Auth::user()->role }}</div>
                            </div>
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-white text-xs font-bold shadow-sm">
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
