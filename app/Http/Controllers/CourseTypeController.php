<?php

namespace App\Http\Controllers;

use App\Models\CourseType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourseTypeController extends Controller
{
    public function index()
    {
        $courseTypes = CourseType::orderBy('name')->get();

        return view('backend.course-types.index', compact('courseTypes'));
    }

    public function create()
    {
        return view('backend.course-types.create-edit', [
            'courseType' => new CourseType(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:course_types,name',
            'status' => 'boolean',
        ]);

        CourseType::create($validated);

        return redirect()->route('course-types.index')->with('success', 'Course type created successfully.');
    }

    public function edit(CourseType $courseType)
    {
        return view('backend.course-types.create-edit', [
            'courseType' => $courseType,
        ]);
    }

    public function update(Request $request, CourseType $courseType)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('course_types', 'name')->ignore($courseType->id)],
            'status' => 'boolean',
        ]);

        $courseType->update($validated);

        return redirect()->route('course-types.index')->with('success', 'Course type updated successfully.');
    }

    public function destroy(CourseType $courseType)
    {
        $courseType->delete();

        return redirect()->route('course-types.index')->with('success', 'Course type deleted successfully.');
    }
}
