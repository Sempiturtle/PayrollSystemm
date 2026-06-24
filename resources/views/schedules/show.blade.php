<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <div class="flex items-center gap-3">
                <span class="text-slate-400 font-medium text-sm">Schedule Matrix</span>
                <span class="text-slate-300 text-sm">/</span>
                <span class="font-bold text-slate-800 text-sm">{{ $user->name }}'s Timetable</span>
            </div>
        </div>
    </x-slot>

    <div x-data="{ 
        showAddModal: false, 
        showEditModal: false, 
        editSlot: { id: '', day_of_week: 'Monday', start_time: '', end_time: '', effective_from: '' },
        openEdit(slot) {
            this.editSlot = { ...slot };
            // Format start_time and end_time to HH:MM for time inputs
            this.editSlot.start_time = slot.start_time.substring(0, 5);
            this.editSlot.end_time = slot.end_time.substring(0, 5);
            this.editSlot.effective_from = slot.effective_from ? slot.effective_from.substring(0, 10) : '';
            this.showEditModal = true;
        }
    }" class="max-w-6xl mx-auto space-y-6">
        
        <!-- Employee Info Header Card -->
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg shadow-sm border border-indigo-100">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 tracking-tight">{{ $user->name }}</h3>
                        <div class="flex items-center gap-2.5 mt-1 text-xs">
                            <span class="text-slate-400 font-semibold tracking-wider uppercase">{{ $user->employee_id }}</span>
                            <span class="text-slate-300">•</span>
                            <span class="px-2 py-0.5 rounded-full font-bold uppercase text-[9px] {{ $user->role === 'professor' ? 'bg-purple-50 text-purple-600 border border-purple-100' : 'bg-blue-50 text-blue-600 border border-blue-100' }}">
                                {{ $user->role }}
                            </span>
                            @if($user->schedule_file)
                                <span class="text-slate-300">•</span>
                                <span class="text-slate-400 font-mono text-[10px] bg-slate-50 px-2 py-0.5 rounded border border-slate-200/40">File: {{ basename($user->schedule_file) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button @click="showAddModal = true" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition shadow-md shadow-indigo-100 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Add Class Slot
                    </button>
                    <a href="{{ route('schedules.index') }}" class="px-4 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Back to Directory
                    </a>
                </div>
            </div>
        </div>

        <!-- Weekly Timetable -->
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                <div>
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Weekly Timetable</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5">Professor Shift Calendar & Class Times</p>
                </div>
                <span class="text-xs font-bold text-indigo-600 bg-indigo-50 border border-indigo-100 px-3 py-1 rounded-full">
                    @php
                        $totalHours = $schedules->sum(function($s) {
                            return round(\Carbon\Carbon::parse($s->end_time)->diffInMinutes(\Carbon\Carbon::parse($s->start_time)) / 60, 1);
                        });
                    @endphp
                    {{ number_format($totalHours, 1) }} Hours / week
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 lg:grid-cols-7 divide-y sm:divide-y-0 lg:divide-x divide-slate-100">
                @php
                    $allDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                    $shortDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                    $today = now()->format('l');
                @endphp
                
                @foreach($allDays as $i => $day)
                    @php
                        $daySchedules = $schedules->where('day_of_week', $day);
                        $isToday = ($day === $today);
                    @endphp
                    <div class="p-5 text-center transition-all duration-300 relative group {{ $isToday ? 'bg-indigo-50/50 ring-2 ring-indigo-500/30 ring-inset' : 'hover:bg-slate-50/50' }} flex flex-col justify-between min-h-[160px]">
                        @if($isToday)
                            <div class="absolute top-2 left-1/2 -translate-x-1/2">
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-extrabold bg-indigo-600 text-white uppercase tracking-wider">Today</span>
                            </div>
                        @endif
                        <div class="text-[10px] font-extrabold uppercase tracking-widest {{ $isToday ? 'text-indigo-600 mt-2' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors mb-4">{{ $shortDays[$i] }}</div>
                        
                        @if($daySchedules->count() > 0)
                            <div class="space-y-3 py-1 flex-1 flex flex-col justify-center">
                                @foreach($daySchedules as $sched)
                                    <div @click="openEdit({{ $sched->toJson() }})" class="space-y-0.5 relative group/slot cursor-pointer hover:bg-indigo-100/30 hover:scale-[1.03] active:scale-[0.98] transition-all duration-200 p-2 rounded-xl border border-transparent hover:border-indigo-100/50">
                                        <div class="font-extrabold text-xs text-slate-800 tracking-tight leading-none">
                                            {{ \Carbon\Carbon::parse($sched->start_time)->format('h:i') }} <span class="text-[8px] font-semibold text-slate-400">{{ \Carbon\Carbon::parse($sched->start_time)->format('A') }}</span>
                                        </div>
                                        <div class="text-[8px] text-slate-300 font-bold uppercase my-0.5">to</div>
                                        <div class="font-extrabold text-xs text-slate-800 tracking-tight leading-none">
                                            {{ \Carbon\Carbon::parse($sched->end_time)->format('h:i') }} <span class="text-[8px] font-semibold text-slate-400">{{ \Carbon\Carbon::parse($sched->end_time)->format('A') }}</span>
                                        </div>
                                        <div class="text-[8px] font-bold text-slate-400 mt-1">
                                            {{ round(\Carbon\Carbon::parse($sched->end_time)->diffInMinutes(\Carbon\Carbon::parse($sched->start_time)) / 60, 1) }}h
                                        </div>
                                    </div>
                                    @if(!$loop->last)
                                        <div class="border-t border-slate-100 my-1.5"></div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="py-5 flex-1 flex items-center justify-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-bold {{ $isToday ? 'bg-indigo-100/50 text-indigo-700' : 'bg-slate-100 text-slate-400' }} uppercase tracking-wider">
                                    Off
                                </span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Detailed List View (CRUD Actions) -->
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Detailed Schedule List</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5">Manage and sync individual class blocks</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-4 text-[9px] font-bold text-slate-400 uppercase tracking-wider">Day</th>
                            <th class="px-6 py-4 text-[9px] font-bold text-slate-400 uppercase tracking-wider">Start Time</th>
                            <th class="px-6 py-4 text-[9px] font-bold text-slate-400 uppercase tracking-wider">End Time</th>
                            <th class="px-6 py-4 text-[9px] font-bold text-slate-400 uppercase tracking-wider">Duration</th>
                            <th class="px-6 py-4 text-[9px] font-bold text-slate-400 uppercase tracking-wider">Effective From</th>
                            <th class="px-6 py-4 text-[9px] font-bold text-slate-400 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($schedules as $sched)
                            @php $isToday = ($sched->day_of_week === $today); @endphp
                            <tr class="hover:bg-slate-50/40 transition group">
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold {{ $isToday ? 'text-indigo-700' : 'text-slate-800' }}">
                                        {{ $sched->day_of_week }}
                                    </span>
                                    @if($isToday)
                                        <span class="ml-2 text-[8px] font-extrabold text-indigo-600 bg-indigo-50 border border-indigo-100 px-1.5 py-0.5 rounded uppercase tracking-wider">Today</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-slate-650 font-mono">
                                    {{ \Carbon\Carbon::parse($sched->start_time)->format('h:i A') }}
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-slate-650 font-mono">
                                    {{ \Carbon\Carbon::parse($sched->end_time)->format('h:i A') }}
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-slate-500 font-mono">
                                    {{ round(\Carbon\Carbon::parse($sched->end_time)->diffInMinutes(\Carbon\Carbon::parse($sched->start_time)) / 60, 1) }} Hrs
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-400 font-bold italic">
                                    {{ $sched->effective_from ? $sched->effective_from->format('M d, Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                        <button @click="openEdit({{ $sched->toJson() }})" class="p-1.5 text-slate-400 hover:text-indigo-650 hover:bg-slate-50 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                        </button>
                                        <form action="{{ route('schedules.destroy_item', $sched->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this schedule slot?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-16 text-center text-slate-400 italic text-xs font-semibold uppercase tracking-wider">
                                    No schedule slots defined for this user.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Alpine Modals -->

        <!-- Add Modal -->
        <div x-show="showAddModal" class="fixed inset-0 z-[110] overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm" @click="showAddModal = false"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border border-slate-200">
                    <div class="bg-slate-900 p-5 text-white">
                        <h3 class="text-sm font-bold uppercase tracking-wider">Add Class Schedule Slot</h3>
                        <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-widest">Create manual schedule entry</p>
                    </div>
                    <form action="{{ route('schedules.store', $user->id) }}" method="POST" class="p-5 space-y-4">
                        @csrf
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Day of Week</label>
                            <select name="day_of_week" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-850 p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                                <option value="Sunday">Sunday</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Start Time</label>
                                <input type="time" name="start_time" required class="w-full bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-850 p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">End Time</label>
                                <input type="time" name="end_time" required class="w-full bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-850 p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Effective From (Optional)</label>
                            <input type="date" name="effective_from" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-850 p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                        </div>
                        <div class="flex gap-3 pt-2">
                            <button type="button" @click="showAddModal = false" class="flex-1 py-3 bg-slate-100 text-slate-650 hover:bg-slate-200/60 rounded-xl font-bold text-[10px] uppercase tracking-wider transition">Cancel</button>
                            <button type="submit" class="flex-1 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-[10px] uppercase tracking-wider shadow-md transition">Save Slot</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div x-show="showEditModal" class="fixed inset-0 z-[110] overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm" @click="showEditModal = false"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border border-slate-200">
                    <div class="bg-slate-900 p-5 text-white">
                        <h3 class="text-sm font-bold uppercase tracking-wider">Edit Class Schedule Slot</h3>
                        <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-widest">Modify manual schedule entry</p>
                    </div>
                    <form :action="'{{ url('/schedules/item') }}/' + editSlot.id" method="POST" class="p-5 space-y-4">
                        @csrf
                        @method('PATCH')
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Day of Week</label>
                            <select name="day_of_week" x-model="editSlot.day_of_week" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-850 p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                                <option value="Sunday">Sunday</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Start Time</label>
                                <input type="time" name="start_time" x-model="editSlot.start_time" required class="w-full bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-850 p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">End Time</label>
                                <input type="time" name="end_time" x-model="editSlot.end_time" required class="w-full bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-850 p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Effective From (Optional)</label>
                            <input type="date" name="effective_from" x-model="editSlot.effective_from" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-850 p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                        </div>
                        <div class="flex gap-3 pt-2">
                            <button type="button" onclick="if(confirm('Are you sure you want to delete this schedule slot?')) document.getElementById('delete-slot-modal-form').submit()" class="px-4 py-3 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl font-bold text-[10px] uppercase tracking-wider transition">Delete</button>
                            <button type="button" @click="showEditModal = false" class="flex-1 py-3 bg-slate-100 text-slate-650 hover:bg-slate-200/60 rounded-xl font-bold text-[10px] uppercase tracking-wider transition">Cancel</button>
                            <button type="submit" class="flex-grow py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-[10px] uppercase tracking-wider shadow-md transition">Update Slot</button>
                        </div>
                    </form>
                    <!-- Delete Form for Modal Action -->
                    <form id="delete-slot-modal-form" :action="'{{ url('/schedules/item') }}/' + editSlot.id" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
