<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Class Activity Calendar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(str_contains(strtolower(auth()->user()->role->name), 'pio'))
                <div class="mb-6 text-right">
                    <a href="{{ route('activities.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition inline-block">
                        + Create New Activity
                    </a>
                </div>
            @endif

            <form method="GET" action="{{ route('activities.index') }}" class="mb-8 bg-white p-5 rounded-lg shadow-sm border border-gray-200 flex flex-col md:flex-row gap-4 md:items-end">
                
                <div class="flex-1">
                    <label for="search" class="block text-sm font-bold text-gray-700 mb-1">Search by Title</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="e.g., Math Quiz..." class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                </div>
                
                <div class="w-full md:w-64">
                    <label for="category_id" class="block text-sm font-bold text-gray-700 mb-1">Filter by Category</label>
                    <select name="category_id" id="category_id" class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2 w-full md:w-auto">
                    <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2.5 px-6 rounded shadow transition w-full md:w-auto">
                        Filter
                    </button>
                    
                    @if(request('search') || request('category_id'))
                        <a href="{{ route('activities.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2.5 px-6 rounded shadow transition text-center w-full md:w-auto">
                            Clear
                        </a>
                    @endif
                </div>
            </form>

            @if(str_contains(strtolower(auth()->user()->role->name), 'student'))
                @php
                    $percentage = $totalActivities > 0 ? round(($completedCount / $totalActivities) * 100) : 0;
                @endphp
                <div class="mb-8 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Your Activity Progress</h3>
                    
                    <div class="w-full bg-gray-200 rounded-full h-4 mb-3 overflow-hidden">
                        <div class="bg-blue-600 h-4 rounded-full transition-all duration-1000 ease-out" style="width: {{ $percentage }}%"></div>
                    </div>
                    
                    <p class="text-sm text-gray-600">
                        You have completed <strong class="text-blue-600 text-base">{{ $completedCount }}</strong> out of <strong>{{ $totalActivities }}</strong> available activities ({{ $percentage }}%).
                    </p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($activities->isEmpty())
                        <div class="text-center py-10">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No activities found</h3>
                            <p class="mt-1 text-sm text-gray-500">Try adjusting your search or category filter.</p>
                        </div>
                    @else
                        <div class="space-y-8">
                            @foreach ($activities as $activity)
                                <div class="border-b border-gray-200 pb-6 last:border-0 last:pb-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-2xl font-bold text-gray-800">{{ $activity->title }}</h3>
                                            <div class="flex flex-wrap gap-x-4 text-sm text-gray-500 mt-1">
                                                <span><strong class="text-gray-700">Category:</strong> {{ $activity->category->name }}</span>
                                                <span><strong class="text-gray-700">Date:</strong> {{ \Carbon\Carbon::parse($activity->activity_date)->format('M d, Y @ h:i A') }}</span>
                                                <span><strong class="text-gray-700">Posted by:</strong> {{ $activity->owner->name }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="mt-4 text-gray-700 leading-relaxed">
                                        {{ $activity->description }}
                                    </p>

                                    @if(str_contains(strtolower(auth()->user()->role->name), 'pio'))
                                        @php
                                            $studentsDone = $activity->students()->wherePivot('status', 'Completed')->count();
                                        @endphp
                                        
                                        <div class="mt-5 flex flex-wrap items-center gap-4">
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-md bg-blue-50 text-blue-700 border border-blue-200 text-sm font-semibold">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                                {{ $studentsDone }} Student(s) Completed
                                            </span>

                                            @can('update', $activity)
                                                <a href="{{ route('activities.edit', $activity) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white px-4 py-1.5 rounded text-sm font-bold transition shadow-sm">
                                                    Edit
                                                </a>
                                                <form action="{{ route('activities.destroy', $activity) }}" method="POST" onsubmit="return confirm('Delete this activity permanently?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-1.5 rounded text-sm font-bold transition shadow-sm">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    @endif

                                    @if(str_contains(strtolower(auth()->user()->role->name), 'student'))
                                        @php
                                            $isDone = auth()->user()->rsvpdActivities->where('id', $activity->id)->where('pivot.status', 'Completed')->count() > 0;
                                        @endphp

                                        <div class="mt-5">
                                            @if($isDone)
                                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-green-100 text-green-700 border border-green-200">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    Successfully Completed
                                                </span>
                                            @else
                                                <form action="{{ route('activities.complete', $activity) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm shadow-sm transition">
                                                        Mark as Completed
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>