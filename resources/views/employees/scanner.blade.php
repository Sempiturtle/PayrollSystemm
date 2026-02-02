<x-app-layout>
    <x-slot name="header">
        Live Attendance Scanner
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="relative group">
            <!-- Background Decoration -->
            <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-[2.5rem] blur opacity-25 group-hover:opacity-40 transition duration-1000 group-hover:duration-200"></div>
            
            <div class="relative bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-2xl overflow-hidden min-h-[500px] flex flex-col">
                <!-- Scanner Header -->
                <div class="p-10 text-center border-b border-slate-50 dark:border-slate-800">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-full text-xs font-bold uppercase tracking-widest mb-6 animate-pulse">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                        System Online
                    </div>
                    
                    <div x-data="{ time: '' }" x-init="setInterval(() => { time = new Date().toLocaleTimeString('en-US', { timeZone: 'Asia/Manila', hour: '2-digit', minute: '2-digit', second: '2-digit' }) }, 1000)" class="text-7xl font-light tracking-tighter text-slate-900 dark:text-slate-100 mb-2 tabular-nums">
                        <span x-text="time || '{{ date('h:i:s A') }}'"></span>
                    </div>
                    <div class="text-slate-400 font-bold tracking-[0.2em] uppercase text-sm">{{ date('l, F j, Y') }}</div>
                </div>

                <!-- Input Area -->
                <div class="flex-1 p-10 flex flex-col items-center justify-center space-y-8">
                    <div class="w-full max-w-sm space-y-4 text-center">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block">Waiting for Input...</label>
                        <input type="text" id="id_input" autofocus
                               class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl p-6 text-center text-2xl font-bold tracking-widest text-indigo-600 focus:ring-4 focus:ring-indigo-500/10 placeholder:text-slate-200 transition" 
                               placeholder="SCAN CARD OR ENTER ID">
                        <p class="text-xs text-slate-400 mt-4 leading-relaxed italic">Secondary biometric verification active as fallback.</p>
                    </div>

                    <div class="flex gap-4">
                        <button onclick="simulateScan('RFID')" class="px-8 py-4 bg-slate-900 text-white rounded-2xl font-bold shadow-xl hover:bg-black hover:-translate-y-1 transition active:scale-95 flex items-center gap-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                            Scan RFID
                        </button>
                        <button onclick="simulateScan('Biometric')" class="px-8 py-4 bg-white text-slate-900 border-2 border-slate-100 rounded-2xl font-bold hover:border-indigo-600 hover:text-indigo-600 transition flex items-center gap-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 3v1c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 3v1m0 0c.851 0 1.673.1 2.459.29m0 0A10.016 10.016 0 0115.353 10H14a3 3 0 00-2.828 4M12 11c0 3.517 1.009 6.799 2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 3v1m0 0c.851 0 1.673.1 2.459.29m0 0a10.016 10.016 0 011.094 9.71m3.44 2.04l-.054-.09a10.003 10.003 0 010-19.142m0 19.142A10.002 10.002 0 0120 13v-1M12 11c0 3.517 1.009 6.799 2.753 9.571m0 0c.851 0 1.673.1 2.459.29m0 0a10.016 10.016 0 011.094 9.71m-1.094-9.71A10.016 10.016 0 0018 11.29"></path></svg>
                            Use Fingerprint
                        </button>
                    </div>
                </div>

                <!-- Response Banner (Pop-up style) -->
                <div id="response_banner" class="hidden absolute bottom-8 left-1/2 -translate-x-1/2 w-full max-w-sm p-6 rounded-3xl shadow-2xl animate-bounce">
                    <div class="flex items-center gap-4">
                        <div id="status_icon" class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl"></div>
                        <div>
                            <div id="status_title" class="font-bold text-lg leading-tight uppercase tracking-tight"></div>
                            <div id="status_msg" class="text-sm opacity-80 font-medium"></div>
                        </div>
                    </div>
                </div>

                <!-- Recent Scans List -->
                <div class="px-10 pb-10 mt-auto">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4 border-t border-slate-50 dark:border-slate-800 pt-6">Recent Activity Today</div>
                    <div class="space-y-3" id="recent_scans_list">
                        @forelse($recentLogs as $log)
                        <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800/10 rounded-2xl border border-slate-100 dark:border-slate-800 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 font-bold text-xs uppercase">
                                    {{ substr($log->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ $log->user->name }}</div>
                                    <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $log->time_out ? 'CHECK-OUT' : 'CHECK-IN' }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs font-bold text-slate-900 dark:text-slate-100 tabular-nums">
                                    {{ \Carbon\Carbon::parse($log->time_out ?? $log->time_in)->format('h:i A') }}
                                </div>
                                <span class="text-[9px] px-2 py-0.5 rounded-full {{ $log->status == 'On-time' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }} font-bold">{{ $log->status }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4 text-xs italic text-slate-400">No active scans yet today.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function simulateScan(source) {
            const id = document.getElementById('id_input').value;
            if (!id) {
                alert('Please enter an ID or scan a card');
                return;
            }
            processScan(id, source);
        }

        function processScan(id, source) {
            fetch('{{ route('attendance.scan') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    id_value: id,
                    source: source
                })
            })
            .then(response => response.json())
            .then(data => {
                const banner = document.getElementById('response_banner');
                const icon = document.getElementById('status_icon');
                const title = document.getElementById('status_title');
                const msg = document.getElementById('status_msg');

                banner.classList.remove('hidden', 'bg-emerald-600', 'bg-rose-600', 'bg-slate-900', 'text-white');
                
                if (data.success) {
                    banner.classList.add('bg-emerald-600', 'text-white');
                    icon.innerHTML = '✓';
                    icon.className = 'w-12 h-12 rounded-2xl flex items-center justify-center text-2xl bg-white/20';
                    title.innerText = 'Access Granted';
                    msg.innerText = data.message;

                    // Update recent scans list
                    const list = document.getElementById('recent_scans_list');
                    if (list.querySelector('.italic')) list.innerHTML = ''; // Remove empty message
                    
                    const newItem = document.createElement('div');
                    newItem.className = "flex items-center justify-between p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl border border-indigo-100 dark:border-indigo-800 transition animate-pulse";
                    newItem.innerHTML = `
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold text-xs uppercase underline">NEW</div>
                            <div>
                                <div class="text-xs font-bold text-slate-700 dark:text-slate-300">Scan Recorded</div>
                                <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">${source}</div>
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
                    title.innerText = 'Authentication Failed';
                    msg.innerText = data.message;
                }

                banner.classList.remove('hidden');
                document.getElementById('id_input').value = '';
                document.getElementById('id_input').focus();

                setTimeout(() => {
                    banner.classList.add('hidden');
                }, 4000);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</x-app-layout>
