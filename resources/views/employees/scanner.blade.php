<x-app-layout>
    <x-slot name="header">
        Live Attendance Scanner
    </x-slot>

    <div class="max-w-md mx-auto">
        <div class="relative group">
            <!-- Background Decoration -->
            <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-[2.5rem] blur opacity-25 group-hover:opacity-40 transition duration-1000 group-hover:duration-200"></div>
            
            <div class="relative bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-xl overflow-hidden min-h-[450px] flex flex-col">
                <!-- Scanner Header -->
                <div class="p-6 text-center border-b border-slate-50 dark:border-slate-800">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-full text-[10px] font-bold uppercase tracking-widest mb-4 animate-pulse">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                        Terminal Active
                    </div>
                    
                    <div x-data="{ time: '' }" x-init="setInterval(() => { time = new Date().toLocaleTimeString('en-US', { timeZone: 'Asia/Manila', hour: '2-digit', minute: '2-digit', second: '2-digit' }) }, 1000)" class="text-4xl font-light tracking-tighter text-slate-900 dark:text-slate-100 mb-1 tabular-nums">
                        <span x-text="time || '{{ date('h:i:s A') }}'"></span>
                    </div>
                    <div class="text-slate-400 font-bold tracking-[0.1em] uppercase text-[10px]">{{ date('l, F j, Y') }}</div>
                </div>

                <!-- Input Area -->
                <div class="flex-1 p-6 flex flex-col items-center justify-center space-y-6">
                    <div class="w-full space-y-3 text-center" x-data="{ rfid: '', fingerprint: '', lastUsed: '' }">
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-4">Flexible Dual-Factor Authorization</label>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <!-- RFID Slot -->
                            <div @click="lastUsed = 'rfid'" :class="lastUsed === 'rfid' ? 'ring-2 ring-indigo-500/50' : ''" class="relative bg-slate-50 dark:bg-slate-800 rounded-xl p-4 transition cursor-pointer group">
                                <div class="text-[8px] font-bold text-slate-400 uppercase mb-2 group-hover:text-indigo-500 transition">Identity Card</div>
                                <input type="text" x-model="rfid" @focus="lastUsed = 'rfid'"
                                       class="w-full bg-transparent border-none p-0 text-center text-sm font-bold tracking-widest text-indigo-600 focus:ring-0 placeholder:text-slate-300" 
                                       placeholder="SCAN OR ID">
                                <div x-show="rfid" class="absolute top-2 right-2 text-indigo-500 animate-pulse">●</div>
                            </div>

                            <!-- Fingerprint Slot -->
                            <div @click="lastUsed = 'fp'" :class="lastUsed === 'fp' ? 'ring-2 ring-emerald-500/50' : ''" class="relative bg-slate-50 dark:bg-slate-800 rounded-xl p-4 transition cursor-pointer group">
                                <div class="text-[8px] font-bold text-slate-400 uppercase mb-2 group-hover:text-emerald-500 transition">Biometric</div>
                                <input type="number" x-model="fingerprint" @focus="lastUsed = 'fp'"
                                       class="w-full bg-transparent border-none p-0 text-center text-sm font-bold tracking-widest text-emerald-600 focus:ring-0 placeholder:text-slate-300" 
                                       placeholder="ID 1-127">
                                <div x-show="fingerprint" class="absolute top-2 right-2 text-emerald-500 animate-pulse">●</div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button x-show="rfid && fingerprint" 
                                    @click="processMFA(rfid, fingerprint); rfid=''; fingerprint='';" 
                                    class="w-full py-3 bg-slate-900 text-white rounded-xl font-bold text-xs hover:bg-black shadow-lg transition animate-in zoom-in-95 duration-300">
                                Authorize Terminal
                            </button>
                            <div x-show="!rfid || !fingerprint" class="py-3 text-[9px] font-bold text-slate-300 uppercase tracking-widest bg-slate-50/50 dark:bg-slate-800/50 rounded-xl border border-dashed border-slate-200 dark:border-slate-700">
                                Waiting for <span x-text="!rfid ? 'Identity' : 'Fingerprint'"></span>...
                            </div>
                        </div>

                        <button @click="lastUsed = 'rfid'; $nextTick(() => document.querySelector('input[x-model=rfid]').focus())" 
                                class="text-[9px] text-slate-400 mt-6 leading-relaxed italic hover:text-indigo-500 transition-colors cursor-pointer">
                            Forgot Card? Use Employee ID + Biometrics.
                        </button>
                    </div>
                </div>

                <!-- Response Banner (Floating Toast) -->
                <div id="response_banner" class="hidden fixed top-8 left-1/2 -translate-x-1/2 z-[200] w-[calc(100%-2rem)] max-w-sm p-6 rounded-[2rem] shadow-2xl backdrop-blur-xl border border-white/20 transition-all duration-500 transform scale-95 opacity-0">
                    <div class="flex items-center gap-4">
                        <div id="status_icon" class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl shrink-0"></div>
                        <div>
                            <div id="status_title" class="font-black text-lg leading-tight uppercase tracking-tight"></div>
                            <div id="status_msg" class="text-xs opacity-90 font-bold leading-snug mt-0.5"></div>
                        </div>
                    </div>
                </div>

                <!-- Recent Scans List -->
                <div class="px-6 pb-6 mt-auto">
                    <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-3 border-t border-slate-50 dark:border-slate-800 pt-4">Recent Verified Scans</div>
                    <div class="space-y-2" id="recent_scans_list">
                        @forelse($recentLogs as $log)
                        <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-800/10 rounded-xl border border-slate-100 dark:border-slate-800 transition">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 font-bold text-[10px] uppercase">
                                    {{ substr($log->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-[10px] font-bold text-slate-700 dark:text-slate-300">{{ $log->user->name }}</div>
                                    <div class="text-[8px] font-bold text-emerald-500 uppercase tracking-tight">SECURED</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-[10px] font-bold text-slate-900 dark:text-slate-100 tabular-nums">
                                    {{ \Carbon\Carbon::parse($log->time_out ?? $log->time_in)->format('h:i A') }}
                                </div>
                                <span class="text-[8px] px-1.5 py-0.5 rounded-full {{ $log->status == 'On-time' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }} font-bold">{{ $log->status }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-2 text-[10px] italic text-slate-400">No active scans yet.</div>
                        @endforelse
                    </div>

                    <!-- Sync Simulation (Thesis Demo Tool) -->
                    <div class="mt-6 p-4 bg-slate-900 rounded-2xl border border-slate-800" x-data="{ open: false, rfid: '', fp: '', hoursAgo: 1 }">
                        <button @click="open = !open" class="w-full flex items-center justify-between text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                            <span>Thesis: Internet Recovery Simulation</span>
                            <span x-text="open ? '−' : '+'"></span>
                        </button>
                        
                        <div x-show="open" x-transition class="mt-4 space-y-3">
                            <div class="grid grid-cols-2 gap-2">
                                <input type="text" x-model="rfid" placeholder="RFID" class="bg-slate-800 border-none rounded-lg text-[10px] text-white focus:ring-indigo-500">
                                <input type="number" x-model="fp" placeholder="FP ID" class="bg-slate-800 border-none rounded-lg text-[10px] text-white focus:ring-indigo-500">
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[8px] text-slate-500 font-bold uppercase">Delay:</span>
                                <input type="range" x-model="hoursAgo" min="1" max="48" class="flex-1 h-1 bg-slate-800 rounded-lg appearance-none cursor-pointer">
                                <span class="text-[9px] text-indigo-400 font-bold tabular-nums" x-text="hoursAgo + 'h'"></span>
                            </div>
                            <button @click="simulateSync(rfid, fp, hoursAgo)" 
                                    class="w-full py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg text-[10px] font-bold transition">
                                Trigger Delayed Hardware Sync
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

                banner.classList.remove('hidden', 'bg-emerald-600', 'bg-rose-600', 'bg-slate-900', 'text-white', 'scale-95', 'opacity-0');
                banner.classList.add('scale-100', 'opacity-100');
                
                if (data.success) {
                    banner.classList.add('bg-emerald-600', 'text-white');
                    icon.innerHTML = '✓';
                    icon.className = 'w-12 h-12 rounded-2xl flex items-center justify-center text-2xl bg-white/20';
                    title.innerText = 'MFA Verified';
                    msg.innerText = data.message;

                    // Update recent scans list
                    const list = document.getElementById('recent_scans_list');
                    if (list.querySelector('.italic')) list.innerHTML = ''; 
                    
                    const newItem = document.createElement('div');
                    newItem.className = "flex items-center justify-between p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl border border-emerald-100 dark:border-emerald-800 transition-all duration-700 animate-in fade-in slide-in-from-top-4";
                    newItem.innerHTML = `
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center text-white font-bold text-xs uppercase underline">NEW</div>
                            <div>
                                <div class="text-xs font-bold text-slate-700 dark:text-slate-300">Scan Recorded</div>
                                <div class="text-[9px] font-bold text-emerald-500 uppercase tracking-widest">MFA (RFID + Biometric)</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs font-bold text-slate-900 dark:text-slate-100 tabular-nums">${data.time}</div>
                        </div>
                    `;
                    list.prepend(newItem);
                } else {
                    banner.classList.add('bg-rose-600', 'text-white');
                    icon.innerHTML = '✕';
                    icon.className = 'w-12 h-12 rounded-2xl flex items-center justify-center text-2xl bg-white/20';
                    title.innerText = 'MFA Failed';
                    msg.innerText = data.message;
                }

                banner.classList.remove('hidden');

                setTimeout(() => {
                    banner.classList.replace('scale-100', 'scale-95');
                    banner.classList.replace('opacity-100', 'opacity-0');
                    setTimeout(() => banner.classList.add('hidden'), 500);
                }, 4000);
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

                banner.classList.remove('hidden', 'bg-emerald-600', 'bg-rose-600', 'bg-slate-900', 'text-white');
                
                if (data.success && data.processed > 0) {
                    banner.classList.add('bg-slate-900', 'text-white');
                    title.innerText = 'Sync Successful';
                    msg.innerText = `Recovered ${data.processed} log(s) from memory.`;
                    
                    setTimeout(() => window.location.reload(), 2000);
                } else {
                    banner.classList.add('bg-rose-600', 'text-white');
                    title.innerText = 'Sync Failed';
                    msg.innerText = data.details[0] || 'Hardware authentication failed.';
                }

                banner.classList.remove('hidden');
                setTimeout(() => banner.classList.add('hidden'), 4000);
            });
        }
    </script>
</x-app-layout>
