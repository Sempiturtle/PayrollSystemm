<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <span class="text-[#101D33]/40 font-bold uppercase tracking-[0.2em] text-[10px]">Terminal</span>
            <span class="text-[#101D33]/20">/</span>
            <span class="font-['DM_Serif_Text'] text-[#101D33] italic">Identity Authentication</span>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto py-12">
        <div class="relative group">
            <!-- Institutional Glow -->
            <div class="absolute -inset-4 bg-gradient-to-r from-[#101D33] to-[#660000] rounded-[4rem] blur-2xl opacity-10 group-hover:opacity-20 transition duration-1000"></div>
            
            <div class="relative bg-white rounded-[3.5rem] border border-[#101D33]/10 shadow-[0_50px_150px_rgba(16,29,51,0.15)] overflow-hidden min-h-[600px] flex flex-col">
                <!-- Terminal Header -->
                <div class="p-12 text-center border-b border-[#101D33]/5 bg-[#FDFCF8]/50">
                    <div class="inline-flex items-center gap-3 px-5 py-2 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black uppercase tracking-[0.3em] mb-8 animate-pulse border border-emerald-100">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full shadow-[0_0_10px_rgba(16,185,129,0.5)]"></span>
                        Uplink Established
                    </div>
                    
                    <div x-data="{ time: '' }" x-init="setInterval(() => { time = new Date().toLocaleTimeString('en-US', { timeZone: 'Asia/Manila', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }) }, 1000)" class="text-7xl font-['DM_Serif_Display'] tracking-tight text-[#101D33] mb-4 tabular-nums">
                        <span x-text="time || '00:00:00'"></span>
                    </div>
                    <div class="text-[#101D33]/40 font-bold tracking-[0.3em] uppercase text-[11px]">{{ date('l, F j, Y') }}</div>
                </div>

                <!-- Input Area -->
                <div class="flex-1 p-12 flex flex-col items-center justify-center space-y-10">
                    <div class="w-full space-y-8 text-center" x-data="{ rfid: '', fingerprint: '', lastUsed: '' }">
                        <h2 class="text-[11px] font-bold text-[#101D33]/30 uppercase tracking-[0.4em]">Multifactor Identity Verification</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- RFID Slot -->
                            <div @click="lastUsed = 'rfid'; $nextTick(() => $refs.rfidInput.focus())" :class="lastUsed === 'rfid' ? 'ring-2 ring-[#D4AF37] border-[#D4AF37]/20 shadow-xl' : 'border-[#101D33]/5'" class="relative bg-white rounded-[2rem] p-8 transition-all duration-500 cursor-pointer group border shadow-sm">
                                <div class="w-12 h-12 rounded-2xl bg-[#101D33]/5 flex items-center justify-center text-[#101D33]/20 mb-4 mx-auto group-hover:bg-[#101D33] group-hover:text-white transition-all duration-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                </div>
                                <div class="text-[9px] font-bold text-[#101D33]/30 uppercase mb-3 tracking-widest">RFID Credential</div>
                                <input type="text" x-model="rfid" x-ref="rfidInput" @focus="lastUsed = 'rfid'"
                                       class="w-full bg-transparent border-none p-0 text-center text-lg font-bold tracking-[0.2em] text-[#101D33] focus:ring-0 placeholder:text-slate-200" 
                                       placeholder="SCAN CARD">
                                <div x-show="rfid" class="absolute top-4 right-4 text-[#D4AF37] animate-pulse">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                </div>
                            </div>

                            <!-- Fingerprint Slot -->
                            <div @click="lastUsed = 'fp'; $nextTick(() => $refs.fpInput.focus())" :class="lastUsed === 'fp' ? 'ring-2 ring-emerald-500/30 border-emerald-500/20 shadow-xl' : 'border-[#101D33]/5'" class="relative bg-white rounded-[2rem] p-8 transition-all duration-500 cursor-pointer group border shadow-sm">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 mb-4 mx-auto group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09a13.916 13.916 0 002.103-4.42m2.586-4.983a11.956 11.956 0 014.688 4.407m-.315-3.125a12.01 12.01 0 011.039 3.06m-9.454-9.454a12.01 12.01 0 0110.399 5.64M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16m4 0h2m-4 0h-1m1 0H9m2 2h2"></path></svg>
                                </div>
                                <div class="text-[9px] font-bold text-[#101D33]/30 uppercase mb-3 tracking-widest">Biometric Node</div>
                                <input type="number" x-model="fingerprint" x-ref="fpInput" @focus="lastUsed = 'fp'"
                                       class="w-full bg-transparent border-none p-0 text-center text-lg font-bold tracking-[0.2em] text-emerald-600 focus:ring-0 placeholder:text-slate-200" 
                                       placeholder="SCAN INDEX">
                                <div x-show="fingerprint" class="absolute top-4 right-4 text-emerald-500 animate-pulse">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6">
                            <button x-show="rfid && fingerprint" 
                                    @click="processMFA(rfid, fingerprint); rfid=''; fingerprint='';" 
                                    class="w-full py-5 bg-[#101D33] text-white rounded-[2rem] font-bold text-sm hover:bg-[#660000] shadow-[0_20px_50px_rgba(16,29,51,0.3)] transition-all duration-500 animate-in zoom-in-95 flex items-center justify-center gap-3">
                                <svg class="w-5 h-5 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                Authorize Identity
                            </button>
                            <div x-show="!rfid || !fingerprint" class="py-5 text-[10px] font-bold text-slate-300 uppercase tracking-[0.4em] bg-[#FDFCF8] rounded-[2rem] border border-dashed border-[#101D33]/10">
                                Awaiting <span class="text-[#101D33]/50" x-text="!rfid ? 'Credential Card' : 'Biometric Scan'"></span>
                            </div>
                        </div>

                        <button @click="lastUsed = 'rfid'; $nextTick(() => $refs.rfidInput.focus())" 
                                class="text-[10px] font-['DM_Serif_Text'] text-[#101D33]/40 mt-8 italic hover:text-[#D4AF37] transition-all cursor-pointer">
                            "Manual override required for lost credentials? Present to Registry Office."
                        </button>
                    </div>
                </div>

                <!-- Response Banner (Floating Toast) -->
                <div id="response_banner" class="hidden fixed top-12 left-1/2 -translate-x-1/2 z-[200] w-[calc(100%-4rem)] max-w-lg p-8 rounded-[3rem] shadow-[0_50px_100px_rgba(0,0,0,0.4)] backdrop-blur-3xl border border-white/20 transition-all duration-700 transform scale-90 opacity-0">
                    <div class="flex items-center gap-6">
                        <div id="status_icon" class="w-20 h-20 rounded-[2rem] flex items-center justify-center text-4xl shrink-0 shadow-inner"></div>
                        <div>
                            <div id="status_title" class="font-['DM_Serif_Display'] text-2xl leading-none text-white tracking-tight"></div>
                            <div id="status_msg" class="text-sm text-white/80 font-['DM_Serif_Text'] italic mt-2 leading-relaxed"></div>
                        </div>
                    </div>
                </div>

                <!-- Recent Scans List -->
                <div class="px-12 pb-12 mt-auto">
                    <div class="text-[10px] font-bold text-[#101D33]/30 uppercase tracking-[0.4em] mb-6 border-t border-[#101D33]/5 pt-8">Recent Authorized Streams</div>
                    <div class="space-y-4" id="recent_scans_list">
                        @forelse($recentLogs as $log)
                        <div class="flex items-center justify-between p-5 bg-[#FDFCF8] rounded-[2rem] border border-[#101D33]/5 transition-all hover:scale-[1.02] hover:shadow-lg">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-2xl bg-[#101D33] text-white flex items-center justify-center font-['DM_Serif_Display'] text-sm relative overflow-hidden">
                                    <div class="absolute inset-0 bg-gradient-to-br from-[#660000]/30 to-transparent"></div>
                                    <span class="relative z-10">{{ substr($log->user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <div class="text-sm font-['DM_Serif_Text'] text-[#101D33] tracking-tight">{{ $log->user->name }}</div>
                                    <div class="text-[9px] font-bold text-emerald-500 uppercase tracking-widest mt-1">Institutional Clearance Granted</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-bold text-[#101D33] tabular-nums tracking-tighter">
                                    {{ \Carbon\Carbon::parse($log->time_out ?? $log->time_in)->format('H:i:s') }}
                                </div>
                                <span class="text-[9px] px-3 py-1 rounded-full {{ $log->status == 'On-time' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-amber-50 text-amber-600 border border-amber-100' }} font-bold uppercase tracking-widest">{{ $log->status }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-[11px] font-['DM_Serif_Text'] italic text-[#101D33]/20 uppercase tracking-widest">System memory idle. Awaiting terminal signal.</div>
                        @endforelse
                    </div>

                    <!-- Sync Simulation (Thesis Demo Tool) -->
                    <div class="mt-12 p-8 bg-[#101D33] rounded-[2.5rem] border border-white/5 relative overflow-hidden group/sync" x-data="{ open: false, rfid: '', fp: '', hoursAgo: 1 }">
                        <div class="absolute inset-0 bg-gradient-to-tr from-[#660000]/20 to-transparent opacity-50"></div>
                        <button @click="open = !open" class="relative z-10 w-full flex items-center justify-between text-[10px] font-bold text-white/40 uppercase tracking-[0.4em]">
                            <span class="flex items-center gap-3">
                                <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                Network Recovery Protocol
                            </span>
                            <span class="text-white/20" x-text="open ? '−' : '+'"></span>
                        </button>
                        
                        <div x-show="open" x-transition class="mt-8 space-y-6 relative z-10">
                            <div class="grid grid-cols-2 gap-4">
                                <input type="text" x-model="rfid" placeholder="IDENTITY ID" class="bg-white/5 border-white/10 rounded-2xl text-[11px] text-white focus:ring-[#D4AF37] placeholder:text-white/10 py-3 px-5 uppercase tracking-widest">
                                <input type="number" x-model="fp" placeholder="BIOMETRIC ID" class="bg-white/5 border-white/10 rounded-2xl text-[11px] text-white focus:ring-[#D4AF37] placeholder:text-white/10 py-3 px-5">
                            </div>
                            <div class="flex items-center gap-6">
                                <span class="text-[9px] text-white/30 font-bold uppercase tracking-widest">Temporal Offset</span>
                                <input type="range" x-model="hoursAgo" min="1" max="48" class="flex-1 h-1 bg-white/10 rounded-lg appearance-none cursor-pointer accent-[#D4AF37]">
                                <span class="text-[11px] text-[#D4AF37] font-bold tabular-nums" x-text="hoursAgo + 'h'"></span>
                            </div>
                            <button @click="simulateSync(rfid, fp, hoursAgo)" 
                                    class="w-full py-4 bg-[#660000] hover:bg-[#800000] text-white rounded-2xl text-[10px] font-bold uppercase tracking-[0.3em] transition-all shadow-lg shadow-black/20">
                                Force Hardware Synchonization
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function processMFA(rfid, fingerprint) {
            fetch('{{ route('attendance.scan') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    rfid: rfid,
                    fingerprint_id: fingerprint,
                    source: 'MFA'
                })
            })
            .then(response => response.json())
            .then(data => {
                const banner = document.getElementById('response_banner');
                const icon = document.getElementById('status_icon');
                const title = document.getElementById('status_title');
                const msg = document.getElementById('status_msg');

                banner.classList.remove('hidden', 'bg-emerald-600', 'bg-[#660000]', 'bg-[#101D33]', 'scale-90', 'opacity-0');
                banner.classList.add('scale-100', 'opacity-100');
                
                if (data.success) {
                    banner.classList.add('bg-emerald-600');
                    icon.innerHTML = '✓';
                    icon.className = 'w-20 h-20 rounded-[2rem] flex items-center justify-center text-4xl bg-white/20 text-white';
                    title.innerText = 'Identity Verified';
                    msg.innerText = data.message;

                    // Update recent scans list
                    const list = document.getElementById('recent_scans_list');
                    if (list.querySelector('.italic')) list.innerHTML = ''; 
                    
                    const newItem = document.createElement('div');
                    newItem.className = "flex items-center justify-between p-6 bg-emerald-50 rounded-[2rem] border border-emerald-100 transition-all duration-1000 animate-in fade-in slide-in-from-top-4";
                    newItem.innerHTML = `
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-2xl bg-emerald-600 flex items-center justify-center text-white font-black text-[10px] uppercase shadow-lg">NEW</div>
                            <div>
                                <div class="text-sm font-['DM_Serif_Text'] text-[#101D33]">Authorization Stream Active</div>
                                <div class="text-[9px] font-bold text-emerald-500 uppercase tracking-[0.2em] mt-1">Secure MFA Handshake Successful</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-bold text-[#101D33] tabular-nums">${data.time}</div>
                        </div>
                    `;
                    list.prepend(newItem);
                } else {
                    banner.classList.add('bg-[#660000]');
                    icon.innerHTML = '✕';
                    icon.className = 'w-20 h-20 rounded-[2rem] flex items-center justify-center text-4xl bg-white/20 text-white';
                    title.innerText = 'Authentication Failed';
                    msg.innerText = data.message;
                }

                banner.classList.remove('hidden');

                setTimeout(() => {
                    banner.classList.add('scale-90', 'opacity-0');
                    setTimeout(() => banner.classList.add('hidden'), 700);
                }, 5000);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
        function simulateSync(rfid, fp, hoursAgo) {
            const scannedAt = new Date(Date.now() - (hoursAgo * 3600000)).toISOString();
            
            fetch('{{ route('attendance.sync') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    token: 'AISAT_SECURE_SYNC_2024',
                    logs: [{
                        rfid: rfid,
                        fingerprint_id: fp,
                        scanned_at: scannedAt,
                        source: 'MFA'
                    }]
                })
            })
            .then(response => response.json())
            .then(data => {
                const banner = document.getElementById('response_banner');
                const title = document.getElementById('status_title');
                const msg = document.getElementById('status_msg');

                banner.classList.remove('hidden', 'bg-emerald-600', 'bg-[#660000]', 'bg-[#101D33]');
                
                if (data.success && data.processed > 0) {
                    banner.classList.add('bg-[#101D33]');
                    title.innerText = 'Recovery Protocol Success';
                    msg.innerText = `Successfully reconciled ${data.processed} offline node event(s).`;
                    
                    setTimeout(() => window.location.reload(), 2000);
                } else {
                    banner.classList.add('bg-[#660000]');
                    title.innerText = 'Sync Protocol Error';
                    msg.innerText = data.details[0] || 'Hardware authentication handshake failed.';
                }

                banner.classList.remove('hidden');
                setTimeout(() => banner.classList.add('hidden'), 5000);
            });
        }
    </script>
</x-app-layout>
