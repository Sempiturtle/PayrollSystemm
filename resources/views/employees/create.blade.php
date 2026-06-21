<x-app-layout>
    <x-slot name="header">
        Onboard New Member
    </x-slot>

    <div class="max-w-7xl mx-auto mt-4">
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="py-3 px-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50/50">
                <div>
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-100 tracking-tight">Identity Registration</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">AISAT Personnel Registry</p>
                </div>
                <a href="{{ route('employees.index') }}" class="text-xs font-bold text-slate-400 hover:text-rose-600 transition">Cancel</a>
            </div>

            <!-- Horizontal Multi-Column Form -->
            <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data" class="p-4">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    
                    {{-- Column 1: Personal & Role Details --}}
                    <div class="space-y-3">
                        <!-- Basic Information -->
                        <div class="space-y-2">
                            <div class="px-2 py-0.5 bg-slate-50 dark:bg-slate-800/50 rounded text-[9px] font-bold text-slate-400 uppercase tracking-wider">Basic Information</div>
                            <div class="grid grid-cols-2 gap-2">
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">Full Name</label>
                                    <input type="text" name="name" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1.5 px-2.5 focus:ring-2 focus:ring-indigo-600/20 text-xs font-medium">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">Email Address</label>
                                    <input type="email" name="email" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1.5 px-2.5 focus:ring-2 focus:ring-indigo-600/20 text-xs font-medium">
                                </div>
                            </div>
                        </div>

                        <!-- Employment Type & Compensation -->
                        <div class="space-y-2.5" x-data="{ empType: 'professor' }">
                            <div class="px-2 py-0.5 bg-slate-50 dark:bg-slate-800/50 rounded text-[9px] font-bold text-slate-400 uppercase tracking-wider">Employment Type & Compensation</div>
                            
                            <div class="grid grid-cols-3 gap-2">
                                {{-- Employment Type Selector --}}
                                <div class="col-span-1 space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">Type</label>
                                    <select name="employment_type" x-model="empType" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1.5 px-2 focus:ring-2 focus:ring-indigo-600/20 text-xs font-bold">
                                        <option value="professor">Professor</option>
                                        <option value="staff">Staff</option>
                                        <option value="part_time">Part-Time</option>
                                    </select>
                                </div>

                                {{-- Dynamic Rate/Salary --}}
                                <div class="col-span-1 space-y-1">
                                    <div x-show="empType === 'professor'">
                                        <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">Rate (₱)</label>
                                        <input type="number" step="0.01" name="hourly_rate" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1.5 px-2 focus:ring-2 focus:ring-indigo-600/20 text-xs font-bold" :required="empType === 'professor'">
                                    </div>
                                    <div x-show="empType !== 'professor'" style="display:none;">
                                        <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">Salary (₱)</label>
                                        <input type="number" step="0.01" name="monthly_salary" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1.5 px-2 focus:ring-2 focus:ring-indigo-600/20 text-xs font-bold text-emerald-600" :required="empType !== 'professor'">
                                    </div>
                                </div>

                                {{-- Personnel Role --}}
                                <div class="col-span-1 space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">System Role</label>
                                    <select name="role" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1.5 px-2 focus:ring-2 focus:ring-indigo-600/20 text-xs font-bold">
                                        <option value="professor">Professor</option>
                                        <option value="employee">Staff</option>
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

                        <!-- Leave Allocation -->
                        <div class="space-y-2">
                            <div class="px-2 py-0.5 bg-slate-50 dark:bg-slate-800/50 rounded text-[9px] font-bold text-slate-400 uppercase tracking-wider">Initial Leaves</div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">Sick Credits</label>
                                    <input type="number" step="0.5" name="sick_leave_credits" value="0" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1.5 px-2.5 focus:ring-2 focus:ring-indigo-600/20 text-xs font-bold text-rose-600">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">Vacation Credits</label>
                                    <input type="number" step="0.5" name="vacation_leave_credits" value="0" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1.5 px-2.5 focus:ring-2 focus:ring-indigo-600/20 text-xs font-bold text-emerald-600">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Column 2: System Credentials & Identifiers --}}
                    <div class="space-y-3">
                        <!-- System Credentials -->
                        <div class="space-y-2">
                            <div class="px-2 py-0.5 bg-slate-50 dark:bg-slate-800/50 rounded text-[9px] font-bold text-slate-400 uppercase tracking-wider">System Credentials</div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">Employee ID</label>
                                    <input type="text" name="employee_id" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1.5 px-2.5 focus:ring-2 focus:ring-indigo-600/20 text-xs font-bold tracking-widest text-indigo-600">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">RFID Tag Num</label>
                                    <input type="text" name="rfid_card_num" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1.5 px-2.5 focus:ring-2 focus:ring-indigo-600/20 text-xs font-medium">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1" x-data="{ 
                                    startEnroll() {
                                        let name = document.querySelector('input[name=name]').value;
                                        if(!name) { alert('Please enter a name first.'); return; }
                                        alert('Note: To use the automatic enrollment, you should save the employee first, then use the Enroll button in the Edit page.');
                                    }
                                }">
                                    <div class="flex items-center justify-between">
                                        <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">Fingerprint ID</label>
                                        <button type="button" @click="startEnroll" class="text-[8px] font-bold text-indigo-600 bg-indigo-50 px-1.5 py-0.5 rounded hover:bg-indigo-100 transition">
                                            Enroll Help
                                        </button>
                                    </div>
                                    <input type="number" name="fingerprint_id" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1.5 px-2.5 focus:ring-2 focus:ring-indigo-600/20 text-xs font-bold text-indigo-600" placeholder="e.g. 1">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">Biometric Template</label>
                                    <input type="text" name="biometric_template" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1.5 px-2.5 focus:ring-2 focus:ring-indigo-600/20 text-xs font-medium">
                                </div>
                            </div>
                        </div>

                        <!-- Institutional Identifiers -->
                        <div class="space-y-2">
                            <div class="px-2 py-0.5 bg-slate-50 dark:bg-slate-800/50 rounded text-[9px] font-bold text-slate-400 uppercase tracking-wider">Institutional Identifiers</div>
                            <div class="grid grid-cols-2 gap-2">
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">TIN (Tax ID)</label>
                                    <input type="text" name="tin_id" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1.5 px-2.5 focus:ring-2 focus:ring-indigo-600/20 text-xs font-medium" placeholder="000-000-000">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">SSS Number</label>
                                    <input type="text" name="sss_id" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1.5 px-2.5 focus:ring-2 focus:ring-indigo-600/20 text-xs font-medium" placeholder="00-0000000-0">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">PhilHealth ID</label>
                                    <input type="text" name="philhealth_id" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1.5 px-2.5 focus:ring-2 focus:ring-indigo-600/20 text-xs font-medium" placeholder="00-000000000-0">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700 dark:text-slate-300">Pag-IBIG Number</label>
                                    <input type="text" name="pagibig_id" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1.5 px-2.5 focus:ring-2 focus:ring-indigo-600/20 text-xs font-medium" placeholder="0000-0000-0000">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Column 3: Weekly Schedule (Optional) --}}
                    <div class="space-y-3">
                        <div class="px-2 py-0.5 bg-indigo-50 dark:bg-indigo-900/30 rounded text-[9px] font-bold text-indigo-500 uppercase tracking-wider flex items-center gap-1.5">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Weekly Schedule (Optional)
                        </div>

                        <!-- Dropzone area -->
                        <div class="relative group">
                            <label class="block">
                                <div id="schedule-dropzone" class="flex items-center justify-center px-3 py-2 border-2 border-slate-200 dark:border-slate-700 border-dashed rounded-xl hover:border-indigo-400 transition-all cursor-pointer group-hover:bg-indigo-50/20">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-slate-300 group-hover:text-indigo-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                        <div class="text-left">
                                            <p class="text-[11px] font-bold text-slate-600 dark:text-slate-300 leading-tight" id="schedule-file-label">Choose .xlsx or .csv file</p>
                                        </div>
                                    </div>
                                </div>
                                <input type="file" name="schedule_file" accept=".xlsx,.xls,.csv" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" 
                                       onchange="document.getElementById('schedule-file-label').innerText = this.files[0].name; document.getElementById('schedule-dropzone').classList.add('border-indigo-500', 'bg-indigo-50/50');">
                            </label>
                        </div>

                        <!-- Format Reference details -->
                        <div class="bg-indigo-50 dark:bg-indigo-900/20 p-3 rounded-xl border border-indigo-100 dark:border-indigo-900/30 space-y-2">
                            <div class="flex items-center justify-between">
                                <h4 class="text-[9px] font-bold text-indigo-600 uppercase tracking-widest flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                    Format details
                                </h4>
                                <a href="{{ route('schedule.template') }}" class="text-[9px] font-bold text-indigo-600 hover:text-indigo-800 bg-white px-2 py-0.5 rounded shadow-sm hover:shadow transition flex items-center gap-1">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Template
                                </a>
                            </div>
                            <div class="overflow-hidden rounded-lg border border-indigo-100/50">
                                <table class="w-full text-[9px] text-left">
                                    <thead>
                                        <tr class="bg-indigo-100/30 text-indigo-700 font-bold uppercase tracking-wider">
                                            <th class="px-2 py-1">day_of_week</th>
                                            <th class="px-2 py-1">start_time</th>
                                            <th class="px-2 py-1">end_time</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-indigo-600/70 font-medium">
                                        <tr class="border-t border-indigo-50/50"><td class="px-2 py-0.5">Monday</td><td class="px-2 py-0.5">08:00</td><td class="px-2 py-0.5">12:00</td></tr>
                                        <tr class="border-t border-indigo-50/50"><td class="px-2 py-0.5">Tuesday</td><td class="px-2 py-0.5">13:00</td><td class="px-2 py-0.5">17:00</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer confirmation bar -->
                <div class="pt-3 mt-3 border-t border-slate-100 flex justify-between items-center">
                    <a href="{{ route('employees.index') }}" class="text-xs font-bold text-slate-500 hover:text-rose-600 transition">Cancel Registration</a>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-lg font-bold shadow hover:bg-indigo-700 transition duration-200">
                        Confirm & Save Record
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
