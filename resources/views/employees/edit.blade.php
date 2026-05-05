<x-app-layout>
    <x-slot name="header">
        Modify Member Details
    </x-slot>

    <div class="max-w-3xl mx-auto mt-8">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-50 dark:border-slate-800 text-center">
                <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 italic tracking-tight">Profile Adjustment</h3>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-2">Editing: {{ $employee->name }}</p>
            </div>

            {{-- Success / Warning Messages --}}
            @if(session('success'))
                <div class="mx-8 mt-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-2xl">
                    <p class="text-sm font-bold text-emerald-700 dark:text-emerald-300 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        {{ session('success') }}
                    </p>
                </div>
            @endif
            @if(session('warning'))
                <div class="mx-8 mt-6 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-2xl">
                    <p class="text-sm font-bold text-amber-700 dark:text-amber-300">{{ session('warning') }}</p>
                </div>
            @endif

            <form action="{{ route('employees.update', $employee) }}" method="POST" enctype="multipart/form-data" class="p-4 space-y-4">
                @csrf
                @method('PATCH')
                
                <div class="space-y-6">
                    <div class="px-4 py-2 bg-slate-50 dark:bg-slate-800/50 rounded-xl text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Basic Information</div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Full Name</label>
                            <input type="text" name="name" value="{{ $employee->name }}" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Email Address</label>
                            <input type="email" name="email" value="{{ $employee->email }}" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-medium">
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="px-4 py-2 bg-slate-50 dark:bg-slate-800/50 rounded-xl text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">System Credentials</div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Employee ID</label>
                            <input type="text" name="employee_id" value="{{ $employee->employee_id }}" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-bold tracking-widest text-indigo-600">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">RFID Tag Num</label>
                            <input type="text" name="rfid_card_num" value="{{ $employee->rfid_card_num }}" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-medium">
                        </div>
                      <div class="space-y-2" x-data="{
    isEnrolling: false,
    status: '',
    pollInterval: null,
    timeoutHandle: null,

    startEnroll() {
        this.isEnrolling = true;
        this.status = 'Initiating...';

        fetch('{{ route('employees.enroll', $employee) }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(res => res.json())
        .then(data => {
            if (!data.action_id) {
                this.isEnrolling = false;
                this.status = 'Failed to start.';
                return;
            }
            this.status = 'Waiting for finger...';
            this.pollEnrollment(data.action_id);

            // Auto-cancel after 2 minutes if ESP32 never responds
            this.timeoutHandle = setTimeout(() => {
                clearInterval(this.pollInterval);
                this.isEnrolling = false;
                this.status = 'Timed out. Try again.';
            }, 120000);
        })
        .catch(() => {
            this.isEnrolling = false;
            this.status = 'Network error.';
        });
    },

    pollEnrollment(actionId) {
        this.pollInterval = setInterval(() => {
            fetch(`/biometrics/actions/${actionId}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    this.cleanup();
                    this.status = 'Enrolled! Reloading...';
                    setTimeout(() => window.location.reload(), 1500);

                } else if (data.status === 'failed') {
                    this.cleanup();
                    this.isEnrolling = false;
                    this.status = 'Enrollment failed. Try again.';

                } else if (data.status === 'expired') {
                    this.cleanup();
                    this.isEnrolling = false;
                    this.status = '';
                }
                // 'pending' → keep polling
            })
            .catch(() => {
                // Network blip — keep polling, don't cancel
            });
        }, 3000);
    },

    cleanup() {
        clearInterval(this.pollInterval);
        clearTimeout(this.timeoutHandle);
    }
}">

    <button type="button"
            @click="startEnroll"
            x-show="!isEnrolling"
            class="text-[10px] font-bold text-indigo-600 bg-indigo-50 dark:bg-indigo-900/30 px-3 py-1 rounded-lg hover:bg-indigo-100 transition flex items-center gap-1">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
        </svg>
        Enroll Now
    </button>

    <div x-show="isEnrolling" class="text-[10px] text-indigo-500 flex items-center gap-1">
        <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
        </svg>
        <span x-text="status"></span>
    </div>

    <p x-show="!isEnrolling && status !== ''"
       x-text="status"
       class="text-[10px] text-gray-400 mt-1">
    </p>
</div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Biometric Template (Legacy)</label>
                            <input type="text" name="biometric_template" value="{{ $employee->biometric_template }}" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-medium">
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="px-4 py-2 bg-slate-50 dark:bg-slate-800/50 rounded-xl text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Institutional Identifiers</div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">TIN (Tax ID)</label>
                            <input type="text" name="tin_id" value="{{ $employee->tin_id }}" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-medium" placeholder="000-000-000-000">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">SSS Number</label>
                            <input type="text" name="sss_id" value="{{ $employee->sss_id }}" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-medium" placeholder="00-0000000-0">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">PhilHealth ID</label>
                            <input type="text" name="philhealth_id" value="{{ $employee->philhealth_id }}" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-medium" placeholder="00-000000000-0">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Pag-IBIG Number</label>
                            <input type="text" name="pagibig_id" value="{{ $employee->pagibig_id }}" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-medium" placeholder="0000-0000-0000">
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="px-4 py-2 bg-slate-50 dark:bg-slate-800/50 rounded-xl text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Leave Balance Management</div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Sick Leave Credits</label>
                            <input type="number" step="0.5" name="sick_leave_credits" value="{{ $employee->sick_leave_credits }}" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-bold text-rose-600">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Vacation Leave Credits</label>
                            <input type="number" step="0.5" name="vacation_leave_credits" value="{{ $employee->vacation_leave_credits }}" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-bold text-emerald-600">
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="px-4 py-2 bg-slate-50 dark:bg-slate-800/50 rounded-xl text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Role & Compensation</div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Hourly Rate (₱)</label>
                            <input type="number" step="0.01" name="hourly_rate" value="{{ $employee->hourly_rate }}" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-bold">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Personnel Role</label>
                            <select name="role" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-bold">
                                <option value="professor" {{ $employee->role == 'professor' ? 'selected' : '' }}>Professor</option>
                                <option value="employee" {{ $employee->role == 'employee' ? 'selected' : '' }}>Staff / Regular</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- ═══════════════════════════════════════════════════ --}}
                {{-- SCHEDULE UPLOAD SECTION --}}
                {{-- ═══════════════════════════════════════════════════ --}}
                <div class="space-y-6">
                    <div class="px-4 py-2 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl text-[10px] font-bold text-indigo-500 uppercase tracking-[0.2em] flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Weekly Schedule (Excel Upload)
                    </div>

                    {{-- Current Schedule Preview --}}
                    @if($schedules->count() > 0)
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-700">
                            <div class="px-5 py-3 bg-slate-100/50 dark:bg-slate-700/30 flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Current Schedule</span>
                                @if($employee->schedule_file)
                                    <span class="text-[10px] font-bold text-indigo-500 bg-indigo-50 dark:bg-indigo-900/30 px-2 py-1 rounded-lg">
                                        📄 {{ basename($employee->schedule_file) }}
                                    </span>
                                @endif
                            </div>
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-700">
                                        <th class="px-5 py-2 text-left">Day</th>
                                        <th class="px-5 py-2 text-left">Start</th>
                                        <th class="px-5 py-2 text-left">End</th>
                                        <th class="px-5 py-2 text-left">Hours</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($schedules as $sched)
                                        <tr class="border-b border-slate-50 dark:border-slate-800 last:border-0 hover:bg-white dark:hover:bg-slate-800 transition">
                                            <td class="px-5 py-2.5 font-bold text-slate-700 dark:text-slate-200">{{ $sched->day_of_week }}</td>
                                            <td class="px-5 py-2.5 text-slate-500 font-medium">{{ \Carbon\Carbon::parse($sched->start_time)->format('h:i A') }}</td>
                                            <td class="px-5 py-2.5 text-slate-500 font-medium">{{ \Carbon\Carbon::parse($sched->end_time)->format('h:i A') }}</td>
                                            <td class="px-5 py-2.5">
                                                <span class="text-xs font-bold text-indigo-600 bg-indigo-50 dark:bg-indigo-900/30 px-2 py-0.5 rounded-lg">
                                                    {{ $sched->scheduled_hours }}h
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bg-amber-50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-800/30 rounded-2xl p-5 text-center">
                            <svg class="w-8 h-8 text-amber-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                            <p class="text-sm font-bold text-amber-700 dark:text-amber-400">No schedule uploaded yet</p>
                            <p class="text-xs text-amber-500/70 mt-1">Upload an Excel file below to set this employee's weekly schedule.</p>
                        </div>
                    @endif

                    {{-- Upload New Schedule --}}
                    <div class="relative group">
                        <label class="block">
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-2 block">
                                {{ $schedules->count() > 0 ? 'Replace Schedule (Upload New Excel)' : 'Upload Schedule Excel' }}
                            </span>
                            <div class="flex items-center gap-4">
                                <div class="flex-1 relative">
                                    <div id="schedule-dropzone" class="flex items-center justify-center px-5 py-6 border-2 border-slate-200 dark:border-slate-700 border-dashed rounded-2xl hover:border-indigo-400 transition-all cursor-pointer group-hover:bg-indigo-50/30 dark:group-hover:bg-indigo-900/10">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-8 h-8 text-slate-300 group-hover:text-indigo-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                            <div>
                                                <p class="text-sm font-bold text-slate-600 dark:text-slate-300" id="schedule-file-label">Choose .xlsx or .csv file</p>
                                                <p class="text-[10px] text-slate-400 mt-0.5">Columns: day_of_week, start_time, end_time</p>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="file" name="schedule_file" accept=".xlsx,.xls,.csv" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" 
                                           onchange="document.getElementById('schedule-file-label').innerText = this.files[0].name; document.getElementById('schedule-dropzone').classList.add('border-indigo-500', 'bg-indigo-50/50');">
                                </div>
                            </div>
                        </label>
                    </div>

                    {{-- Format Reference & Template Download --}}
                    <div class="bg-indigo-50 dark:bg-indigo-900/20 p-5 rounded-2xl border border-indigo-100 dark:border-indigo-900/30">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                Expected Format
                            </h4>
                            <a href="{{ route('schedule.template') }}" class="text-[10px] font-bold text-indigo-600 hover:text-indigo-800 bg-white dark:bg-slate-800 px-3 py-1.5 rounded-lg shadow-sm hover:shadow transition flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Download Template
                            </a>
                        </div>
                        <div class="overflow-hidden rounded-xl border border-indigo-100 dark:border-indigo-900/40">
                            <table class="w-full text-[10px]">
                                <thead>
                                    <tr class="bg-indigo-100/50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 font-bold uppercase tracking-widest">
                                        <th class="px-3 py-2 text-left">day_of_week</th>
                                        <th class="px-3 py-2 text-left">start_time</th>
                                        <th class="px-3 py-2 text-left">end_time</th>
                                    </tr>
                                </thead>
                                <tbody class="text-indigo-600/70 dark:text-indigo-400/70 font-medium">
                                    <tr class="border-t border-indigo-50 dark:border-indigo-900/20"><td class="px-3 py-1.5">Monday</td><td class="px-3 py-1.5">08:00</td><td class="px-3 py-1.5">12:00</td></tr>
                                    <tr class="border-t border-indigo-50 dark:border-indigo-900/20"><td class="px-3 py-1.5">Tuesday</td><td class="px-3 py-1.5">13:00</td><td class="px-3 py-1.5">17:00</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex justify-between items-center">
                    <a href="{{ route('employees.index') }}" class="text-sm font-bold text-slate-400 hover:text-rose-600 transition">Discard Changes</a>
                    <button type="submit" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-xl hover:bg-indigo-700 hover:-translate-y-1 transition duration-300">
                        Update Record
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
