<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <span class="text-[#101D33]/40 font-bold uppercase tracking-[0.2em] text-[10px]">Academic</span>
            <span class="text-[#101D33]/20">/</span>
            <span class="font-['DM_Serif_Text'] text-[#101D33] italic">Calendar Master</span>
        </div>
    </x-slot>

    <div class="py-12 animate-in-up" x-data="{ 
        editModal: false, 
        currentView: 'calendar',
        editHoliday: { id: '', name: '', date: '', type: '', pay_option: '', description: '' },
        openEdit(holiday) {
            this.editHoliday = { ...holiday };
            if (this.editHoliday.date) {
                this.editHoliday.date = new Date(this.editHoliday.date).toISOString().split('T')[0];
            }
            this.editModal = true;
        }
    }" @open-edit-holiday.window="openEdit($event.detail)">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Form Column -->
                <div class="lg:col-span-1">
                    <div class="bg-[#101D33] rounded-[3rem] border border-white/10 shadow-[0_30px_100px_rgba(16,29,51,0.2)] p-8 sticky top-8 overflow-hidden group">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-[#660000]/20 blur-3xl -mr-16 -mt-16 transition-transform group-hover:scale-110"></div>
                        <h3 class="text-xl font-['DM_Serif_Display'] text-white mb-8 relative z-10">Declare <span class="text-[#D4AF37]">Observance</span></h3>
                        
                        <form action="{{ route('holidays.store') }}" method="POST" class="space-y-6 relative z-10">
                            @csrf
                            <div>
                                <label class="block text-[9px] font-black text-white/30 uppercase tracking-[0.3em] mb-3">Event Nomenclature</label>
                                <input type="text" name="name" required placeholder="e.g. Founder's Day" class="w-full bg-white/5 border-white/10 rounded-2xl focus:ring-2 focus:ring-[#D4AF37] focus:border-transparent text-sm text-white placeholder:text-white/10 py-4 font-['DM_Serif_Text']">
                            </div>

                            <div>
                                <label class="block text-[9px] font-black text-white/30 uppercase tracking-[0.3em] mb-3">Cycle Date</label>
                                <input type="date" name="date" required class="w-full bg-white/5 border-white/10 rounded-2xl focus:ring-2 focus:ring-[#D4AF37] focus:border-transparent text-sm text-white py-4">
                            </div>

                            <div>
                                <label class="block text-[9px] font-black text-white/30 uppercase tracking-[0.3em] mb-3">Classification</label>
                                <select name="type" required class="w-full bg-white/5 border-white/10 rounded-2xl focus:ring-2 focus:ring-[#D4AF37] focus:border-transparent text-sm text-white py-4 appearance-none">
                                    <option value="Regular Holiday">Regular Holiday</option>
                                    <option value="Special Non-Working">Special Non-Working</option>
                                    <option value="Suspension">Institutional Suspension</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[9px] font-black text-white/30 uppercase tracking-[0.3em] mb-3">Remuneration Protocol</label>
                                <select name="pay_option" required class="w-full bg-white/5 border-white/10 rounded-2xl focus:ring-2 focus:ring-[#D4AF37] focus:border-transparent text-sm text-white py-4 appearance-none">
                                    <option value="unpaid">Unpaid Cycle</option>
                                    <option value="paid" selected>Standard Remuneration (100%)</option>
                                    <option value="double">Double Remuneration (200%)</option>
                                </select>
                            </div>

                            <div class="pt-4">
                                <button type="submit" class="w-full py-4 bg-[#D4AF37] hover:bg-yellow-600 text-[#101D33] rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] shadow-xl shadow-[#D4AF37]/10 transition-all hover:scale-[1.02] active:scale-[0.98]">
                                    Save Declaration
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- List & Calendar Column -->
                <div class="lg:col-span-3 space-y-6">
                    <div class="flex justify-end bg-white/50 backdrop-blur-md p-1.5 rounded-[2rem] border border-[#101D33]/5 w-fit ml-auto shadow-sm">
                        <button @click="currentView = 'calendar'" :class="currentView === 'calendar' ? 'bg-[#101D33] text-white shadow-xl shadow-[#101D33]/20' : 'text-[#101D33]/40 hover:text-[#101D33]'" class="px-8 py-3 rounded-[1.5rem] text-[10px] font-black uppercase tracking-[0.2em] transition-all flex items-center gap-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Matrix View
                        </button>
                        <button @click="currentView = 'list'" :class="currentView === 'list' ? 'bg-[#101D33] text-white shadow-xl shadow-[#101D33]/20' : 'text-[#101D33]/40 hover:text-[#101D33]'" class="px-8 py-3 rounded-[1.5rem] text-[10px] font-black uppercase tracking-[0.2em] transition-all flex items-center gap-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                            Registry View
                        </button>
                    </div>

                    <!-- Calendar View -->
                    <div x-show="currentView === 'calendar'" class="bg-white rounded-[3rem] border border-[#101D33]/5 shadow-[0_30px_100px_rgba(16,29,51,0.06)] p-8" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <div id="calendar" class="institutional-calendar"></div>
                    </div>

                    <!-- List View -->
                    <div x-show="currentView === 'list'" style="display: none;" class="bg-white rounded-[3rem] border border-[#101D33]/5 shadow-[0_30px_100px_rgba(16,29,51,0.06)] overflow-hidden" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="p-8 border-b border-[#101D33]/5 bg-[#FDFCF8]/30">
                            <h3 class="text-2xl font-['DM_Serif_Display'] text-[#101D33]">Declared <span class="text-[#660000]">Observances</span></h3>
                            <p class="text-[11px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-2">Historical and future institutional no-work cycle registry</p>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-[#101D33] text-[10px] font-bold text-white/40 uppercase tracking-[0.3em]">
                                        <th class="px-10 py-6 border-r border-white/5">Temporal Cycle</th>
                                        <th class="px-10 py-6 border-r border-white/5">Event Nomenclature</th>
                                        <th class="px-10 py-6 border-r border-white/5">Classification</th>
                                        <th class="px-10 py-6 border-r border-white/5 text-center">Fiscal Benefit</th>
                                        <th class="px-10 py-6 text-right">Registry Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#101D33]/5">
                                    @forelse($holidays as $holiday)
                                        <tr class="hover:bg-[#FDFCF8] transition-all group">
                                            <td class="px-10 py-8">
                                                <div class="text-base font-['DM_Serif_Display'] text-[#101D33] leading-none mb-2">{{ $holiday->date->format('M d, Y') }}</div>
                                                <div class="text-[10px] text-slate-300 font-bold uppercase tracking-[0.2em] italic">{{ $holiday->date->format('l') }}</div>
                                            </td>
                                            <td class="px-10 py-8">
                                                <div class="text-sm font-['DM_Serif_Text'] text-[#101D33] tracking-tight leading-none">{{ $holiday->name }}</div>
                                            </td>
                                            <td class="px-10 py-8">
                                                <span class="inline-flex px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-[0.2em] border shadow-sm
                                                    @if($holiday->type === 'Regular Holiday') bg-[#101D33]/5 text-[#101D33] border-[#101D33]/10
                                                    @elseif($holiday->type === 'Special Non-Working') bg-[#D4AF37]/10 text-[#D4AF37] border-[#D4AF37]/20
                                                    @else bg-[#660000]/5 text-[#660000] border-[#660000]/10 @endif">
                                                    {{ $holiday->type }}
                                                </span>
                                            </td>
                                            <td class="px-10 py-8 text-center">
                                                <div class="flex flex-col items-center gap-1">
                                                    @if($holiday->is_paid)
                                                        <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest {{ $holiday->is_double_pay ? 'bg-[#D4AF37] text-[#101D33] shadow-lg shadow-[#D4AF37]/20' : 'bg-emerald-50 text-emerald-600 border border-emerald-100' }}">
                                                            {{ $holiday->is_double_pay ? '2x Double Remuneration' : '1x Standard Pay' }}
                                                        </span>
                                                    @else
                                                        <span class="text-[9px] font-black text-slate-200 uppercase tracking-[0.3em] italic">Unpaid Cycle</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-10 py-8 text-right">
                                                <div class="flex justify-end items-center gap-4 opacity-0 group-hover:opacity-100 transition-all transform translate-x-4 group-hover:translate-x-0">
                                                    <button @click="openEdit({{ $holiday->toJson() }})" class="w-10 h-10 flex items-center justify-center text-[#101D33]/40 hover:text-[#101D33] hover:bg-white rounded-xl transition-all shadow-none hover:shadow-lg border border-transparent hover:border-[#101D33]/5">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                                    </button>
                                                    
                                                    <form action="{{ route('holidays.destroy', $holiday) }}" method="POST" onsubmit="return confirm('Expunge this institutional observance?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="w-10 h-10 flex items-center justify-center text-[#660000]/40 hover:text-[#660000] hover:bg-white rounded-xl transition-all shadow-none hover:shadow-lg border border-transparent hover:border-[#660000]/5">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-10 py-32 text-center">
                                                <div class="flex flex-col items-center gap-4 opacity-40">
                                                    <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">Institutional calendar stream is currently vacant</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div x-show="editModal" 
             class="fixed inset-0 z-[200] overflow-y-auto" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100">
            
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-[#101D33]/90 backdrop-blur-xl" @click="editModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-[3rem] shadow-[0_50px_100px_rgba(0,0,0,0.5)] sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-white/20">
                    <div class="px-10 pt-12 pb-8">
                        <div class="flex items-center justify-between mb-10">
                            <div>
                                <h3 class="text-3xl font-['DM_Serif_Display'] text-[#101D33] leading-none mb-3">Refine <span class="text-[#660000]">Observance</span></h3>
                                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-[0.2em]">Institutional adjustment protocol</p>
                            </div>
                            <button @click="editModal = false" class="text-slate-300 hover:text-[#660000] transition-colors">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <form :action="'{{ url('holidays') }}/' + editHoliday.id" method="POST" class="space-y-8">
                            @csrf
                            @method('PATCH')
                            
                            <div>
                                <label class="block text-[10px] font-black text-[#101D33]/30 uppercase tracking-[0.3em] mb-4">Event Nomenclature</label>
                                <input type="text" name="name" x-model="editHoliday.name" required class="w-full bg-[#FDFCF8] border border-[#101D33]/5 rounded-2xl focus:ring-2 focus:ring-[#101D33] focus:border-transparent text-sm text-[#101D33] py-4 font-['DM_Serif_Text'] shadow-sm">
                            </div>

                            <div class="grid grid-cols-2 gap-8">
                                <div>
                                    <label class="block text-[10px] font-black text-[#101D33]/30 uppercase tracking-[0.3em] mb-4">Cycle Date</label>
                                    <input type="date" name="date" x-model="editHoliday.date" required class="w-full bg-[#FDFCF8] border border-[#101D33]/5 rounded-2xl focus:ring-2 focus:ring-[#101D33] focus:border-transparent text-sm text-[#101D33] py-4 shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-[#101D33]/30 uppercase tracking-[0.3em] mb-4">Classification</label>
                                    <select name="type" x-model="editHoliday.type" required class="w-full bg-[#FDFCF8] border border-[#101D33]/5 rounded-2xl focus:ring-2 focus:ring-[#101D33] focus:border-transparent text-sm text-[#101D33] py-4 shadow-sm appearance-none">
                                        <option value="Regular Holiday">Regular Holiday</option>
                                        <option value="Special Non-Working">Special Non-Working</option>
                                        <option value="Suspension">Institutional Suspension</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-[#101D33]/30 uppercase tracking-[0.3em] mb-4">Remuneration Protocol</label>
                                <select name="pay_option" x-model="editHoliday.pay_option" required class="w-full bg-[#FDFCF8] border border-[#101D33]/5 rounded-2xl focus:ring-2 focus:ring-[#101D33] focus:border-transparent text-sm text-[#101D33] py-4 shadow-sm appearance-none">
                                    <option value="unpaid">Unpaid Cycle</option>
                                    <option value="paid">Standard Remuneration (100%)</option>
                                    <option value="double">Double Remuneration (200%)</option>
                                </select>
                            </div>

                            <div class="pt-6 flex gap-6">
                                <button type="button" @click="editModal = false" class="flex-1 py-4 text-[10px] font-black text-slate-400 hover:text-[#660000] transition-colors uppercase tracking-[0.2em]">Abort Changes</button>
                                <button type="submit" class="flex-1 py-4 bg-[#101D33] text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] hover:bg-[#660000] transition-all shadow-xl shadow-[#101D33]/10 active:scale-95">Update Record</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 'auto',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,dayGridWeek'
                },
                events: [
                    @foreach($holidays as $holiday)
                    {
                        title: '{!! addslashes($holiday->name) !!}',
                        start: '{{ $holiday->date->format("Y-m-d") }}',
                        color: '{{ $holiday->type === "Regular Holiday" ? "#101D33" : ($holiday->type === "Special Non-Working" ? "#D4AF37" : "#660000") }}',
                        textColor: '{{ $holiday->type === "Special Non-Working" ? "#101D33" : "#fff" }}',
                        extendedProps: {
                            holidayData: @json($holiday)
                        }
                    },
                    @endforeach
                ],
                eventClick: function(info) {
                    var data = info.event.extendedProps.holidayData;
                    window.dispatchEvent(new CustomEvent('open-edit-holiday', { detail: data }));
                },
                eventMouseEnter: function(info) {
                    info.el.style.cursor = 'pointer';
                    info.el.style.opacity = '0.8';
                },
                eventMouseLeave: function(info) {
                    info.el.style.opacity = '1';
                }
            });
            calendar.render();

            window.addEventListener('alpine:initialized', function () {
                let alpineState = Alpine.$data(document.querySelector('.py-12'));
                Alpine.effect(() => {
                    if (alpineState.currentView === 'calendar') {
                        setTimeout(() => calendar.render(), 100);
                    }
                });
            });
            
            setInterval(() => {
                const el = document.querySelector('.py-12');
                if(el && el.__x && el.__x.$data.currentView === 'calendar') {
                     window.dispatchEvent(new Event('resize'));
                }
            }, 500);
        });
    </script>
    <style>
        .fc-event {
            border: none !important;
            padding: 6px 10px !important;
            border-radius: 12px !important;
            font-size: 10px !important;
            font-weight: 800 !important;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .fc .fc-toolbar-title {
            font-family: 'DM Serif Display', serif !important;
            font-size: 1.75rem !important;
            color: #101D33 !important;
        }
        .fc .fc-button-primary {
            background-color: transparent !important;
            border-color: #101D3310 !important;
            color: #101D3340 !important;
            font-size: 10px !important;
            font-weight: 900 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.2em !important;
            padding: 10px 20px !important;
            border-radius: 12px !important;
            transition: all 0.3s !important;
        }
        .fc .fc-button-primary:hover {
            background-color: #101D33 !important;
            color: white !important;
            border-color: #101D33 !important;
        }
        .fc .fc-button-primary:not(:disabled).fc-button-active {
            background-color: #101D33 !important;
            color: white !important;
            border-color: #101D33 !important;
            box-shadow: 0 10px 20px rgba(16,29,51,0.1) !important;
        }
        .fc-theme-standard .fc-scrollgrid {
            border: 1px solid #101D3305 !important;
            border-radius: 2rem !important;
            overflow: hidden !important;
        }
        .fc-col-header-cell {
            background-color: #FDFCF8 !important;
            padding: 15px 0 !important;
            font-size: 10px !important;
            font-weight: 900 !important;
            color: #101D3320 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.3em !important;
        }
        .fc-daygrid-day-number {
            font-family: 'DM Serif Display', serif !important;
            font-size: 1.25rem !important;
            color: #101D3320 !important;
            padding: 15px !important;
        }
        .fc-day-today {
            background-color: #101D3303 !important;
        }
        .fc-day-today .fc-daygrid-day-number {
            color: #101D33 !important;
        }
    </style>
    @endpush
</x-app-layout>


