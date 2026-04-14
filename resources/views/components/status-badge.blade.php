@props(['type' => 'info'])

@php
$styles = [
    'On-time'   => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200/60',
    'Late'      => 'bg-amber-50 text-amber-700 ring-1 ring-amber-200/60',
    'Absent'    => 'bg-rose-50 text-rose-700 ring-1 ring-rose-200/60',
    'Released'  => 'bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200/60',
    'admin'     => 'bg-slate-100 text-slate-700 ring-1 ring-slate-200/60',
    'professor' => 'bg-violet-50 text-violet-700 ring-1 ring-violet-200/60',
    'employee'  => 'bg-sky-50 text-sky-700 ring-1 ring-sky-200/60',
    'finalized' => 'bg-emerald-100 text-emerald-800 ring-1 ring-emerald-300',
    'draft'     => 'bg-slate-100 text-slate-600 ring-1 ring-slate-200',
];

$dotColors = [
    'On-time'   => 'bg-emerald-500',
    'Late'      => 'bg-amber-500',
    'Absent'    => 'bg-rose-500',
    'Released'  => 'bg-indigo-500',
    'admin'     => 'bg-slate-500',
    'professor' => 'bg-violet-500',
    'employee'  => 'bg-sky-500',
    'finalized' => 'bg-emerald-600',
    'draft'     => 'bg-slate-400',
];

$selected = $styles[$type] ?? 'bg-slate-100 text-slate-600 ring-1 ring-slate-200/60';
$dot = $dotColors[$type] ?? 'bg-slate-400';
@endphp

<span class="badge {{ $selected }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $dot }}"></span>
    {{ $slot }}
</span>
