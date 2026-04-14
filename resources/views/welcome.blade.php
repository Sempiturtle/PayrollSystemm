<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AISAT College | Attendance & Payroll Intelligence</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 font-['Inter'] antialiased overflow-x-hidden">
    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 px-6 py-4 flex justify-between items-center transition-all duration-500 bg-white/70 backdrop-blur-xl border-b border-slate-200/60 shadow-sm">
        <div class="max-w-7xl mx-auto w-full flex justify-between items-center">
            <div class="flex items-center gap-3 group cursor-pointer">
                <div class="w-12 h-12 rounded-xl bg-white p-1 shadow-md group-hover:scale-110 transition-transform">
                    <img src="{{ asset('images/logo.png') }}" alt="AISAT Logo" class="w-full h-full object-contain overflow-hidden">
                </div>
                <div class="flex flex-col">
                    <span class="text-xl font-bold tracking-tighter text-slate-900 uppercase italic leading-none">AISAT <small class="text-indigo-600 font-medium tracking-normal text-[10px] block">COLLEGE</small></span>
                </div>
            </div>
            
            <div class="flex items-center gap-8">
                <div class="hidden md:flex items-center gap-6">
                    <a href="#features" class="text-sm font-semibold text-slate-500 hover:text-indigo-600 transition-colors">Features</a>
                    <a href="#" class="text-sm font-semibold text-slate-500 hover:text-indigo-600 transition-colors">Resources</a>
                </div>
                <div>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-6 py-2.5 bg-slate-900 text-white rounded-full font-bold text-sm hover:bg-slate-800 transition shadow-lg shadow-slate-200">Go to Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="px-6 py-2.5 bg-indigo-600 text-white rounded-full font-bold text-sm hover:bg-indigo-500 transition shadow-lg shadow-indigo-200">Enter Portal</a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section (Premium Dark Mode) -->
    <section class="relative min-h-screen pt-40 pb-20 mesh-gradient-bg flex flex-col items-center justify-center overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0 z-0">
            <div class="absolute top-20 left-1/2 -translate-x-1/2 w-[800px] h-[800px] bg-indigo-600/20 rounded-full blur-[120px] opacity-50"></div>
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 mix-blend-overlay"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 relative z-10 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 backdrop-blur-md mb-8 animate-fade-in">
                <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                <span class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em]">Next-Gen Payroll Engine Active</span>
            </div>

            <h1 class="text-6xl md:text-8xl font-black mb-8 leading-[0.95] tracking-tighter-lg hero-gradient-text text-glow-indigo">
                Intelligence <br/> Behind <span class="text-indigo-400">Attendance.</span>
            </h1>
            
            <p class="text-lg md:text-xl text-slate-400 max-w-2xl mx-auto mb-12 leading-relaxed">
                Experience the transition from manual logs to biometric precision. AISAT's integrated engine automates time-tracking, leaves, and payroll in real-time.
            </p>

            <div class="flex flex-wrap justify-center gap-4 mb-24">
                <a href="{{ route('login') }}" class="px-10 py-5 bg-white text-slate-950 rounded-2xl font-black text-lg hover:scale-105 active:scale-95 transition-all shadow-[0_0_30px_-10px_rgba(255,255,255,0.3)]">
                    Launch Identity Terminal
                </a>
                <a href="#features" class="px-10 py-5 bg-slate-900 text-white border border-white/10 rounded-2xl font-bold text-lg hover:bg-slate-800 transition-all">
                    System Overview
                </a>
            </div>

            <!-- Product Reveal (Mockup) -->
            <div class="relative w-full max-w-5xl mx-auto perspective-1000">
                <div class="relative z-10 rounded-3xl p-2 bg-gradient-to-br from-white/10 to-white/5 border border-white/20 shadow-2xl floating-element">
                    <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&q=80&w=2426&ixlib=rb-4.0.3" alt="System Preview" class="rounded-2xl w-full h-auto shadow-2xl grayscale-[20%] Contrast-[110%]">
                    <div class="absolute inset-0 rounded-2xl bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                </div>
                <!-- Ambient Glow Behind Image -->
                <div class="absolute -inset-10 bg-indigo-600/30 rounded-[100px] blur-[100px] -z-10 animate-pulse"></div>
            </div>
        </div>
    </section>

    <!-- Tech Stack / Stats -->
    <section class="py-20 bg-white border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-12 text-center">
                <div class="space-y-1">
                    <div class="text-4xl font-black text-slate-900 tracking-tighter">100<span class="text-indigo-600">%</span></div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Real-time Sync</div>
                </div>
                <div class="space-y-1">
                    <div class="text-4xl font-black text-slate-900 tracking-tighter">2s</div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Auth Speed</div>
                </div>
                <div class="space-y-1">
                    <div class="text-4xl font-black text-slate-900 tracking-tighter">0</div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Manual Errors</div>
                </div>
                <div class="space-y-1">
                    <div class="text-4xl font-black text-slate-900 tracking-tighter">RSA</div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Encryption</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-32 bg-slate-50 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-24">
                <span class="text-indigo-600 font-black tracking-widest uppercase text-[11px] mb-4 block">Engine Core</span>
                <h2 class="text-5xl font-black text-slate-900 tracking-tighter mb-6">Designed for Excellence.</h2>
                <p class="text-slate-500 max-w-xl mx-auto font-medium">Built with professional Laravel architecture to ensure speed, security, and scalability for AISAT College.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="group p-8 bg-white rounded-[2.5rem] border border-slate-200/60 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
                    <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white mb-8 shadow-xl shadow-indigo-100 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 3v1m0 0c.851 0 1.673.1 2.459.29m0 0a10.016 10.016 0 011.094 9.71m3.44 2.04l-.054-.09a10.003 10.003 0 010-19.142m0 19.142A10.002 10.002 0 0120 13v-1"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-slate-900 tracking-tight">Biometric Core</h3>
                    <p class="text-slate-500 text-sm leading-relaxed font-medium">Advanced fingerprint and RFID integration ensuring that attendance is verified, secure, and irrefutable.</p>
                </div>

                <!-- Card 2 -->
                <div class="group p-8 bg-white rounded-[2.5rem] border border-slate-200/60 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
                    <div class="w-14 h-14 bg-slate-900 rounded-2xl flex items-center justify-center text-white mb-8 shadow-xl shadow-slate-100 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-slate-900 tracking-tight">Payroll Engine</h3>
                    <p class="text-slate-500 text-sm leading-relaxed font-medium">Automated computation based on precise attendance data, including late deductions, bonuses, and tax logic.</p>
                </div>

                <!-- Card 3 -->
                <div class="group p-8 bg-white rounded-[2.5rem] border border-slate-200/60 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
                    <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-8 shadow-xl shadow-indigo-50 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-slate-900 tracking-tight">Schedule Matrix</h3>
                    <p class="text-slate-500 text-sm leading-relaxed font-medium">Dynamic Excel-based schedule importing with per-professor customization to match unique campus workflows.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-950 pt-24 pb-12 px-6 overflow-hidden relative">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-start gap-12 mb-20">
                <div class="max-w-sm">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-xl bg-white p-1">
                            <img src="{{ asset('images/logo.png') }}" alt="AISAT Logo" class="w-full h-full object-contain">
                        </div>
                        <span class="text-2xl font-black tracking-tighter text-white italic uppercase">AISAT <small class="text-indigo-400 font-bold block text-[10px]">COLLEGE</small></span>
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed font-medium">Leading the digital transformation of educational administration through secure biometric intelligence and automated payroll systems.</p>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-2 gap-16">
                    <div>
                        <h4 class="text-white font-bold text-xs uppercase tracking-widest mb-6">Platform</h4>
                        <ul class="space-y-4 text-slate-500 text-sm font-medium">
                            <li><a href="#" class="hover:text-indigo-400 transition">Attendance Terminal</a></li>
                            <li><a href="#" class="hover:text-indigo-400 transition">Payroll Matrix</a></li>
                            <li><a href="#" class="hover:text-indigo-400 transition">Leave Engine</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-bold text-xs uppercase tracking-widest mb-6">Support</h4>
                        <ul class="space-y-4 text-slate-500 text-sm font-medium">
                            <li><a href="#" class="hover:text-indigo-400 transition">IT Department</a></li>
                            <li><a href="#" class="hover:text-indigo-400 transition">Admin Portal</a></li>
                            <li><a href="#" class="hover:text-indigo-400 transition">Security Protocol</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between gap-6">
                <p class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">&copy; {{ date('Y') }} AISAT College Dasmariñas Campus. Engineered for Excellence.</p>
                <div class="flex gap-8 text-[10px] font-bold text-slate-600 uppercase tracking-widest">
                    <a href="#" class="hover:text-indigo-400 transition">Privacy Cipher</a>
                    <a href="#" class="hover:text-indigo-400 transition">Terms of Service</a>
                </div>
            </div>
        </div>
        <!-- Subtle Glow in Footer -->
        <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-indigo-600/10 rounded-full blur-[100px]"></div>
    </footer>
</body>
</html>
