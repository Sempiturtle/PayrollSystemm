@props(['type' => 'info'])

@php
$styles = [
    'On-time'   => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/10',
    'Late'      => 'bg-amber-50 text-amber-700 ring-1 ring-amber-600/10',
    'Absent'    => 'bg-[#660000]/5 text-[#660000] ring-1 ring-[#660000]/10',
    'Released'  => 'bg-[#101D33]/5 text-[#101D33] ring-1 ring-[#101D33]/10',
    'admin'     => 'bg-[#101D33] text-white ring-1 ring-white/10',
    'professor' => 'bg-[#660000] text-white ring-1 ring-white/10',
    'employee'  => 'bg-slate-100 text-slate-700 ring-1 ring-slate-200',
    'finalized' => 'bg-emerald-600 text-white shadow-sm ring-1 ring-white/10',
    'draft'     => 'bg-slate-100 text-slate-500 ring-1 ring-slate-200',
];

$dotColors = [
    'On-time'   => 'bg-emerald-500',
    'Late'      => 'bg-amber-500',
    'Absent'    => 'bg-[#660000]',
    'Released'  => 'bg-[#101D33]',
    'admin'     => 'bg-[#D4AF37]',
    'professor' => 'bg-[#D4AF37]',
    'employee'  => 'bg-slate-400',
    'finalized' => 'bg-white',
    'draft'     => 'bg-slate-400',
];

$selected = $styles[$type] ?? 'bg-slate-50 text-slate-600 ring-1 ring-slate-200';
$dot = $dotColors[$type] ?? 'bg-slate-400';
@endphp

<span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.1em] transition-all duration-300 {{ $selected }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $dot }} shadow-sm"></span>
    {{ $slot }}
</span>
