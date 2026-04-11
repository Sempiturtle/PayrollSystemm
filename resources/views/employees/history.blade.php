<x-app-layout>
    <x-slot name="header">
        My Attendance History
    </x-slot>

    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-50 dark:border-slate-800">
            <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100 italic tracking-tight">Personal Attendance Logs</h3>
            <p class="text-sm text-slate-500 mt-1">Review your check-in and check-out history.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                        <th class="px-8 py-4">Date</th>
                        <th class="px-8 py-4">Check In</th>
                        <th class="px-8 py-4">Check Out</th>
                        <th class="px-8 py-4">Status</th>
                        <th class="px-8 py-4">Method</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition duration-200">
                        <td class="px-8 py-6">
                            <div class="text-sm font-bold text-slate-900 dark:text-slate-100 tracking-tight">
                                {{ \Carbon\Carbon::parse($log->date)->format('M d, Y') }}
                            </div>
                            <div class="text-[10px] text-slate-400 font-medium tracking-wide uppercase">
                                {{ \Carbon\Carbon::parse($log->date)->format('l') }}
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                                {{ $log->time_in ? \Carbon\Carbon::parse($log->time_in)->format('h:i A') : '--:--' }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                                {{ $log->time_out ? \Carbon\Carbon::parse($log->time_out)->format('h:i A') : 'Active' }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            @if($log->status === 'On-time')
                                <span class="px-3 py-1 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                                    On-time
                                </span>
                            @else
                                <span class="px-3 py-1 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                                    Late
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full {{ $log->source === 'RFID' ? 'bg-indigo-400' : 'bg-amber-400' }}"></div>
                                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-tight">{{ $log->source }}</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-slate-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-slate-400 font-bold tracking-tight">No attendance records found yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
        <div class="p-6 border-t border-slate-50 dark:border-slate-800">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
