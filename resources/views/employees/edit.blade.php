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

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Username</label>
                            <input type="text" name="username" value="{{ $employee->username }}" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-bold text-indigo-600">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Reset Password</label>
                            <input type="password" name="password" minlength="6" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-medium" placeholder="Leave blank to keep current">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Confirm New Password</label>
                            <input type="password" name="password_confirmation" minlength="6" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-medium" placeholder="Re-enter new password">
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
                            startEnroll() {
                                this.isEnrolling = true;
                                this.status = 'Initiating...';
                                fetch('{{ route('employees.enroll', $employee) }}', {
                                    method: 'POST',
                                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                                })
                                .then(res => res.json())
                                .then(data => {
                                    this.status = 'Waiting for Finger...';
                                    this.pollEnrollment(data.action_id);
                                });
                            },
                            pollEnrollment(actionId) {
                                this.pollInterval = setInterval(() => {
                                    fetch(`/biometrics/actions/${actionId}`)
                                    .then(res => res.json())
                                    .then(data => {
                                        if (data.status === 'success') {
                                            clearInterval(this.pollInterval);
                                            this.status = 'Success! Reloading...';
                                            setTimeout(() => window.location.reload(), 1500);
                                        } else if (data.status === 'failed') {
                                            clearInterval(this.pollInterval);
                                            this.isEnrolling = false;
                                            alert('Enrollment failed. Please try again.');
                                        } else if (data.status === 'expired') {
                                            clearInterval(this.pollInterval);
                                            this.isEnrolling = false;
                                        }
                                    });
                                }, 3000);
                            }
                        }">
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Fingerprint ID (Hardware)</label>
                                <button type="button" 
                                        @click="startEnroll" 
                                        x-show="!isEnrolling"
                                        class="text-[10px] font-bold text-indigo-600 bg-indigo-50 dark:bg-indigo-900/30 px-3 py-1 rounded-lg hover:bg-indigo-100 transition flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                    Enroll Now
                                </button>
                                <div x-show="isEnrolling" class="flex items-center gap-2">
                                    <div class="w-2 h-2 bg-indigo-600 rounded-full animate-pulse"></div>
                                    <span class="text-[10px] font-bold text-indigo-600" x-text="status"></span>
                                </div>
                            </div>
                            <input type="number" name="fingerprint_id" value="{{ $employee->fingerprint_id }}" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-bold text-indigo-600" placeholder="e.g. 1">
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
                                <option value="professor" {{ $employee->role === 'professor' ? 'selected' : '' }}>Professor</option>
                                <option value="employee" {{ $employee->role === 'employee' ? 'selected' : '' }}>Staff / Regular</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- ══════════════════════════════════════════════════ --}}
                {{-- OFFICIAL SCHEDULE IMAGE --}}
                {{-- ══════════════════════════════════════════════════ --}}
                <div class="space-y-6">
                                        <div class="flex items-center justify-between">
                        <div class="px-4 py-2 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl text-[10px] font-bold text-indigo-500 uppercase tracking-[0.2em] flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Official Schedule Image
                        </div>
                        
                        @if($employee->schedule_image)
                            <button type="button" @click="$dispatch('open-ai-scan')" class="px-3 py-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg text-[10px] font-black uppercase tracking-widest hover:shadow-lg transition flex items-center gap-2">
                                <span class="animate-pulse">✨</span> AI Auto-Scan
                            </button>
                        @endif
                    </div>

                    @if($employee->schedule_image)
                        <div class="relative group">
                            <div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 shadow-sm bg-slate-50 dark:bg-slate-900 flex items-center justify-center p-4">
                                <img src="{{ asset($employee->schedule_image) }}" alt="Official Schedule" class="max-w-full h-auto rounded-lg shadow-md max-h-[600px] object-contain">
                                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-4">
                                    <a href="{{ asset($employee->schedule_image) }}" target="_blank" class="px-4 py-2 bg-white text-slate-900 rounded-lg font-bold text-xs shadow-lg hover:bg-indigo-50 transition">View Full Size</a>
                                </div>
                            </div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-2 text-center">Current Official Schedule Image</p>
                        </div>
                    @else
                        <div class="bg-amber-50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-800/30 rounded-2xl p-5 text-center">
                            <svg class="w-8 h-8 text-amber-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                            <p class="text-sm font-bold text-amber-700 dark:text-amber-400">No schedule image uploaded yet</p>
                            <p class="text-xs text-amber-500/70 mt-1">Upload the official schedule image below.</p>
                        </div>
                    @endif

                    {{-- Upload New Image --}}
                    <div class="relative group">
                        <label class="block">
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-2 block">
                                {{ $employee->schedule_image ? 'Update Official Schedule Image' : 'Upload Official Schedule Image' }}
                            </span>
                            <div class="flex items-center gap-4">
                                <div class="flex-1 relative">
                                    <div id="schedule-dropzone" class="flex items-center justify-center px-5 py-6 border-2 border-slate-200 dark:border-slate-700 border-dashed rounded-2xl hover:border-indigo-400 transition-all cursor-pointer group-hover:bg-indigo-50/30 dark:group-hover:bg-indigo-900/10">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-8 h-8 text-slate-300 group-hover:text-indigo-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <div>
                                                <p class="text-sm font-bold text-slate-600 dark:text-slate-300" id="schedule-file-label">Choose image file (.jpg, .png, .jfif)</p>
                                                <p class="text-[10px] text-slate-400 mt-0.5">Upload the official school-issued schedule</p>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="file" name="schedule_image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" 
                                           onchange="document.getElementById('schedule-file-label').innerText = this.files[0].name; document.getElementById('schedule-dropzone').classList.add('border-indigo-500', 'bg-indigo-50/50');">
                                </div>
                            </div>
                        </label>
                    </div>
                {{-- EXCEL SCHEDULE DATA --}}
                <div class="space-y-6">
                    <div class="px-4 py-2 bg-emerald-50 dark:bg-emerald-900/30 rounded-xl text-[10px] font-bold text-emerald-500 uppercase tracking-[0.2em] flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 00-4-4H5m11 0h.01M13 21h4a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2h4"></path></svg>
                        Update Schedule Data (Excel)
                    </div>

                    @if($employee->schedule_file)
                        <div class="p-4 bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-800/30 rounded-2xl flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-emerald-500 text-white rounded-xl flex items-center justify-center shadow-lg shadow-emerald-200">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-emerald-800 dark:text-emerald-300">Active Schedule Ledger</p>
                                    <p class="text-[10px] text-emerald-600/70 font-medium">Linked Official Record</p>
                                </div>
                            </div>
                            <a href="{{ asset($employee->schedule_file) }}" target="_blank" class="px-4 py-2 bg-white dark:bg-slate-800 text-emerald-600 rounded-lg text-xs font-bold shadow-sm hover:shadow-md transition">Download Record</a>
                        </div>
                    @endif

                    <div class="relative group">
                        <label class="block">
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-2 block">Upload Schedule Excel</span>
                            <div class="flex items-center gap-4">
                                <div class="flex-1 relative">
                                    <div id="excel-dropzone" class="flex items-center justify-center px-5 py-6 border-2 border-slate-200 dark:border-slate-700 border-dashed rounded-2xl hover:border-emerald-400 transition-all cursor-pointer group-hover:bg-emerald-50/30 dark:group-hover:bg-indigo-900/10">
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
                </div><div class="pt-6 flex justify-between items-center">
                    <a href="{{ route('employees.index') }}" class="text-sm font-bold text-slate-400 hover:text-rose-600 transition">Discard Changes</a>
                    <button type="submit" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-xl hover:bg-indigo-700 hover:-translate-y-1 transition duration-300">
                        Update Record
                    </button>
                </div>
            </form>
        </div>
    </div>
    {{-- AI SCANNING MODAL --}}
    <div x-data="{ 
        open: false, 
        scanning: false, 
        results: [], 
        error: '',
        startScan() {
            this.scanning = true;
            this.error = '';
            fetch('{{ route('employees.auto-scan', $employee->id) }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
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
        },
        saveResults() {
            fetch('{{ route('employees.save-scan', $employee->id) }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
                body: JSON.stringify({ schedules: this.results })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message);
                }
            });
        }
    }" 
    @open-ai-scan.window="open = true; if(results.length === 0) startScan()"
    x-show="open" 
    class="fixed inset-0 z-[150] overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-md" @click="open = false"></div>
            
            <div class="relative bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl w-full max-w-2xl overflow-hidden border border-slate-200 dark:border-slate-800">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-2xl font-black text-slate-900 dark:text-slate-100 italic tracking-tight">AI Neural Scan</h3>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Extracting official institutional timelines</p>
                        </div>
                        <button @click="open = false" class="text-slate-400 hover:text-slate-600 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="min-h-[300px] flex flex-col">
                        @if($employee->schedule_image)
                            <div class="mb-6 rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-800 h-32 relative group">
                                <img src="{{ asset($employee->schedule_image) }}" class="w-full h-full object-cover blur-[2px] group-hover:blur-0 transition duration-500">
                                <div class="absolute inset-0 bg-indigo-600/10 flex items-center justify-center">
                                    <span class="text-[10px] font-black text-white uppercase tracking-widest bg-slate-900/50 px-4 py-2 rounded-full backdrop-blur-md">Target: Official Record</span>
                                </div>
                            </div>
                        @endif

                        <div x-show="scanning" class="flex-1 flex flex-col items-center justify-center py-12">
                            <div class="w-16 h-16 border-4 border-indigo-100 border-t-indigo-600 rounded-full animate-spin mb-6"></div>
                            <p class="text-sm font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest animate-pulse">Analyzing Neural Patterns...</p>
                        </div>

                        <div x-show="!scanning && results.length > 0" class="flex-1 space-y-3">
                            <div class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Scan Successful: Verification Required
                            </div>
                            
                            <div class="max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                                <template x-for="(s, index) in results" :key="index">
                                    <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-700 mb-2 group hover:border-indigo-500 transition-colors">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-900 flex items-center justify-center text-[10px] font-black text-indigo-600 uppercase shadow-sm" x-text="s.day_of_week.substring(0,3)"></div>
                                            <div>
                                                <div class="text-xs font-black text-slate-900 dark:text-slate-100" x-text="s.day_of_week"></div>
                                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Verified Shift Block</div>
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
                            <button @click="startScan" class="mt-6 text-xs font-black text-indigo-600 uppercase tracking-widest hover:underline">Re-initialize Scan</button>
                        </div>
                    </div>
                </div>

                <div class="p-8 bg-slate-50 dark:bg-slate-800/50 flex items-center justify-between border-t border-slate-100 dark:border-slate-800">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest max-w-[200px]">Review all timestamps before finalizing synchronization.</p>
                    <div class="flex items-center gap-4">
                        <button @click="open = false" class="text-xs font-black text-slate-400 uppercase tracking-widest hover:text-slate-600 transition">Cancel</button>
                        <button @click="saveResults" x-show="results.length > 0" class="px-8 py-3 bg-indigo-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-indigo-700 transition shadow-xl shadow-indigo-100">Synchronize Ledger</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
