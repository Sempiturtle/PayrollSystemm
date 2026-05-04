<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <span class="text-[#101D33]/40 font-bold uppercase tracking-[0.2em] text-[10px]">Personnel</span>
            <span class="text-[#101D33]/20">/</span>
            <span class="font-['DM_Serif_Text'] text-[#101D33] italic">Dossier Adjustment</span>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto mt-12 mb-20 animate-in-up">
        <div class="bg-white rounded-[3rem] border border-[#101D33]/5 shadow-[0_40px_100px_rgba(16,29,51,0.06)] overflow-hidden">
            <div class="p-10 border-b border-[#101D33]/5 bg-[#FDFCF8]/50 text-center relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-tr from-[#660000]/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <h3 class="text-3xl font-['DM_Serif_Display'] text-[#101D33] leading-none mb-4 relative z-10">Institutional <span class="text-[#D4AF37]">Profile</span> Adjustment</h3>
                <p class="text-[10px] text-[#101D33]/30 font-black uppercase tracking-[0.4em] relative z-10">Editing: {{ $employee->name }}</p>
            </div>

            {{-- Notifications --}}
            @if(session('success'))
                <div class="mx-10 mt-10 p-6 bg-emerald-50 border border-emerald-100 rounded-3xl flex items-center gap-4 animate-in fade-in slide-in-from-top-4">
                    <div class="w-10 h-10 bg-emerald-500 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-black text-emerald-800 uppercase tracking-widest leading-none mb-1">Authorization Success</p>
                        <p class="text-xs font-bold text-emerald-600/70">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <form action="{{ route('employees.update', $employee) }}" method="POST" enctype="multipart/form-data" class="p-10 space-y-12 relative z-10">
                @csrf
                @method('PATCH')
                
                <!-- Personal Identity -->
                <div class="space-y-8">
                    <div class="flex items-center gap-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#D4AF37]"></span>
                        <div class="text-[10px] font-black text-[#101D33]/30 uppercase tracking-[0.3em]">Personal Identity Parameters</div>
                        <div class="flex-1 h-px bg-[#101D33]/5"></div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em] ml-2">Legal Identity</label>
                            <input type="text" name="name" value="{{ $employee->name }}" required class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl p-5 focus:ring-[#101D33] text-sm font-['DM_Serif_Text'] shadow-sm">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em] ml-2">Institutional Email</label>
                            <input type="email" name="email" value="{{ $employee->email }}" required class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl p-5 focus:ring-[#101D33] text-sm font-bold text-[#101D33] shadow-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em] ml-2">Registry Username</label>
                            <input type="text" name="username" value="{{ $employee->username }}" required class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl p-5 focus:ring-[#101D33] text-sm font-black text-[#101D33] shadow-sm tabular-nums">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em] ml-2">Reset Access Key</label>
                            <input type="password" name="password" minlength="6" class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl p-5 focus:ring-[#660000] text-sm font-medium" placeholder="••••••••">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em] ml-2">Confirm Key</label>
                            <input type="password" name="password_confirmation" minlength="6" class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl p-5 focus:ring-[#660000] text-sm font-medium" placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <!-- Technical Auth -->
                <div class="space-y-8">
                    <div class="flex items-center gap-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#D4AF37]"></span>
                        <div class="text-[10px] font-black text-[#101D33]/30 uppercase tracking-[0.3em]">Technical Authentication Protocol</div>
                        <div class="flex-1 h-px bg-[#101D33]/5"></div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em] ml-2">Employee ID</label>
                            <input type="text" name="employee_id" value="{{ $employee->employee_id }}" required class="w-full bg-[#101D33] text-[#D4AF37] border-none rounded-2xl p-5 focus:ring-[#D4AF37] text-sm font-black tracking-[0.2em] shadow-xl">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em] ml-2">RFID Identity Token</label>
                            <input type="text" name="rfid_card_num" value="{{ $employee->rfid_card_num }}" class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl p-5 focus:ring-[#101D33] text-sm font-bold text-[#101D33] tracking-widest shadow-sm">
                        </div>
                        <div class="space-y-3" x-data="{ 
                            isEnrolling: false, 
                            status: '', 
                            pollInterval: null,
                            startEnroll() {
                                this.isEnrolling = true;
                                this.status = 'Initializing...';
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
                            <div class="flex items-center justify-between ml-2">
                                <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em]">Biometric Fingerprint Register</label>
                                <button type="button" x-on:click="startEnroll" x-show="!isEnrolling" class="text-[9px] font-black text-[#D4AF37] bg-[#101D33] px-3 py-1.5 rounded-lg hover:bg-[#660000] transition flex items-center gap-2 shadow-lg">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                    Initiate Enrollment
                                </button>
                                <div x-show="isEnrolling" class="flex items-center gap-2">
                                    <div class="w-2 h-2 bg-[#660000] rounded-full animate-pulse"></div>
                                    <span class="text-[10px] font-black text-[#660000] uppercase tracking-widest" x-text="status"></span>
                                </div>
                            </div>
                            <input type="number" name="fingerprint_id" value="{{ $employee->fingerprint_id }}" class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl p-5 focus:ring-[#101D33] text-sm font-black text-[#101D33] tabular-nums shadow-sm" placeholder="e.g. 001">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em] ml-2">Biometric Data stream (Legacy)</label>
                            <input type="text" name="biometric_template" value="{{ $employee->biometric_template }}" class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl p-5 focus:ring-[#101D33] text-sm font-bold text-[#101D33] shadow-sm">
                        </div>
                    </div>
                </div>

                <!-- Section: Statutory Identifiers -->
                <div class="space-y-8">
                    <div class="flex items-center gap-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#D4AF37]"></span>
                        <div class="text-[10px] font-black text-[#101D33]/30 uppercase tracking-[0.3em]">Statutory Identifiers</div>
                        <div class="flex-1 h-px bg-[#101D33]/5"></div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em] ml-2">Tax ID (TIN)</label>
                            <input type="text" name="tin_id" value="{{ $employee->tin_id }}" class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl p-5 focus:ring-[#101D33] text-sm font-bold text-[#101D33] tabular-nums shadow-sm" placeholder="000-000-000-000">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em] ml-2">SSS Number</label>
                            <input type="text" name="sss_id" value="{{ $employee->sss_id }}" class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl p-5 focus:ring-[#101D33] text-sm font-bold text-[#101D33] tabular-nums shadow-sm" placeholder="00-0000000-0">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em] ml-2">PhilHealth ID</label>
                            <input type="text" name="philhealth_id" value="{{ $employee->philhealth_id }}" class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl p-5 focus:ring-[#101D33] text-sm font-bold text-[#101D33] tabular-nums shadow-sm" placeholder="00-000000000-0">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em] ml-2">Pag-IBIG Number</label>
                            <input type="text" name="pagibig_id" value="{{ $employee->pagibig_id }}" class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl p-5 focus:ring-[#101D33] text-sm font-bold text-[#101D33] tabular-nums shadow-sm" placeholder="0000-0000-0000">
                        </div>
                    </div>
                </div>

                <!-- Section: Leave Allocation -->
                <div class="space-y-8">
                    <div class="flex items-center gap-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#D4AF37]"></span>
                        <div class="text-[10px] font-black text-[#101D33]/30 uppercase tracking-[0.3em]">Temporal Leave Allocation</div>
                        <div class="flex-1 h-px bg-[#101D33]/5"></div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em] ml-2">Sick Leave Credits</label>
                            <input type="number" step="0.5" name="sick_leave_credits" value="{{ $employee->sick_leave_credits }}" class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl p-5 focus:ring-[#101D33] text-sm font-black text-[#660000] tabular-nums shadow-sm">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em] ml-2">Vacation Leave Credits</label>
                            <input type="number" step="0.5" name="vacation_leave_credits" value="{{ $employee->vacation_leave_credits }}" class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl p-5 focus:ring-[#101D33] text-sm font-black text-emerald-600 tabular-nums shadow-sm">
                        </div>
                    </div>
                </div>

                <!-- Section: Fiscal Compensation -->
                <div class="space-y-8">
                    <div class="flex items-center gap-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#D4AF37]"></span>
                        <div class="text-[10px] font-black text-[#101D33]/30 uppercase tracking-[0.3em]">Fiscal Remuneration Parameters</div>
                        <div class="flex-1 h-px bg-[#101D33]/5"></div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em] ml-2">Hourly Remuneration (₱)</label>
                            <input type="number" step="0.01" name="hourly_rate" value="{{ $employee->hourly_rate }}" required class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl p-5 focus:ring-[#101D33] text-sm font-black text-[#101D33] tabular-nums shadow-sm">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em] ml-2">Institutional Protocol (Role)</label>
                            <select name="role" class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl p-5 focus:ring-[#101D33] text-sm font-black text-[#101D33] uppercase tracking-widest shadow-sm">
                                <option value="professor" {{ $employee->role === 'professor' ? 'selected' : '' }}>Faculty / Professor</option>
                                <option value="employee" {{ $employee->role === 'employee' ? 'selected' : '' }}>Institutional Staff</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section: Temporal Schedule Assets -->
                <div class="space-y-8">
                    <div class="flex items-center gap-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#D4AF37]"></span>
                        <div class="text-[10px] font-black text-[#101D33]/30 uppercase tracking-[0.3em]">Temporal Schedule Assets</div>
                        <div class="flex-1 h-px bg-[#101D33]/5"></div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                        <div class="space-y-6">
                            <div class="flex items-center justify-between ml-2">
                                <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em]">Official Schedule Imaging</label>
                                @if($employee->schedule_image)
                                    <button type="button" x-on:click="$dispatch('open-ai-scan')" class="px-4 py-2 bg-[#101D33] text-[#D4AF37] rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-[#660000] hover:text-white transition-all shadow-lg flex items-center gap-3">
                                        <span class="animate-pulse">✨</span> Initiate AI Auto-Scan
                                    </button>
                                @endif
                            </div>
                            
                            @if($employee->schedule_image)
                                <div class="relative group rounded-[2rem] overflow-hidden border border-[#101D33]/10 shadow-inner bg-[#FDFCF8] p-4 flex items-center justify-center min-h-[300px]">
                                    <img src="{{ asset($employee->schedule_image) }}" class="max-w-full h-auto rounded-xl shadow-2xl max-h-[500px] object-contain group-hover:scale-[1.02] transition duration-700">
                                    <div class="absolute inset-0 bg-[#101D33]/40 opacity-0 group-hover:opacity-100 transition duration-500 flex items-center justify-center">
                                        <a href="{{ asset($employee->schedule_image) }}" target="_blank" class="px-8 py-4 bg-white text-[#101D33] rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-2xl hover:bg-[#D4AF37] transition-all">Expand Asset Ledger</a>
                                    </div>
                                </div>
                            @else
                                <div class="bg-[#660000]/5 border border-[#660000]/10 rounded-[2rem] p-10 text-center">
                                    <svg class="w-12 h-12 text-[#660000]/20 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                                    <p class="text-xs font-black text-[#660000] uppercase tracking-widest">No imaging assets localized</p>
                                </div>
                            @endif

                            <div class="relative group">
                                <div id="schedule-dropzone" class="flex flex-col items-center justify-center p-8 border-2 border-[#101D33]/10 border-dashed rounded-[2rem] hover:border-[#D4AF37] transition-all cursor-pointer bg-[#FDFCF8]/50 group-hover:bg-[#FDFCF8]">
                                    <svg class="w-10 h-10 text-[#101D33]/10 group-hover:text-[#D4AF37] transition duration-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <p class="text-[10px] font-black text-[#101D33]/40 uppercase tracking-widest text-center" id="schedule-file-label">Update Schedule Imaging</p>
                                    <input type="file" name="schedule_image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="document.getElementById('schedule-file-label').innerText = this.files[0].name; document.getElementById('schedule-dropzone').classList.add('border-[#D4AF37]', 'bg-[#FDFCF8]');">
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em] ml-2">Digital Ledger Streams (Excel)</label>
                            
                            @if($employee->schedule_file)
                                <div class="p-8 bg-emerald-50 border border-emerald-100 rounded-[2rem] flex items-center justify-between shadow-sm">
                                    <div class="flex items-center gap-5">
                                        <div class="w-12 h-12 bg-emerald-500 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-200">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-black text-emerald-800 uppercase tracking-widest mb-1">Active Schedule Stream</p>
                                            <p class="text-[10px] text-emerald-600/60 font-bold italic">Official Institutional Ledger</p>
                                        </div>
                                    </div>
                                    <a href="{{ asset($employee->schedule_file) }}" target="_blank" class="px-6 py-3 bg-white text-emerald-600 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm hover:shadow-md transition-all">Export Record</a>
                                </div>
                            @endif

                            <div class="relative group">
                                <div id="excel-dropzone" class="flex flex-col items-center justify-center p-8 border-2 border-[#101D33]/10 border-dashed rounded-[2rem] hover:border-[#660000] transition-all cursor-pointer bg-[#FDFCF8]/50 group-hover:bg-[#FDFCF8]">
                                    <svg class="w-10 h-10 text-[#101D33]/10 group-hover:text-[#660000] transition duration-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                    <p class="text-[10px] font-black text-[#101D33]/40 uppercase tracking-widest text-center" id="excel-file-label">Update Ledger Data (.xlsx)</p>
                                    <input type="file" name="schedule_file" accept=".xlsx,.xls,.csv" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="document.getElementById('excel-file-label').innerText = this.files[0].name; document.getElementById('excel-dropzone').classList.add('border-[#660000]', 'bg-[#FDFCF8]');">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Controls -->
                <div class="pt-10 flex flex-col sm:flex-row justify-between items-center gap-6 border-t border-[#101D33]/5">
                    <a href="{{ route('employees.index') }}" class="text-[10px] font-black text-[#660000] uppercase tracking-[0.2em] hover:opacity-70 transition-all underline underline-offset-8 decoration-2">Discard Adjustments</a>
                    <button type="submit" class="w-full sm:w-auto px-12 py-5 bg-[#101D33] text-white rounded-[2rem] font-black text-xs uppercase tracking-[0.3em] shadow-[0_20px_50px_rgba(16,29,51,0.2)] hover:bg-[#D4AF37] hover:text-[#101D33] hover:-translate-y-1 transition-all duration-500 active:scale-95">
                        Commit Adjustment Registry
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- AI SCANNING OVERLAY MODAL --}}
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
                this.error = 'Institutional AI communication interrupted.';
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
    x-on:open-ai-scan.window="open = true; if(results.length === 0) startScan()"
    x-show="open" 
    class="fixed inset-0 z-[150] overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-6">
            <div class="fixed inset-0 bg-[#101D33]/80 backdrop-blur-2xl" x-on:click="open = false"></div>
            
            <div class="relative bg-white rounded-[3rem] shadow-2xl w-full max-w-3xl overflow-hidden border border-white/20 animate-in zoom-in-95 duration-500">
                <div class="bg-[#101D33] p-12 text-white flex items-center justify-between">
                    <div>
                        <h3 class="text-3xl font-['DM_Serif_Display'] leading-none">AI <span class="text-[#D4AF37]">Neural</span> Scan</h3>
                        <p class="text-[9px] font-bold text-white/30 uppercase tracking-[0.4em] mt-6">Extracting Institutional Timelines</p>
                    </div>
                    <button x-on:click="open = false" class="w-14 h-14 rounded-2xl bg-white/5 text-white/20 hover:text-white hover:bg-white/10 flex items-center justify-center transition-all">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-12 min-h-[400px] flex flex-col">
                    @if($employee->schedule_image)
                        <div class="mb-10 rounded-[2rem] overflow-hidden border border-[#101D33]/5 h-40 relative group shadow-inner">
                            <img src="{{ asset($employee->schedule_image) }}" class="w-full h-full object-cover grayscale opacity-20 blur-[1px] group-hover:grayscale-0 group-hover:opacity-100 group-hover:blur-0 transition duration-700">
                            <div class="absolute inset-0 bg-gradient-to-b from-transparent to-[#101D33]/40 flex items-end justify-center pb-6">
                                <span class="text-[9px] font-black text-white uppercase tracking-[0.4em] bg-[#101D33]/80 px-5 py-2.5 rounded-full backdrop-blur-xl border border-white/10">Target Asset: Official Record</span>
                            </div>
                        </div>
                    @endif

                    <div x-show="scanning" class="flex-1 flex flex-col items-center justify-center py-20">
                        <div class="relative w-24 h-24 mb-10">
                            <div class="absolute inset-0 border-4 border-[#101D33]/5 rounded-full"></div>
                            <div class="absolute inset-0 border-4 border-t-[#D4AF37] rounded-full animate-spin"></div>
                        </div>
                        <p class="text-[11px] font-black text-[#101D33] uppercase tracking-[0.5em] animate-pulse">Processing Neural Patterns...</p>
                    </div>

                    <div x-show="!scanning && results.length > 0" class="flex-1 space-y-6">
                        <div class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.4em] mb-8 flex items-center gap-3">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></span>
                            Scan Successful: Verification Required
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[350px] overflow-y-auto pr-4 custom-scrollbar">
                            <template x-for="(s, index) in results" :key="index">
                                <div class="bg-[#FDFCF8] rounded-[2rem] border border-[#101D33]/5 p-6 hover:border-[#D4AF37] transition-all group">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="text-xs font-black text-[#101D33] uppercase tracking-[0.2em]" x-text="s.day_of_week"></div>
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-0.5 bg-emerald-500/10 text-emerald-500 rounded-full text-[7px] font-black uppercase tracking-widest border border-emerald-500/20">Verified Node</span>
                                            <div class="w-8 h-8 rounded-xl bg-[#101D33] text-[#D4AF37] flex items-center justify-center text-[10px] font-black" x-text="s.day_of_week.substring(0,3)"></div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <input type="text" x-model="s.start_time" class="flex-1 bg-white border border-[#101D33]/5 rounded-xl text-[11px] font-black text-center py-3 focus:ring-[#101D33] tabular-nums">
                                        <span class="text-[#101D33]/20">→</span>
                                        <input type="text" x-model="s.end_time" class="flex-1 bg-white border border-[#101D33]/5 rounded-xl text-[11px] font-black text-center py-3 focus:ring-[#101D33] tabular-nums">
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div x-show="error" class="flex-1 flex flex-col items-center justify-center py-20 text-center">
                        <div class="w-20 h-20 bg-[#660000]/5 text-[#660000] rounded-full flex items-center justify-center mb-8">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-xs font-black text-[#660000] uppercase tracking-[0.4em]" x-text="error"></p>
                        <button x-on:click="startScan" class="mt-8 text-[10px] font-black text-[#101D33] uppercase tracking-[0.3em] hover:text-[#D4AF37] underline underline-offset-8">Re-initialize Neural Probe</button>
                    </div>

                    <div class="mt-12 pt-10 border-t border-[#101D33]/5 flex justify-end items-center gap-8">
                        <button x-on:click="open = false" class="text-[10px] font-black text-[#101D33]/30 uppercase tracking-[0.3em] hover:text-[#660000] transition-all">Discard Adjustment</button>
                        <button x-on:click="saveResults" x-show="results.length > 0" class="px-12 py-5 bg-[#101D33] text-[#D4AF37] rounded-[2rem] font-black text-[10px] uppercase tracking-[0.3em] shadow-2xl hover:bg-[#D4AF37] hover:text-[#101D33] transition-all">
                            Commit Neural Sync
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
