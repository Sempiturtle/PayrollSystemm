<x-app-layout>
    <x-slot name="header">
        Modify Member Details
    </x-slot>

    <div class="max-w-7xl mx-auto mt-4">
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="py-3 px-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50/50">
                <div>
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-100 tracking-tight">Profile Adjustment</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Editing: {{ $employee->name }}</p>
                </div>
                <a href="{{ route('employees.index') }}" class="text-xs font-bold text-slate-400 hover:text-rose-600 transition">Discard Changes</a>
            </div>

            {{-- Success / Warning Messages --}}
            @if(session('success'))
                <div class="mx-6 mt-4 p-3 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 rounded-xl">
                    <p class="text-xs font-bold text-emerald-700 dark:text-emerald-300 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        {{ session('success') }}
                    </p>
                </div>
            @endif
            @if(session('warning'))
                <div class="mx-6 mt-4 p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800 rounded-xl">
                    <p class="text-xs font-bold text-amber-700 dark:text-amber-300">{{ session('warning') }}</p>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('employees.update', $employee) }}" method="POST" enctype="multipart/form-data" class="p-4">
                @csrf
                @method('PATCH')
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    
                    {{-- Column 1: Personal & Role Details --}}
                    <div class="space-y-3">
                        <!-- Basic Information -->
                        <div class="space-y-2">
                            <div class="px-2 py-0.5 bg-slate-50 dark:bg-slate-800/50 rounded text-[9px] font-bold text-slate-400 uppercase tracking-wider">Basic Information</div>
                            <div class="grid grid-cols-2 gap-2">
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">Full Name</label>
                                    <input type="text" name="name" value="{{ $employee->name }}" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1.5 px-2.5 focus:ring-2 focus:ring-indigo-600/20 text-xs font-medium">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">Email Address</label>
                                    <input type="email" name="email" value="{{ $employee->email }}" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1.5 px-2.5 focus:ring-2 focus:ring-indigo-600/20 text-xs font-medium">
                                </div>
                            </div>
                        </div>

                        <!-- Employment Type & Compensation -->
                        <div class="space-y-2.5" x-data="{ empType: '{{ $employee->employment_type ?? 'professor' }}' }">
                            <div class="px-2 py-0.5 bg-slate-50 dark:bg-slate-800/50 rounded text-[9px] font-bold text-slate-400 uppercase tracking-wider">Employment Type & Compensation</div>
                            
                            <div class="grid grid-cols-3 gap-2">
                                {{-- Employment Type Selector --}}
                                <div class="col-span-1 space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">Type</label>
                                    <select name="employment_type" x-model="empType" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1.5 px-2 focus:ring-2 focus:ring-indigo-600/20 text-xs font-bold">
                                        <option value="professor" {{ ($employee->employment_type ?? 'professor') == 'professor' ? 'selected' : '' }}>Professor</option>
                                        <option value="staff" {{ ($employee->employment_type ?? '') == 'staff' ? 'selected' : '' }}>Staff</option>
                                        <option value="part_time" {{ ($employee->employment_type ?? '') == 'part_time' ? 'selected' : '' }}>Part-Time</option>
                                    </select>
                                </div>

                                {{-- Dynamic Rate/Salary --}}
                                <div class="col-span-1 space-y-1">
                                    <div x-show="empType === 'professor'">
                                        <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">Rate (₱)</label>
                                        <input type="number" step="0.01" name="hourly_rate" value="{{ $employee->hourly_rate }}" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1.5 px-2 focus:ring-2 focus:ring-indigo-600/20 text-xs font-bold" :required="empType === 'professor'">
                                    </div>
                                    <div x-show="empType !== 'professor'" style="display:none;">
                                        <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">Salary (₱)</label>
                                        <input type="number" step="0.01" name="monthly_salary" value="{{ $employee->monthly_salary }}" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1.5 px-2 focus:ring-2 focus:ring-indigo-600/20 text-xs font-bold text-emerald-600" :required="empType !== 'professor'">
                                    </div>
                                </div>

                                {{-- System Role --}}
                                <div class="col-span-1 space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">System Role</label>
                                    <select name="role" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1.5 px-2 focus:ring-2 focus:ring-indigo-600/20 text-xs font-bold">
                                        <option value="professor" {{ $employee->role == 'professor' ? 'selected' : '' }}>Professor</option>
                                        <option value="employee" {{ $employee->role == 'employee' ? 'selected' : '' }}>Staff</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Info Badge --}}
                            <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[9px] font-semibold transition-all"
                                 :class="empType === 'professor' ? 'bg-violet-50 text-violet-600' : (empType === 'staff' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600')">
                                <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                <span x-show="empType === 'professor'">Paid hourly. No overtime.</span>
                                <span x-show="empType === 'staff'">Fixed salary. Overtime at 1.25×.</span>
                                <span x-show="empType === 'part_time'">Fixed salary. No overtime.</span>
                            </div>
                        </div>

                        <!-- Leave Balance Management -->
                        <div class="space-y-2">
                            <div class="px-2 py-0.5 bg-slate-50 dark:bg-slate-800/50 rounded text-[9px] font-bold text-slate-400 uppercase tracking-wider">Leave Balances</div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">Sick Credits</label>
                                    <input type="number" step="0.5" name="sick_leave_credits" value="{{ $employee->sick_leave_credits }}" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1.5 px-2.5 focus:ring-2 focus:ring-indigo-600/20 text-xs font-bold text-rose-600">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">Vacation Credits</label>
                                    <input type="number" step="0.5" name="vacation_leave_credits" value="{{ $employee->vacation_leave_credits }}" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1.5 px-2.5 focus:ring-2 focus:ring-indigo-600/20 text-xs font-bold text-emerald-600">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Column 2: Credentials & Identifiers --}}
                    <div class="space-y-3">
                        <!-- System Credentials -->
                        <div class="space-y-2">
                            <div class="px-2 py-0.5 bg-slate-50 dark:bg-slate-800/50 rounded text-[9px] font-bold text-slate-400 uppercase tracking-wider">System Credentials</div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">Employee ID</label>
                                    <input type="text" name="employee_id" value="{{ $employee->employee_id }}" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1.5 px-2.5 focus:ring-2 focus:ring-indigo-600/20 text-xs font-bold tracking-widest text-indigo-600">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">RFID Tag Num</label>
                                    <input type="text" name="rfid_card_num" value="{{ $employee->rfid_card_num }}" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1.5 px-2.5 focus:ring-2 focus:ring-indigo-600/20 text-xs font-medium">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1" x-data="{
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
                                                this.status = 'Failed...';
                                                return;
                                            }
                                            this.status = 'Finger...';
                                            this.pollEnrollment(data.action_id);
                                            this.timeoutHandle = setTimeout(() => {
                                                clearInterval(this.pollInterval);
                                                this.isEnrolling = false;
                                                this.status = 'Timeout';
                                            }, 120000);
                                        })
                                        .catch(() => {
                                            this.isEnrolling = false;
                                            this.status = 'Err';
                                        });
                                    },
                                    pollEnrollment(actionId) {
                                        this.pollInterval = setInterval(() => {
                                            fetch(`/biometrics/actions/${actionId}`)
                                            .then(res => res.json())
                                            .then(data => {
                                                if (data.status === 'success') {
                                                    this.cleanup();
                                                    this.status = 'Done!';
                                                    setTimeout(() => window.location.reload(), 1500);
                                                } else if (data.status === 'failed') {
                                                    this.cleanup();
                                                    this.isEnrolling = false;
                                                    this.status = 'Failed';
                                                } else if (data.status === 'expired') {
                                                    this.cleanup();
                                                    this.isEnrolling = false;
                                                    this.status = '';
                                                }
                                            }).catch(() => {});
                                        }, 3000);
                                    },
                                    cleanup() {
                                        clearInterval(this.pollInterval);
                                        clearTimeout(this.timeoutHandle);
                                    }
                                }">
                                    <div class="flex items-center justify-between">
                                        <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">Fingerprint ID</label>
                                        <button type="button" @click="startEnroll" x-show="!isEnrolling" class="text-[8px] font-bold text-indigo-600 bg-indigo-50 px-1.5 py-0.5 rounded hover:bg-indigo-100 transition">
                                            Enroll Now
                                        </button>
                                    </div>
                                    <div x-show="isEnrolling" class="text-[10px] text-indigo-500 py-1 flex items-center gap-1">
                                        <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                                        <span x-text="status"></span>
                                    </div>
                                    <input type="number" name="fingerprint_id" value="{{ $employee->fingerprint_id }}" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1.5 px-2.5 focus:ring-2 focus:ring-indigo-600/20 text-xs font-bold text-indigo-600" placeholder="e.g. 1">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">Biometric Template</label>
                                    <input type="text" name="biometric_template" value="{{ $employee->biometric_template }}" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1.5 px-2.5 focus:ring-2 focus:ring-indigo-600/20 text-xs font-medium">
                                </div>
                            </div>
                        </div>

                        <!-- Institutional Identifiers -->
                        <div class="space-y-2">
                            <div class="px-2 py-0.5 bg-slate-50 dark:bg-slate-800/50 rounded text-[9px] font-bold text-slate-400 uppercase tracking-wider">Institutional Identifiers</div>
                            <div class="grid grid-cols-2 gap-2">
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">TIN (Tax ID)</label>
                                    <input type="text" name="tin_id" value="{{ $employee->tin_id }}" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1.5 px-2.5 focus:ring-2 focus:ring-indigo-600/20 text-xs font-medium" placeholder="000-000-000">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">SSS Number</label>
                                    <input type="text" name="sss_id" value="{{ $employee->sss_id }}" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1.5 px-2.5 focus:ring-2 focus:ring-indigo-600/20 text-xs font-medium" placeholder="00-0000000-0">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">PhilHealth ID</label>
                                    <input type="text" name="philhealth_id" value="{{ $employee->philhealth_id }}" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1.5 px-2.5 focus:ring-2 focus:ring-indigo-600/20 text-xs font-medium" placeholder="00-000000000-0">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">Pag-IBIG Number</label>
                                    <input type="text" name="pagibig_id" value="{{ $employee->pagibig_id }}" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1.5 px-2.5 focus:ring-2 focus:ring-indigo-600/20 text-xs font-medium" placeholder="0000-0000-0000">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Column 3: Weekly Schedule (Optional) & Current Schedule --}}
                    <div class="space-y-4">
                        <!-- Current Schedule Preview -->
                        <div class="space-y-3">
                            <div class="px-2 py-0.5 bg-indigo-50 dark:bg-indigo-900/30 rounded text-[9px] font-bold text-indigo-500 uppercase tracking-wider flex items-center justify-between">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Current Schedule
                                </span>
                                @if($employee->schedule_file)
                                    <span class="text-[8px] font-bold text-indigo-500 bg-indigo-50 px-2 py-0.5 rounded">
                                        📄 {{ basename($employee->schedule_file) }}
                                    </span>
                                @endif
                            </div>
                            
                            @if($schedules->count() > 0)
                                <div class="overflow-hidden rounded-lg border border-slate-100 max-h-[140px] overflow-y-auto">
                                    <table class="w-full text-[10px]">
                                        <thead>
                                            <tr class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100">
                                                <th class="px-2 py-0.5">Day</th>
                                                <th class="px-2 py-0.5">Start</th>
                                                <th class="px-2 py-0.5">End</th>
                                                <th class="px-2 py-0.5">Hours</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($schedules as $sched)
                                                <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50 transition">
                                                    <td class="px-2 py-1 font-bold text-slate-700">{{ $sched->day_of_week }}</td>
                                                    <td class="px-2 py-1 text-slate-500">{{ \Carbon\Carbon::parse($sched->start_time)->format('h:i A') }}</td>
                                                    <td class="px-2 py-1 text-slate-500">{{ \Carbon\Carbon::parse($sched->end_time)->format('h:i A') }}</td>
                                                    <td class="px-2 py-1 font-bold text-indigo-600">{{ $sched->scheduled_hours }}h</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="bg-amber-50 border border-amber-100 rounded-xl p-3 text-center">
                                    <p class="text-xs font-bold text-amber-700">No schedule uploaded yet</p>
                                </div>
                            @endif
                        </div>

                        <!-- Dropzone area -->
                        <div class="relative group">
                            <label class="block">
                                <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1 block">
                                    {{ $schedules->count() > 0 ? 'Replace Schedule' : 'Upload Schedule Excel' }}
                                </span>
                                <div id="schedule-dropzone" class="flex items-center justify-center px-3 py-2 border-2 border-slate-200 dark:border-slate-700 border-dashed rounded-xl hover:border-indigo-400 transition-all cursor-pointer group-hover:bg-indigo-50/20">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-slate-300 group-hover:text-indigo-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                        <p class="text-[11px] font-bold text-slate-600 leading-tight" id="schedule-file-label">Choose .xlsx or .csv file</p>
                                    </div>
                                </div>
                                <input type="file" name="schedule_file" accept=".xlsx,.xls,.csv" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" 
                                       onchange="document.getElementById('schedule-file-label').innerText = this.files[0].name; document.getElementById('schedule-dropzone').classList.add('border-indigo-500', 'bg-indigo-50/50');">
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Footer confirmation bar -->
                <div class="pt-3 mt-3 border-t border-slate-100 flex justify-between items-center">
                    <a href="{{ route('employees.index') }}" class="text-xs font-bold text-slate-500 hover:text-rose-600 transition">Discard Changes</a>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-lg font-bold shadow hover:bg-indigo-700 transition duration-200">
                        Update Record
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
