<x-app-layout>
    <x-slot name="header">
        {{ $user->name }}'s Schedule
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Employee Info -->
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-4 flex items-center justify-between">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-xl">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100 tracking-tight">{{ $user->name }}</h3>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="text-sm text-slate-400 font-medium">{{ $user->employee_id }}</span>
                            <span class="text-xs px-2 py-0.5 rounded-full font-bold {{ $user->role === 'professor' ? 'bg-purple-50 text-purple-600' : 'bg-blue-50 text-blue-600' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('schedules.index') }}" class="text-sm font-bold text-slate-400 hover:text-indigo-600 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to All
                </a>
            </div>
        </div>

        <!-- Weekly Timetable -->
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-50 dark:border-slate-800 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 italic">Weekly Timetable</h3>
                <span class="text-sm font-bold text-slate-400">
                    @php
                        $totalHours = $schedules->sum(function($s) {
                            return round(\Carbon\Carbon::parse($s->end_time)->diffInMinutes(\Carbon\Carbon::parse($s->start_time)) / 60, 1);
                        });
                    @endphp
                    {{ $totalHours }}h / week
                </span>
            </div>

            <div class="grid grid-cols-7 divide-x divide-slate-100 dark:divide-slate-800">
                @php
                    $allDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                    $shortDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                    $today = now()->format('l');
                @endphp
                
                @foreach($allDays as $i => $day)
                    @php
                        $sched = $schedules->firstWhere('day_of_week', $day);
                        $isToday = ($day === $today);
                    @endphp
                    <div class="p-6 text-center {{ $isToday ? 'bg-indigo-50 dark:bg-indigo-950/30 ring-2 ring-inset ring-indigo-200 dark:ring-indigo-800' : '' }}">
                        <div class="text-[10px] font-bold uppercase tracking-widest {{ $isToday ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400' }} mb-4">
                            {{ $shortDays[$i] }}
                            @if($isToday)
                                <span class="ml-1 inline-block w-1.5 h-1.5 bg-indigo-500 rounded-full animate-pulse"></span>
                            @endif
                        </div>
                        
                        @if($sched)
                            <div class="space-y-2">
                                <div class="text-sm font-bold {{ $isToday ? 'text-indigo-700 dark:text-indigo-300' : 'text-slate-700 dark:text-slate-300' }} tabular-nums">
                                    {{ \Carbon\Carbon::parse($sched->start_time)->format('h:i A') }}
                                </div>
                                <div class="text-slate-300 dark:text-slate-600 text-xs">↓</div>
                                <div class="text-sm font-bold {{ $isToday ? 'text-indigo-700 dark:text-indigo-300' : 'text-slate-700 dark:text-slate-300' }} tabular-nums">
                                    {{ \Carbon\Carbon::parse($sched->end_time)->format('h:i A') }}
                                </div>
                                <div class="mt-3 text-[10px] font-bold {{ $isToday ? 'text-indigo-500' : 'text-slate-400' }}">
                                    {{ round(\Carbon\Carbon::parse($sched->end_time)->diffInMinutes(\Carbon\Carbon::parse($sched->start_time)) / 60, 1) }}h
                                </div>
                                @if($sched->effective_from)
                                    <div class="text-[9px] text-slate-400 mt-1">
                                        from {{ $sched->effective_from->format('M d') }}
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-xs text-slate-300 dark:text-slate-600 italic py-6">Off</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Detailed List View -->
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-50 dark:border-slate-800">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 italic">Detailed View</h3>
            </div>
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                        <th class="px-8 py-4">Day</th>
                        <th class="px-8 py-4">Start Time</th>
                        <th class="px-8 py-4">End Time</th>
                        <th class="px-8 py-4">Hours</th>
                        <th class="px-8 py-4">Effective From</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($schedules as $schedule)
                    @php $isToday = ($schedule->day_of_week === $today); @endphp
                    <tr class="{{ $isToday ? 'bg-indigo-50/50 dark:bg-indigo-950/20' : '' }} hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition">
                        <td class="px-8 py-5">
                            <span class="text-sm font-bold {{ $isToday ? 'text-indigo-700 dark:text-indigo-300' : 'text-slate-700 dark:text-slate-300' }}">
                                {{ $schedule->day_of_week }}
                            </span>
                            @if($isToday)
                                <span class="ml-2 text-[9px] font-bold text-indigo-500 uppercase tracking-widest">Today</span>
                            @endif
                        </td>
                        <td class="px-8 py-5 text-sm font-bold text-slate-600 dark:text-slate-400 tabular-nums">
                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}
                        </td>
                        <td class="px-8 py-5 text-sm font-bold text-slate-600 dark:text-slate-400 tabular-nums">
                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                        </td>
                        <td class="px-8 py-5 text-sm font-bold text-slate-500 tabular-nums">
                            {{ round(\Carbon\Carbon::parse($schedule->end_time)->diffInMinutes(\Carbon\Carbon::parse($schedule->start_time)) / 60, 1) }}h
                        </td>
                        <td class="px-8 py-5 text-sm font-bold text-slate-400 italic">
                            {{ $schedule->effective_from ? $schedule->effective_from->format('M d, Y') : 'N/A' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
