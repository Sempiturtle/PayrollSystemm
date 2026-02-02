<x-app-layout>
    <x-slot name="header">
        Employee Management
    </x-slot>

    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-50 dark:border-slate-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100 italic tracking-tight">Active Faculty & Staff</h3>
                <p class="text-sm text-slate-500 mt-1">Manage employee records, rates, and access levels.</p>
            </div>
            <a href="{{ route('employees.create') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold flex items-center gap-2 hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 dark:shadow-none hover:-translate-y-1">
                <svg class="w-5 h-5 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Add Member
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                        <th class="px-8 py-4">Identity</th>
                        <th class="px-8 py-4">Role</th>
                        <th class="px-8 py-4">RFID Card</th>
                        <th class="px-8 py-4">Hourly Rate</th>
                        <th class="px-8 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($employees as $employee)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-indigo-600 font-bold text-lg group-hover:bg-indigo-600 group-hover:text-white transition duration-300">
                                    {{ substr($employee->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-900 dark:text-slate-100 tracking-tight">{{ $employee->name }}</div>
                                    <div class="text-xs text-slate-400 font-medium tracking-wide uppercase">{{ $employee->employee_id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <x-status-badge :type="$employee->role">{{ ucfirst($employee->role) }}</x-status-badge>
                        </td>
                        <td class="px-8 py-6">
                            <code class="px-2 py-1 bg-slate-100 dark:bg-slate-800 rounded-lg text-xs font-bold text-slate-500 tracking-tighter">
                                {{ $employee->rfid_card_num ?? 'NOT_LINKED' }}
                            </code>
                        </td>
                        <td class="px-8 py-6 text-sm font-bold text-slate-600 dark:text-slate-400">
                            ₱{{ number_format($employee->hourly_rate, 2) }}
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition">
                                <a href="{{ route('employees.edit', $employee) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-xl transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('employees.destroy', $employee) }}" method="POST" onsubmit="return confirm('Archive this record?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
