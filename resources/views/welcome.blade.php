<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AISAT College | Attendance & Payroll System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 overflow-x-hidden">
    <!-- Navbar -->
    <nav class="fixed w-full z-50 glass-card px-6 py-4 flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-xl shadow-lg ring-4 ring-indigo-50">A</div>
            <span class="text-xl font-bold tracking-tight text-slate-800">AISAT <small class="text-indigo-600 font-medium tracking-normal text-sm">ATTENDANCE</small></span>
        </div>
        <div>
            @if (Route::has('login'))
                <div class="space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-6 py-2.5 bg-indigo-600 text-white rounded-full font-semibold hover:bg-indigo-700 transition shadow-md">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-2.5 bg-indigo-600 text-white rounded-full font-semibold hover:bg-indigo-700 transition shadow-md">Enter Portal</a>
                    @endauth
                </div>
            @endif
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 px-6 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-pink-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-pulse" style="animation-delay: 2s"></div>
        
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center gap-12">
            <div class="flex-1 text-center lg:text-left">
                <h1 class="text-5xl lg:text-7xl font-bold leading-tight text-slate-900 mb-6">
                    Streamline Campus <span class="text-indigo-600">Attendance</span> & <span class="text-indigo-600">Payroll</span>
                </h1>
                <p class="text-lg text-slate-600 mb-10 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                    Designed specifically for AISAT College. A secure, rule-based system utilizing RFID and Biometric authentication for 100% accurate time tracking and automated payroll integration.
                </p>
                <div class="flex flex-wrap justify-center lg:justify-start gap-4">
                    <a href="{{ route('login') }}" class="px-8 py-4 bg-indigo-600 text-white rounded-xl font-bold text-lg hover:bg-indigo-700 transition shadow-xl hover:scale-105 active:scale-95">
                        Launch System
                    </a>
                    <a href="#features" class="px-8 py-4 bg-white text-slate-700 border border-slate-200 rounded-xl font-bold text-lg hover:bg-slate-50 transition">
                        Explore Features
                    </a>
                </div>
                <div class="mt-10 flex items-center justify-center lg:justify-start gap-8 opacity-60">
                    <div class="flex flex-col">
                        <span class="text-2xl font-bold text-slate-900">100%</span>
                        <span class="text-sm font-medium">Real-time Data</span>
                    </div>
                    <div class="w-px h-8 bg-slate-300"></div>
                    <div class="flex flex-col">
                        <span class="text-2xl font-bold text-slate-900">Manila</span>
                        <span class="text-sm font-medium">Synced Time</span>
                    </div>
                    <div class="w-px h-8 bg-slate-300"></div>
                    <div class="flex flex-col">
                        <span class="text-2xl font-bold text-slate-900">Secure</span>
                        <span class="text-sm font-medium">Biometric Auth</span>
                    </div>
                </div>
            </div>
            <div class="flex-1 relative">
                <div class="relative z-10 p-4 bg-indigo-100 rounded-3xl shadow-2xl">
                    <div class="bg-white rounded-2xl p-6 shadow-sm overflow-hidden">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="font-bold text-indigo-900 lg:text-xl">Daily Report Overview</h3>
                            <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-xs font-bold rounded-lg">{{ date('M d, Y') }}</span>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center gap-4 p-3 bg-slate-50 rounded-xl">
                                <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center font-bold">✓</div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-sm">Professor Juan Dela Cruz</h4>
                                    <p class="text-xs text-slate-500">Checked in at 7:45 AM (On-time)</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 p-3 bg-slate-50 rounded-xl">
                                <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center font-bold">!</div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-sm">Admin Sarah Smith</h4>
                                    <p class="text-xs text-slate-500">Checked in at 8:15 AM (Late - 15m)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 bg-white px-6">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <span class="text-indigo-600 font-bold tracking-widest uppercase text-sm">System Core</span>
                <h2 class="text-4xl font-bold mt-2 text-slate-900">Professional Grade Infrastructure</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Card 1 -->
                <div class="p-8 group bg-slate-50 rounded-3xl hover:bg-white hover:shadow-2xl transition duration-300 border border-transparent hover:border-indigo-100">
                    <div class="w-14 h-14 bg-indigo-600 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition">
                        <svg class="w-8 h-8 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-900 tracking-tight">RFID Integration</h3>
                    <p class="text-slate-600 leading-relaxed text-sm">Secure, contactless attendance logging using industry-standard RFID technology for instant verification.</p>
                </div>

                <!-- Card 2 -->
                <div class="p-8 group bg-slate-50 rounded-3xl hover:bg-white hover:shadow-2xl transition duration-300 border border-transparent hover:border-indigo-100">
                    <div class="w-14 h-14 bg-pink-600 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition">
                        <svg class="w-8 h-8 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 3v1c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 3v1m0 0c.851 0 1.673.1 2.459.29m0 0A10.016 10.016 0 0115.353 10H14a3 3 0 00-2.828 4M12 11c0 3.517 1.009 6.799 2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 3v1m0 0c.851 0 1.673.1 2.459.29m0 0a10.016 10.016 0 011.094 9.71m3.44 2.04l-.054-.09a10.003 10.003 0 010-19.142m0 19.142A10.002 10.002 0 0120 13v-1M12 11c0 3.517 1.009 6.799 2.753 9.571m0 0c.851 0 1.673.1 2.459.29m0 0a10.016 10.016 0 011.094 9.71m-1.094-9.71A10.016 10.016 0 0018 11.29"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-900 tracking-tight">Biometric Fallback</h3>
                    <p class="text-slate-600 leading-relaxed text-sm">Advanced fingerprint recognition as a secondary authentication method when cards are unavailable.</p>
                </div>

                <!-- Card 3 -->
                <div class="p-8 group bg-slate-50 rounded-3xl hover:bg-white hover:shadow-2xl transition duration-300 border border-transparent hover:border-indigo-100">
                    <div class="w-14 h-14 bg-emerald-600 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition">
                        <svg class="w-8 h-8 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-900 tracking-tight">Rule-Based Logic</h3>
                    <p class="text-slate-600 leading-relaxed text-sm">Automated evaluation against uploaded Excel schedules using Asia/Manila server time.</p>
                </div>

                <!-- Card 4 -->
                <div class="p-8 group bg-slate-50 rounded-3xl hover:bg-white hover:shadow-2xl transition duration-300 border border-transparent hover:border-indigo-100">
                    <div class="w-14 h-14 bg-slate-800 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition">
                        <svg class="w-8 h-8 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-900 tracking-tight">Payroll Insight</h3>
                    <p class="text-slate-600 leading-relaxed text-sm">Integrated computation of late deductions and absences directly into payroll reports.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-50 py-12 px-6 border-t border-slate-200">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-8 text-slate-500 text-sm">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 bg-indigo-600 rounded text-white flex items-center justify-center font-bold text-xs shadow-sm">A</div>
                <span class="font-bold text-slate-800">AISAT College</span>
            </div>
            <p>&copy; {{ date('Y') }} AISAT College. All rights reserved.</p>
            <div class="flex gap-6">
                <a href="#" class="hover:text-indigo-600 transition">Contact Admin</a>
                <a href="#" class="hover:text-indigo-600 transition">User Policy</a>
            </div>
        </div>
    </footer>
</body>
</html>
