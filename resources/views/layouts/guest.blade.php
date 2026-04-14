<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'AISAT Payroll') }} | AISAT College</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Inter', 'Outfit', sans-serif; }
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="h-full bg-slate-950 text-slate-100 antialiased overflow-hidden tracking-tight-sm mesh-gradient-bg">
        <div class="flex h-full min-h-screen relative z-10">
            <!-- Brand Sidebar (Elite Mesh) -->
            <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden items-center justify-center p-12 border-r border-white/5">
                <!-- Background Decoration (Mesh Gradients & Patterns) -->
                <div class="absolute inset-0 z-0">
                    <div class="absolute -top-32 -left-32 w-[600px] h-[600px] bg-indigo-600/30 rounded-full blur-[120px]"></div>
                    <div class="absolute -bottom-32 -right-32 w-[600px] h-[600px] bg-violet-600/30 rounded-full blur-[120px]"></div>
                    <div class="absolute inset-0 bg-slate-950/20 backdrop-blur-[2px]"></div>
                    <!-- Grid Pattern Overlay -->
                    <div class="absolute inset-0 grid-pattern pointer-events-none opacity-[0.2]"></div>
                </div>

                <div class="relative z-10 max-w-lg text-center lg:text-left">
                    <div class="flex items-center gap-4 mb-16 px-1">
                        <div class="rotate-12 w-16 h-16 bg-white rounded-3xl flex items-center justify-center p-2 shadow-2xl saas-shadow-pro">
                            <img src="{{ asset('images/logo.png') }}" alt="AISAT Logo" class="w-full h-full object-contain">
                        </div>
                        <span class="text-3xl font-black tracking-tighter-lg text-white uppercase italic text-glow">AISAT <span class="text-indigo-400">College</span></span>
                    </div>

                    <h1 class="text-7xl font-black text-white leading-[0.95] mb-8 tracking-tighter-lg drop-shadow-2xl">
                        Personnel <br/> <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-violet-400">Management</span> <br/> Redefined.
                    </h1>

                    <p class="text-xl text-slate-400 font-medium leading-relaxed mb-12 max-w-sm pl-1 border-l-2 border-indigo-500/50">
                        Handcrafted engineering tools for high-fidelity attendance analytics and secure disbursement.
                    </p>
                    
                    <div class="grid grid-cols-2 gap-8 pt-12 border-t border-white/5">
                        <div class="space-y-1">
                            <div class="text-3xl font-black text-white tracking-tighter-lg">2,410+</div>
                            <div class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest px-1">Successful Logs Sync</div>
                        </div>
                        <div class="space-y-1">
                            <div class="text-3xl font-black text-white tracking-tighter-lg">99.98<span class="text-indigo-500">%</span></div>
                            <div class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest px-1">Personnel Uptime</div>
                        </div>
                    </div>
                </div>

                <!-- Abstract Micro-detail Decoration -->
                <div class="absolute top-12 left-12 text-[9px] font-mono text-slate-800 rotate-90 origin-left tracking-[0.3em] uppercase pointer-events-none">X-PROJECT-A-SYNC-2026</div>
                <div class="absolute bottom-12 right-12 text-[9px] font-mono text-slate-800 rotate-90 origin-right tracking-[0.3em] uppercase pointer-events-none">COORD: 14.599 / 120.984</div>
            </div>

            <!-- Auth Form Area -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 md:p-24 overflow-y-auto bg-slate-900/10 backdrop-blur-3xl">
                <div class="w-full max-w-sm space-y-10 relative">
                    <!-- Back to Website Subtle Button -->
                    <a href="/" class="absolute -top-16 left-0 flex items-center gap-2.5 text-[9px] font-black text-slate-500 hover:text-indigo-400 transition-all duration-300 uppercase tracking-[0.2em] group">
                        <div class="w-6 h-6 border border-slate-800 rounded-lg flex items-center justify-center transition-all group-hover:scale-110 group-hover:border-indigo-500/30">
                            <svg class="w-3 h-3 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </div>
                        Back to Origin
                    </a>

                    <!-- Mobile Center Logo -->
                    <div class="lg:hidden mb-16 flex justify-center">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center p-1.5 shadow-lg">
                                <img src="{{ asset('images/logo.png') }}" alt="AISAT Logo" class="w-full h-full object-contain">
                            </div>
                            <span class="text-xl font-bold tracking-tight text-white uppercase italic">AISAT</span>
                        </div>
                    </div>
                    
                    {{ $slot }}

                    <div class="pt-12 text-center border-t border-white/5 space-y-2 opacity-50">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em]">&copy; MMXXVI AISAT COLLEGE CENTRAL</p>
                        <p class="text-[8px] font-mono text-slate-600 tracking-widest">SECURE RSA-4096 ENCRYPTION ACTIVE</p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
