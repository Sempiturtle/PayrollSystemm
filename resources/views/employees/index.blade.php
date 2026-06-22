<x-app-layout>
    <x-slot name="header">
        Employee Management
    </x-slot>

    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
        {{-- Header --}}
        <div class="p-4 border-b border-slate-50 dark:border-slate-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 italic tracking-tight">Active Faculty & Staff</h3>
                <p class="text-xs text-slate-500 mt-0.5">Manage employee records, rates, and access levels.</p>
            </div>
            <a href="{{ route('employees.create') }}" class="w-full sm:w-auto px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 dark:shadow-none active:scale-95 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Add Member
            </a>
        </div>

        {{-- Desktop Table --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                        <th class="px-6 py-4">Identity</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">RFID Card</th>
                        <th class="px-6 py-4">Compensation</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($employees as $employee)
                    @php $empType = $employee->employment_type ?? 'professor'; @endphp
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-indigo-600 font-bold text-base group-hover:bg-indigo-600 group-hover:text-white transition duration-300 shrink-0">
                                    {{ substr($employee->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-900 dark:text-slate-100 tracking-tight">{{ $employee->name }}</div>
                                    <div class="text-[10px] text-slate-400 font-medium tracking-wide uppercase">{{ $employee->employee_id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($empType === 'professor')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-violet-50 text-violet-600">Professor</span>
                            @elseif($empType === 'staff')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-emerald-50 text-emerald-600">Staff</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-amber-50 text-amber-600">Part-Time</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <code class="px-2 py-1 bg-slate-100 dark:bg-slate-800 rounded-lg text-xs font-bold text-slate-500 tracking-tighter">
                                {{ $employee->rfid_card_num ?? 'NOT_LINKED' }}
                            </code>
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-slate-600 dark:text-slate-400">
                            @if($empType === 'professor')
                                ₱{{ number_format($employee->hourly_rate, 2) }}<span class="text-[10px] text-slate-400 font-medium"> /hr</span>
                            @else
                                ₱{{ number_format($employee->monthly_salary, 2) }}<span class="text-[10px] text-slate-400 font-medium"> /mo</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition">
                                <a href="{{ route('employees.edit', $employee) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-xl transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('employees.destroy', $employee) }}" method="POST" onsubmit="return confirm('Archive this record?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile Card List --}}
        <div class="md:hidden divide-y divide-slate-100 dark:divide-slate-800">
            @foreach($employees as $employee)
            @php $empType = $employee->employment_type ?? 'professor'; @endphp
            <div class="p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold text-base shrink-0">
                    {{ substr($employee->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-bold text-slate-900 truncate">{{ $employee->name }}</div>
                    <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                        <span class="text-[10px] text-slate-400 font-medium uppercase">{{ $employee->employee_id }}</span>
                        @if($empType === 'professor')
                            <span class="text-[9px] px-1.5 py-0.5 rounded-full font-bold bg-violet-50 text-violet-600">Professor</span>
                        @elseif($empType === 'staff')
                            <span class="text-[9px] px-1.5 py-0.5 rounded-full font-bold bg-emerald-50 text-emerald-600">Staff</span>
                        @else
                            <span class="text-[9px] px-1.5 py-0.5 rounded-full font-bold bg-amber-50 text-amber-600">Part-Time</span>
                        @endif
                        <span class="text-[10px] font-bold text-slate-600">
                            @if($empType === 'professor')
                                ₱{{ number_format($employee->hourly_rate, 2) }}/hr
                            @else
                                ₱{{ number_format($employee->monthly_salary, 2) }}/mo
                            @endif
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-1.5 shrink-0">
                    <a href="{{ route('employees.edit', $employee) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </a>
                    <form action="{{ route('employees.destroy', $employee) }}" method="POST" onsubmit="return confirm('Archive this record?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
