<x-app-layout>
    <x-slot name="header">
        Employee Schedules
    </x-slot>

    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-50 dark:border-slate-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100 italic tracking-tight">Academic Timelines</h3>
                <p class="text-sm text-slate-500 mt-1">Review and manage weekly shift assignments for faculty and staff.</p>
            </div>
            <a href="{{ route('schedules.create') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold flex items-center gap-2 hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 dark:shadow-none hover:-translate-y-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Import Excel
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                        <th class="px-8 py-4">Employee</th>
                        <th class="px-8 py-4">Schedule Details</th>
                        <th class="px-8 py-4">Status</th>
                        <th class="px-8 py-4">Effective Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($schedules as $schedule)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold">
                                    {{ substr($schedule->user->name, 0, 1) }}
                                </div>
                                <div class="font-bold text-slate-900 dark:text-slate-100 text-sm tracking-tight">{{ $schedule->user->name }}</div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest w-24">{{ $schedule->day_of_week }}</span>
                                <div class="flex items-center gap-2 px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
                                    <span class="text-sm font-bold text-slate-700 dark:text-slate-300 tabular-nums">
                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}
                                    </span>
                                    <span class="text-slate-300">→</span>
                                    <span class="text-sm font-bold text-slate-700 dark:text-slate-300 tabular-nums">
                                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                Active
                            </span>
                        </td>
                        <td class="px-8 py-6 text-sm font-bold text-slate-500 italic">
                            {{ $schedule->effective_from ? \Carbon\Carbon::parse($schedule->effective_from)->format('M d, Y') : 'N/A' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
