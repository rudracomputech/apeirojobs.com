<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $allowedSorts = ['id', 'name',  'duration', 'price', 'discount_price', 'status', 'created_by', 'created_at', 'updated_at'];
        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction') === 'desc' ? 'desc' : 'asc';
        $limit = $request->input('limit', 10);

        if ($limit === 'all') {
            $limit = 1000000;
        } else {
            $limit = (int) $limit;
        }

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'id';
        }

        $query = Course::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('created_by')) {
            $query->where('created_by', $request->input('created_by'));
        }

        if ($request->filled('duration_min')) {
            $query->where('duration', '>=', $request->input('duration_min'));
        }

        if ($request->filled('duration_max')) {
            $query->where('duration', '<=', $request->input('duration_max'));
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->input('price_min'));
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->input('price_max'));
        }

        $sortColumn = $sort === 'name' ? 'name' : $sort;
        $courses = $query->orderBy($sortColumn, $direction)->paginate($limit)->withQueryString();
        $users = User::orderBy('name')->pluck('name', 'id');

        return view('backend.courses.index', compact('courses', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::orderBy('name')->pluck('name', 'id');

        $courseTypes = CourseType::where('status', true)->pluck('name', 'id');

        return view('backend.courses.create-edit', compact('users', 'courseTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|integer|min:0',
            'duration_type' => ['nullable', Rule::in(['days', 'weeks', 'months'])],
            'price' => 'nullable|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'status' => 'nullable|boolean',
            'course_type_id' => 'nullable|exists:course_types,id',
            'created_by' => 'nullable|exists:users,id',
        ]);

        Course::create($validated);

        return redirect()->route('courses.index')->with(['status' => 'success', 'message' => 'Course created successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        return view('backend.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        $users = User::orderBy('name')->pluck('name', 'id');
        $courseTypes = CourseType::where('status', true)->pluck('name', 'id');

        return view('backend.courses.create-edit', compact('course', 'users', 'courseTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|integer|min:0',
            'duration_type' => ['nullable', Rule::in(['days', 'weeks', 'months'])],
            'price' => 'nullable|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'status' => 'nullable|boolean',
            'course_type_id' => 'nullable|exists:course_types,id',
            'created_by' => 'nullable|exists:users,id',
        ]);

        $course->update($validated);

        return redirect()->route('courses.index')->with(['status' => 'success', 'message' => 'Course updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('courses.index')->with(['status' => 'success', 'message' => 'Course deleted successfully.']);
    }
}
