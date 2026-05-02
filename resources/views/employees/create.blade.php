<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <span class="text-[#101D33]/40 font-bold uppercase tracking-[0.2em] text-[10px]">Personnel</span>
            <span class="text-[#101D33]/20">/</span>
            <span class="font-['DM_Serif_Text'] text-[#101D33] italic">Dossier Creation</span>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto mt-12 mb-20 animate-in-up">
        <div class="bg-white rounded-[3rem] border border-[#101D33]/5 shadow-[0_40px_100px_rgba(16,29,51,0.06)] overflow-hidden">
            <div class="p-10 border-b border-[#101D33]/5 bg-[#FDFCF8]/50 text-center relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-tr from-[#660000]/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <h3 class="text-3xl font-['DM_Serif_Display'] text-[#101D33] leading-none mb-4 relative z-10">Institutional <span class="text-[#D4AF37]">Identity</span> Registration</h3>
                <p class="text-[10px] text-[#101D33]/30 font-black uppercase tracking-[0.4em] relative z-10">Personnel Registry Authorization Protocol</p>
            </div>

            <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data" class="p-10 space-y-12 relative z-10"
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
                            document.getElementById('schedule-dropzone').classList.add('border-[#D4AF37]', 'bg-[#FDFCF8]');
                        }
                    },
                    startScan() {
                        const fileInput = document.querySelector('input[name=schedule_image]');
                        if (!fileInput.files || !fileInput.files[0]) {
                            alert('Please select an official schedule image first.');
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
                            this.error = 'Institutional AI communication interrupted.';
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
                
                <!-- Section: Personal Dossier -->
                <div class="space-y-8">
                    <div class="flex items-center gap-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#D4AF37]"></span>
                        <div class="text-[10px] font-black text-[#101D33]/30 uppercase tracking-[0.3em]">Personal Dossier Information</div>
                        <div class="flex-1 h-px bg-[#101D33]/5"></div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em] ml-2">Legal Identity (Full Name)</label>
                            <input type="text" name="name" required class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl p-5 focus:ring-[#101D33] text-sm font-['DM_Serif_Text'] placeholder:text-slate-300 transition-all shadow-sm">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em] ml-2">Institutional Email</label>
                            <input type="email" name="email" required class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl p-5 focus:ring-[#101D33] text-sm font-bold text-[#101D33] transition-all shadow-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em] ml-2">Registry Username</label>
                            <input x-ref="usernameField" type="text" name="username" required class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl p-5 focus:ring-[#101D33] text-sm font-black text-[#101D33] tabular-nums" :value="autoUsername" placeholder="Synchronizing...">
                            <p class="text-[9px] text-slate-300 italic px-2">Generated via Identity Protocol. Editable.</p>
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em] ml-2">Access Key (Password)</label>
                            <input x-ref="passwordField" type="text" name="password" required minlength="6" class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl p-5 focus:ring-[#101D33] text-sm font-black text-[#660000] tabular-nums" :value="autoPassword" placeholder="Awaiting ID...">
                            <p class="text-[9px] text-slate-300 italic px-2">Standard: AISAT-{ID}</p>
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em] ml-2">Key Confirmation</label>
                            <input x-ref="passwordConfirmField" type="text" name="password_confirmation" required minlength="6" class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl p-5 focus:ring-[#101D33] text-sm font-black text-[#660000] tabular-nums" :value="autoPassword" placeholder="Awaiting ID...">
                        </div>
                    </div>
                </div>

                <!-- Section: Technical Authentication -->
                <div class="space-y-8">
                    <div class="flex items-center gap-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#D4AF37]"></span>
                        <div class="text-[10px] font-black text-[#101D33]/30 uppercase tracking-[0.3em]">Technical Authentication Streams</div>
                        <div class="flex-1 h-px bg-[#101D33]/5"></div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em] ml-2">Employee ID</label>
                            <input type="text" name="employee_id" required class="w-full bg-[#101D33] text-[#D4AF37] border-none rounded-2xl p-5 focus:ring-[#D4AF37] text-sm font-black tracking-[0.2em] shadow-xl">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em] ml-2">RFID Identity Token</label>
                            <input type="text" name="rfid_card_num" class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl p-5 focus:ring-[#101D33] text-sm font-bold text-[#101D33] tracking-widest shadow-sm">
                        </div>
                        <div class="space-y-3" x-data="{ 
                            startEnroll() {
                                alert('Note: Automated enrollment requires active user record. Please finalize registration first, then use the Enrollment Protocol in the Edit Interface.');
                            }
                        }">
                            <div class="flex items-center justify-between ml-2">
                                <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em]">Fingerprint Register (ID)</label>
                                <button type="button" @click="startEnroll" class="text-[9px] font-black text-[#D4AF37] bg-[#101D33] px-3 py-1.5 rounded-lg hover:bg-[#660000] transition flex items-center gap-2 shadow-lg">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                    Protocol Guide
                                </button>
                            </div>
                            <input type="number" name="fingerprint_id" class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl p-5 focus:ring-[#101D33] text-sm font-black text-[#101D33] tabular-nums shadow-sm" placeholder="e.g. 001">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em] ml-2">Biometric Hash Template</label>
                            <input type="text" name="biometric_template" class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl p-5 focus:ring-[#101D33] text-sm font-bold text-[#101D33] shadow-sm">
                        </div>
                    </div>
                </div>

                <!-- Section: Statutory Identifiers -->
                <div class="space-y-8">
                    <div class="flex items-center gap-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#D4AF37]"></span>
                        <div class="text-[10px] font-black text-[#101D33]/30 uppercase tracking-[0.3em]">Statutory Compliance Identifiers</div>
                        <div class="flex-1 h-px bg-[#101D33]/5"></div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em] ml-2">Tax Identity (TIN)</label>
                            <input type="text" name="tin_id" class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl p-5 focus:ring-[#101D33] text-sm font-bold text-[#101D33] tabular-nums shadow-sm" placeholder="000-000-000-000">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em] ml-2">SSS Universal ID</label>
                            <input type="text" name="sss_id" class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl p-5 focus:ring-[#101D33] text-sm font-bold text-[#101D33] tabular-nums shadow-sm" placeholder="00-0000000-0">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em] ml-2">PhilHealth Identity</label>
                            <input type="text" name="philhealth_id" class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl p-5 focus:ring-[#101D33] text-sm font-bold text-[#101D33] tabular-nums shadow-sm" placeholder="00-000000000-0">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em] ml-2">Pag-IBIG Number</label>
                            <input type="text" name="pagibig_id" class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl p-5 focus:ring-[#101D33] text-sm font-bold text-[#101D33] tabular-nums shadow-sm" placeholder="0000-0000-0000">
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
                            <input type="number" step="0.01" name="hourly_rate" required class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl p-5 focus:ring-[#101D33] text-sm font-black text-[#101D33] tabular-nums shadow-sm">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em] ml-2">Institutional Protocol (Role)</label>
                            <select name="role" class="w-full bg-[#FDFCF8] border-[#101D33]/5 rounded-2xl p-5 focus:ring-[#101D33] text-sm font-black text-[#101D33] uppercase tracking-widest shadow-sm">
                                <option value="professor">Faculty / Professor</option>
                                <option value="employee">Institutional Staff</option>
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
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em] ml-2">Official Schedule Imaging</label>
                            <div class="relative group h-full">
                                <div id="schedule-dropzone" class="flex flex-col items-center justify-center p-8 border-2 border-[#101D33]/10 border-dashed rounded-[2rem] hover:border-[#D4AF37] transition-all cursor-pointer bg-[#FDFCF8]/50 group-hover:bg-[#FDFCF8] h-full min-h-[200px]">
                                    <svg class="w-12 h-12 text-[#101D33]/10 group-hover:text-[#D4AF37] transition duration-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <p class="text-xs font-black text-[#101D33]/40 uppercase tracking-widest text-center" id="schedule-file-label">Localized Schedule Asset</p>
                                    <input type="file" name="schedule_image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" @change="handleFileChange">
                                </div>
                                <button type="button" @click="startScan" x-show="imagePreview" class="absolute bottom-4 left-4 right-4 py-4 bg-[#101D33] text-[#D4AF37] rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] shadow-2xl hover:bg-[#660000] hover:text-white transition-all flex items-center justify-center gap-3 animate-in fade-in zoom-in-95">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    Initiate AI Intelligence Scan
                                </button>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#101D33] uppercase tracking-[0.2em] ml-2">Digital Ledger Import (Excel)</label>
                            <div class="relative group h-full">
                                <div id="excel-dropzone" class="flex flex-col items-center justify-center p-8 border-2 border-[#101D33]/10 border-dashed rounded-[2rem] hover:border-[#660000] transition-all cursor-pointer bg-[#FDFCF8]/50 group-hover:bg-[#FDFCF8] h-full min-h-[200px]">
                                    <svg class="w-12 h-12 text-[#101D33]/10 group-hover:text-[#660000] transition duration-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                    <p class="text-xs font-black text-[#101D33]/40 uppercase tracking-widest text-center" id="excel-file-label">Excel Data Stream (.xlsx)</p>
                                    <input type="file" name="schedule_file" accept=".xlsx,.xls,.csv" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="document.getElementById('excel-file-label').innerText = this.files[0].name; document.getElementById('excel-dropzone').classList.add('border-[#660000]', 'bg-[#FDFCF8]');">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Controls -->
                <div class="pt-10 flex flex-col sm:flex-row justify-between items-center gap-6 border-t border-[#101D33]/5">
                    <a href="{{ route('employees.index') }}" class="text-[10px] font-black text-[#660000] uppercase tracking-[0.2em] hover:opacity-70 transition-all underline underline-offset-8 decoration-2">Abort Registration</a>
                    <button type="submit" class="w-full sm:w-auto px-12 py-5 bg-[#101D33] text-white rounded-[2rem] font-black text-xs uppercase tracking-[0.3em] shadow-[0_20px_50px_rgba(16,29,51,0.2)] hover:bg-[#D4AF37] hover:text-[#101D33] hover:-translate-y-1 transition-all duration-500 active:scale-95">
                        Confirm & Commit Registry
                    </button>
                </div>

                {{-- AI SCAN OVERLAY MODAL --}}
                <div x-show="open" class="fixed inset-0 z-[150] overflow-y-auto" style="display: none;">
                    <div class="flex items-center justify-center min-h-screen px-6">
                        <div class="fixed inset-0 bg-[#101D33]/80 backdrop-blur-2xl" @click="open = false"></div>
                        
                        <div class="relative bg-white rounded-[3rem] shadow-2xl w-full max-w-3xl overflow-hidden border border-white/20 animate-in zoom-in-95 duration-500">
                            <div class="bg-[#101D33] p-12 text-white flex items-center justify-between">
                                <div>
                                    <h3 class="text-3xl font-['DM_Serif_Display'] leading-none">AI <span class="text-[#D4AF37]">Onboarding</span> Scan</h3>
                                    <p class="text-[9px] font-bold text-white/30 uppercase tracking-[0.4em] mt-6">Temporal Stream Extraction Protocol</p>
                                </div>
                                <button type="button" @click="open = false" class="w-14 h-14 rounded-2xl bg-white/5 text-white/20 hover:text-white hover:bg-white/10 flex items-center justify-center transition-all">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>

                            <div class="p-12 min-h-[400px] flex flex-col">
                                <div x-show="imagePreview" class="mb-10 rounded-[2rem] overflow-hidden border border-[#101D33]/5 h-48 relative group shadow-inner">
                                    <img :src="imagePreview" class="w-full h-full object-cover grayscale opacity-20 blur-[1px] group-hover:grayscale-0 group-hover:opacity-100 group-hover:blur-0 transition duration-700">
                                    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-[#101D33]/40 flex items-end justify-center pb-8">
                                        <span class="text-[10px] font-black text-white uppercase tracking-[0.4em] bg-[#101D33]/80 px-6 py-3 rounded-full backdrop-blur-xl border border-white/10">Analyzing Source Asset</span>
                                    </div>
                                </div>

                                <div x-show="scanning" class="flex-1 flex flex-col items-center justify-center py-20">
                                    <div class="relative w-24 h-24 mb-10">
                                        <div class="absolute inset-0 border-4 border-[#101D33]/5 rounded-full"></div>
                                        <div class="absolute inset-0 border-4 border-t-[#D4AF37] rounded-full animate-spin"></div>
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-[#D4AF37] animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                        </div>
                                    </div>
                                    <p class="text-[11px] font-black text-[#101D33] uppercase tracking-[0.5em] animate-pulse">Processing Neural Patterns...</p>
                                </div>

                                <div x-show="!scanning && results.length > 0" class="flex-1 space-y-6">
                                    <div class="flex items-center justify-between mb-8">
                                        <div class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.4em] flex items-center gap-3">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></span>
                                            Extraction Successful: Synchronize
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[350px] overflow-y-auto pr-4 custom-scrollbar">
                                        <template x-for="(s, index) in results" :key="index">
                                            <div class="bg-[#FDFCF8] rounded-[2rem] border border-[#101D33]/5 p-6 hover:border-[#D4AF37] transition-all group shadow-sm">
                                                <div class="flex items-center justify-between mb-4">
                                                    <div class="text-xs font-black text-[#101D33] uppercase tracking-[0.2em]" x-text="s.day_of_week"></div>
                                                    <div class="w-8 h-8 rounded-xl bg-[#101D33] text-[#D4AF37] flex items-center justify-center text-[10px] font-black" x-text="s.day_of_week.substring(0,3)"></div>
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
                                    <button type="button" @click="startScan" class="mt-8 text-[10px] font-black text-[#101D33] uppercase tracking-[0.3em] hover:text-[#D4AF37] underline underline-offset-8 transition-all">Retry Analysis Protocol</button>
                                </div>

                                <div class="mt-12 pt-10 border-t border-[#101D33]/5 flex justify-end items-center gap-8">
                                    <button type="button" @click="open = false" class="text-[10px] font-black text-[#101D33]/30 uppercase tracking-[0.3em] hover:text-[#660000] transition-all">Discard Streams</button>
                                    <button type="button" @click="open = false" x-show="results.length > 0" class="px-12 py-5 bg-[#101D33] text-[#D4AF37] rounded-[2rem] font-black text-[10px] uppercase tracking-[0.3em] shadow-2xl hover:bg-[#D4AF37] hover:text-[#101D33] transition-all">
                                        Synchronize Streams
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
