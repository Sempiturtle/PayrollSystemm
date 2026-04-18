<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Holiday & Suspension Management
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ 
        editModal: false, 
        currentView: 'calendar',
        editHoliday: { id: '', name: '', date: '', type: '', pay_option: '', description: '' },
        openEdit(holiday) {
            this.editHoliday = { ...holiday };
            // Ensure date is in YYYY-MM-DD for the input
            if (this.editHoliday.date) {
                this.editHoliday.date = new Date(this.editHoliday.date).toISOString().split('T')[0];
            }
            this.editModal = true;
        }
    }" @open-edit-holiday.window="openEdit($event.detail)">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                <!-- Form Column -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 sticky top-4">
                        <h3 class="text-base font-bold text-gray-800 mb-5">Declare No-Work Day</h3>
                        
                        <form action="{{ route('holidays.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Event Name</label>
                                <input type="text" name="name" required placeholder="e.g. Christmas Day" class="w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Date</label>
                                <input type="date" name="date" required class="w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Type</label>
                                <select name="type" required class="w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2">
                                    <option value="Regular Holiday">Regular Holiday</option>
                                    <option value="Special Non-Working">Special Non-Working</option>
                                    <option value="Suspension">Suspension</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Pay Option</label>
                                <select name="pay_option" required class="w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2">
                                    <option value="unpaid">Unpaid (No Pay)</option>
                                    <option value="paid" selected>Standard (100%)</option>
                                    <option value="double">Double Pay</option>
                                </select>
                            </div>

                            <div class="pt-3">
                                <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-sm transition active:scale-[0.98] text-sm">
                                    Save Declaration
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- List & Calendar Column -->
                <div class="lg:col-span-3">
                    <div class="flex justify-end mb-4 bg-white p-1 rounded-xl shadow-sm border border-gray-100 w-fit ml-auto">
                        <button @click="currentView = 'calendar'" :class="currentView === 'calendar' ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-2 rounded-lg text-sm font-bold transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Calendar
                        </button>
                        <button @click="currentView = 'list'" :class="currentView === 'list' ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-2 rounded-lg text-sm font-bold transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                            List View
                        </button>
                    </div>

                    <!-- Calendar View -->
                    <div x-show="currentView === 'calendar'" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4" x-transition>
                        <div id="calendar"></div>
                    </div>

                    <!-- List View -->
                    <div x-show="currentView === 'list'" style="display: none;" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" x-transition>
                        <div class="p-4 border-b border-gray-50">
                            <h3 class="text-lg font-bold text-gray-800">Declared Holidays & Suspensions</h3>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-50">
                                    <tr class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        <th class="px-6 py-4">Date</th>
                                        <th class="px-6 py-4">Event</th>
                                        <th class="px-6 py-4">Type</th>
                                        <th class="px-6 py-4 text-center">Benefit</th>
                                        <th class="px-6 py-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($holidays as $holiday)
                                        <tr class="hover:bg-gray-50/50 transition">
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-bold text-gray-700">{{ $holiday->date->format('M d, Y') }}</div>
                                                <div class="text-[10px] text-gray-400 font-medium italic">{{ $holiday->date->format('l') }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-bold text-gray-800">{{ $holiday->name }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-1 text-[10px] font-bold rounded-full border
                                                    @if($holiday->type === 'Regular Holiday') bg-indigo-50 text-indigo-600 border-indigo-100
                                                    @elseif($holiday->type === 'Special Non-Working') bg-amber-50 text-amber-600 border-amber-100
                                                    @else bg-rose-50 text-rose-600 border-rose-100 @endif uppercase tracking-wider">
                                                    {{ $holiday->type }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <div class="flex flex-col items-center gap-1">
                                                    @if($holiday->is_paid)
                                                        <span class="px-2 py-0.5 rounded text-[9px] font-bold {{ $holiday->is_double_pay ? 'bg-amber-100 text-amber-600 border border-amber-200' : 'bg-emerald-100 text-emerald-600 border border-emerald-200' }}">
                                                            {{ $holiday->is_double_pay ? '2x Double Pay' : '1x Paid' }}
                                                        </span>
                                                    @else
                                                        <span class="text-[9px] font-bold text-gray-300 uppercase italic">Unpaid</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex justify-end items-center gap-2">
                                                    <button @click="openEdit({{ $holiday->toJson() }})" class="text-slate-400 hover:text-indigo-600 transition">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                                    </button>
                                                    
                                                    <form action="{{ route('holidays.destroy', $holiday) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-slate-300 hover:text-rose-500 transition">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-20 text-center">
                                                <p class="text-gray-400 text-sm font-medium italic">No holidays declared yet.</p>
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
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-slate-900/40 backdrop-blur-sm" @click="editModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-slate-800">Edit Holiday Record</h3>
                            <button @click="editModal = false" class="text-slate-400 hover:text-slate-600 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <form :action="'{{ url('holidays') }}/' + editHoliday.id" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Event Name</label>
                                <input type="text" name="name" x-model="editHoliday.name" required class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Date</label>
                                    <input type="date" name="date" x-model="editHoliday.date" required class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Type</label>
                                    <select name="type" x-model="editHoliday.type" required class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                        <option value="Regular Holiday">Regular Holiday</option>
                                        <option value="Special Non-Working">Special Non-Working</option>
                                        <option value="Suspension">Suspension</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Pay Option</label>
                                <select name="pay_option" x-model="editHoliday.pay_option" required class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                    <option value="unpaid">Unpaid (No work, no pay)</option>
                                    <option value="paid">Standard Paid (100% Pay)</option>
                                    <option value="double">Double Pay (200% if worked)</option>
                                </select>
                            </div>

                            <div class="pt-6 flex gap-3">
                                <button type="button" @click="editModal = false" class="flex-1 py-3 px-4 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold transition">
                                    Cancel
                                </button>
                                <button type="submit" class="flex-1 py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-sm shadow-indigo-200 transition active:scale-[0.98]">
                                    Update Details
                                </button>
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
                        color: '{{ $holiday->type === "Regular Holiday" ? "#4f46e5" : ($holiday->type === "Special Non-Working" ? "#d97706" : "#e11d48") }}',
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
                }
            });
            calendar.render();

            // Re-render when switching to calendar view
            window.addEventListener('alpine:initialized', function () {
                let alpineState = Alpine.$data(document.querySelector('.py-12'));
                Alpine.effect(() => {
                    if (alpineState.currentView === 'calendar') {
                        setTimeout(() => calendar.render(), 100);
                    }
                });
            });
            
            // To handle when alpine state changes (since alpine:initialized might be too late/early sometimes)
            setInterval(() => {
                if(document.querySelector('.py-12').__x.$data.currentView === 'calendar') {
                     // Calendar resizing handling
                     window.dispatchEvent(new Event('resize'));
                }
            }, 500);
        });
    </script>
    <style>
        .fc-event {
            border: none;
            padding: 2px 4px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .fc .fc-toolbar-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
        }
        .fc .fc-button-primary {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }
        .fc .fc-button-primary:hover {
            background-color: #4338ca;
            border-color: #4338ca;
        }
        .fc .fc-button-primary:not(:disabled).fc-button-active, .fc .fc-button-primary:not(:disabled):active {
            background-color: #3730a3;
            border-color: #3730A3;
        }
        .fc-theme-standard .fc-scrollgrid {
            border: 1px solid #f1f5f9;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .fc-daygrid-day {
            transition: background-color 0.2s;
        }
        .fc-daygrid-day:hover {
            background-color: #f8fafc;
        }
    </style>
    @endpush
</x-app-layout>

