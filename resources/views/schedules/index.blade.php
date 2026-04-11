<x-app-layout>
    <x-slot name="header">
        Employee Schedules
    </x-slot>

    <div class="space-y-6">
        <!-- Header Bar -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100 italic tracking-tight">Academic Timelines</h3>
                    <p class="text-sm text-slate-500 mt-1">
                        <span class="font-bold text-indigo-600">{{ $totalScheduled }}</span> employees with 
                        <span class="font-bold text-indigo-600">{{ $totalEntries }}</span> schedule entries
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <!-- Clear All Schedules -->
                    <form action="{{ route('schedules.destroy') }}" method="POST" onsubmit="return confirm('Are you sure? This will delete ALL schedules.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-5 py-3 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-2xl font-bold text-sm flex items-center gap-2 hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-900/20 dark:hover:text-rose-400 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Clear All
                        </button>
                    </form>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="px-8 pb-6">
                <form action="{{ route('schedules.index') }}" method="GET" class="relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Search by name or employee ID..." 
                        value="{{ $search ?? '' }}"
                        class="w-full pl-12 pr-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl text-sm font-medium text-slate-700 dark:text-slate-300 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                    >
                </form>
            </div>
        </div>

        <!-- Employees Schedule Cards -->
        @forelse($employees as $employee)
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden" 
             x-data="{ open: true }">
            
            <!-- Employee Header (clickable to toggle) -->
            <button @click="open = !open" class="w-full p-6 flex items-center justify-between hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-lg">
                        {{ substr($employee->name, 0, 1) }}
                    </div>
                    <div class="text-left">
                        <div class="font-bold text-slate-900 dark:text-slate-100 tracking-tight">{{ $employee->name }}</div>
                        <div class="flex items-center gap-3 mt-0.5">
                            <span class="text-xs text-slate-400 font-medium">{{ $employee->employee_id }}</span>
                            <span class="text-xs px-2 py-0.5 rounded-full font-bold {{ $employee->role === 'professor' ? 'bg-purple-50 text-purple-600 dark:bg-purple-900/20 dark:text-purple-400' : 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' }}">
                                {{ ucfirst($employee->role) }}
                            </span>
                            <span class="text-xs text-slate-400 font-bold">
                                {{ $employee->schedules->count() }} day{{ $employee->schedules->count() !== 1 ? 's' : '' }}/week
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm font-bold text-slate-400 tabular-nums">
                        {{ $employee->schedules->sum(fn($s) => round(\Carbon\Carbon::parse($s->end_time)->diffInMinutes(\Carbon\Carbon::parse($s->start_time)) / 60, 1)) }}h/week
                    </span>
                    <svg class="w-5 h-5 text-slate-300 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </button>

            <!-- Weekly Timetable Grid -->
            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <div class="border-t border-slate-100 dark:border-slate-800">
                    <div class="grid grid-cols-7 divide-x divide-slate-100 dark:divide-slate-800">
                        @php
                            $allDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                            $shortDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                            $today = now()->format('l');
                        @endphp
                        
                        @foreach($allDays as $i => $day)
                            @php
                                $sched = $employee->schedules->firstWhere('day_of_week', $day);
                                $isToday = ($day === $today);
                            @endphp
                            <div class="p-4 text-center {{ $isToday ? 'bg-indigo-50/50 dark:bg-indigo-950/20' : '' }}">
                                <div class="text-[10px] font-bold uppercase tracking-widest {{ $isToday ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400' }} mb-3">
                                    {{ $shortDays[$i] }}
                                </div>
                                @if($sched)
                                    <div class="text-xs font-bold {{ $isToday ? 'text-indigo-700 dark:text-indigo-300' : 'text-slate-700 dark:text-slate-300' }} tabular-nums leading-relaxed">
                                        {{ \Carbon\Carbon::parse($sched->start_time)->format('h:i A') }}
                                        <br>
                                        <span class="text-slate-300 dark:text-slate-600">↓</span>
                                        <br>
                                        {{ \Carbon\Carbon::parse($sched->end_time)->format('h:i A') }}
                                    </div>
                                @else
                                    <div class="text-xs text-slate-300 dark:text-slate-600 italic py-2">Off</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm p-16 text-center">
            <svg class="w-16 h-16 text-slate-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <h4 class="text-lg font-bold text-slate-400 italic">No Schedules Found</h4>
            <p class="text-sm text-slate-400 mt-2">No schedules have been uploaded for any employee yet.</p>
        </div>
        @endforelse
    </div>
</x-app-layout>
