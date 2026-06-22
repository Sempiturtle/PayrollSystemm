<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="text-slate-400 font-medium">Organization</span>
            <span class="text-slate-300">/</span>
            <span class="font-bold text-slate-800">Personnel Directory</span>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Personnel Directory</h1>
                <p class="text-sm text-slate-500 mt-1">Manage employee records, rates, and authentication card tokens.</p>
            </div>
            <a href="{{ route('employees.create') }}" class="btn-action-indigo text-xs py-2.5 px-4 shadow-sm w-full sm:w-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Add Member
            </a>
        </div>

        <!-- Directory Card Grid -->
        <div class="card-reference overflow-hidden">
            {{-- Desktop Table --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            <th class="px-6 py-4">Identity</th>
                            <th class="px-6 py-4">Type</th>
                            <th class="px-6 py-4">RFID Card</th>
                            <th class="px-6 py-4">Compensation</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($employees as $employee)
                        @php $empType = $employee->employment_type ?? 'professor'; @endphp
                        <tr class="hover:bg-indigo-50/10 transition group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-indigo-600 font-black text-base shadow-sm group-hover:bg-gradient-to-tr group-hover:from-indigo-600 group-hover:to-violet-500 group-hover:text-white group-hover:border-transparent transition-all duration-300 shrink-0">
                                        {{ substr($employee->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-extrabold text-slate-900 tracking-tight">{{ $employee->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold tracking-wider uppercase mt-0.5">{{ $employee->employee_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($empType === 'professor')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-violet-50 text-violet-600 border border-violet-100">Professor</span>
                                @elseif($empType === 'staff')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">Staff</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-100">Part-Time</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <code class="px-2 py-1 bg-slate-50 border border-slate-100 rounded-lg text-xs font-bold text-slate-500 tracking-tight">
                                    {{ $employee->rfid_card_num ?? 'NOT_LINKED' }}
                                </code>
                            </td>
                            <td class="px-6 py-4 text-sm font-extrabold text-slate-700">
                                @if($empType === 'professor')
                                    ₱{{ number_format($employee->hourly_rate, 2) }}<span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider"> /hr</span>
                                @else
                                    ₱{{ number_format($employee->monthly_salary, 2) }}<span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider"> /mo</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition duration-200">
                                    <a href="{{ route('employees.edit', $employee) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 border border-transparent hover:border-indigo-100 rounded-xl transition-all" title="Edit Member">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('employees.destroy', $employee) }}" method="POST" onsubmit="return confirm('Archive this record?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-100 rounded-xl transition-all" title="Archive Member">
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
            <div class="md:hidden divide-y divide-slate-100">
                @foreach($employees as $employee)
                @php $empType = $employee->employment_type ?? 'professor'; @endphp
                <div class="p-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-55 flex items-center justify-center text-indigo-600 border border-slate-100 font-black text-base shrink-0">
                        {{ substr($employee->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-extrabold text-slate-900 truncate">{{ $employee->name }}</div>
                        <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                          <span class="text-[10px] text-slate-400 font-bold uppercase">{{ $employee->employee_id }}</span>
                          @if($empType === 'professor')
                              <span class="text-[9px] px-2 py-0.5 rounded-full font-bold bg-violet-50 text-violet-600">Professor</span>
                          @elseif($empType === 'staff')
                              <span class="text-[9px] px-2 py-0.5 rounded-full font-bold bg-emerald-50 text-emerald-600">Staff</span>
                          @else
                              <span class="text-[9px] px-2 py-0.5 rounded-full font-bold bg-amber-50 text-amber-600">Part-Time</span>
                          @endif
                          <span class="text-[10px] font-extrabold text-slate-700">
                              @if($empType === 'professor')
                                  ₱{{ number_format($employee->hourly_rate, 2) }}/hr
                              @else
                                  ₱{{ number_format($employee->monthly_salary, 2) }}/mo
                              @endif
                          </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 shrink-0">
                        <a href="{{ route('employees.edit', $employee) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 border border-slate-100/50 rounded-xl transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        <form action="{{ route('employees.destroy', $employee) }}" method="POST" onsubmit="return confirm('Archive this record?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 border border-slate-100/50 rounded-xl transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            @if($employees->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $employees->links() }}
                </div>
            @endif

            <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 text-[10px] font-bold text-slate-450 uppercase tracking-widest flex justify-between items-center">
                <span>Database directory records</span>
                <span class="tabular-nums">{{ $employees->total() }} Total Personnel</span>
            </div>
        </div>
    </div>
</x-app-layout>
