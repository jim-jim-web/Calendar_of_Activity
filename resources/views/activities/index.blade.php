<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 leading-tight tracking-tight">
                    Class Activity Dashboard
                </h2>
                <p class="text-sm text-slate-500 mt-1 font-normal">Manage and track all scheduled activities for your courses.</p>
            </div>
            @if(auth()->user()->role && str_contains(strtolower(auth()->user()->role->name), 'pio'))
                <div>
                    <a href="{{ route('activities.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 rounded-lg text-sm font-bold text-white hover:bg-blue-700 transition shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                        Create New Activity
                    </a>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="pb-10 pt-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <form method="GET" action="{{ route('activities.index') }}" class="mb-8 bg-white p-2.5 rounded-2xl shadow-sm border border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="relative w-full md:w-1/3 flex items-center">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    </div>
                    <select name="category_id" id="category_id" onchange="this.form.submit()" class="bg-transparent border-0 text-slate-700 font-medium text-sm rounded-xl focus:ring-0 block w-full pl-10 p-2.5 appearance-none">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <input type="hidden" name="search" value="{{ request('search') }}">

                <div class="flex gap-2 w-full md:w-auto overflow-x-auto px-2 pb-2 md:pb-0 md:px-0">
                    <a href="{{ route('activities.index', ['filter' => 'today']) }}" class="px-5 py-2 rounded-full text-xs font-bold whitespace-nowrap {{ request('filter') === 'today' ? 'bg-blue-100 text-blue-700' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 transition' }}">Today</a>
                    <a href="{{ route('activities.index', ['filter' => 'upcoming']) }}" class="px-5 py-2 rounded-full text-xs font-bold whitespace-nowrap {{ request('filter') === 'upcoming' ? 'bg-blue-100 text-blue-700' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 transition' }}">Upcoming</a>
                    <a href="{{ route('activities.index', ['filter' => 'completed']) }}" class="px-5 py-2 rounded-full text-xs font-bold whitespace-nowrap {{ request('filter') === 'completed' ? 'bg-blue-100 text-blue-700' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 transition' }}">Completed</a>
                </div>
            </form>

            @if(auth()->user()->role && str_contains(strtolower(auth()->user()->role->name), 'student'))
                @php
                    $percentage = $totalActivities > 0 ? round(($completedCount / $totalActivities) * 100) : 0;
                @endphp
                <div class="mb-8 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800 mb-2">Your Activity Progress</h3>
                    
                    <div class="w-full bg-slate-100 rounded-full h-3 mb-3 overflow-hidden">
                        <div class="bg-blue-600 h-3 rounded-full transition-all duration-1000 ease-out" style="width: {{ $percentage }}%"></div>
                    </div>
                    
                    <p class="text-sm text-slate-600">
                        You have completed <strong class="text-blue-600">{{ $completedCount }}</strong> out of <strong>{{ $totalActivities }}</strong> available activities ({{ $percentage }}%).
                    </p>
                </div>
            @endif

            @if($activities->isEmpty())
                <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-slate-100">
                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <h3 class="mt-4 text-base font-bold text-slate-800">No activities found</h3>
                    <p class="mt-1 text-sm text-slate-500">Try adjusting your search or category filter.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($activities as $activity)
                        <div class="bg-white rounded-2xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-slate-100/60 hover:shadow-lg hover:border-slate-200 transition duration-300 flex flex-col h-full relative group">
                            <!-- Top Row: Category Pill & Actions -->
                            <div class="flex justify-between items-start mb-5">
                                @php
                                    $catName = strtolower($activity->category->name);
                                    $catColor = 'bg-slate-100 text-slate-700'; // default
                                    if(str_contains($catName, 'exam')) $catColor = 'bg-red-50 text-red-500';
                                    elseif(str_contains($catName, 'lecture')) $catColor = 'bg-blue-50 text-blue-500';
                                    elseif(str_contains($catName, 'lab')) $catColor = 'bg-green-50 text-green-500';
                                    elseif(str_contains($catName, 'assignment')) $catColor = 'bg-yellow-100 text-yellow-700';
                                @endphp
                                <span class="px-3 py-1 text-[11px] font-bold tracking-wide rounded-full {{ $catColor }}">
                                    {{ $activity->category->name }}
                                </span>

                                @if(auth()->user()->role && str_contains(strtolower(auth()->user()->role->name), 'pio'))
                                    @can('update', $activity)
                                        <div class="flex gap-2.5 transition-opacity">
                                            <a href="{{ route('activities.edit', $activity) }}" class="text-slate-400 hover:text-blue-600 transition" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>
                                            <form action="{{ route('activities.destroy', $activity) }}" method="POST" onsubmit="return confirm('Delete this activity permanently?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-slate-400 hover:text-red-600 transition" title="Delete">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    @endcan
                                @endif
                            </div>

                            <!-- Title -->
                            <h3 class="text-xl font-bold text-slate-900 mb-3">{{ $activity->title }}</h3>

                            <!-- Date -->
                            <div class="flex items-center text-slate-500 text-sm mb-4">
                                <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ \Carbon\Carbon::parse($activity->activity_date)->format('M d, Y @ h:i A') }}
                            </div>

                            <!-- Posted By -->
                            <div class="flex items-center mt-auto pt-5 mb-5 border-t border-slate-50/50">
                                <div class="w-6 h-6 rounded-full bg-slate-200 flex-shrink-0 flex items-center justify-center overflow-hidden mr-2">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($activity->owner->name) }}&background=random" alt="{{ $activity->owner->name }}" class="w-full h-full object-cover">
                                </div>
                                <span class="text-xs text-slate-500">Posted by {{ $activity->owner->name }}</span>
                            </div>

                            <!-- Bottom Button / Action Area -->
                            <div>
                                @if(auth()->user()->role && str_contains(strtolower(auth()->user()->role->name), 'pio'))
                                    @php
                                        $completedStudents = $activity->students()->wherePivot('status', 'Completed')->get();
                                        $studentsDone = $completedStudents->count();
                                    @endphp
                                    
                                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'completed-students-{{ $activity->id }}')" class="w-full bg-slate-50 hover:bg-blue-50 transition rounded-xl p-3 flex justify-between items-center cursor-pointer group/btn">
                                        <span class="text-sm font-bold text-blue-600 group-hover/btn:text-blue-700">View Submissions</span>
                                        <span class="bg-blue-600 text-white text-[10px] font-bold px-2.5 py-1 rounded-full">{{ $studentsDone }} Students</span>
                                    </button>
                                    
                                    <!-- Modal -->
                                    <x-modal name="completed-students-{{ $activity->id }}" focusable>
                                        <div class="p-6">
                                            <h2 class="text-lg font-bold text-slate-900 mb-4 border-b border-slate-100 pb-3">
                                                Students who completed "{{ $activity->title }}"
                                            </h2>
                                            <div class="mt-4 max-h-[60vh] overflow-y-auto pr-2">
                                                @if($completedStudents->isEmpty())
                                                    <p class="text-sm text-slate-500 py-8 text-center bg-slate-50 rounded-xl">No students have completed this activity yet.</p>
                                                @else
                                                    <ul class="divide-y divide-slate-100">
                                                        @foreach($completedStudents as $student)
                                                            <li class="py-3 flex justify-between items-center group">
                                                                <div class="flex items-center">
                                                                    <div class="w-8 h-8 rounded-full bg-slate-200 overflow-hidden mr-3">
                                                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=random" class="w-full h-full object-cover">
                                                                    </div>
                                                                    <div>
                                                                        <p class="text-sm font-bold text-slate-900">{{ $student->name }}</p>
                                                                        <p class="text-xs text-slate-500">{{ $student->email }}</p>
                                                                    </div>
                                                                </div>
                                                                <span class="text-xs font-medium text-slate-400 bg-slate-50 group-hover:bg-slate-100 transition px-2.5 py-1 rounded-md">
                                                                    {{ $student->pivot->updated_at->diffForHumans() }}
                                                                </span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                            <div class="mt-6 flex justify-end">
                                                <button type="button" x-on:click="$dispatch('close')" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2 px-5 rounded-lg shadow-sm transition">
                                                    Close
                                                </button>
                                            </div>
                                        </div>
                                    </x-modal>
                                @endif

                                @if(auth()->user()->role && str_contains(strtolower(auth()->user()->role->name), 'student'))
                                    @php
                                        $isDone = auth()->user()->rsvpdActivities->where('id', $activity->id)->where('pivot.status', 'Completed')->count() > 0;
                                    @endphp

                                    @if($isDone)
                                        <div class="w-full bg-green-50 rounded-xl p-3 flex justify-between items-center">
                                            <span class="text-sm font-bold text-green-700 flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                Completed
                                            </span>
                                        </div>
                                    @else
                                        <form action="{{ route('activities.complete', $activity) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full bg-blue-50 hover:bg-blue-100 transition rounded-xl p-3 flex justify-between items-center cursor-pointer">
                                                <span class="text-sm font-bold text-blue-700">Mark as Completed</span>
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>