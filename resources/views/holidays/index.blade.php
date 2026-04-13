<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Holiday & Suspension Management
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Form Column -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-8">
                        <h3 class="text-lg font-bold text-gray-800 mb-6">Declare No-Work Day</h3>
                        
                        <form action="{{ route('holidays.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Event Name</label>
                                <input type="text" name="name" required placeholder="e.g. Christmas Day" class="w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Date</label>
                                <input type="date" name="date" required class="w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Type</label>
                                <select name="type" required class="w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                    <option value="Regular Holiday">Regular Holiday</option>
                                    <option value="Special Non-Working">Special Non-Working</option>
                                    <option value="Suspension">Suspension</option>
                                </select>
                            </div>

                            <div class="space-y-3 pt-2">
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-100">
                                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wide">Paid Holiday?</span>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="hidden" name="is_paid" value="0">
                                        <input type="checkbox" name="is_paid" value="1" checked class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-100">
                                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wide">Double Pay?</span>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="hidden" name="is_double_pay" value="0">
                                        <input type="checkbox" name="is_double_pay" value="1" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-600"></div>
                                    </label>
                                </div>
                            </div>

                            <div class="pt-4">
                                <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-sm transition active:scale-[0.98]">
                                    Save Declaration
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- List Column -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-gray-50">
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
                                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                                        @if($holiday->is_double_pay)
                                                            <span class="text-[9px] font-bold text-amber-600 bg-amber-50 px-1 py-0.5 rounded border border-amber-200">2x Pay</span>
                                                        @endif
                                                    @else
                                                        <span class="text-[9px] font-bold text-gray-300 uppercase">Unpaid</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <form action="{{ route('holidays.destroy', $holiday) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-gray-300 hover:text-red-500 transition">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
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
    </div>
</x-app-layout>
