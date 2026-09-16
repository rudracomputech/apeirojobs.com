<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $allowedSorts = ['id', 'name', 'start_date', 'end_date', 'capacity', 'status', 'created_at', 'updated_at'];
        $sort = $request->input('sort', 'id');
        $direction = $request->input('direction') === 'desc' ? 'desc' : 'asc';
        $limit = $request->input('limit', 10);

        if ($limit === 'all') {
            $limit = 1000000; // effectively disable pagination
        } else {
            $limit = (int) $limit;
            $limit = $limit > 0 ? $limit : 10;
        }

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'id';
        }

        $query = Batch::with(['course', 'instructor']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('course', fn($query) => $query->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('instructor', fn($query) => $query->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('course')) {
            $query->where('course_id', $request->input('course'));
        }

        if ($request->filled('instructor')) {
            $query->where('instructor_id', $request->input('instructor'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('start_date_from')) {
            $query->whereDate('start_date', '>=', $request->input('start_date_from'));
        }

        if ($request->filled('start_date_to')) {
            $query->whereDate('start_date', '<=', $request->input('start_date_to'));
        }

        if ($request->filled('end_date_from')) {
            $query->whereDate('end_date', '>=', $request->input('end_date_from'));
        }

        if ($request->filled('end_date_to')) {
            $query->whereDate('end_date', '<=', $request->input('end_date_to'));
        }

        if ($request->filled('capacity_min')) {
            $query->where('capacity', '>=', $request->input('capacity_min'));
        }

        if ($request->filled('capacity_max')) {
            $query->where('capacity', '<=', $request->input('capacity_max'));
        }

        $courses = Course::orderBy('name')->pluck('name', 'id');
        $users = User::whereHas('roles', function ($q) {
            $q->where('id', 3);
        })->orderBy('name')->pluck('name', 'id');
        $statuses = $this->statusOptions();

        $batches = $query->orderBy($sort, $direction)->paginate($limit)->withQueryString();

        return view('backend.batches.index', compact('batches', 'courses', 'users', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::orderBy('name')->pluck('name', 'id');
        $users = User::whereHas('roles', function ($q) {
    $q->where('id', 3);
})->orderBy('name')->pluck('name', 'id');
        $statuses = $this->statusOptions();

        return view('backend.batches.create-edit', compact('courses', 'users', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'capacity' => 'nullable|integer|min:0',
            'instructor_id' => 'nullable|exists:users,id',
            'status' => ['required', Rule::in(array_keys($this->statusOptions()))],
        ]);

        Batch::create($validated);

        return redirect()->route('batches.index')->with('success', 'Batch created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Batch $batch)
    {
        $batch->load(['course', 'instructor']);

        return view('backend.batches.show', compact('batch'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Batch $batch)
    {
        $courses = Course::orderBy('name')->pluck('name', 'id');
        $users = User::orderBy('name')->pluck('name', 'id');
        $statuses = $this->statusOptions();

        return view('backend.batches.create-edit', compact('batch', 'courses', 'users', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Batch $batch)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'capacity' => 'nullable|integer|min:0',
            'instructor_id' => 'nullable|exists:users,id',
            'status' => ['required', Rule::in(array_keys($this->statusOptions()))],
        ]);

        $batch->update($validated);

        return redirect()->route('batches.index')->with('success', 'Batch updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Batch $batch)
    {
        $batch->delete();

        return redirect()->route('batches.index')->with('success', 'Batch deleted successfully.');
    }

    private function statusOptions(): array
    {
        return [
            'upcoming' => 'Upcoming',
            'active' => 'Active',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];
    }
}
