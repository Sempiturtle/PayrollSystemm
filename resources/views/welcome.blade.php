<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AISAT College | Attendance & Payroll</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 15s infinite alternate ease-in-out;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 font-['Inter'] antialiased overflow-x-hidden selection:bg-indigo-500 selection:text-white">
    <!-- Ambient Core Background -->
    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#4f4f4f2e_1px,transparent_1px),linear-gradient(to_bottom,#4f4f4f2e_1px,transparent_1px)] bg-[size:24px_24px] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)] opacity-30"></div>
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-indigo-600/20 rounded-full blur-[120px] animate-blob"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-blue-600/20 rounded-full blur-[120px] animate-blob animation-delay-2000"></div>
    </div>

    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 px-6 py-4 flex justify-between items-center transition-all duration-300 bg-slate-950/60 backdrop-blur-2xl border-b border-white/5">
        <div class="max-w-[120rem] mx-auto w-full flex justify-between items-center">
            <div class="flex items-center gap-3 group cursor-pointer">
                <div class="w-8 h-8 rounded-lg bg-white p-0.5 shadow-md flex items-center justify-center">
                    <img src="{{ asset('images/logo.png') }}" alt="AISAT Logo" class="w-full h-full object-contain">
                </div>
                <div class="flex flex-col">
                    <span class="text-lg font-black tracking-tighter text-white uppercase italic leading-none">AISAT <small class="text-indigo-400 font-bold block text-[9px] tracking-widest mt-0.5">COLLEGE</small></span>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-5 py-2 bg-white text-slate-950 rounded-lg font-bold text-xs hover:bg-slate-200 transition shadow-[0_0_20px_-5px_rgba(255,255,255,0.4)]">Access Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2 bg-indigo-600 text-white rounded-lg font-bold text-xs hover:bg-indigo-500 transition shadow-[0_0_20px_-5px_rgba(79,70,229,0.4)]">Launch Portal</a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Main Content Container (Compact) -->
    <main class="relative z-10 max-w-[120rem] mx-auto px-6 pt-32 pb-16 min-h-screen flex flex-col">
        
        <!-- Hero Section -->
        <div class="text-left md:text-center max-w-4xl mx-auto mb-20 animate-in-up">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-indigo-500/10 border border-indigo-500/20 mb-6">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-pulse"></span>
                <span class="text-[10px] font-black text-indigo-300 uppercase tracking-widest">v2.0 Personnel Engine</span>
            </div>

            <h1 class="text-5xl md:text-6xl font-black mb-6 leading-[1.05] tracking-tighter text-white">
                Intelligent payroll, <br class="hidden md:block"/> synchronized via <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-blue-400">biometrics.</span>
            </h1>
            
            <p class="text-base text-slate-400 max-w-2xl mx-auto mb-10 leading-relaxed font-medium">
                The absolute standard for structural attendance reporting and statutory computations. Designed exclusively to automate administrative friction at AISAT.
            </p>

            <!-- Compact Inline Stats / Quick Tech Specs -->
            <div class="flex flex-wrap items-center justify-start md:justify-center gap-3 md:gap-8 p-4 bg-white/5 border border-white/10 rounded-2xl backdrop-blur-xl">
                <div class="flex items-center gap-3 pr-8 border-r border-white/10">
                    <div class="text-2xl font-black text-white leading-none">100<span class="text-indigo-400">%</span></div>
                    <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest text-left">Automated<br/>Sync</div>
                </div>
                <div class="flex items-center gap-3 pr-8 border-r border-white/10">
                    <div class="text-2xl font-black text-white leading-none">0.2<span class="text-indigo-400">s</span></div>
                    <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest text-left">Identity<br/>Scan</div>
                </div>
                <div class="flex items-center gap-3 pr-8 border-r hidden md:flex border-white/10">
                    <div class="text-2xl font-black text-white leading-none">0</div>
                    <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest text-left">Manual<br/>Errors</div>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    <div class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest">End-to-End Encrypted</div>
                </div>
            </div>
        </div>

        <!-- Bento Grid System Matrix -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 max-w-6xl mx-auto w-full flex-1">
            
            <!-- Bento Box 1: Biometrics (Spans 8 columns) -->
            <div class="col-span-1 md:col-span-8 bg-white/5 border border-white/10 rounded-3xl p-8 hover:bg-white/[0.07] transition-colors relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-6 opacity-0 group-hover:opacity-10 transition-opacity">
                    <svg class="w-32 h-32 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 3v1m0 0c.851 0 1.673.1 2.459.29m0 0a10.016 10.016 0 011.094 9.71m3.44 2.04l-.054-.09a10.003 10.003 0 010-19.142m0 19.142A10.002 10.002 0 0120 13v-1"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-white tracking-tight mb-3">Hardware-Linked Biometrics</h3>
                <p class="text-sm text-slate-400 max-w-md leading-relaxed font-medium mb-6">Attendance logs generated via ESP32 terminal integration instantly. Immutable timestamps processed exactly as they happen.</p>
                <div class="flex gap-2">
                    <span class="px-2 py-1 bg-white/10 text-slate-300 text-[10px] font-bold rounded uppercase tracking-widest">Fingerprint</span>
                    <span class="px-2 py-1 bg-white/10 text-slate-300 text-[10px] font-bold rounded uppercase tracking-widest">RFID Card</span>
                </div>
            </div>

            <!-- Bento Box 2: Secure (Spans 4 columns) -->
            <div class="col-span-1 md:col-span-4 bg-gradient-to-br from-indigo-600 to-blue-700 border border-indigo-500/50 rounded-3xl p-8 hover:shadow-[0_0_40px_-10px_rgba(79,70,229,0.5)] transition-all">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-white mb-6 backdrop-blur-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-white tracking-tight mb-3">Institutional Grade</h3>
                <p class="text-sm text-indigo-100 leading-relaxed font-medium">Built using Laravel architecture ensuring total data integrity.</p>
            </div>

            <!-- Bento Box 3: Payroll (Spans 6 columns) -->
            <div class="col-span-1 md:col-span-6 bg-white/5 border border-white/10 rounded-3xl p-8 hover:bg-white/[0.07] transition-colors flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-white tracking-tight mb-3">Tax & Statutory Engine</h3>
                    <p class="text-sm text-slate-400 max-w-sm leading-relaxed font-medium">Automated SSS, Pag-IBIG, PhilHealth, and WHT tracking. Exportable to official spreadsheets instantly.</p>
                </div>
                <div class="w-16 h-16 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center text-emerald-400 shadow-inner hidden md:flex shrink-0">
                    <span class="text-2xl font-black">₱</span>
                </div>
            </div>

            <!-- Bento Box 4: Architecture (Spans 6 columns) -->
            <div class="col-span-1 md:col-span-6 bg-white/5 border border-white/10 rounded-3xl p-8 hover:bg-white/[0.07] transition-colors">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-white tracking-tight">Academic Scheduling</h3>
                    <span class="px-2 py-1 bg-emerald-500/10 text-emerald-400 text-[10px] font-bold rounded uppercase tracking-widest border border-emerald-500/20">Matrix Active</span>
                </div>
                <p class="text-sm text-slate-400 leading-relaxed font-medium">Handle fragmented professor schedules, shifting time-ins, and automated leave deduction logic strictly adhering to AISAT policy.</p>
            </div>
            
        </div>

        <div class="flex items-center justify-between mt-16 pt-8 border-t border-white/10">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">&copy; {{ date('Y') }} AISAT College. Engineered Output.</p>
            <div class="text-[10px] font-bold text-slate-700 uppercase tracking-widest hidden md:block">System Node // Active</div>
        </div>
    </main>
</body>
</html>
