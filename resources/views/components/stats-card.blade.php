@props(['title', 'value', 'icon' => 'users', 'color' => 'indigo', 'trend' => null])

@php
$colorMap = [
    'indigo'  => ['bg' => 'bg-indigo-50',  'text' => 'text-indigo-600',  'ring' => 'ring-indigo-100'],
    'emerald' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'ring' => 'ring-emerald-100'],
    'rose'    => ['bg' => 'bg-rose-50',    'text' => 'text-rose-600',    'ring' => 'ring-rose-100'],
    'amber'   => ['bg' => 'bg-amber-50',   'text' => 'text-amber-600',   'ring' => 'ring-amber-100'],
];

$c = $colorMap[$color] ?? $colorMap['indigo'];

$iconPaths = [
    'users' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
    'check' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
    'clock' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
    'alert' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
];
@endphp

<div class="stat-card group">
    <div class="flex items-center justify-between mb-4">
        <div class="w-10 h-10 rounded-xl {{ $c['bg'] }} {{ $c['text'] }} flex items-center justify-center ring-1 {{ $c['ring'] }} transition-transform duration-300 group-hover:scale-110">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $iconPaths[$icon] ?? $iconPaths['users'] }}"></path>
            </svg>
        </div>
        @if($trend)
            <div class="flex items-center gap-1 px-2 py-1 {{ str_starts_with($trend, '-') ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600' }} rounded-lg text-[11px] font-semibold">
                @if(!str_starts_with($trend, '-'))
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                @else
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                @endif
                {{ $trend }}%
            </div>
        @endif
    </div>

    <div>
        <div class="text-3xl font-bold text-slate-900 tracking-tight group-hover:text-indigo-600 transition-colors duration-300">{{ $value }}</div>
        <h4 class="text-sm font-medium text-slate-500 mt-1">{{ $title }}</h4>
    </div>
</div>
