<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="text-slate-400 font-medium">Organization</span>
            <span class="text-slate-300">/</span>
            <span class="font-bold text-slate-800">Academic Calendar</span>
        </div>
    </x-slot>

    <div class="space-y-6" x-data="{ 
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
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Academic Calendar</h1>
                <p class="text-sm text-slate-500 mt-1">Declare holidays, class suspensions, and non-working days for payroll adjustments.</p>
            </div>
            
            <div class="flex bg-white p-1 rounded-xl shadow-sm border border-slate-200 w-fit shrink-0">
                <button @click="currentView = 'calendar'" :class="currentView === 'calendar' ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-slate-550 hover:text-slate-800'" class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Calendar
                </button>
                <button @click="currentView = 'list'" :class="currentView === 'list' ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-slate-550 hover:text-slate-800'" class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    List View
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-start">
            <!-- Form Column -->
            <div class="lg:col-span-1">
                <div class="card-reference p-5 bg-white/95 backdrop-blur-xl border border-slate-200/60 shadow-lg sticky top-2">
                    <h3 class="text-sm font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        Declare Event
                    </h3>
                    
                    <form action="{{ route('holidays.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Event Name</label>
                            <input type="text" name="name" required placeholder="e.g. Independence Day" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-semibold">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Date</label>
                            <input type="date" name="date" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-semibold">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Type</label>
                                <select name="type" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-2 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-bold">
                                    <option value="Regular Holiday">Regular</option>
                                    <option value="Special Non-Working">Special</option>
                                    <option value="Suspension">Suspension</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Pay Option</label>
                                <select name="pay_option" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-2 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-bold">
                                    <option value="unpaid">Unpaid</option>
                                    <option value="paid" selected>Paid</option>
                                    <option value="double">Double</option>
                                </select>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full btn-action-indigo text-xs py-2.5">
                                Save Declaration
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- List & Calendar Column -->
            <div class="lg:col-span-3">
                <!-- Calendar View -->
                <div x-show="currentView === 'calendar'" class="card-reference p-4 bg-white/95 backdrop-blur-xl border border-slate-200/60 shadow-lg" x-transition>
                    <div id="calendar"></div>
                </div>

                <!-- List View -->
                <div x-show="currentView === 'list'" style="display: none;" class="card-reference overflow-hidden bg-white/95 backdrop-blur-xl border border-slate-200/60 shadow-lg" x-transition>
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                            <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Declared Suspenions & Holidays</h3>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                    <th class="px-6 py-4">Date</th>
                                    <th class="px-6 py-4">Event</th>
                                    <th class="px-6 py-4">Type</th>
                                    <th class="px-6 py-4 text-center">Benefit</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($holidays as $holiday)
                                    <tr class="hover:bg-indigo-50/10 transition group">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-extrabold text-slate-700">{{ $holiday->date->format('M d, Y') }}</div>
                                            <div class="text-[10px] text-slate-450 font-bold uppercase mt-0.5 tracking-wider">{{ $holiday->date->format('l') }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-extrabold text-slate-905">{{ $holiday->name }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($holiday->type === 'Regular Holiday')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-indigo-50 text-indigo-650 border border-indigo-100 uppercase tracking-wider">Regular Holiday</span>
                                            @elseif($holiday->type === 'Special Non-Working')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-amber-50 text-amber-650 border border-amber-100 uppercase tracking-wider">Special Day</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-rose-50 text-rose-650 border border-rose-100 uppercase tracking-wider">Suspension</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($holiday->is_paid)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[9px] font-bold {{ $holiday->is_double_pay ? 'bg-amber-50 text-amber-600 border border-amber-100' : 'bg-emerald-50 text-emerald-600 border border-emerald-100' }} uppercase tracking-wider">
                                                    {{ $holiday->is_double_pay ? '2x Double' : '1x Paid' }}
                                                </span>
                                            @else
                                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Unpaid</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end items-center gap-2 opacity-0 group-hover:opacity-100 transition duration-200">
                                                <button @click="openEdit({{ $holiday->toJson() }})" class="p-2 text-indigo-600 hover:bg-indigo-50 border border-transparent hover:border-indigo-100 rounded-xl transition-all" title="Edit Holiday">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                                </button>
                                                
                                                <form action="{{ route('holidays.destroy', $holiday) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-100 rounded-xl transition-all" title="Delete Holiday">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic text-sm">No holidays declared yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($holidays->hasPages())
                        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
                            {{ $holidays->links() }}
                        </div>
                    @endif

                    <div class="px-6 py-3 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Academic Calendar Registry</div>
                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest tabular-nums">{{ $holidays->total() }} Total Declarations</div>
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

                <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100 p-6">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-sm font-bold text-slate-900">Edit Holiday Record</h3>
                        <button @click="editModal = false" class="text-slate-400 hover:text-slate-650 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form :action="'{{ url('holidays') }}/' + editHoliday.id" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')
                        
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Event Name</label>
                            <input type="text" name="name" x-model="editHoliday.name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-semibold">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Date</label>
                                <input type="date" name="date" x-model="editHoliday.date" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-semibold">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Type</label>
                                <select name="type" x-model="editHoliday.type" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-2 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-bold">
                                    <option value="Regular Holiday">Regular Holiday</option>
                                    <option value="Special Non-Working">Special Non-Working</option>
                                    <option value="Suspension">Suspension</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Pay Option</label>
                            <select name="pay_option" x-model="editHoliday.pay_option" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-2 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs font-bold">
                                <option value="unpaid">Unpaid</option>
                                <option value="paid">Standard Paid (100% Pay)</option>
                                <option value="double">Double Pay (200% if worked)</option>
                            </select>
                        </div>

                        <div class="pt-3 flex gap-3">
                            <button type="button" @click="editModal = false" class="flex-1 btn-action-secondary py-3 text-xs">
                                Cancel
                            </button>
                            <button type="submit" class="flex-1 btn-action-indigo py-3 text-xs">
                                Update Details
                            </button>
                        </div>
                    </form>
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
                    @foreach($allHolidays as $holiday)
                    {
                        title: '{!! addslashes($holiday->name) !!}',
                        start: '{{ $holiday->date->format("Y-m-d") }}',
                        color: '{{ $holiday->type === "Regular Holiday" ? "#5334F6" : ($holiday->type === "Special Non-Working" ? "#d97706" : "#f43f5e") }}',
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

            window.addEventListener('alpine:initialized', function () {
                let alpineState = Alpine.$data(document.querySelector('.space-y-6'));
                Alpine.effect(() => {
                    if (alpineState.currentView === 'calendar') {
                        setTimeout(() => calendar.render(), 100);
                    }
                });
            });
            
            setInterval(() => {
                let container = document.querySelector('.space-y-6');
                if (container) {
                    let data = container.__x ? container.__x.$data : null;
                    if(data && data.currentView === 'calendar') {
                         window.dispatchEvent(new Event('resize'));
                    }
                }
            }, 500);
        });
    </script>
    <style>
        .fc-event {
            border: none;
            padding: 4px 6px;
            border-radius: 8px;
            font-size: 0.65rem;
            font-weight: 700;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
        }
        .fc .fc-toolbar-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: #1e293b;
            letter-spacing: -0.02em;
        }
        /* Style FullCalendar buttons to match modern theme */
        .fc .fc-button-primary {
            background-color: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            color: #475569 !important;
            padding: 0.45rem 0.8rem !important;
            font-size: 0.7rem !important;
            font-weight: 700 !important;
            border-radius: 0.75rem !important;
            transition: all 0.15s ease !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
            text-transform: capitalize;
        }
        .fc .fc-button-primary:hover {
            background-color: #f8fafc !important;
            border-color: #cbd5e1 !important;
            color: #0f172a !important;
        }
        .fc .fc-button-primary:focus {
            box-shadow: 0 0 0 3px rgba(83, 52, 246, 0.15) !important;
        }
        .fc .fc-button-primary:not(:disabled).fc-button-active, 
        .fc .fc-button-primary:not(:disabled):active {
            background-color: #f5f3ff !important;
            border-color: #c7d2fe !important;
            color: #5334F6 !important;
            box-shadow: none !important;
        }
        .fc .fc-button-group {
            gap: 1px;
        }
        .fc .fc-button-group > .fc-button {
            border-radius: 0.75rem !important;
        }
        /* Custom scrollgrid and headers */
        .fc-theme-standard .fc-scrollgrid {
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            overflow: hidden;
        }
        .fc-theme-standard td, .fc-theme-standard th {
            border: 1px solid #f1f5f9 !important;
        }
        .fc .fc-col-header-cell-cushion {
            font-size: 0.65rem;
            font-weight: 700;
            color: #64748b;
            letter-spacing: 0.05em;
            padding: 10px 4px !important;
        }
        .fc-daygrid-day {
            transition: background-color 0.15s ease;
        }
        .fc-daygrid-day:hover {
            background-color: #f8fafc;
        }
        .fc .fc-day-today {
            background-color: rgba(83, 52, 246, 0.04) !important;
        }
        .fc .fc-day-today .fc-daygrid-day-number {
            color: #5334F6;
            font-weight: 800;
        }
        .fc .fc-daygrid-body-unrestricted .fc-daygrid-day-frame {
            min-height: 55px !important;
        }
    </style>
    @endpush
</x-app-layout>
