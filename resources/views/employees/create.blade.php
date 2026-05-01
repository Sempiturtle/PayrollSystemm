<x-app-layout>
    <x-slot name="header">
        Onboard New Member
    </x-slot>

    <div class="max-w-3xl mx-auto mt-8">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-50 dark:border-slate-800 text-center">
                <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 italic tracking-tight">Identity Registration</h3>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-2">AISAT Personnel Registry</p>
            </div>

            <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data" class="p-4 space-y-4"
                x-data="{
                    empName: '',
                    empId: '',
                    open: false, 
                    scanning: false, 
                    results: [], 
                    error: '',
                    imagePreview: null,
                    get autoUsername() {
                        if (!this.empName) return '';
                        return this.empName.toLowerCase().replace(/[^a-z0-9\s.-]/g, '').trim().replace(/\s+/g, '.');
                    },
                    get autoPassword() {
                        if (!this.empId) return '';
                        return 'AISAT-' + this.empId;
                    },
                    handleFileChange(e) {
                        const file = e.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = (e) => { this.imagePreview = e.target.result; };
                            reader.readAsDataURL(file);
                            document.getElementById('schedule-file-label').innerText = file.name;
                            document.getElementById('schedule-dropzone').classList.add('border-indigo-500', 'bg-indigo-50/50');
                        }
                    },
                    startScan() {
                        const fileInput = document.querySelector('input[name=schedule_image]');
                        if (!fileInput.files || !fileInput.files[0]) {
                            alert('Please select an image first.');
                            return;
                        }
                        this.open = true;
                        this.scanning = true;
                        this.error = '';
                        this.results = [];
                        
                        const formData = new FormData();
                        formData.append('image', fileInput.files[0]);
                        
                        fetch('{{ route('employees.pre-scan') }}', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: formData
                        })
                        .then(r => r.json())
                        .then(data => {
                            this.scanning = false;
                            if (data.success) {
                                this.results = data.schedules;
                            } else {
                                this.error = data.message;
                            }
                        })
                        .catch(e => {
                            this.scanning = false;
                            this.error = 'System failure during AI communication.';
                        });
                    }
                }"
                x-init="
                    $watch('autoUsername', v => { if(v) $refs.usernameField.value = v });
                    $watch('autoPassword', v => { if(v) { $refs.passwordField.value = v; $refs.passwordConfirmField.value = v; } });
                    document.querySelector('input[name=name]').addEventListener('input', e => empName = e.target.value);
                    document.querySelector('input[name=employee_id]').addEventListener('input', e => empId = e.target.value);
                ">
                @csrf

                <input type="hidden" name="scanned_schedules" :value="JSON.stringify(results)">
                
                <div class="space-y-6">
                    <div class="px-4 py-2 bg-slate-50 dark:bg-slate-800/50 rounded-xl text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Basic Information</div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Full Name</label>
                            <input type="text" name="name" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Email Address</label>
                            <input type="email" name="email" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-medium">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Username</label>
                            <input x-ref="usernameField" type="text" name="username" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-bold text-indigo-600" :value="autoUsername" placeholder="auto-generated">
                            <p class="text-[10px] text-slate-400 italic pl-1">Auto-generated from name. Editable.</p>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Default Password</label>
                            <input x-ref="passwordField" type="text" name="password" required minlength="6" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-bold text-emerald-600 font-mono" :value="autoPassword" placeholder="auto-generated">
                            <p class="text-[10px] text-slate-400 italic pl-1">Default: AISAT-{Employee ID}</p>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Confirm Password</label>
                            <input x-ref="passwordConfirmField" type="text" name="password_confirmation" required minlength="6" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-bold text-emerald-600 font-mono" :value="autoPassword" placeholder="auto-generated">
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="px-4 py-2 bg-slate-50 dark:bg-slate-800/50 rounded-xl text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">System Credentials</div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Employee ID</label>
                            <input type="text" name="employee_id" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-bold tracking-widest text-indigo-600">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">RFID Tag Num</label>
                            <input type="text" name="rfid_card_num" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-medium">
                        </div>
                        <div class="space-y-2" x-data="{ 
                            isEnrolling: false, 
                            status: '', 
                            pollInterval: null,
                            startEnroll() {
                                // Since the user doesn't exist yet, we check if they filled out the name
                                let name = document.querySelector('input[name=name]').value;
                                if(!name) { alert('Please enter a name first.'); return; }
                                
                                alert('Note: To use the automatic enrollment, you should save the employee first, then use the Enroll button in the Edit page. For now, please input the ID manually if you have it, or save and return here!');
                            }
                        }">
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Fingerprint ID (Hardware)</label>
                                <button type="button" 
                                        @click="startEnroll" 
                                        class="text-[10px] font-bold text-indigo-600 bg-indigo-50 dark:bg-indigo-900/30 px-3 py-1 rounded-lg hover:bg-indigo-100 transition flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                    Enroll Help
                                </button>
                            </div>
                            <input type="number" name="fingerprint_id" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-bold text-indigo-600" placeholder="e.g. 1">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Biometric Hash/Template</label>
                            <input type="text" name="biometric_template" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-medium">
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="px-4 py-2 bg-slate-50 dark:bg-slate-800/50 rounded-xl text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Institutional Identifiers</div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">TIN (Tax ID)</label>
                            <input type="text" name="tin_id" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-medium" placeholder="000-000-000-000">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">SSS Number</label>
                            <input type="text" name="sss_id" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-medium" placeholder="00-0000000-0">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">PhilHealth ID</label>
                            <input type="text" name="philhealth_id" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-medium" placeholder="00-000000000-0">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Pag-IBIG Number</label>
                            <input type="text" name="pagibig_id" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-medium" placeholder="0000-0000-0000">
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="px-4 py-2 bg-slate-50 dark:bg-slate-800/50 rounded-xl text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Initial Leave Allocation</div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Sick Leave Credits</label>
                            <input type="number" step="0.5" name="sick_leave_credits" value="0" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-bold text-rose-600">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Vacation Leave Credits</label>
                            <input type="number" step="0.5" name="vacation_leave_credits" value="0" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-bold text-emerald-600">
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="px-4 py-2 bg-slate-50 dark:bg-slate-800/50 rounded-xl text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Role & Compensation</div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Hourly Rate (₱)</label>
                            <input type="number" step="0.01" name="hourly_rate" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-bold">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Personnel Role</label>
                            <select name="role" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-bold">
                                <option value="professor">Professor</option>
                                <option value="employee">Staff / Regular</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- ══════════════════════════════════════════════════ --}}
                {{-- OFFICIAL SCHEDULE IMAGE --}}
                {{-- ══════════════════════════════════════════════════ --}}
                <div class="space-y-6">
                    <div class="px-4 py-2 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl text-[10px] font-bold text-indigo-500 uppercase tracking-[0.2em] flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Official Schedule Image (Optional)
                    </div>

                    <div class="relative group">
                        <label class="block">
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-2 block">Upload Schedule Image</span>
                            <div class="flex items-center gap-4">
                                <div class="flex-1 relative">
                                    <div id="schedule-dropzone" class="flex items-center justify-center px-5 py-6 border-2 border-slate-200 dark:border-slate-700 border-dashed rounded-2xl hover:border-indigo-400 transition-all cursor-pointer group-hover:bg-indigo-50/30 dark:group-hover:bg-indigo-900/10">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-8 h-8 text-slate-300 group-hover:text-indigo-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <div>
                                                <p class="text-sm font-bold text-slate-600 dark:text-slate-300" id="schedule-file-label">Choose image file (.jpg, .png, .jfif)</p>
                                                <p class="text-[10px] text-slate-400 mt-0.5">Official school-issued schedule for this employee</p>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="file" name="schedule_image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                           @change="handleFileChange">
                                </div>
                                <button type="button" @click="startScan" x-show="imagePreview" class="px-6 py-4 bg-indigo-600 text-white rounded-2xl font-bold text-xs shadow-lg hover:bg-indigo-700 transition flex items-center gap-2 animate-in zoom-in-95">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                    Scan Image
                                </button>
                            </div>
                        </label>
                    </div>
                {{-- EXCEL SCHEDULE DATA --}}
                <div class="space-y-6">
                    <div class="px-4 py-2 bg-emerald-50 dark:bg-emerald-900/30 rounded-xl text-[10px] font-bold text-emerald-500 uppercase tracking-[0.2em] flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 00-4-4H5m11 0h.01M13 21h4a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2h4"></path></svg>
                        Import Schedule Data (Excel)
                    </div>

                    <div class="relative group">
                        <label class="block">
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-2 block">Upload Schedule Excel</span>
                            <div class="flex items-center gap-4">
                                <div class="flex-1 relative">
                                    <div id="excel-dropzone" class="flex items-center justify-center px-5 py-6 border-2 border-slate-200 dark:border-slate-700 border-dashed rounded-2xl hover:border-emerald-400 transition-all cursor-pointer group-hover:bg-emerald-50/30 dark:group-hover:bg-emerald-900/10">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-8 h-8 text-slate-300 group-hover:text-emerald-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                            <div>
                                                <p class="text-sm font-bold text-slate-600 dark:text-slate-300" id="excel-file-label">Choose .xlsx or .csv file</p>
                                                <p class="text-[10px] text-slate-400 mt-0.5">Required for Scanner/Payroll logic to work</p>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="file" name="schedule_file" accept=".xlsx,.xls,.csv" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                           onchange="document.getElementById('excel-file-label').innerText = this.files[0].name; document.getElementById('excel-dropzone').classList.add('border-emerald-500', 'bg-emerald-50/50');">
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
                </div>
                <div class="pt-6 flex justify-between items-center">
                    <a href="{{ route('employees.index') }}" class="text-sm font-bold text-slate-400 hover:text-rose-600 transition">Cancel Registration</a>
                    <button type="submit" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-xl shadow-indigo-100 dark:shadow-none hover:bg-indigo-700 hover:-translate-y-1 transition duration-300 active:scale-95">
                        Confirm & Save Record
                    </button>
                </div>

                {{-- ══════════════════════════════════════════════════ --}}
                {{-- AI SCAN MODAL --}}
                {{-- ══════════════════════════════════════════════════ --}}
                <div x-show="open" class="fixed inset-0 z-[150] overflow-y-auto" style="display: none;">
                    <div class="flex items-center justify-center min-h-screen px-4">
                        <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-md" @click="open = false"></div>
                        
                        <div class="relative bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl w-full max-w-2xl overflow-hidden border border-slate-200 dark:border-slate-800">
                            <div class="p-8">
                                <div class="flex items-center justify-between mb-8">
                                    <div>
                                        <h3 class="text-2xl font-black text-slate-900 dark:text-slate-100 italic tracking-tight">AI Onboarding Scan</h3>
                                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Extracting schedule for new member</p>
                                    </div>
                                    <button type="button" @click="open = false" class="text-slate-400 hover:text-slate-600 transition">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>

                                <div class="min-h-[300px] flex flex-col">
                                    <div x-show="imagePreview" class="mb-6 rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-800 h-32 relative group">
                                        <img :src="imagePreview" class="w-full h-full object-cover blur-[2px] group-hover:blur-0 transition duration-500">
                                        <div class="absolute inset-0 bg-indigo-600/10 flex items-center justify-center">
                                            <span class="text-[10px] font-black text-white uppercase tracking-widest bg-slate-900/50 px-4 py-2 rounded-full backdrop-blur-md">Analyzing Pending Upload</span>
                                        </div>
                                    </div>

                                    <div x-show="scanning" class="flex-1 flex flex-col items-center justify-center py-12">
                                        <div class="w-16 h-16 border-4 border-indigo-100 border-t-indigo-600 rounded-full animate-spin mb-6"></div>
                                        <p class="text-sm font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest animate-pulse">Analyzing Neural Patterns...</p>
                                    </div>

                                    <div x-show="!scanning && results.length > 0" class="flex-1 space-y-3">
                                        <div class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Scan Successful: Verify & Sync
                                        </div>
                                        
                                        <div class="max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                                            <template x-for="(s, index) in results" :key="index">
                                                <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-700 mb-2 group hover:border-indigo-500 transition-colors">
                                                    <div class="flex items-center gap-4">
                                                        <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-900 flex items-center justify-center text-[10px] font-black text-indigo-600 uppercase shadow-sm" x-text="s.day_of_week.substring(0,3)"></div>
                                                        <div>
                                                            <div class="text-xs font-black text-slate-900 dark:text-slate-100" x-text="s.day_of_week"></div>
                                                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Detected Shift</div>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center gap-3">
                                                        <input type="text" x-model="s.start_time" class="w-20 bg-white dark:bg-slate-900 border-none rounded-lg text-xs font-black text-center p-2 focus:ring-2 focus:ring-indigo-500">
                                                        <span class="text-slate-300">→</span>
                                                        <input type="text" x-model="s.end_time" class="w-20 bg-white dark:bg-slate-900 border-none rounded-lg text-xs font-black text-center p-2 focus:ring-2 focus:ring-indigo-500">
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <div x-show="error" class="flex-1 flex flex-col items-center justify-center py-12 text-center">
                                        <div class="w-16 h-16 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mb-6">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <p class="text-sm font-black text-rose-600 uppercase tracking-widest" x-text="error"></p>
                                        <button type="button" @click="startScan" class="mt-6 text-xs font-black text-indigo-600 uppercase tracking-widest hover:underline">Retry Analysis</button>
                                    </div>
                                </div>

                                <div class="mt-8 pt-6 border-t border-slate-50 dark:border-slate-800 flex justify-end gap-3">
                                    <button type="button" @click="open = false" class="px-6 py-3 text-xs font-black text-slate-400 uppercase tracking-widest hover:text-slate-600 transition">Discard Scan</button>
                                    <button type="button" @click="open = false" x-show="results.length > 0" class="px-8 py-3 bg-indigo-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-indigo-100 dark:shadow-none hover:bg-indigo-700 transition">
                                        Synchronize with Profile
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
