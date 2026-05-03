<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        // 1. Start building the query
        $query = Activity::with(['category', 'owner'])->latest();

        // 2. Check if the user is searching by title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // 3. Check if the user is filtering by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 4. Get the final filtered list of activities
        $activities = $query->get();

        // 5. Get all categories so we can populate the dropdown menu
        $categories = \App\Models\Category::all();

        // --- Variables for Student Progress ---
        // Notice we use Activity::count() instead of $activities->count() 
        // so the progress bar doesn't break when we filter the list!
        $totalActivities = Activity::count(); 
        $completedCount = 0;

        if (auth()->check() && auth()->user()->role && str_contains(strtolower(auth()->user()->role->name), 'student')) {
            $completedCount = auth()->user()->rsvpdActivities()->wherePivot('status', 'Completed')->count();
        }

        return view('activities.index', compact('activities', 'totalActivities', 'completedCount', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('activities.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'activity_date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Automatically assign the logged-in PIO as the owner
        $validated['pio_id'] = auth()->id(); 

        Activity::create($validated);

        return redirect()->route('activities.index')->with('success', 'Activity created successfully!');
    }

    public function edit(Activity $activity)
    {
        // AUTHORIZATION: Will throw a 403 error if this PIO didn't create this activity
        Gate::authorize('update', $activity);

        $categories = Category::all();
        return view('activities.edit', compact('activity', 'categories'));
    }

    public function update(Request $request, Activity $activity)
    {
        Gate::authorize('update', $activity);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'activity_date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
        ]);

        $activity->update($validated);

        return redirect()->route('activities.index')->with('success', 'Activity updated successfully!');
    }

    public function destroy(Activity $activity)
    {
        Gate::authorize('delete', $activity);
        
        $activity->delete();

        return redirect()->route('activities.index')->with('success', 'Activity deleted successfully!');
    }

    public function markCompleted(Request $request, Activity $activity)
    {
        // Get the currently logged-in student
        $user = auth()->user();

        // syncWithoutDetaching will add the student to the activity_user pivot table 
        // with the status 'Completed', or update it if they are already in there!
        $user->rsvpdActivities()->syncWithoutDetaching([
            $activity->id => ['status' => 'Completed']
        ]);

        return back()->with('success', 'Activity marked as completed!');
    }

    public function calendar()
    {
        // Eager load the category and owner to prevent database slowdowns
        $activities = Activity::with(['category', 'owner'])->get();

        // Format the data for FullCalendar
        $events = $activities->map(function ($activity) {
            return [
                'id' => $activity->id,
                'title' => $activity->title,
                'start' => \Carbon\Carbon::parse($activity->activity_date)->toIso8601String(),
                // 'extendedProps' is a special FullCalendar feature to hold custom data
                'extendedProps' => [
                    'category' => $activity->category ? $activity->category->name : 'Uncategorized',
                    'owner' => $activity->owner ? $activity->owner->name : 'Unknown',
                    'description' => $activity->description,
                    'formatted_date' => \Carbon\Carbon::parse($activity->activity_date)->format('F j, Y @ g:i A'),
                ]
            ];
        });

        return view('activities.calendar', compact('events'));
    }
}