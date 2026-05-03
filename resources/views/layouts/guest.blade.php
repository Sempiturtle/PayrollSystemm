<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'AISAT Payroll') }} | Institutional Registry</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

        <!-- Institutional Typography -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Serif+Text:ital@0;1&family=Inter:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Inter', sans-serif; }
            [x-cloak] { display: none !important; }
            .font-serif-display { font-family: 'DM+Serif+Display', serif; }
            
            /* Premium Shadow Stacking */
            .shadow-institutional {
                box-shadow: 
                    0 4px 6px -1px rgba(16, 29, 51, 0.05),
                    0 10px 15px -3px rgba(16, 29, 51, 0.1),
                    0 20px 25px -5px rgba(16, 29, 51, 0.05);
            }
        </style>
    </head>
    <body class="h-full bg-[#101D33] text-white antialiased overflow-hidden selection:bg-[#D4AF37] selection:text-[#101D33]">
        <div class="flex h-full min-h-screen relative z-10">
            
            <!-- Institutional Command Sidebar -->
            <div class="hidden lg:flex lg:w-5/12 relative overflow-hidden items-center justify-center p-12 border-r border-white/5 bg-[#101D33]">
                <!-- Background Architecture -->
                <div class="absolute inset-0 z-0">
                    <div class="absolute -top-32 -left-32 w-[700px] h-[700px] bg-[#660000]/10 rounded-full blur-[150px]"></div>
                    <div class="absolute -bottom-32 -right-32 w-[700px] h-[700px] bg-[#D4AF37]/5 rounded-full blur-[150px]"></div>
                    <!-- Subtle Grid -->
                    <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff05_1px,transparent_1px),linear-gradient(to_bottom,#ffffff05_1px,transparent_1px)] bg-[size:40px_40px]"></div>
                </div>

                <div class="relative z-10 max-w-lg">
                    <div class="flex items-center gap-4 mb-12 px-1">
                        <div class="w-16 h-16 rounded-[1.5rem] bg-white flex items-center justify-center p-2 shadow-2xl border border-white/10 transition-transform hover:scale-105">
                            <img src="{{ asset('images/logo.png') }}" alt="AISAT Logo" class="w-full h-full object-contain">
                        </div>
                        <div class="flex flex-col">
                            <span class="text-2xl font-['DM_Serif_Display'] tracking-tight text-white uppercase italic leading-none mb-1">AISAT <span class="text-[#D4AF37]">COLLEGE</span></span>
                            <span class="text-[9px] font-black text-white/30 uppercase tracking-[0.4em] leading-none">Administrative Sovereign</span>
                        </div>
                    </div>

                    <h1 class="text-6xl font-['DM_Serif_Display'] text-white leading-[0.9] mb-8 tracking-tight drop-shadow-2xl">
                        Personnel <br/> <span class="text-[#D4AF37]">Registry</span> <br/> Redefined.
                    </h1>

                    <p class="text-base text-white/40 font-['DM_Serif_Text'] italic leading-relaxed mb-12 max-w-sm pl-4 border-l-2 border-[#D4AF37]/30">
                        Handcrafted institutional instruments for high-fidelity attendance analytics and secure fiscal disbursement.
                    </p>
                    
                    <div class="grid grid-cols-2 gap-8 pt-12 border-t border-white/5">
                        <div class="space-y-1">
                            <div class="text-3xl font-['DM_Serif_Display'] text-white tracking-tight">2,410<span class="text-[#D4AF37]">+</span></div>
                            <div class="text-[9px] font-black text-white/20 uppercase tracking-[0.3em]">Synchronized Logs</div>
                        </div>
                        <div class="space-y-1">
                            <div class="text-3xl font-['DM_Serif_Display'] text-white tracking-tight">99.98<span class="text-[#D4AF37]">%</span></div>
                            <div class="text-[9px] font-black text-white/20 uppercase tracking-[0.3em]">Registry Uptime</div>
                        </div>
                    </div>
                </div>

                <!-- Administrative Meta-data Decoration -->
                <div class="absolute top-12 left-12 text-[10px] font-black text-white/5 rotate-90 origin-left tracking-[0.5em] uppercase pointer-events-none">INSTITUTIONAL-ASSET-SYNC-2026</div>
                <div class="absolute bottom-12 right-12 text-[10px] font-black text-white/5 rotate-90 origin-right tracking-[0.5em] uppercase pointer-events-none">NODE_AUTHORIZED // RM-221</div>
            </div>

            <!-- Auth Gateway Form -->
            <div class="w-full lg:w-7/12 flex items-center justify-center p-8 sm:p-12 md:p-16 overflow-y-auto bg-[#FDFCF8] text-[#101D33]">
                <div class="w-full max-w-md space-y-12 relative">
                    <!-- Navigation to Origin -->
                    <a href="/" class="absolute -top-24 left-0 flex items-center gap-4 text-[10px] font-black text-[#101D33]/40 hover:text-[#D4AF37] transition-all duration-500 uppercase tracking-[0.3em] group">
                        <div class="w-8 h-8 bg-[#101D33] text-[#D4AF37] rounded-xl flex items-center justify-center transition-all group-hover:bg-[#660000] group-hover:text-white shadow-xl">
                            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </div>
                        Back to Origin
                    </a>

                    <!-- Mobile Command Center Logo -->
                    <div class="lg:hidden mb-20 flex justify-center">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-[#101D33] rounded-2xl flex items-center justify-center p-2 shadow-2xl">
                                <img src="{{ asset('images/logo.png') }}" alt="AISAT Logo" class="w-full h-full object-contain">
                            </div>
                            <span class="text-2xl font-['DM_Serif_Display'] tracking-tight text-[#101D33] uppercase italic">AISAT</span>
                        </div>
                    </div>
                    
                    <div class="animate-in fade-in slide-in-from-bottom-8 duration-700">
                        {{ $slot }}
                    </div>

                    <div class="pt-16 text-center border-t border-[#101D33]/5 space-y-3">
                        <p class="text-[10px] font-black text-[#101D33]/20 uppercase tracking-[0.4em] italic">&copy; MMXXVI AISAT COLLEGE CENTRAL</p>
                        <p class="text-[9px] font-bold text-[#101D33]/10 tracking-[0.2em]">GOVERNANCE PROTOCOL: ACTIVE</p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
