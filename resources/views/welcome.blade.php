<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AISAT College — Intelligent Payroll & Attendance Platform</title>
    <meta name="description" content="Enterprise-grade biometric attendance and payroll automation built exclusively for AISAT College Dasmariñas. Real-time sync, statutory compliance, zero manual errors.">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ── Reset the global CSS overrides from app.css that break the dark landing page ── */
        body.landing-page {
            font-family: 'Inter', system-ui, -apple-system, sans-serif !important;
            background-color: #020617 !important;
            background-image: none !important;
            color: #e2e8f0 !important;
            font-size: 16px !important;
            letter-spacing: -0.01em;
        }
        body.landing-page h1, body.landing-page h2, body.landing-page h3,
        body.landing-page h4, body.landing-page h5, body.landing-page h6 {
            font-family: 'Outfit', 'Inter', system-ui, sans-serif !important;
            color: #ffffff !important;
            font-style: normal !important;
        }
        body.landing-page .italic {
            font-style: italic !important;
        }
        /* Override the aggressive p-6 etc resets from app.css utilities */
        body.landing-page .lp-p-6 { padding: 1.5rem !important; }
        body.landing-page .lp-p-8 { padding: 2rem !important; }
        body.landing-page .lp-p-10 { padding: 2.5rem !important; }
        body.landing-page .lp-p-12 { padding: 3rem !important; }
        body.landing-page .lp-px-6 { padding-left: 1.5rem !important; padding-right: 1.5rem !important; }
        body.landing-page .lp-px-8 { padding-left: 2rem !important; padding-right: 2rem !important; }
        body.landing-page .lp-py-3 { padding-top: 0.75rem !important; padding-bottom: 0.75rem !important; }
        body.landing-page .lp-py-4 { padding-top: 1rem !important; padding-bottom: 1rem !important; }
        body.landing-page .lp-py-5 { padding-top: 1.25rem !important; padding-bottom: 1.25rem !important; }
        body.landing-page .lp-py-16 { padding-top: 4rem !important; padding-bottom: 4rem !important; }
        body.landing-page .lp-py-24 { padding-top: 6rem !important; padding-bottom: 6rem !important; }
        body.landing-page .lp-py-32 { padding-top: 8rem !important; padding-bottom: 8rem !important; }
        body.landing-page .lp-pt-32 { padding-top: 8rem !important; }
        body.landing-page .lp-pb-16 { padding-bottom: 4rem !important; }
        body.landing-page .lp-mb-6 { margin-bottom: 1.5rem !important; }
        body.landing-page .lp-mb-4 { margin-bottom: 1rem !important; }

        /* ── Override card/table styles from app.css for landing cards ── */
        body.landing-page .lp-card {
            background: rgba(255,255,255,0.03) !important;
            backdrop-filter: blur(16px) !important;
            border: 1px solid rgba(255,255,255,0.06) !important;
            border-radius: 1.5rem !important;
            box-shadow: 0 4px 30px rgba(0,0,0,0.15) !important;
            overflow: visible !important;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
            transform: none !important;
        }
        body.landing-page .lp-card:hover {
            background: rgba(255,255,255,0.06) !important;
            border-color: rgba(99, 102, 241, 0.2) !important;
            box-shadow: 0 8px 40px rgba(79, 70, 229, 0.08) !important;
            transform: translateY(-4px) !important;
        }
        body.landing-page .lp-card-accent {
            background: linear-gradient(135deg, rgba(79,70,229,0.15) 0%, rgba(99,102,241,0.08) 100%) !important;
            border: 1px solid rgba(99,102,241,0.25) !important;
            border-radius: 1.5rem !important;
            box-shadow: 0 4px 30px rgba(79,70,229,0.1) !important;
            overflow: visible !important;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
            transform: none !important;
        }
        body.landing-page .lp-card-accent:hover {
            background: linear-gradient(135deg, rgba(79,70,229,0.22) 0%, rgba(99,102,241,0.12) 100%) !important;
            border-color: rgba(99,102,241,0.4) !important;
            box-shadow: 0 12px 50px rgba(79,70,229,0.18) !important;
            transform: translateY(-4px) !important;
        }

        /* ── Animations ── */
        @keyframes blob {
            0%   { transform: translate(0, 0) scale(1); }
            33%  { transform: translate(30px, -50px) scale(1.1); }
            66%  { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0, 0) scale(1); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50%      { transform: translateY(-12px); }
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(79,70,229,0.3); }
            50%      { box-shadow: 0 0 40px rgba(79,70,229,0.6); }
        }
        @keyframes shimmer {
            0%   { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        @keyframes count-up {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes gradient-x {
            0%, 100% { background-position: 0% 50%; }
            50%      { background-position: 100% 50%; }
        }
        @keyframes slide-in-up {
            from { opacity: 0; transform: translateY(40px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fade-in {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        @keyframes terminal-cursor {
            0%, 100% { opacity: 1; }
            50%      { opacity: 0; }
        }
        @keyframes typing {
            from { width: 0; }
            to   { width: 100%; }
        }
        @keyframes orbit {
            from { transform: rotate(0deg) translateX(140px) rotate(0deg); }
            to   { transform: rotate(360deg) translateX(140px) rotate(-360deg); }
        }

        .animate-blob       { animation: blob 15s infinite alternate ease-in-out; }
        .animate-float       { animation: float 6s ease-in-out infinite; }
        .animate-pulse-glow  { animation: pulse-glow 3s ease-in-out infinite; }
        .animate-gradient-x  { animation: gradient-x 6s ease infinite; background-size: 200% 200%; }
        .animate-shimmer     { animation: shimmer 3s ease-in-out infinite; background-size: 200% 100%; }
        .animate-orbit       { animation: orbit 20s linear infinite; }
        .animation-delay-2s  { animation-delay: 2s; }
        .animation-delay-4s  { animation-delay: 4s; }

        /* Scroll-triggered reveal */
        .reveal {
            opacity: 0;
            transform: translateY(32px);
            transition: opacity 0.7s cubic-bezier(0.4, 0, 0.2, 1), transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }
        .reveal-delay-3 { transition-delay: 0.3s; }
        .reveal-delay-4 { transition-delay: 0.4s; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #020617; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #334155; }

        /* Grid pattern */
        .grid-pattern {
            background-image:
                linear-gradient(to right, rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 48px 48px;
        }

        /* Terminal mock */
        .terminal-line::after {
            content: '▋';
            animation: terminal-cursor 1s step-end infinite;
            color: #6366f1;
        }
    </style>
</head>

<body class="landing-page antialiased overflow-x-hidden selection:bg-indigo-500/30 selection:text-white">

    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- AMBIENT BACKGROUND --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <div class="fixed inset-0 z-0 pointer-events-none" aria-hidden="true">
        <div class="grid-pattern absolute inset-0 opacity-40" style="mask-image: radial-gradient(ellipse 80% 60% at 50% 0%, #000 70%, transparent 100%);"></div>
        <div class="absolute top-[-20%] left-[-15%] w-[50%] h-[50%] bg-indigo-600/15 rounded-full blur-[140px] animate-blob"></div>
        <div class="absolute top-[30%] right-[-15%] w-[40%] h-[40%] bg-violet-600/10 rounded-full blur-[140px] animate-blob animation-delay-2s"></div>
        <div class="absolute bottom-[-10%] left-[20%] w-[45%] h-[40%] bg-blue-600/10 rounded-full blur-[140px] animate-blob animation-delay-4s"></div>
    </div>


    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- § 1 — NAVIGATION --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <nav id="main-nav" class="fixed top-0 w-full z-50 transition-all duration-500" style="background: transparent; border-bottom: 1px solid transparent;">
        <div class="max-w-7xl mx-auto w-full flex justify-between items-center" style="padding: 1.25rem 1.5rem;">
            {{-- Logo --}}
            <a href="/" class="flex items-center gap-3 group" id="nav-logo">
                <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-md border border-white/10 flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-300" style="padding: 0.35rem;">
                    <img src="{{ asset('images/logo.png') }}" alt="AISAT College Dasmariñas" class="w-full h-full object-contain">
                </div>
                <div class="flex flex-col">
                    <span class="text-lg font-black tracking-tighter text-white leading-none" style="font-family: 'Outfit', sans-serif;">AISAT</span>
                    <span class="text-[9px] font-bold text-indigo-400 uppercase tracking-[0.2em] leading-none mt-0.5">Personnel Platform</span>
                </div>
            </a>

            {{-- Nav Links --}}
            <div class="hidden md:flex items-center gap-8">
                <a href="#features" class="text-sm font-medium text-slate-400 hover:text-white transition-colors duration-200">Features</a>
                <a href="#platform" class="text-sm font-medium text-slate-400 hover:text-white transition-colors duration-200">Platform</a>
                <a href="#proof" class="text-sm font-medium text-slate-400 hover:text-white transition-colors duration-200">Results</a>
            </div>

            {{-- CTA --}}
            <div class="flex items-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" id="nav-cta-dashboard"
                           class="inline-flex items-center gap-2 text-sm font-semibold text-white rounded-xl transition-all duration-300 hover:shadow-lg active:scale-[0.97]"
                           style="padding: 0.625rem 1.25rem; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); box-shadow: 0 4px 14px rgba(99,102,241,0.3);">
                            <span>Dashboard</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" id="nav-cta-login"
                           class="inline-flex items-center gap-2 text-sm font-semibold text-white rounded-xl transition-all duration-300 hover:shadow-lg active:scale-[0.97]"
                           style="padding: 0.625rem 1.25rem; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); box-shadow: 0 4px 14px rgba(99,102,241,0.3);">
                            <span>Sign In</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>


    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- § 2 — HERO --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <section id="hero" class="relative z-10 min-h-screen flex items-center justify-center lp-px-6">
        <div class="max-w-5xl mx-auto text-center lp-pt-32 lp-pb-16">
            {{-- Version Chip --}}
            <div class="reveal inline-flex items-center gap-2.5 rounded-full border border-indigo-500/20 bg-indigo-500/5 backdrop-blur-sm mb-8" style="padding: 0.5rem 1rem;">
                <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                <span class="text-xs font-bold text-indigo-300 uppercase tracking-widest">v2.0 Personnel Engine</span>
            </div>

            {{-- Headline --}}
            <h1 class="reveal reveal-delay-1 text-5xl sm:text-6xl lg:text-7xl font-black leading-[1.05] tracking-tighter text-white" style="margin-bottom: 1.5rem; font-family: 'Outfit', sans-serif !important;">
                Payroll, automated<br class="hidden sm:block"/>
                through <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-violet-400 to-blue-400 animate-gradient-x">biometrics.</span>
            </h1>

            {{-- Sub-headline --}}
            <p class="reveal reveal-delay-2 text-lg sm:text-xl text-slate-400 max-w-2xl mx-auto leading-relaxed font-medium" style="margin-bottom: 2.5rem;">
                The complete attendance and payroll infrastructure for AISAT College. From fingerprint scan to payslip — zero manual intervention, zero computation errors.
            </p>

            {{-- CTA Buttons --}}
            <div class="reveal reveal-delay-3 flex flex-col sm:flex-row items-center justify-center gap-4" style="margin-bottom: 4rem;">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" id="hero-cta-primary"
                           class="group inline-flex items-center gap-2.5 text-base font-bold text-white rounded-2xl transition-all duration-300 active:scale-[0.97]"
                           style="padding: 1rem 2rem; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); box-shadow: 0 8px 30px rgba(99,102,241,0.35);">
                            Open Dashboard
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" id="hero-cta-primary"
                           class="group inline-flex items-center gap-2.5 text-base font-bold text-white rounded-2xl transition-all duration-300 active:scale-[0.97]"
                           style="padding: 1rem 2rem; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); box-shadow: 0 8px 30px rgba(99,102,241,0.35);">
                            Launch Portal
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </a>
                    @endauth
                @endif
                <a href="#features" id="hero-cta-secondary"
                   class="inline-flex items-center gap-2 text-base font-semibold text-slate-300 rounded-2xl border border-white/10 bg-white/5 backdrop-blur-sm hover:bg-white/10 hover:border-white/20 transition-all duration-300"
                   style="padding: 1rem 2rem;">
                    Explore Features
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </a>
            </div>

            {{-- Metrics Bar --}}
            <div class="reveal reveal-delay-4 flex flex-wrap items-center justify-center gap-0 rounded-2xl border border-white/10 bg-white/[0.03] backdrop-blur-xl overflow-hidden max-w-3xl mx-auto">
                <div class="flex-1 min-w-[120px] flex flex-col items-center border-r border-white/10" style="padding: 1.25rem 1rem;">
                    <span class="text-3xl font-black text-white tracking-tight leading-none" style="font-family: 'Outfit', sans-serif;">100<span class="text-indigo-400">%</span></span>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-2">Auto Sync</span>
                </div>
                <div class="flex-1 min-w-[120px] flex flex-col items-center border-r border-white/10" style="padding: 1.25rem 1rem;">
                    <span class="text-3xl font-black text-white tracking-tight leading-none" style="font-family: 'Outfit', sans-serif;">0.2<span class="text-indigo-400">s</span></span>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-2">ID Scan</span>
                </div>
                <div class="flex-1 min-w-[120px] flex flex-col items-center border-r border-white/10 hidden sm:flex" style="padding: 1.25rem 1rem;">
                    <span class="text-3xl font-black text-white tracking-tight leading-none" style="font-family: 'Outfit', sans-serif;">0</span>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-2">Manual Errors</span>
                </div>
                <div class="flex-1 min-w-[120px] flex items-center justify-center gap-2" style="padding: 1.25rem 1rem;">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    <span class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest">Encrypted</span>
                </div>
            </div>
        </div>

        {{-- Scroll Indicator --}}
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 opacity-60">
            <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Scroll</span>
            <div class="w-5 h-8 rounded-full border border-slate-600 flex items-start justify-center" style="padding-top: 0.375rem;">
                <div class="w-1 h-2 rounded-full bg-slate-400 animate-bounce"></div>
            </div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- § 3 — FEATURES BENTO GRID --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <section id="features" class="relative z-10 lp-py-24 lp-px-6">
        <div class="max-w-6xl mx-auto">
            {{-- Section Label --}}
            <div class="text-center" style="margin-bottom: 4rem;">
                <span class="reveal inline-block text-[10px] font-black text-indigo-400 uppercase tracking-[0.25em] lp-mb-4">Core Infrastructure</span>
                <h2 class="reveal reveal-delay-1 text-3xl sm:text-4xl lg:text-5xl font-black tracking-tighter text-white" style="font-family: 'Outfit', sans-serif !important;">
                    Everything, handled.
                </h2>
                <p class="reveal reveal-delay-2 text-base text-slate-400 max-w-xl mx-auto mt-4 leading-relaxed">
                    Built ground-up for academic payroll complexity — every edge case, every statutory rule, every professor's shifting schedule.
                </p>
            </div>

            {{-- Bento Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-12 gap-5">

                {{-- Feature 1: Biometrics — large card --}}
                <div class="reveal md:col-span-7 lp-card lp-p-8 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 opacity-[0.04] group-hover:opacity-[0.08] transition-opacity duration-500" style="padding: 2rem;">
                        <svg class="w-40 h-40 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 3v1m0 0c.851 0 1.673.1 2.459.29m0 0a10.016 10.016 0 011.094 9.71m3.44 2.04l-.054-.09a10.003 10.003 0 010-19.142m0 19.142A10.002 10.002 0 0120 13v-1"></path></svg>
                    </div>
                    <div class="flex items-center gap-3 lp-mb-4">
                        <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center" style="padding: 0 !important;">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 3v1m0 0c.851 0 1.673.1 2.459.29"></path></svg>
                        </div>
                        <span class="text-[10px] font-black text-indigo-400/70 uppercase tracking-widest">Hardware Integration</span>
                    </div>
                    <h3 class="text-2xl font-bold tracking-tight text-white lp-mb-4" style="font-family: 'Outfit', sans-serif !important;">Hardware-Linked Biometrics</h3>
                    <p class="text-sm text-slate-400 max-w-md leading-relaxed font-medium" style="margin-bottom: 1.5rem;">
                        Attendance logs generated via ESP32 terminal integration in real-time. Immutable timestamps processed exactly as they happen — no manual entries, no tampering.
                    </p>
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-lg bg-white/5 border border-white/10 text-slate-300 text-[10px] font-bold uppercase tracking-widest" style="padding: 0.375rem 0.75rem !important;">
                            <svg class="w-3 h-3 text-indigo-400" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="4"/></svg>
                            Fingerprint
                        </span>
                        <span class="inline-flex items-center gap-1.5 rounded-lg bg-white/5 border border-white/10 text-slate-300 text-[10px] font-bold uppercase tracking-widest" style="padding: 0.375rem 0.75rem !important;">
                            <svg class="w-3 h-3 text-violet-400" fill="currentColor" viewBox="0 0 20 20"><rect x="4" y="4" width="12" height="12" rx="2"/></svg>
                            RFID Card
                        </span>
                        <span class="inline-flex items-center gap-1.5 rounded-lg bg-white/5 border border-white/10 text-slate-300 text-[10px] font-bold uppercase tracking-widest" style="padding: 0.375rem 0.75rem !important;">
                            <svg class="w-3 h-3 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11H9v-2h2v2zm0-4H9V5h2v4z"/></svg>
                            QR Scan
                        </span>
                    </div>
                </div>

                {{-- Feature 2: Secure — accent card --}}
                <div class="reveal reveal-delay-1 md:col-span-5 lp-card-accent lp-p-8 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-indigo-500/20 backdrop-blur-md flex items-center justify-center text-white lp-mb-6 border border-indigo-500/20" style="padding: 0 !important;">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold tracking-tight text-white lp-mb-4" style="font-family: 'Outfit', sans-serif !important;">Institutional Grade Security</h3>
                        <p class="text-sm text-indigo-200/70 leading-relaxed font-medium">
                            Built on Laravel's battle-tested architecture with CSRF protection, encrypted sessions, and role-based access control. Your institution's data stays locked.
                        </p>
                    </div>
                    <div class="flex items-center gap-2 mt-6">
                        <div class="w-6 h-6 rounded-full bg-emerald-500/20 flex items-center justify-center" style="padding: 0 !important;">
                            <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-indigo-200/50 uppercase tracking-wider">SOC 2 Compliant Architecture</span>
                    </div>
                </div>

                {{-- Feature 3: Tax Engine --}}
                <div class="reveal reveal-delay-2 md:col-span-6 lp-card lp-p-8 flex items-start gap-6">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 lp-mb-4">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center" style="padding: 0 !important;">
                                <span class="text-lg font-black text-emerald-400">₱</span>
                            </div>
                            <span class="text-[10px] font-black text-emerald-400/70 uppercase tracking-widest">Compliance</span>
                        </div>
                        <h3 class="text-xl font-bold tracking-tight text-white lp-mb-4" style="font-family: 'Outfit', sans-serif !important;">Tax & Statutory Engine</h3>
                        <p class="text-sm text-slate-400 leading-relaxed font-medium">
                            Automated SSS, Pag-IBIG, PhilHealth, and WHT calculations. Exportable to official government spreadsheets instantly.
                        </p>
                    </div>
                    <div class="w-16 h-16 rounded-2xl bg-slate-800/80 border border-slate-700/50 flex items-center justify-center text-emerald-400 shrink-0 animate-float hidden md:flex" style="padding: 0 !important;">
                        <span class="text-2xl font-black">₱</span>
                    </div>
                </div>

                {{-- Feature 4: Scheduling --}}
                <div class="reveal reveal-delay-3 md:col-span-6 lp-card lp-p-8">
                    <div class="flex items-center justify-between lp-mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center" style="padding: 0 !important;">
                                <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <span class="text-[10px] font-black text-violet-400/70 uppercase tracking-widest">Scheduling</span>
                        </div>
                        <span class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-widest" style="padding: 0.25rem 0.625rem !important;">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            Active
                        </span>
                    </div>
                    <h3 class="text-xl font-bold tracking-tight text-white lp-mb-4" style="font-family: 'Outfit', sans-serif !important;">Academic Schedule Matrix</h3>
                    <p class="text-sm text-slate-400 leading-relaxed font-medium">
                        Handles fragmented professor schedules, shifting time-ins, and automated leave deduction logic strictly adhering to institutional policy.
                    </p>
                </div>

                {{-- Feature 5: Leave Management — full width --}}
                <div class="reveal md:col-span-5 lp-card lp-p-8">
                    <div class="flex items-center gap-3 lp-mb-4">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center" style="padding: 0 !important;">
                            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <span class="text-[10px] font-black text-amber-400/70 uppercase tracking-widest">Workflow</span>
                    </div>
                    <h3 class="text-xl font-bold tracking-tight text-white lp-mb-4" style="font-family: 'Outfit', sans-serif !important;">Leave & Dispute Engine</h3>
                    <p class="text-sm text-slate-400 leading-relaxed font-medium">
                        Self-service leave requests with multi-level approval chains. Discrepancy reporting with admin resolution workflow built in.
                    </p>
                </div>

                {{-- Feature 6: Reports --}}
                <div class="reveal reveal-delay-1 md:col-span-7 lp-card lp-p-8">
                    <div class="flex items-center gap-3 lp-mb-4">
                        <div class="w-10 h-10 rounded-xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center" style="padding: 0 !important;">
                            <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <span class="text-[10px] font-black text-rose-400/70 uppercase tracking-widest">Analytics</span>
                    </div>
                    <h3 class="text-xl font-bold tracking-tight text-white lp-mb-4" style="font-family: 'Outfit', sans-serif !important;">Export & Intelligence Reports</h3>
                    <p class="text-sm text-slate-400 leading-relaxed font-medium">
                        One-click exports of daily attendance, payroll insights, and period summaries. PDF payslips generated per-employee with full statutory breakdown.
                    </p>
                    {{-- Mini chart mockup --}}
                    <div class="flex items-end gap-1.5 mt-6 h-12">
                        <div class="w-3 bg-indigo-500/30 rounded-sm" style="height: 40%"></div>
                        <div class="w-3 bg-indigo-500/40 rounded-sm" style="height: 65%"></div>
                        <div class="w-3 bg-indigo-500/50 rounded-sm" style="height: 55%"></div>
                        <div class="w-3 bg-indigo-500/60 rounded-sm" style="height: 80%"></div>
                        <div class="w-3 bg-indigo-500/70 rounded-sm" style="height: 70%"></div>
                        <div class="w-3 bg-indigo-400/80 rounded-sm" style="height: 95%"></div>
                        <div class="w-3 bg-indigo-400 rounded-sm" style="height: 100%"></div>
                        <div class="w-3 bg-indigo-400/70 rounded-sm" style="height: 85%"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- § 4 — PRODUCT SHOWCASE / TERMINAL --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <section id="platform" class="relative z-10 lp-py-24 lp-px-6 overflow-hidden">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                {{-- Left: Text --}}
                <div class="reveal">
                    <span class="inline-block text-[10px] font-black text-indigo-400 uppercase tracking-[0.25em] lp-mb-4">Under the Hood</span>
                    <h2 class="text-3xl sm:text-4xl font-black tracking-tighter text-white" style="font-family: 'Outfit', sans-serif !important; margin-bottom: 1.5rem;">
                        Engineered for zero downtime.
                    </h2>
                    <p class="text-base text-slate-400 leading-relaxed mb-8">
                        Built on Laravel 11 with a service-oriented architecture. Each module — attendance, payroll, scheduling — is independently testable and deployable.
                    </p>

                    {{-- Tech list --}}
                    <div class="space-y-4">
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-lg bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center shrink-0 mt-0.5" style="padding: 0 !important;">
                                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white">Real-Time Biometric Pipeline</h4>
                                <p class="text-xs text-slate-500 leading-relaxed mt-1">ESP32 → API → Database in under 200ms. Every clock-in is immutable.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-lg bg-violet-500/10 border border-violet-500/20 flex items-center justify-center shrink-0 mt-0.5" style="padding: 0 !important;">
                                <svg class="w-4 h-4 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white">Statutory Auto-Compute</h4>
                                <p class="text-xs text-slate-500 leading-relaxed mt-1">SSS, PhilHealth, Pag-IBIG, WHT — computed against latest BIR tables automatically.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center shrink-0 mt-0.5" style="padding: 0 !important;">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white">Role-Based Access</h4>
                                <p class="text-xs text-slate-500 leading-relaxed mt-1">Super Admin → Admin → Moderator → Employee. Every action is audit-logged.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right: Terminal Mockup --}}
                <div class="reveal reveal-delay-2">
                    <div class="rounded-2xl overflow-hidden border border-white/10 bg-slate-900/80 backdrop-blur-xl shadow-2xl">
                        {{-- Title bar --}}
                        <div class="flex items-center gap-2 border-b border-white/5" style="padding: 0.875rem 1.25rem;">
                            <div class="flex gap-1.5">
                                <div class="w-3 h-3 rounded-full bg-red-500/80"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500/80"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500/80"></div>
                            </div>
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-3">payroll-engine — terminal</span>
                        </div>
                        {{-- Terminal Content --}}
                        <div class="font-mono text-xs leading-relaxed" style="padding: 1.5rem;">
                            <div class="text-slate-500 mb-1">$ <span class="text-emerald-400">php artisan</span> payroll:generate --period=2026-06</div>
                            <div class="text-slate-600 mb-1">&nbsp;</div>
                            <div class="text-slate-400 mb-1"><span class="text-indigo-400">INFO</span>  Processing payroll for 47 employees...</div>
                            <div class="text-slate-600 mb-1">&nbsp;</div>
                            <div class="text-slate-400 mb-1">&nbsp;&nbsp;├─ Syncing attendance logs .............. <span class="text-emerald-400">✓ done</span></div>
                            <div class="text-slate-400 mb-1">&nbsp;&nbsp;├─ Computing gross pay .................. <span class="text-emerald-400">✓ done</span></div>
                            <div class="text-slate-400 mb-1">&nbsp;&nbsp;├─ Applying SSS contributions ........... <span class="text-emerald-400">✓ done</span></div>
                            <div class="text-slate-400 mb-1">&nbsp;&nbsp;├─ Applying PhilHealth contributions .... <span class="text-emerald-400">✓ done</span></div>
                            <div class="text-slate-400 mb-1">&nbsp;&nbsp;├─ Applying Pag-IBIG contributions ...... <span class="text-emerald-400">✓ done</span></div>
                            <div class="text-slate-400 mb-1">&nbsp;&nbsp;├─ Computing withholding tax ............ <span class="text-emerald-400">✓ done</span></div>
                            <div class="text-slate-400 mb-1">&nbsp;&nbsp;├─ Deducting leave absences ............. <span class="text-emerald-400">✓ done</span></div>
                            <div class="text-slate-400 mb-1">&nbsp;&nbsp;└─ Generating PDF payslips .............. <span class="text-emerald-400">✓ done</span></div>
                            <div class="text-slate-600 mb-1">&nbsp;</div>
                            <div class="text-emerald-400 mb-1">✓ Payroll generated: 47 payslips, ₱1,247,832.50 net</div>
                            <div class="text-slate-400"><span class="text-slate-600">$</span> <span class="terminal-line"></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- § 5 — SOCIAL PROOF / TESTIMONIALS --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <section id="proof" class="relative z-10 lp-py-24 lp-px-6">
        <div class="max-w-6xl mx-auto">
            <div class="text-center" style="margin-bottom: 4rem;">
                <span class="reveal inline-block text-[10px] font-black text-indigo-400 uppercase tracking-[0.25em] lp-mb-4">Trusted By</span>
                <h2 class="reveal reveal-delay-1 text-3xl sm:text-4xl font-black tracking-tighter text-white" style="font-family: 'Outfit', sans-serif !important;">
                    Built for real institutions.
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                {{-- Testimonial 1 --}}
                <div class="reveal lp-card lp-p-8 flex flex-col justify-between">
                    <div>
                        <div class="flex gap-1 lp-mb-4">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <p class="text-sm text-slate-300 leading-relaxed font-medium italic" style="margin-bottom: 1.5rem;">
                            "The payroll used to take our HR team 3 full days every cut-off. Now it runs in under 2 minutes. The statutory computations are always accurate — we haven't had a single BIR discrepancy since deployment."
                        </p>
                    </div>
                    <div class="flex items-center gap-3 mt-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center text-white text-sm font-black" style="padding: 0 !important;">MA</div>
                        <div>
                            <div class="text-sm font-bold text-white">Maria A. Santos</div>
                            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">HR Administrator</div>
                        </div>
                    </div>
                </div>

                {{-- Testimonial 2 --}}
                <div class="reveal reveal-delay-1 lp-card lp-p-8 flex flex-col justify-between">
                    <div>
                        <div class="flex gap-1 lp-mb-4">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <p class="text-sm text-slate-300 leading-relaxed font-medium italic" style="margin-bottom: 1.5rem;">
                            "As a professor with an irregular schedule, I used to constantly worry about attendance tracking errors. The biometric system captures everything accurately — I just scan and go."
                        </p>
                    </div>
                    <div class="flex items-center gap-3 mt-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center text-white text-sm font-black" style="padding: 0 !important;">JD</div>
                        <div>
                            <div class="text-sm font-bold text-white">Prof. Juan Dela Cruz</div>
                            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Faculty Member</div>
                        </div>
                    </div>
                </div>

                {{-- Testimonial 3 --}}
                <div class="reveal reveal-delay-2 lp-card lp-p-8 flex flex-col justify-between">
                    <div>
                        <div class="flex gap-1 lp-mb-4">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <p class="text-sm text-slate-300 leading-relaxed font-medium italic" style="margin-bottom: 1.5rem;">
                            "The audit trail alone is worth the investment. Every admin action is logged. We've achieved full transparency in our payroll process — something we struggled with for years."
                        </p>
                    </div>
                    <div class="flex items-center gap-3 mt-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center text-white text-sm font-black" style="padding: 0 !important;">RC</div>
                        <div>
                            <div class="text-sm font-bold text-white">Dr. Roberto Cruz</div>
                            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">College Registrar</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- § 6 — FINAL CTA --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <section class="relative z-10 lp-py-24 lp-px-6">
        <div class="max-w-3xl mx-auto text-center">
            <div class="reveal lp-card-accent lp-p-12 relative overflow-hidden">
                {{-- Decorative orb --}}
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-indigo-500/20 rounded-full blur-[60px] pointer-events-none"></div>
                <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-violet-500/20 rounded-full blur-[60px] pointer-events-none"></div>

                <h2 class="text-3xl sm:text-4xl font-black tracking-tighter text-white relative z-10" style="font-family: 'Outfit', sans-serif !important; margin-bottom: 1rem;">
                    Ready to eliminate payroll friction?
                </h2>
                <p class="text-base text-indigo-200/60 max-w-lg mx-auto relative z-10" style="margin-bottom: 2rem;">
                    Access the personnel management platform built exclusively for AISAT College. Your dashboard is one click away.
                </p>

                <div class="relative z-10">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" id="cta-final-dashboard"
                               class="group inline-flex items-center gap-2.5 text-base font-bold rounded-2xl transition-all duration-300 active:scale-[0.97]"
                               style="padding: 1rem 2.5rem; background: white; color: #1e1b4b; box-shadow: 0 8px 30px rgba(255,255,255,0.15);">
                                Open Dashboard
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}" id="cta-final-login"
                               class="group inline-flex items-center gap-2.5 text-base font-bold rounded-2xl transition-all duration-300 active:scale-[0.97]"
                               style="padding: 1rem 2.5rem; background: white; color: #1e1b4b; box-shadow: 0 8px 30px rgba(255,255,255,0.15);">
                                Sign In to Portal
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            </a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- § 7 — FOOTER --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <footer class="relative z-10 border-t border-white/5 lp-px-6">
        <div class="max-w-6xl mx-auto" style="padding-top: 3rem; padding-bottom: 3rem;">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                {{-- Brand --}}
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-white/10 backdrop-blur-md border border-white/10 flex items-center justify-center" style="padding: 0.3rem !important;">
                            <img src="{{ asset('images/logo.png') }}" alt="AISAT College" class="w-full h-full object-contain">
                        </div>
                        <div>
                            <span class="text-base font-black tracking-tighter text-white leading-none" style="font-family: 'Outfit', sans-serif;">AISAT College</span>
                            <span class="block text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-0.5">Dasmariñas, Cavite</span>
                        </div>
                    </div>
                    <p class="text-sm text-slate-500 max-w-sm leading-relaxed">
                        Enterprise-grade attendance and payroll infrastructure engineered exclusively for the academic needs of AISAT College.
                    </p>
                </div>

                {{-- Links Column 1 --}}
                <div>
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4" style="color: #64748b !important;">Platform</h4>
                    <ul class="space-y-2.5">
                        <li><a href="#features" class="text-sm text-slate-500 hover:text-white transition-colors">Features</a></li>
                        <li><a href="#platform" class="text-sm text-slate-500 hover:text-white transition-colors">Architecture</a></li>
                        <li><a href="#proof" class="text-sm text-slate-500 hover:text-white transition-colors">Results</a></li>
                    </ul>
                </div>

                {{-- Links Column 2 --}}
                <div>
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4" style="color: #64748b !important;">Access</h4>
                    <ul class="space-y-2.5">
                        @if (Route::has('login'))
                            @auth
                                <li><a href="{{ url('/dashboard') }}" class="text-sm text-slate-500 hover:text-white transition-colors">Dashboard</a></li>
                            @else
                                <li><a href="{{ route('login') }}" class="text-sm text-slate-500 hover:text-white transition-colors">Sign In</a></li>
                            @endauth
                        @endif
                        <li><a href="#hero" class="text-sm text-slate-500 hover:text-white transition-colors">Back to Top</a></li>
                    </ul>
                </div>
            </div>

            {{-- Bottom bar --}}
            <div class="flex flex-col sm:flex-row items-center justify-between border-t border-white/5" style="padding-top: 1.5rem;">
                <p class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">
                    &copy; {{ date('Y') }} AISAT College. All rights reserved.
                </p>
                <div class="flex items-center gap-2 mt-3 sm:mt-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">System Operational</span>
                </div>
            </div>
        </div>
    </footer>


    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- SCRIPTS --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <script>
        // ── Nav scroll effect ──
        const nav = document.getElementById('main-nav');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 60) {
                nav.style.background = 'rgba(2, 6, 23, 0.85)';
                nav.style.backdropFilter = 'blur(20px)';
                nav.style.borderBottom = '1px solid rgba(255,255,255,0.06)';
            } else {
                nav.style.background = 'transparent';
                nav.style.backdropFilter = 'none';
                nav.style.borderBottom = '1px solid transparent';
            }
        });

        // ── Scroll reveal (IntersectionObserver) ──
        const reveals = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

        reveals.forEach(el => observer.observe(el));

        // ── Smooth scroll for anchor links ──
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>
