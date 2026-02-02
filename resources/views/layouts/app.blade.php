<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} | AISAT College</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Outfit', sans-serif; }
            [x-cloak] { display: none !important; }
            .glass-header {
                background: rgba(255, 255, 255, 0.8);
                backdrop-filter: blur(12px);
                border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            }
        </style>
    </head>
    <body class="h-full bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 antialiased overflow-hidden" 
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
                <header class="h-16 flex items-center justify-between px-4 md:px-8 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 z-30">
                    <div class="flex items-center gap-4">
                        <!-- Hamburger Button (Desktop) -->
                        <button @click="toggleSidebar()" class="hidden lg:flex p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        
                        <!-- Hamburger Button (Mobile) -->
                        <button @click="mobileSidebar = true" class="lg:hidden p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>

                        @isset($header)
                            <div class="text-lg md:text-xl font-bold tracking-tight text-slate-800 dark:text-slate-100 italic">
                                {{ $header }}
                            </div>
                        @endisset
                    </div>

                    <div class="flex items-center gap-4">
                        <!-- Search or other tools could go here -->
                        <div class="flex items-center gap-3 pl-4 border-l border-slate-200 dark:border-slate-800">
                            <div class="text-right hidden sm:block">
                                <div class="text-sm font-bold text-slate-900 dark:text-slate-100">{{ Auth::user()->name }}</div>
                                <div class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest">{{ Auth::user()->role }}</div>
                            </div>
                            <div class="w-10 h-10 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center text-slate-500 border border-slate-200 dark:border-slate-700">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path></svg>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-slate-50 dark:bg-slate-950">
                    <div class="max-w-7xl mx-auto space-y-6">
                        <!-- Session Messages -->
                        @if (session('success'))
                            <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-3xl flex items-center gap-3 shadow-sm border-l-4 border-l-emerald-500">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                <span class="text-sm font-bold">{{ session('success') }}</span>
                            </div>
                        @endif

                        @if (session('error') || $errors->any())
                            <div class="p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-3xl space-y-2 shadow-sm border-l-4 border-l-rose-500">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                    <span class="text-sm font-bold">{{ session('error') ?? 'Please correct the following errors:' }}</span>
                                </div>
                                @if($errors->any())
                                    <ul class="list-disc list-inside text-xs font-medium pl-8 opacity-80">
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
