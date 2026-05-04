<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AISAT College | Institutional Personnel & Fiscal Registry</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    <!-- Institutional Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Serif+Text:ital@0;1&family=Inter:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        @keyframes subtle-float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(1deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }
        .animate-institutional-float {
            animation: subtle-float 20s infinite ease-in-out;
        }
        .institutional-gradient {
            background: radial-gradient(circle at 50% 0%, #FDFCF8 0%, #F1F5F9 100%);
        }
        .text-gold { color: #D4AF37; }
        .bg-oxford { background-color: #101D33; }
        .bg-eton { background-color: #660000; }
        .border-gold { border-color: #D4AF37; }
        
        /* Premium Shadow Stacking */
        .shadow-premium {
            box-shadow: 
                0 4px 6px -1px rgba(16, 29, 51, 0.05),
                0 10px 15px -3px rgba(16, 29, 51, 0.1),
                0 20px 25px -5px rgba(16, 29, 51, 0.05);
        }
    </style>
</head>
<body class="institutional-gradient text-[#101D33] font-['Inter'] antialiased overflow-x-hidden selection:bg-[#D4AF37] selection:text-[#101D33]">
    <!-- Institutional Background Architecture -->
    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#101D3305_1px,transparent_1px),linear-gradient(to_bottom,#101D3305_1px,transparent_1px)] bg-[size:40px_40px] opacity-100"></div>
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-[#D4AF37]/5 rounded-full blur-[150px] animate-institutional-float"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-[#660000]/5 rounded-full blur-[150px] animate-institutional-float" style="animation-delay: -5s;"></div>
    </div>

    <!-- Administrative Navigation -->
    <nav class="fixed top-0 w-full z-50 px-8 py-6 flex justify-between items-center transition-all duration-500 bg-[#FDFCF8]/80 backdrop-blur-xl border-b border-[#101D33]/5">
        <div class="max-w-[140rem] mx-auto w-full flex justify-between items-center">
            <div class="flex items-center gap-4 group cursor-pointer">
                <div class="w-12 h-12 rounded-2xl bg-[#101D33] p-1.5 shadow-2xl shadow-[#101D33]/20 flex items-center justify-center border border-white/10 transition-transform group-hover:scale-105">
                    <img src="{{ asset('images/logo.png') }}" alt="AISAT Logo" class="w-full h-full object-contain">
                </div>
                <div class="flex flex-col">
                    <span class="text-xl font-['DM_Serif_Display'] tracking-tight text-[#101D33] leading-none mb-1">AISAT <span class="text-[#660000]">COLLEGE</span></span>
                    <span class="text-[9px] font-black text-[#D4AF37] uppercase tracking-[0.4em] leading-none opacity-80">Institutional Authority</span>
                </div>
            </div>
            
            <div class="flex items-center gap-8">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-8 py-3 bg-[#101D33] text-[#D4AF37] rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] shadow-xl hover:bg-[#660000] hover:text-white transition-all transform active:scale-95">Access Terminal</a>
                    @else
                        <div class="hidden md:flex items-center gap-8 mr-8">
                            <a href="#" class="text-[10px] font-black text-[#101D33]/40 uppercase tracking-[0.2em] hover:text-[#101D33] transition-colors">Faculty Support</a>
                            <a href="#" class="text-[10px] font-black text-[#101D33]/40 uppercase tracking-[0.2em] hover:text-[#101D33] transition-colors">Governance</a>
                        </div>
                        <a href="{{ route('login') }}" class="px-8 py-4 bg-[#101D33] text-[#D4AF37] rounded-2xl font-black text-[10px] uppercase tracking-[0.3em] shadow-2xl shadow-[#101D33]/20 hover:bg-[#660000] hover:text-white transition-all transform hover:-translate-y-0.5 active:scale-95">Launch Secure Portal</a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Main Content Architecture -->
    <main class="relative z-10 max-w-[140rem] mx-auto px-8 pt-48 pb-20 min-h-screen flex flex-col">
        
        <!-- Hero Section: The Academic Mandate -->
        <div class="text-left md:text-center max-w-5xl mx-auto mb-32 animate-in-up">
            <div class="inline-flex items-center gap-3 px-5 py-2 rounded-full bg-[#101D33] border border-white/10 mb-10 shadow-2xl">
                <span class="w-2 h-2 rounded-full bg-[#D4AF37] animate-pulse shadow-[0_0_10px_#D4AF37]"></span>
                <span class="text-[10px] font-black text-white uppercase tracking-[0.4em]">Official Personnel Engine // v2.4</span>
            </div>

            <h1 class="text-6xl md:text-8xl font-['DM_Serif_Display'] mb-10 leading-[1.05] tracking-tight text-[#101D33]">
                Prestigious Attendance. <br class="hidden md:block"/> Absolute <span class="text-[#660000] italic">Fiscal Precision.</span>
            </h1>
            
            <p class="text-lg text-slate-500 max-w-3xl mx-auto mb-16 leading-relaxed font-['DM_Serif_Text'] opacity-80">
                The authoritative standard for academic personnel management and statutory fiscal compliance. 
                Engineered exclusively to orchestrate administrative excellence within the AISAT College ecosystem.
            </p>

            <!-- Institutional Metrics Console -->
            <div class="flex flex-wrap items-center justify-start md:justify-center gap-6 md:gap-12 p-8 bg-white/40 border border-[#101D33]/5 rounded-[3rem] backdrop-blur-3xl shadow-premium">
                <div class="flex items-center gap-6 pr-12 border-r border-[#101D33]/10">
                    <div class="text-4xl font-['DM_Serif_Display'] text-[#101D33] leading-none">100<span class="text-[#D4AF37] text-2xl">%</span></div>
                    <div class="text-[9px] font-bold text-[#101D33]/40 uppercase tracking-[0.3em] text-left leading-relaxed">Automated<br/>Synchronization</div>
                </div>
                <div class="flex items-center gap-6 pr-12 border-r border-[#101D33]/10">
                    <div class="text-4xl font-['DM_Serif_Display'] text-[#101D33] leading-none">0.2<span class="text-[#D4AF37] text-2xl">s</span></div>
                    <div class="text-[9px] font-bold text-[#101D33]/40 uppercase tracking-[0.3em] text-left leading-relaxed">Identity<br/>Extraction</div>
                </div>
                <div class="flex items-center gap-6 pr-12 border-r hidden md:flex border-[#101D33]/10">
                    <div class="text-4xl font-['DM_Serif_Display'] text-[#101D33] leading-none">Zero</div>
                    <div class="text-[9px] font-bold text-[#101D33]/40 uppercase tracking-[0.3em] text-left leading-relaxed">Compliance<br/>Anomalies</div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-[#660000] text-white flex items-center justify-center shadow-lg shadow-[#660000]/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <div class="text-[10px] font-black text-[#660000] uppercase tracking-[0.4em]">Encrypted Ledger Status</div>
                </div>
            </div>
        </div>

        <!-- The Institutional Matrix: Core Modules -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-10 max-w-[140rem] mx-auto w-full flex-1">
            
            <!-- Module 1: Biometrics Architecture -->
            <div class="col-span-1 md:col-span-8 bg-white rounded-[3.5rem] border border-[#101D33]/5 p-12 hover:shadow-[0_50px_100px_rgba(16,29,51,0.08)] transition-all duration-700 relative overflow-hidden group shadow-premium">
                <div class="absolute top-[-20%] right-[-10%] w-[40%] h-[150%] bg-[#D4AF37]/5 -rotate-12 transform group-hover:rotate-0 transition-transform duration-1000"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 rounded-3xl bg-[#101D33] text-[#D4AF37] flex items-center justify-center mb-10 shadow-2xl border border-white/10">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 3v1m0 0c.851 0 1.673.1 2.459.29m0 0a10.016 10.016 0 011.094 9.71m3.44 2.04l-.054-.09a10.003 10.003 0 010-19.142m0 19.142A10.002 10.002 0 0120 13v-1"></path></svg>
                    </div>
                    <h3 class="text-3xl font-['DM_Serif_Display'] text-[#101D33] mb-6 tracking-tight">Identity Stream Integration</h3>
                    <p class="text-base text-slate-500 max-w-md leading-relaxed font-['DM_Serif_Text'] opacity-80 mb-10">Hardware-linked biometric authentication utilizing ESP32 terminal architecture. Absolute temporal fidelity for every presence log, processed in sub-second latency.</p>
                    <div class="flex gap-4">
                        <span class="px-5 py-2 bg-[#FDFCF8] border border-[#101D33]/5 text-[#101D33]/60 text-[9px] font-black rounded-xl uppercase tracking-widest shadow-sm">Neural Fingerprint</span>
                        <span class="px-5 py-2 bg-[#FDFCF8] border border-[#101D33]/5 text-[#101D33]/60 text-[9px] font-black rounded-xl uppercase tracking-widest shadow-sm">RFID Token</span>
                    </div>
                </div>
            </div>

            <!-- Module 2: Fiscal Authority -->
            <div class="col-span-1 md:col-span-4 bg-[#101D33] rounded-[3.5rem] p-12 hover:shadow-[0_50px_100px_rgba(102,0,0,0.2)] transition-all duration-700 relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-br from-[#660000]/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                <div class="relative z-10 h-full flex flex-col justify-between">
                    <div>
                        <div class="w-14 h-14 bg-white/5 rounded-2xl flex items-center justify-center text-[#D4AF37] mb-8 border border-white/10 backdrop-blur-md">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <h3 class="text-3xl font-['DM_Serif_Display'] text-white mb-6 tracking-tight">Institutional Governance</h3>
                        <p class="text-sm text-white/50 leading-relaxed font-['DM_Serif_Text']">A deterministic framework ensuring total data integrity across the entire personnel registry.</p>
                    </div>
                    <div class="mt-8 pt-8 border-t border-white/10">
                        <div class="text-[9px] font-black text-[#D4AF37] uppercase tracking-[0.4em]">Regulatory Grade Engine</div>
                    </div>
                </div>
            </div>

            <!-- Module 3: Fiscal Disbursement -->
            <div class="col-span-1 md:col-span-6 bg-white rounded-[3.5rem] border border-[#101D33]/5 p-12 hover:shadow-premium transition-all duration-500 flex items-center justify-between shadow-sm group">
                <div>
                    <h3 class="text-3xl font-['DM_Serif_Display'] text-[#101D33] mb-6 tracking-tight">Fiscal Ledger Hub</h3>
                    <p class="text-base text-slate-500 max-w-sm leading-relaxed font-['DM_Serif_Text'] opacity-80">Automated statutory computations for SSS, PhilHealth, and withholding tax streams. Zero-friction exports to institutional spreadsheets.</p>
                </div>
                <div class="w-24 h-24 rounded-[2.5rem] bg-[#FDFCF8] border border-[#101D33]/5 flex items-center justify-center text-[#D4AF37] shadow-inner hidden md:flex shrink-0 group-hover:scale-110 transition-transform duration-500">
                    <span class="text-4xl font-['DM_Serif_Display']">₱</span>
                </div>
            </div>

            <!-- Module 4: Temporal Scheduling -->
            <div class="col-span-1 md:col-span-6 bg-white rounded-[3.5rem] border border-[#101D33]/5 p-12 hover:shadow-premium transition-all duration-500 shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-3xl font-['DM_Serif_Display'] text-[#101D33] tracking-tight">Temporal Matrix</h3>
                    <span class="px-4 py-1.5 bg-[#660000]/5 text-[#660000] text-[9px] font-black rounded-xl uppercase tracking-[0.2em] border border-[#660000]/10 shadow-sm">Active Monitoring</span>
                </div>
                <p class="text-base text-slate-500 leading-relaxed font-['DM_Serif_Text'] opacity-80">Precise orchestration of complex faculty schedules, dynamic time-ins, and automated leave deduction logic strictly calibrated to AISAT College regulatory policy.</p>
            </div>
            
        </div>

        <!-- Institutional Footer -->
        <footer class="mt-32 pt-12 border-t border-[#101D33]/5 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="flex items-center gap-6">
                <div class="w-10 h-10 rounded-xl bg-[#101D33] p-1.5 flex items-center justify-center">
                    <img src="{{ asset('images/logo.png') }}" alt="AISAT Logo" class="w-full h-full object-contain grayscale opacity-50">
                </div>
                <p class="text-[10px] font-black text-[#101D33]/30 uppercase tracking-[0.4em]">&copy; {{ date('Y') }} AISAT College. Authorized Personnel Only.</p>
            </div>
            <div class="flex items-center gap-12">
                <div class="text-[10px] font-black text-[#101D33]/20 uppercase tracking-[0.3em] flex items-center gap-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    Central Infrastructure: Operational
                </div>
                <div class="text-[10px] font-black text-[#660000] uppercase tracking-[0.3em] hidden md:block">System Node // ID-771</div>
            </div>
        </footer>
    </main>
</body>
</html>
