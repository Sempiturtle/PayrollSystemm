<x-app-layout>
    <x-slot name="header">
        Import Academic Schedules
    </x-slot>

    <div class="max-w-2xl mx-auto mt-12">
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-xl overflow-hidden">
            <div class="p-10 border-b border-slate-50 dark:border-slate-800 text-center relative overflow-hidden">
                <!-- Decoration -->
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-indigo-50 rounded-full dark:bg-slate-800"></div>
                
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center text-white mx-auto mb-6 shadow-lg shadow-indigo-100">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 dark:text-slate-100 italic tracking-tight">Excel Spreadsheet Upload</h3>
                    <p class="text-sm text-slate-500 mt-2">Upload your department schedule in .xlsx or .csv format.</p>
                </div>
            </div>

            <form action="{{ route('schedules.import') }}" method="POST" enctype="multipart/form-data" class="p-10 space-y-8">
                @csrf
                
                <div class="relative group">
                    <label class="block">
                        <span class="sr-only">Choose file</span>
                        <div class="mt-1 flex justify-center px-6 pt-10 pb-12 border-2 border-slate-100 dark:border-slate-800 border-dashed rounded-[2rem] hover:border-indigo-500 transition-colors group-hover:bg-slate-50/50 dark:group-hover:bg-slate-800/30">
                            <div class="space-y-2 text-center">
                                <svg class="mx-auto h-12 w-12 text-slate-300 group-hover:text-indigo-500 transition" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-slate-600 dark:text-slate-400">
                                    <span class="relative cursor-pointer font-bold text-indigo-600 hover:text-indigo-500">Pick a file</span>
                                    <p class="pl-1 text-slate-400">or drag and drop here</p>
                                </div>
                                <p class="text-xs text-slate-400 font-medium">XLSX, XLS up to 10MB</p>
                            </div>
                        </div>
                        <input type="file" name="file" class="hidden" required onchange="this.previousElementSibling.querySelector('.text-indigo-600').innerText = this.files[0].name">
                    </label>
                </div>

                <div class="bg-indigo-50 dark:bg-indigo-900/20 p-6 rounded-2xl border border-indigo-100 dark:border-indigo-900/30">
                    <h4 class="text-xs font-bold text-indigo-600 uppercase tracking-widest mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                        Format Requirement
                    </h4>
                    <p class="text-[11px] text-indigo-700/70 dark:text-indigo-400/70 leading-relaxed font-medium">Excel columns must include: <span class="text-indigo-900 dark:text-indigo-200 font-bold">employee_id, day_of_week, start_time, end_time</span>. Times must be in 24-hour format (e.g. 08:30:00).</p>
                </div>

                <div class="flex items-center justify-between gap-4 pt-4">
                    <a href="{{ route('schedules.index') }}" class="text-sm font-bold text-slate-400 hover:text-rose-600 transition">Return to List</a>
                    <button type="submit" class="px-10 py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-xl shadow-indigo-100 dark:shadow-none hover:bg-indigo-700 hover:-translate-y-1 transition duration-300">
                        Initiate Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
