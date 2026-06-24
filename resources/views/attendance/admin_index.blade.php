<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="text-slate-400 font-medium">Organization</span>
            <span class="text-slate-300">/</span>
            <span class="font-bold text-slate-800">Presence Master List</span>
        </div>
    </x-slot>

    <div x-data="{ 
        editModal: false, 
        addModal: {{ $errors->any() ? 'true' : 'false' }},
        activeLog: { id: '', user_id: '', date: '', time_in: '', time_out: '', status: '' },
        openEdit(log) {
            this.activeLog = { ...log };
            this.editModal = true;
        }
    }" class="space-y-6">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Presence Master List</h1>
                <p class="text-sm text-slate-500 mt-1">Audit daily check-ins, check-outs, status parameters, and manual overrides.</p>
            </div>
        </div>

        {{-- Filters & Actions Card --}}
        <div class="card-reference p-6 bg-white/95 backdrop-blur-xl border border-slate-200/60 shadow-lg">
            <form action="{{ route('attendance.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    {{-- From Date --}}
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">From Date</label>
                        <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-semibold">
                    </div>
                    {{-- To Date --}}
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">To Date</label>
                        <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-semibold">
                    </div>
                    {{-- Employee Selection --}}
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Employee</label>
                        <select name="user_id" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-bold">
                            <option value="">All Employees</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ ($filters['user_id'] ?? '') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Status Selection --}}
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</label>
                        <select name="status" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-bold">
                            <option value="">All Statuses</option>
                            <option value="On-time" {{ ($filters['status'] ?? '') == 'On-time' ? 'selected' : '' }}>On-time</option>
                            <option value="Late" {{ ($filters['status'] ?? '') == 'Late' ? 'selected' : '' }}>Late Only</option>
                        </select>
                    </div>
                </div>

                {{-- Action Controls Row --}}
                <div class="flex items-center justify-between gap-3 pt-2 border-t border-slate-100/80 flex-wrap">
                    <div class="flex items-center gap-2.5">
                        <button type="submit" class="btn-action-indigo text-xs py-2 px-4 shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Filter Search
                        </button>
                        @if(!empty($filters))
                            <a href="{{ route('attendance.index') }}" class="text-xs font-bold text-slate-400 hover:text-rose-500 transition-colors">Clear Filters</a>
                        @endif
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <a href="{{ route('attendance.export', request()->all()) }}" class="btn-action-secondary text-xs py-2 px-3 shadow-sm border-slate-200 bg-emerald-50/50 hover:bg-emerald-50 text-emerald-700 hover:text-emerald-800 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span>Export CSV</span>
                        </a>
                        <button type="button" @click="addModal = true" class="btn-action-indigo text-xs py-2 px-4 shadow-sm flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            <span>Add Manual Log</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Desktop Master Table Card --}}
        <div class="card-reference overflow-hidden bg-white/95 backdrop-blur-xl border border-slate-200/60 shadow-lg">
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            <th class="px-6 py-4">Employee</th>
                            <th class="px-6 py-4">Date / Day</th>
                            <th class="px-6 py-4">Check In</th>
                            <th class="px-6 py-4">Check Out</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Hrs Worked</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($logs as $log)
                            <tr class="hover:bg-indigo-50/10 transition group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-indigo-600 font-black text-sm shadow-sm group-hover:bg-gradient-to-tr group-hover:from-indigo-600 group-hover:to-violet-500 group-hover:text-white group-hover:border-transparent transition-all duration-300 shrink-0">
                                            {{ strtoupper(substr($log->user->name ?? '?', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-extrabold text-slate-900 tracking-tight">{{ $log->user->name ?? 'Unknown' }}</div>
                                            <div class="text-[10px] text-slate-400 font-bold tracking-wider uppercase mt-0.5">{{ $log->user->employee_id ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-extrabold text-slate-700">{{ $log->date->format('M d, Y') }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase mt-0.5 tracking-wider">{{ $log->date->format('l') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-50 border border-slate-100 rounded-lg text-xs font-bold text-slate-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        {{ $log->time_in }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($log->time_out)
                                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-50 border border-slate-100 rounded-lg text-xs font-bold text-slate-600">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            {{ $log->time_out }}
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[9px] font-bold bg-amber-50 text-amber-600 border border-amber-100 uppercase tracking-widest animate-pulse">Active</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($log->status === 'Late')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-100 uppercase tracking-wider">Late</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-wider">On-time</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($log->time_in && $log->time_out)
                                        @php
                                            $in = \Carbon\Carbon::parse($log->date->toDateString() . ' ' . $log->time_in);
                                            $out = \Carbon\Carbon::parse($log->date->toDateString() . ' ' . $log->time_out);
                                            $dayName = $log->date->format('l');
                                            $daySchedules = $log->user->schedules->where('day_of_week', $dayName);
                                            if ($daySchedules->isNotEmpty()) {
                                                $overlapHours = 0;
                                                foreach ($daySchedules as $sched) {
                                                    $schedStart = \Carbon\Carbon::parse($log->date->toDateString() . ' ' . $sched->start_time);
                                                    $schedEnd = \Carbon\Carbon::parse($log->date->toDateString() . ' ' . $sched->end_time);
                                                    $overlapStart = $in->greaterThan($schedStart) ? $in : $schedStart;
                                                    $overlapEnd = $out->lessThan($schedEnd) ? $out : $schedEnd;
                                                    if ($overlapStart->lessThan($overlapEnd)) {
                                                        $overlapHours += $overlapStart->diffInSeconds($overlapEnd) / 3600;
                                                    }
                                                }
                                                $hrs = number_format($overlapHours, 2);
                                            } else {
                                                $hrs = number_format($in->diffInMinutes($out) / 60, 2);
                                            }
                                        @endphp
                                        <div class="text-sm font-extrabold text-slate-700">{{ $hrs }} <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Hrs</span></div>
                                    @else
                                        <span class="text-slate-300 font-bold">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition duration-200">
                                        <button @click="openEdit({{ $log->toJson() }})" class="p-2 text-indigo-600 hover:bg-indigo-50 border border-transparent hover:border-indigo-100 rounded-xl transition-all" title="Edit Log">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                        </button>
                                        <form action="{{ route('attendance.destroy', $log) }}" method="POST" onsubmit="return confirm('Secure delete attendance record? This will affect payroll.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-100 rounded-xl transition-all" title="Delete Log">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center text-slate-400 italic text-sm">
                                    No attendance logs matching the current criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $logs->links() }}
                </div>
            @endif

            <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 text-[10px] font-bold text-slate-455 uppercase tracking-widest flex justify-between items-center">
                <span>Tamper-evident presence ledger</span>
                <span class="tabular-nums">{{ $logs->total() }} Log Entries</span>
            </div>
        </div>

        {{-- Mobile Card List --}}
        <div class="lg:hidden space-y-3">
            @forelse($logs as $log)
                <div class="card-reference p-4 bg-white/95 backdrop-blur-xl border border-slate-200/60 shadow-md">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-indigo-600 font-black text-sm shrink-0">
                                {{ strtoupper(substr($log->user->name ?? '?', 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-sm font-extrabold text-slate-900 leading-tight">{{ $log->user->name ?? 'Unknown' }}</div>
                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $log->user->employee_id ?? '' }}</div>
                            </div>
                        </div>
                        @if($log->status === 'Late')
                            <span class="px-2 py-0.5 rounded-lg text-[9px] font-bold border border-rose-100 bg-rose-50 text-rose-600 uppercase tracking-wider">Late</span>
                        @else
                            <span class="px-2 py-0.5 rounded-lg text-[9px] font-bold border border-emerald-100 bg-emerald-50 text-emerald-600 uppercase tracking-wider">On-time</span>
                        @endif
                    </div>
                    
                    <div class="grid grid-cols-3 gap-2 text-center">
                        <div class="bg-slate-50/50 border border-slate-100 rounded-xl p-2">
                            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Date</div>
                            <div class="text-xs font-extrabold text-slate-700">{{ $log->date->format('M d') }}</div>
                            <div class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">{{ $log->date->format('D') }}</div>
                        </div>
                        <div class="bg-slate-50/50 border border-slate-100 rounded-xl p-2">
                            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">In</div>
                            <div class="text-xs font-extrabold text-emerald-600">{{ $log->time_in ?? '—' }}</div>
                        </div>
                        <div class="bg-slate-50/50 border border-slate-100 rounded-xl p-2">
                            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Out</div>
                            <div class="text-xs font-extrabold text-slate-700">{{ $log->time_out ?? '—' }}</div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end gap-2 mt-3 pt-3 border-t border-slate-100/80">
                        <button @click="openEdit({{ $log->toJson() }})" class="flex items-center gap-1.5 px-3 py-1.5 text-indigo-600 hover:bg-indigo-50 border border-indigo-100/50 rounded-xl text-xs font-bold transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                            Edit
                        </button>
                        <form action="{{ route('attendance.destroy', $log) }}" method="POST" onsubmit="return confirm('Delete this log?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="flex items-center gap-1.5 px-3 py-1.5 text-rose-500 hover:bg-rose-50 border border-rose-100/50 rounded-xl text-xs font-bold transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="card-reference p-12 text-center text-slate-400 italic text-sm bg-white border border-slate-200/60 shadow-md">
                    No attendance logs found.
                </div>
            @endforelse
        </div>

        {{-- Add Manual Log Modal --}}
        <div x-show="addModal" x-cloak class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="addModal = false"></div>
            <div class="bg-white/95 backdrop-blur-xl border border-slate-100 rounded-t-2xl sm:rounded-2xl shadow-2xl w-full sm:max-w-md relative p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Manual Entry Log</h3>

                {{-- Validation Errors --}}
                @if($errors->any())
                    <div class="mb-3 p-3 bg-rose-50 border border-rose-200 rounded-xl">
                        <ul class="text-[11px] font-semibold text-rose-600 space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('attendance.store') }}" method="POST" class="space-y-3"
                      @submit="
                          let ti = $refs.addTimeIn.value;
                          $refs.addTimeInHidden.value = ti && ti.split(':').length === 2 ? ti + ':00' : ti;
                          let to = $refs.addTimeOut.value;
                          $refs.addTimeOutHidden.value = to && to.split(':').length === 2 ? to + ':00' : (to || '');
                      ">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <div class="col-span-2">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Employee</label>
                            <select name="user_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-bold">
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ old('user_id') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Date</label>
                            <input type="date" name="date" required value="{{ old('date', date('Y-m-d')) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-semibold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Status</label>
                            <select name="status" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-bold">
                                <option value="On-time" {{ old('status') == 'On-time' ? 'selected' : '' }}>On-time</option>
                                <option value="Late" {{ old('status') == 'Late' ? 'selected' : '' }}>Late</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Time In</label>
                            <input type="time" x-ref="addTimeIn" step="1" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-bold">
                            <input type="hidden" name="time_in" x-ref="addTimeInHidden">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Time Out</label>
                            <input type="time" x-ref="addTimeOut" step="1" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-bold">
                            <input type="hidden" name="time_out" x-ref="addTimeOutHidden">
                        </div>
                    </div>
                    <input type="hidden" name="source" value="Admin Manual">
                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="addModal = false" class="flex-1 btn-action-secondary py-2.5 text-xs">Cancel</button>
                        <button type="submit" class="flex-1 btn-action-indigo py-2.5 text-xs">Save Log</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Edit Modal --}}
        <div x-show="editModal" x-cloak class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="editModal = false"></div>
            <div class="bg-white/95 backdrop-blur-xl border border-slate-100 rounded-t-2xl sm:rounded-2xl shadow-2xl w-full sm:max-w-md relative p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Adjust Presence Log</h3>
                <form :action="'{{ url('attendance') }}/' + activeLog.id" method="POST" class="space-y-3"
                      @submit="
                          let ti = $refs.editTimeIn.value;
                          $refs.editTimeInHidden.value = ti && ti.split(':').length === 2 ? ti + ':00' : ti;
                          let to = $refs.editTimeOut.value;
                          $refs.editTimeOutHidden.value = to && to.split(':').length === 2 ? to + ':00' : (to || '');
                      ">
                    @csrf @method('PATCH')
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Employee (Read-only)</label>
                        <div class="p-2.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-extrabold text-slate-600" x-text="activeLog.user?.name"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Time In</label>
                            <input type="time" step="1" x-ref="editTimeIn" :value="activeLog.time_in" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-bold">
                            <input type="hidden" name="time_in" x-ref="editTimeInHidden">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Time Out</label>
                            <input type="time" step="1" x-ref="editTimeOut" :value="activeLog.time_out" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-bold">
                            <input type="hidden" name="time_out" x-ref="editTimeOutHidden">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Status</label>
                        <select name="status" x-model="activeLog.status" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-bold">
                            <option value="On-time">On-time</option>
                            <option value="Late">Late</option>
                        </select>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="editModal = false" class="flex-1 btn-action-secondary py-2.5 text-xs">Cancel</button>
                        <button type="submit" class="flex-1 btn-action-indigo py-2.5 text-xs">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Help Tooltip Widget --}}
    <div class="fixed bottom-6 right-6 group z-40">
        <div class="absolute bottom-full right-0 mb-4 w-64 p-4 bg-slate-900 text-white text-[11px] rounded-2xl shadow-2xl opacity-0 translate-y-2 group-hover:opacity-100 group-hover:translate-y-0 transition-all pointer-events-none border border-white/10">
            <div class="font-bold text-white uppercase tracking-widest mb-2 border-b border-white/10 pb-2">Admin Guide</div>
            <p class="leading-relaxed opacity-80 font-medium">Manual logs are marked as "Admin Manual". Adjusting logs here will automatically recalculate associated payroll records on the next sync.</p>
        </div>
        <div class="w-10 h-10 bg-slate-900 text-white rounded-full flex items-center justify-center shadow-2xl cursor-help hover:scale-110 transition active:scale-95 border border-white/10">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>
</x-app-layout>
