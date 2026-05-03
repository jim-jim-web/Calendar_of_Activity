<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight">
            {{ __('Edit Activity') }}
        </h2>
    </x-slot>

    <div class="pb-12 pt-4 bg-slate-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 md:p-8">
                
                <form action="{{ route('activities.update', $activity) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- TITLE -->
                    <div class="mb-5">
                        <label class="block text-slate-800 text-[11px] uppercase tracking-[0.1em] font-bold mb-2">Title</label>
                        <input type="text" name="title" value="{{ $activity->title }}" class="w-full bg-white border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 rounded-xl py-2.5 px-4 text-slate-800 text-sm placeholder-slate-400 transition" required>
                    </div>

                    <!-- CATEGORY & DATE -->
                    <div class="flex flex-col md:flex-row gap-5 mb-5">
                        <div class="w-full md:w-1/2">
                            <label class="block text-slate-800 text-[11px] uppercase tracking-[0.1em] font-bold mb-2">Category</label>
                            <div class="relative">
                                <select name="category_id" class="w-full bg-white border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 rounded-xl py-2.5 pl-4 pr-10 appearance-none text-slate-800 text-sm transition" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $activity->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <div class="w-full md:w-1/2">
                            <label class="block text-slate-800 text-[11px] uppercase tracking-[0.1em] font-bold mb-2">Date & Time</label>
                            <input type="datetime-local" name="activity_date" value="{{ \Carbon\Carbon::parse($activity->activity_date)->format('Y-m-d\TH:i') }}" class="w-full bg-white border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 rounded-xl py-2.5 px-4 text-slate-800 text-sm transition" required>
                        </div>
                    </div>

                    <!-- DESCRIPTION -->
                    <div class="mb-6">
                        <label class="block text-slate-800 text-[11px] uppercase tracking-[0.1em] font-bold mb-2">Description</label>
                        <textarea name="description" rows="3" class="w-full bg-white border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 rounded-xl py-2.5 px-4 text-slate-800 text-sm placeholder-slate-400 transition" required>{{ $activity->description }}</textarea>
                    </div>

                    <!-- ACTIONS -->
                    <div class="flex flex-wrap items-center gap-4 pt-5 border-t border-slate-100">
                        <button type="submit" class="inline-flex items-center justify-center px-8 py-2.5 bg-[#004cd4] rounded-lg text-sm font-bold text-white hover:bg-blue-800 transition shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            Update Activity
                        </button>
                        <a href="{{ route('activities.index') }}" class="inline-flex items-center justify-center px-8 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-lg text-sm font-bold hover:bg-slate-50 transition shadow-sm">
                            Cancel
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>