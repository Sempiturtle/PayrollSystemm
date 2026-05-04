<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <span class="text-[#101D33]/40 font-bold uppercase tracking-[0.2em] text-[10px]">Terminal</span>
            <span class="text-[#101D33]/20">/</span>
            <span class="font-['DM_Serif_Text'] text-[#101D33] italic">Identity Authentication</span>
        </div>
    </x-slot>

    <div class="max-w-[85rem] mx-auto py-6 px-4">
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">
            <!-- Primary Terminal Canvas (Left) -->
            <div class="xl:col-span-7 relative group">
                <!-- Institutional Glow -->
                <div class="absolute -inset-2 bg-gradient-to-r from-[#101D33] to-[#660000] rounded-[2rem] blur-xl opacity-10 group-hover:opacity-20 transition duration-1000"></div>
                
                <div class="relative bg-white rounded-[2rem] border border-[#101D33]/10 shadow-[0_30px_100px_rgba(16,29,51,0.08)] overflow-hidden flex flex-col min-h-[500px]">
                    <!-- Terminal Header -->
                    <div class="p-6 text-center border-b border-[#101D33]/5 bg-[#FDFCF8]/50">
                        <div class="inline-flex items-center gap-2.5 px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[8px] font-black uppercase tracking-[0.3em] mb-4 border border-emerald-100">
                            <span class="w-1 h-1 bg-emerald-500 rounded-full shadow-[0_0_10px_rgba(16,185,129,0.5)]"></span>
                            Terminal Active
                        </div>
                        
                        <div x-data="{ time: '' }" x-init="setInterval(() => { time = new Date().toLocaleTimeString('en-US', { timeZone: 'Asia/Manila', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }) }, 1000)" class="text-5xl font-['DM_Serif_Display'] tracking-tight text-[#101D33] mb-2 tabular-nums leading-none">
                            <span x-text="time || '00:00:00'"></span>
                        </div>
                        <div class="text-[#101D33]/40 font-bold tracking-[0.3em] uppercase text-[9px] leading-none">{{ date('l, F j, Y') }}</div>
                    </div>

                    <!-- Input Area -->
                    <div class="flex-1 p-8 flex flex-col items-center justify-center">
                        <div class="w-full space-y-6 text-center" x-data="{ rfid: '', fingerprint: '', lastUsed: '' }">
                            <h2 class="text-[9px] font-bold text-[#101D33]/30 uppercase tracking-[0.4em]">Multifactor Identity Verification</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- RFID Slot -->
                                <div @click="lastUsed = 'rfid'; $nextTick(() => $refs.rfidInput.focus())" :class="lastUsed === 'rfid' ? 'ring-2 ring-[#D4AF37] border-[#D4AF37]/20 shadow-lg' : 'border-[#101D33]/5'" class="relative bg-white rounded-[1.25rem] p-6 transition-all duration-500 cursor-pointer group border shadow-sm">
                                    <div class="w-9 h-9 rounded-lg bg-[#101D33]/5 flex items-center justify-center text-[#101D33]/20 mb-3 mx-auto group-hover:bg-[#101D33] group-hover:text-white transition-all duration-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                    </div>
                                    <div class="text-[8px] font-bold text-[#101D33]/30 uppercase mb-2 tracking-widest">RFID Credential</div>
                                    <input type="text" x-model="rfid" x-ref="rfidInput" @focus="lastUsed = 'rfid'"
                                           class="w-full bg-transparent border-none p-0 text-center text-base font-bold tracking-[0.2em] text-[#101D33] focus:ring-0 placeholder:text-slate-200" 
                                           placeholder="SCAN CARD">
                                </div>

                                <!-- Fingerprint Slot -->
                                <div @click="lastUsed = 'fp'; $nextTick(() => $refs.fpInput.focus())" :class="lastUsed === 'fp' ? 'ring-2 ring-emerald-500/30 border-emerald-500/20 shadow-lg' : 'border-[#101D33]/5'" class="relative bg-white rounded-[1.25rem] p-6 transition-all duration-500 cursor-pointer group border shadow-sm">
                                    <div class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 mb-3 mx-auto group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09a13.916 13.916 0 002.103-4.42m2.586-4.983a11.956 11.956 0 014.688 4.407m-.315-3.125a12.01 12.01 0 011.039 3.06m-9.454-9.454a12.01 12.01 0 0110.399 5.64M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16m4 0h2m-4 0h-1m1 0H9m2 2h2"></path></svg>
                                    </div>
                                    <div class="text-[8px] font-bold text-[#101D33]/30 uppercase mb-2 tracking-widest">Biometric Node</div>
                                    <input type="number" x-model="fingerprint" x-ref="fpInput" @focus="lastUsed = 'fp'"
                                           class="w-full bg-transparent border-none p-0 text-center text-base font-bold tracking-[0.2em] text-emerald-600 focus:ring-0 placeholder:text-slate-200" 
                                           placeholder="SCAN INDEX">
                                </div>
                            </div>

                            <div class="pt-4">
                                <button x-show="rfid && fingerprint" 
                                        @click="processMFA(rfid, fingerprint); rfid=''; fingerprint='';" 
                                        class="w-full py-3.5 bg-[#101D33] text-white rounded-[1.25rem] font-bold text-xs hover:bg-[#660000] shadow-[0_15px_40px_rgba(16,29,51,0.2)] transition-all duration-500 animate-in zoom-in-95 flex items-center justify-center gap-2.5">
                                    <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                    Authorize Identity
                                </button>
                                <div x-show="!rfid || !fingerprint" class="py-3.5 text-[8px] font-bold text-slate-300 uppercase tracking-[0.4em] bg-[#FDFCF8] rounded-[1.25rem] border border-dashed border-[#101D33]/10">
                                    Awaiting <span class="text-[#101D33]/50" x-text="!rfid ? 'Credential Card' : 'Biometric Scan'"></span>
                                </div>
                            </div>

                            <button @click="lastUsed = 'rfid'; $nextTick(() => $refs.rfidInput.focus())" 
                                    class="text-[8px] font-['DM_Serif_Text'] text-[#101D33]/30 mt-4 italic hover:text-[#D4AF37] transition-all cursor-pointer">
                                "Manual override required for lost credentials?"
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monitoring Sidebar (Right) -->
            <div class="xl:col-span-5 space-y-4">
                <!-- Institutional Pulse -->
                <div class="bg-[#101D33] rounded-[1.5rem] p-6 text-white relative overflow-hidden shadow-2xl">
                    <div class="absolute top-0 right-0 p-4 opacity-10">
                        <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                    </div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-[9px] font-bold text-[#D4AF37] uppercase tracking-[0.4em]">Institutional Pulse</h3>
                            <span class="px-2 py-0.5 bg-emerald-500/20 text-emerald-400 rounded-full text-[8px] font-bold uppercase tracking-widest border border-emerald-500/30">Live</span>
                        </div>
                        <div class="flex items-end gap-3 mb-6">
                            <span class="text-4xl font-['DM_Serif_Display'] leading-none text-white">{{ $onSiteCount }}</span>
                            <span class="text-[9px] font-bold text-white/40 uppercase tracking-widest pb-1">Personnel On-Site</span>
                        </div>
                        
                        @if($expectedArrivals->isNotEmpty())
                        <div class="space-y-2 border-t border-white/5 pt-4">
                            <p class="text-[8px] font-bold text-white/30 uppercase tracking-[0.2em] mb-2">Upcoming Arrivals (2h Window)</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($expectedArrivals as $user)
                                <div class="flex items-center gap-2 px-2 py-1 bg-white/5 rounded-lg border border-white/5">
                                    <div class="w-4 h-4 rounded-full bg-[#660000] flex items-center justify-center text-[7px] font-bold">{{ substr($user->name, 0, 1) }}</div>
                                    <span class="text-[8px] font-medium text-white/60">{{ explode(' ', $user->name)[0] }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Scans List -->
                <div class="bg-white rounded-[1.5rem] border border-[#101D33]/5 shadow-[0_20px_60px_rgba(16,29,51,0.05)] p-6 h-full flex flex-col">
                    <div class="flex items-center justify-between mb-4 border-b border-[#101D33]/5 pb-3">
                        <div>
                            <h3 class="text-[9px] font-bold text-[#101D33] uppercase tracking-[0.25em] leading-none">Access Stream</h3>
                            <p class="text-[8px] text-slate-400 font-['DM_Serif_Text'] italic mt-1 leading-none">Live institutional log.</p>
                        </div>
                        <div class="w-7 h-7 rounded-lg bg-[#101D33]/5 flex items-center justify-center text-[#101D33]/20">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                    </div>
                    
                    <div class="space-y-2.5 max-h-[320px] overflow-y-auto pr-2 custom-scrollbar" id="recent_scans_list">
                        @forelse($recentLogs as $log)
                        <div class="flex items-center justify-between p-3 bg-[#FDFCF8] rounded-[1rem] border border-[#101D33]/5 transition-all hover:bg-white hover:shadow-md">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-[#101D33] text-white flex items-center justify-center font-['DM_Serif_Display'] text-[10px] relative overflow-hidden">
                                    <div class="absolute inset-0 bg-gradient-to-br from-[#660000]/30 to-transparent"></div>
                                    <span class="relative z-10">{{ substr($log->user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <div class="text-[11px] font-['DM_Serif_Text'] text-[#101D33] leading-none mb-0.5">{{ $log->user->name }}</div>
                                    <div class="text-[7px] font-bold text-emerald-500 uppercase tracking-widest">Authorized</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-[11px] font-bold text-[#101D33] tabular-nums tracking-tighter">
                                    {{ \Carbon\Carbon::parse($log->time_out ?? $log->time_in)->format('H:i:s') }}
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-[9px] font-['DM_Serif_Text'] italic text-[#101D33]/20 uppercase tracking-widest">Awaiting signal...</div>
                        @endforelse
                    </div>

                    <!-- Sync Simulation -->
                    <div class="mt-auto pt-4 border-t border-[#101D33]/5">
                        <div class="bg-[#101D33] rounded-[1rem] p-4 relative overflow-hidden group/sync" x-data="{ open: false, rfid: '', fp: '', hoursAgo: 1 }">
                            <div class="absolute inset-0 bg-gradient-to-tr from-[#660000]/20 to-transparent opacity-50"></div>
                            <button @click="open = !open" class="relative z-10 w-full flex items-center justify-between text-[8px] font-bold text-white/40 uppercase tracking-[0.2em]">
                                <span class="flex items-center gap-2">
                                    <svg class="w-3 h-3 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    Institutional Recovery
                                </span>
                                <span class="text-white/20" x-text="open ? '−' : '+'"></span>
                            </button>
                            
                            <div x-show="open" x-transition class="mt-4 space-y-3 relative z-10">
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="text" x-model="rfid" placeholder="ID" class="bg-white/5 border-white/10 rounded-lg text-[9px] text-white focus:ring-[#D4AF37] placeholder:text-white/10 py-2 px-3 uppercase tracking-widest">
                                    <input type="number" x-model="fp" placeholder="BIO" class="bg-white/5 border-white/10 rounded-lg text-[9px] text-white focus:ring-[#D4AF37] placeholder:text-white/10 py-2 px-3">
                                </div>
                                <div class="flex items-center gap-3">
                                    <input type="range" x-model="hoursAgo" min="1" max="48" class="flex-1 h-0.5 bg-white/10 rounded-lg appearance-none cursor-pointer accent-[#D4AF37]">
                                    <span class="text-[9px] text-[#D4AF37] font-bold tabular-nums" x-text="hoursAgo + 'h'"></span>
                                </div>
                                <button @click="simulateSync(rfid, fp, hoursAgo)" 
                                        class="w-full py-2 bg-[#660000] hover:bg-[#800000] text-white rounded-lg text-[8px] font-bold uppercase tracking-[0.3em] transition-all">
                                    Force Sync
                                </button>
                            </div>
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
                const banner = document.getElementById('response_banner');
                const icon = document.getElementById('status_icon');
                const title = document.getElementById('status_title');
                const msg = document.getElementById('status_msg');

                banner.classList.remove('hidden', 'bg-emerald-600', 'bg-[#660000]', 'scale-90', 'opacity-0');
                banner.classList.add('bg-[#101D33]', 'scale-100', 'opacity-100');
                
                icon.innerHTML = '⚡';
                icon.className = 'w-20 h-20 rounded-[2rem] flex items-center justify-center text-4xl bg-white/10 text-[#D4AF37]';
                title.innerText = 'Terminal Throttled';
                msg.innerText = 'Excessive authentication signals detected. Security protocol active. Please wait.';

                setTimeout(() => {
                    banner.classList.add('scale-90', 'opacity-0');
                    setTimeout(() => banner.classList.add('hidden'), 700);
                }, 5000);
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
