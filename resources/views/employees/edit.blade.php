<x-app-layout>
    <x-slot name="header">
        Modify Member Details
    </x-slot>

    <div class="max-w-3xl mx-auto mt-8">
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-8 border-b border-slate-50 dark:border-slate-800 text-center">
                <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 italic tracking-tight">Profile Adjustment</h3>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-2">Editing: {{ $employee->name }}</p>
            </div>

            <form action="{{ route('employees.update', $employee) }}" method="POST" class="p-8 space-y-8">
                @csrf
                @method('PATCH')
                
                <div class="space-y-6">
                    <div class="px-4 py-2 bg-slate-50 dark:bg-slate-800/50 rounded-xl text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Basic Information</div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Full Name</label>
                            <input type="text" name="name" value="{{ $employee->name }}" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Email Address</label>
                            <input type="email" name="email" value="{{ $employee->email }}" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-medium">
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="px-4 py-2 bg-slate-50 dark:bg-slate-800/50 rounded-xl text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">System Credentials</div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Employee ID</label>
                            <input type="text" name="employee_id" value="{{ $employee->employee_id }}" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-bold tracking-widest text-indigo-600">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">RFID Tag Num</label>
                            <input type="text" name="rfid_card_num" value="{{ $employee->rfid_card_num }}" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Biometric Hash/Template</label>
                            <input type="text" name="biometric_template" value="{{ $employee->biometric_template }}" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-medium">
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="px-4 py-2 bg-slate-50 dark:bg-slate-800/50 rounded-xl text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Role & Compensation</div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Hourly Rate (₱)</label>
                            <input type="number" step="0.01" name="hourly_rate" value="{{ $employee->hourly_rate }}" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-bold">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Personnel Role</label>
                            <select name="role" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-4 focus:ring-2 focus:ring-indigo-600/20 text-sm font-bold">
                                <option value="professor" {{ $employee->role == 'professor' ? 'selected' : '' }}>Professor</option>
                                <option value="employee" {{ $employee->role == 'employee' ? 'selected' : '' }}>Staff / Regular</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex justify-between items-center">
                    <a href="{{ route('employees.index') }}" class="text-sm font-bold text-slate-400 hover:text-rose-600 transition">Discard Changes</a>
                    <button type="submit" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-xl hover:bg-indigo-700 hover:-translate-y-1 transition duration-300">
                        Update Record
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
