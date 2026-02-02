@props(['type' => 'info'])

@php
$classes = [
    'On-time' => 'bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800/50',
    'Late' => 'bg-amber-50 text-amber-700 border-amber-100 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800/50',
    'Absent' => 'bg-rose-50 text-rose-700 border-rose-100 dark:bg-rose-900/30 dark:text-rose-400 dark:border-rose-800/50',
    'admin' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
    'professor' => 'bg-purple-50 text-purple-700 border-purple-100',
    'employee' => 'bg-slate-50 text-slate-700 border-slate-100',
];

$selected = $classes[$type] ?? 'bg-slate-50 text-slate-700 border-slate-100';
@endphp

<span class="px-3 py-1 text-xs font-bold rounded-full border {{ $selected }} shadow-sm">
    {{ $slot }}
</span>
