@props(['title', 'value', 'icon' => 'users', 'color' => 'indigo'])

@php
$colors = [
    'indigo' => 'bg-indigo-600 text-white shadow-indigo-200',
    'emerald' => 'bg-emerald-600 text-white shadow-emerald-200',
    'rose' => 'bg-rose-600 text-white shadow-rose-200',
    'amber' => 'bg-amber-600 text-white shadow-amber-200',
    'slate' => 'bg-slate-800 text-white shadow-slate-200',
];

$iconBg = [
    'indigo' => 'bg-indigo-50 text-indigo-600',
    'emerald' => 'bg-emerald-50 text-emerald-600',
    'rose' => 'bg-rose-50 text-rose-600',
    'amber' => 'bg-amber-50 text-amber-600',
    'slate' => 'bg-slate-100 text-slate-800',
];

$iconPaths = [
    'users' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
    'check' => 'M5 13l4 4L19 7',
    'clock' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
    'alert' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
];
@endphp

<div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-md transition duration-300">
    <div class="flex justify-between items-start mb-4">
        <div class="w-12 h-12 rounded-2xl flex items-center justify-center {{ $iconBg[$color] ?? $iconBg['indigo'] }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPaths[$icon] ?? $iconPaths['users'] }}"></path>
            </svg>
        </div>
        @if(isset($trend))
            <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">+{{ $trend }}%</span>
        @endif
    </div>
    <div class="text-3xl font-bold text-slate-900 dark:text-slate-100 tracking-tight mb-1">{{ $value }}</div>
    <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $title }}</div>
</div>
