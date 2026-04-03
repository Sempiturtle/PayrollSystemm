@props(['title', 'value', 'icon' => 'users', 'color' => 'indigo', 'trend' => null])

@php
$iconBg = [
    'indigo' => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20 shadow-indigo-500/5',
    'emerald' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20 shadow-emerald-500/5',
    'rose' => 'bg-rose-500/10 text-rose-400 border-rose-500/20 shadow-rose-500/5',
    'amber' => 'bg-amber-500/10 text-amber-400 border-amber-500/20 shadow-amber-500/5',
];

$iconPaths = [
    'users' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
    'check' => 'M5 13l4 4L19 7',
    'clock' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
    'alert' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
];
@endphp

<div class="premium-card p-6 group grid-pattern relative hover:-translate-y-1 active:translate-y-0 transition-all duration-300">
    <!-- Decorative Corner Micro-detail -->
    <div class="absolute top-2 right-2 w-2 h-2 border-t border-r border-white/10 group-hover:border-indigo-500/30"></div>
    <div class="absolute bottom-2 left-2 w-2 h-2 border-b border-l border-white/10 group-hover:border-indigo-500/30"></div>

    <div class="flex items-start justify-between mb-6 relative">
        <div class="w-11 h-11 rounded-xl flex items-center justify-center border {{ $iconBg[$color] ?? $iconBg['indigo'] }} transition-all duration-500 group-hover:shadow-[0_0_20px_rgba(99,102,241,0.2)]">
            <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $iconPaths[$icon] ?? $iconPaths['users'] }}"></path>
            </svg>
        </div>
        @if($trend)
            <div class="flex items-center gap-1.5 px-2 py-1 bg-emerald-500/10 text-emerald-400 rounded-lg text-[10px] font-bold tracking-tight border border-emerald-500/20 backdrop-blur-sm shadow-xl">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                {{ $trend }}%
            </div>
        @endif
    </div>

    <div class="space-y-1 relative">
        <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ $title }}</h4>
        <div class="text-3xl font-bold text-white tracking-tighter shadow-indigo-500 group-hover:text-indigo-400 transition-colors duration-300">
            {{ $value }}
        </div>
    </div>
    
    <div class="mt-6 pt-4 border-t border-white/5 relative">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                <span class="text-[9px] font-bold text-slate-500 uppercase tracking-tight">Active Engine</span>
            </div>
            <!-- Micro-details like "X-900" or similar bespoke labels -->
            <span class="text-[9px] font-mono text-slate-700 tracking-wider">X-SYNC-{{ mt_rand(100, 999) }}</span>
        </div>
    </div>
</div>
