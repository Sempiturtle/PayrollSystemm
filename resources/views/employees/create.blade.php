<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="text-slate-400 font-medium">Organization</span>
            <span class="text-slate-300">/</span>
            <a href="{{ route('employees.index') }}" class="text-slate-400 font-medium hover:text-slate-600 transition-colors">Personnel Directory</a>
            <span class="text-slate-300">/</span>
            <span class="font-bold text-slate-800">Onboard Member</span>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Onboard New Member</h1>
                <p class="text-sm text-slate-500 mt-1">Register new faculty or staff identity and set initial details.</p>
            </div>
            <a href="{{ route('employees.index') }}" class="btn-action-secondary text-xs py-2 px-4 shadow-sm w-full sm:w-auto">
                Cancel
            </a>
        </div>

        <!-- Form Card -->
        <div class="card-reference overflow-hidden bg-white/90 backdrop-blur-xl border border-slate-200/60 shadow-xl">
            <!-- Form Title -->
            <div class="py-4 px-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <div>
                    <h3 class="text-sm font-bold text-slate-800 tracking-tight">Identity Registration</h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">AISAT Personnel Registry</p>
                </div>
                <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg uppercase tracking-wider">Premium Active</span>
            </div>

            <!-- Form -->
            <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    {{-- Column 1: Personal & Role Details --}}
                    <div class="space-y-4">
                        <!-- Basic Information -->
                        <div class="space-y-3">
                            <div class="px-3 py-1 bg-slate-50 border border-slate-100 rounded-lg text-[9px] font-bold text-slate-400 uppercase tracking-widest">Basic Information</div>
                            <div class="space-y-3">
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700">Full Name</label>
                                    <input type="text" name="name" required class="w-full bg-slate-50/50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-semibold">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700">Email Address</label>
                                    <input type="email" name="email" required class="w-full bg-slate-50/50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-semibold">
                                </div>
                            </div>
                        </div>

                        <!-- Employment Type & Compensation -->
                        <div class="space-y-3" x-data="{ empType: 'professor' }">
                            <div class="px-3 py-1 bg-slate-50 border border-slate-100 rounded-lg text-[9px] font-bold text-slate-400 uppercase tracking-widest">Employment & Compensation</div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                {{-- Employment Type Selector --}}
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700">Type</label>
                                    <select name="employment_type" x-model="empType" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-bold">
                                        <option value="professor">Professor</option>
                                        <option value="staff">Staff</option>
                                    </select>
                                </div>

                                {{-- Dynamic Rate/Salary --}}
                                <div class="space-y-1" x-show="empType === 'professor'">
                                    <label class="text-[11px] font-bold text-slate-700">Rate (₱)</label>
                                    <input type="number" step="0.01" name="hourly_rate" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-bold" :required="empType === 'professor'">
                                </div>
                                <div class="space-y-1" x-show="empType === 'staff'" style="display:none;">
                                    <label class="text-[11px] font-bold text-slate-700">Salary (₱)</label>
                                    <input type="number" step="0.01" name="monthly_salary" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-bold text-emerald-600" :required="empType === 'staff'">
                                </div>
                                <div class="space-y-1 sm:col-span-2" x-show="empType === 'staff'" style="display:none;">
                                    <label class="text-[11px] font-bold text-slate-700">Overtime Rate (₱/hr)</label>
                                    <input type="number" step="0.01" name="overtime_rate" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-bold text-indigo-600" :required="empType === 'staff'">
                                </div>

                                {{-- Personnel Role --}}
                                <div class="space-y-1 sm:col-span-2">
                                    <label class="text-[11px] font-bold text-slate-700">System Role</label>
                                    <select name="role" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-bold">
                                        <option value="professor">Professor</option>
                                        <option value="employee">Staff</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Info Badge --}}
                            <div class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-[10px] font-semibold transition-all border"
                                 :class="empType === 'professor' ? 'bg-violet-50 text-violet-600 border-violet-100' : 'bg-emerald-50 text-emerald-600 border-emerald-100'">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                <span x-show="empType === 'professor'">Paid hourly. No overtime.</span>
                                <span x-show="empType === 'staff'">Fixed salary. Custom overtime rate.</span>
                            </div>
                        </div>

                        <!-- Leave Allocation -->
                        <div class="space-y-3">
                            <div class="px-3 py-1 bg-slate-50 border border-slate-100 rounded-lg text-[9px] font-bold text-slate-400 uppercase tracking-widest">Initial Leaves</div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700">Sick Credits</label>
                                    <input type="number" step="0.5" name="sick_leave_credits" value="0" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-bold text-rose-600">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700">Vacation Credits</label>
                                    <input type="number" step="0.5" name="vacation_leave_credits" value="0" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-bold text-emerald-600">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Column 2: System Credentials & Identifiers --}}
                    <div class="space-y-4">
                        <!-- System Credentials -->
                        <div class="space-y-3">
                            <div class="px-3 py-1 bg-slate-50 border border-slate-100 rounded-lg text-[9px] font-bold text-slate-400 uppercase tracking-widest">System Credentials</div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700">Employee ID</label>
                                    <input type="text" name="employee_id" required class="w-full bg-slate-50/50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-bold tracking-widest text-indigo-600">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700">RFID Tag Num</label>
                                    <input type="text" name="rfid_card_num" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-medium">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="space-y-1" x-data="{ 
                                    startEnroll() {
                                        let name = document.querySelector('input[name=name]').value;
                                        if(!name) { alert('Please enter a name first.'); return; }
                                        alert('Note: To use automatic biometric enrollment, save the employee first, then use the Enroll button in the Edit page.');
                                    }
                                }">
                                    <div class="flex items-center justify-between">
                                        <label class="text-[11px] font-bold text-slate-700">Fingerprint ID</label>
                                        <button type="button" @click="startEnroll" class="text-[8px] font-bold text-indigo-600 bg-indigo-50 border border-indigo-100 px-1.5 py-0.5 rounded hover:bg-indigo-100 transition">
                                            Enroll Info
                                        </button>
                                    </div>
                                    <input type="number" name="fingerprint_id" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-bold text-indigo-600" placeholder="e.g. 1">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700">Biometric Template</label>
                                    <input type="text" name="biometric_template" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-medium">
                                </div>
                            </div>
                        </div>

                        <!-- Institutional Identifiers -->
                        <div class="space-y-3">
                            <div class="px-3 py-1 bg-slate-50 border border-slate-100 rounded-lg text-[9px] font-bold text-slate-400 uppercase tracking-widest">Institutional Identifiers</div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700">TIN (Tax ID)</label>
                                    <input type="text" name="tin_id" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-medium" placeholder="000-000-000">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700">SSS Number</label>
                                    <input type="text" name="sss_id" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-medium" placeholder="00-0000000-0">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700">PhilHealth ID</label>
                                    <input type="text" name="philhealth_id" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-medium" placeholder="00-000000000-0">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-slate-700">Pag-IBIG Number</label>
                                    <input type="text" name="pagibig_id" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-medium" placeholder="0000-0000-0000">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Column 3: Weekly Schedule --}}
                    <div class="space-y-4">
                        <div class="px-3 py-1 bg-indigo-50 border border-indigo-100 rounded-lg text-[9px] font-bold text-indigo-600 uppercase tracking-widest flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Weekly Schedule (Optional)
                        </div>

                        <!-- Dropzone area -->
                        <div class="relative group">
                            <label class="block">
                                <div id="schedule-dropzone" class="flex flex-col items-center justify-center p-6 border-2 border-slate-200 border-dashed rounded-2xl hover:border-indigo-500 hover:bg-indigo-50/10 transition-all cursor-pointer">
                                    <svg class="w-8 h-8 text-slate-300 group-hover:text-indigo-500 transition-colors mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                    <div class="text-center">
                                        <p class="text-xs font-bold text-slate-600 leading-tight" id="schedule-file-label">Drag & drop or browse</p>
                                        <p class="text-[10px] text-slate-400 font-medium mt-1">Excel or CSV schedule matrices</p>
                                    </div>
                                </div>
                                <input type="file" name="schedule_file" accept=".xlsx,.xls,.csv" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" 
                                       onchange="document.getElementById('schedule-file-label').innerText = this.files[0].name; document.getElementById('schedule-dropzone').classList.add('border-indigo-500', 'bg-indigo-50/50');">
                            </label>
                        </div>

                        <!-- Format Reference details -->
                        <div class="bg-indigo-50/40 p-4 rounded-2xl border border-indigo-100/50 space-y-3">
                            <div class="flex items-center justify-between">
                                <h4 class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                    Format Guide
                                </h4>
                                <a href="{{ route('schedule.template') }}" class="text-[9px] font-bold text-indigo-600 hover:text-indigo-800 bg-white border border-indigo-100 px-2.5 py-1 rounded-xl shadow-sm hover:shadow transition flex items-center gap-1">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Template
                                </a>
                            </div>
                            <div class="overflow-hidden rounded-xl border border-indigo-100/50">
                                <table class="w-full text-[10px] text-left">
                                    <thead>
                                        <tr class="bg-indigo-100/30 text-indigo-700 font-bold uppercase tracking-wider">
                                            <th class="px-3 py-1.5">day_of_week</th>
                                            <th class="px-3 py-1.5">start_time</th>
                                            <th class="px-3 py-1.5">end_time</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-indigo-600/70 font-medium">
                                        <tr class="border-t border-indigo-50/50"><td class="px-3 py-1">Monday</td><td class="px-3 py-1">08:00</td><td class="px-3 py-1">12:00</td></tr>
                                        <tr class="border-t border-indigo-50/50"><td class="px-3 py-1">Tuesday</td><td class="px-3 py-1">13:00</td><td class="px-3 py-1">17:00</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer confirmation bar -->
                <div class="pt-4 border-t border-slate-100 flex justify-between items-center">
                    <a href="{{ route('employees.index') }}" class="text-xs font-bold text-slate-500 hover:text-rose-600 transition">Cancel Registration</a>
                    <button type="submit" class="btn-action-indigo text-xs py-2.5 px-5">
                        Confirm & Save Record
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
