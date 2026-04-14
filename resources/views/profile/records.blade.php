<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 shadow-sm border border-slate-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h2a2 2 0 012 2v1m-4 0a2 2 0 002 2h2a2 2 0 002-2m-4 0h4"></path></svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-900 leading-none mb-1">Institutional Profile</h2>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Official Employee Identity & Records</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Identity Card -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-8 border-b border-slate-50 bg-slate-50/50 flex flex-col md:flex-row items-center gap-6">
                <div class="w-24 h-24 rounded-3xl bg-slate-900 text-white flex items-center justify-center text-3xl font-bold shadow-xl">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="text-center md:text-left">
                    <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight mb-1">{{ $user->name }}</h3>
                    <p class="text-xs font-bold text-indigo-600 uppercase tracking-widest mb-2">{{ $user->role }}</p>
                    <div class="flex flex-wrap justify-center md:justify-start gap-4">
                        <div class="flex items-center gap-2 text-slate-500 text-xs font-bold">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            ACTIVE STATUS
                        </div>
                        <div class="text-slate-300">•</div>
                        <div class="text-slate-500 text-xs font-bold uppercase tracking-tighter">ESTABLISHED: {{ $user->created_at->format('M Y') }}</div>
                    </div>
                </div>
                <div class="md:ml-auto">
                    <span class="px-4 py-2 bg-white border border-slate-100 rounded-xl text-xs font-bold text-slate-900 shadow-sm">ID: {{ $user->employee_id ?: 'UNASSIGNED' }}</span>
                </div>
            </div>

            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                <!-- Statutory Information Section -->
                <div class="space-y-6">
                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] flex items-center gap-2">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        Statutory Identifiers
                    </h4>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-slate-50">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-tight">TIN ID</span>
                            <span class="text-xs font-bold text-slate-900 tabular-nums">{{ $maskedTin }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-50">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-tight">SSS Number</span>
                            <span class="text-xs font-bold text-slate-900 tabular-nums">{{ $maskedSss }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-50">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-tight">PhilHealth ID</span>
                            <span class="text-xs font-bold text-slate-900 tabular-nums">{{ $maskedPhilhealth }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-tight">Pag-IBIG Number</span>
                            <span class="text-xs font-bold text-slate-900 tabular-nums">{{ $maskedPagibig }}</span>
                        </div>
                    </div>
                </div>

                <!-- Employment Basics Section -->
                <div class="space-y-6">
                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] flex items-center gap-2">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Remuneration Data
                    </h4>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-slate-50">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-tight">Rate Structure</span>
                            <span class="text-xs font-bold text-slate-900 uppercase italic">Hourly Computation</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-50">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-tight">Standard Rate</span>
                            <span class="px-2 py-0.5 bg-slate-900 text-white text-[10px] font-bold rounded">₱ {{ number_format($user->hourly_rate, 2) }} / hr</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-50">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-tight">Primary Method</span>
                            <span class="text-xs font-bold text-slate-900 tabular-nums">RFID Identity Token</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-tight">Auth Protocol</span>
                            <span class="text-xs font-bold text-slate-900 tabular-nums">Google OAuth 2.0</span>
                        </div>
                    </div>
            </div>
        </div>

        <!-- Institutional Schedule -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-8 py-5 border-b border-slate-50 bg-slate-50/30 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900">Institutional Schedule</h4>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Recurring Weekly Assignment</p>
                    </div>
                </div>
                @isset($user->schedule_file)
                    <a href="{{ route('profile.schedule.download') }}" class="flex items-center gap-2 px-3 py-1.5 bg-slate-900 text-white rounded-lg text-[10px] font-bold hover:bg-slate-800 transition-colors shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        DOWNLOAD EXCEL
                    </a>
                @endisset
            </div>

            <div class="p-0 overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-50">Day</th>
                            <th class="px-8 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-50">Shift Schedule</th>
                            <th class="px-8 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-50 text-right">Hours</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($schedules as $schedule)
                            <tr class="group hover:bg-slate-50/30 transition-colors">
                                <td class="px-8 py-4">
                                    <span class="text-sm font-bold text-slate-900">{{ $schedule->day_of_week }}</span>
                                </td>
                                <td class="px-8 py-4">
                                    <div class="flex items-center gap-2 text-sm font-medium text-slate-600">
                                        <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded text-[11px] font-bold">{{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}</span>
                                        <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded text-[11px] font-bold">{{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-4 text-right">
                                    <span class="text-sm font-bold text-slate-900 tabular-nums">{{ round(\Carbon\Carbon::parse($schedule->end_time)->diffInMinutes(\Carbon\Carbon::parse($schedule->start_time)) / 60, 1) }} hrs</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-8 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">No recurring schedules found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($schedules->isNotEmpty())
                <div class="px-8 py-4 bg-slate-50/20 border-t border-slate-50">
                    <div class="flex items-center justify-between text-[11px] font-bold uppercase tracking-wider">
                        <span class="text-slate-400">Total Weekly Load</span>
                        <span class="text-slate-900">{{ number_format($schedules->sum(fn($s) => round(\Carbon\Carbon::parse($s->end_time)->diffInMinutes(\Carbon\Carbon::parse($s->start_time)) / 60, 1)), 1) }} Hours per Week</span>
                    </div>
                </div>
            @endif
        </div>

        <!-- Integrity Notice -->
        <div class="p-6 bg-slate-900 rounded-3xl text-white shadow-xl flex items-start gap-6">
            <div class="w-10 h-10 rounded-xl bg-amber-500 flex items-center justify-center text-white shrink-0 shadow-lg shadow-amber-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <div>
                <h4 class="text-sm font-bold mb-1">Immutable Record Integrity</h4>
                <p class="text-xs text-slate-400 leading-relaxed font-medium mb-4">Official identifiers are masked for your security. To modify or correct any of these institutional records, you must submit a formal Correction Request through the discrepancy handling system.</p>
                <form action="{{ route('discrepancies.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="payroll_id" value=""> {{-- Passed as empty/null for General --}}
                    <input type="hidden" name="issue_type" value="Other">
                    <input type="hidden" name="description" value="Correction request for personal service records (TIN/SSS/PhilHealth/PagIBIG).">
                    <button type="submit" class="text-xs font-bold text-amber-500 hover:text-amber-400 transition-colors uppercase tracking-widest">
                        Submit Correction Request →
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
