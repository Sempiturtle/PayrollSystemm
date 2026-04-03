@props(['type' => 'info'])

@php
$classes = [
    'On-time' => 'from-emerald-500/20 to-emerald-500/5 text-emerald-400 border-emerald-500/20 shadow-emerald-500/5',
    'Late' => 'from-amber-500/20 to-amber-500/5 text-amber-400 border-amber-500/20 shadow-amber-500/5',
    'Absent' => 'from-rose-500/20 to-rose-500/5 text-rose-400 border-rose-500/20 shadow-rose-500/5',
    'Released' => 'from-indigo-500/20 to-indigo-500/5 text-indigo-400 border-indigo-500/20 shadow-indigo-500/5',
    'admin' => 'from-white/10 to-white/5 text-white border-white/20',
    'professor' => 'from-violet-500/20 to-violet-500/5 text-violet-400 border-violet-500/20 shadow-violet-500/5',
    'employee' => 'from-slate-500/20 to-slate-500/5 text-slate-400 border-slate-500/20 shadow-slate-500/5',
];

$selected = $classes[$type] ?? 'from-slate-500/20 to-slate-500/5 text-slate-400 border-slate-500/20';
@endphp

<span class="inline-flex items-center px-2 py-0.5 text-[9px] font-black rounded border bg-gradient-to-br {{ $selected }} shadow-pro transition-all duration-300 hover:brightness-125 uppercase tracking-[0.15em] relative overflow-hidden group">
    <!-- Tactile Inner Glow -->
    <span class="absolute inset-x-0 top-0 h-px bg-white/10"></span>
    <span class="w-1 h-1 rounded-full bg-current mr-1.5 opacity-60 group-hover:scale-150 transition-transform"></span>
    {{ $slot }}
</span>
