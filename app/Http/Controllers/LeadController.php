<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lead;
use App\Models\Status;
use App\Models\Student;
use App\Models\User;
use App\Imports\LeadImport;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $allowedSorts = ['id', 'name', 'email', 'status', 'next_followup_date', 'converted_at', 'created_at', 'updated_at'];
        $sort = $request->input('sort', 'id');
        $direction = $request->input('direction') === 'desc' ? 'desc' : 'asc';
        $limit = $request->input('limit', 10);




        if ($limit === 'all') {
            $limit = 1000000; // A very large number to effectively disable pagination
        } else {
            $limit = (int) $limit;
        }

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'id';
        }

        $baseQuery = Lead::query();

        $courses = Course::whereIn('id', (clone $baseQuery)->select('course_interest_id')->distinct())->pluck('name', 'id');

        $users = User::orderBy('name', 'asc')->pluck('name', 'id');

        $statuses = $this->statusOptions();

        $sources = (clone $baseQuery)->whereNotNull('source')->where('source', '!=', '')->distinct()->pluck('source');

        $states = (clone $baseQuery)->whereNotNull('state')->where('state', '!=', '')->distinct()->pluck('state');
        $cities = (clone $baseQuery)->whereNotNull('city')->where('city', '!=', '')->distinct()->pluck('city');



        $query =  $baseQuery->with(['courseInterest', 'assignedTo', 'student', 'followups.user']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%")
                    ->orWhere('source', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('state', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('course')) {
            $query->where('course_interest_id', $request->input('course'));
        }

        if ($request->filled('email')) {
            $query->where('email', $request->input('email'));
        }
        if ($request->filled('source')) {
            $query->where('source', $request->input('source'));
        }

        if ($request->filled('assigned_to')) {
            if ($request->input('assigned_to') === 'null') {
                $query->whereNull('assigned_to');
            } else {
                $query->where('assigned_to', $request->input('assigned_to'));
            }
        }
        if ($request->filled('state')) {
            $query->where('state', $request->input('state'));
        }



        if ($request->filled('city')) {
            $query->where('city', $request->input('city'));
        }

        if ($request->filled('followup_date_from')) {
            $query->whereDate('next_followup_date', '>=', $request->input('followup_date_from'));
        }

        if ($request->filled('followup_date_to')) {
            $query->whereDate('next_followup_date', '<=', $request->input('followup_date_to'));
        }

        if ($request->filled('converted_date_from')) {
            $query->whereDate('converted_at', '>=', $request->input('converted_date_from'));
        }

        if ($request->filled('converted_date_to')) {
            $query->whereDate('converted_at', '<=', $request->input('converted_date_to'));
        }

        if ($request->has('has_email')) {
            $query->whereNotNull('email')->where('email', '!=', '');
        }

        if ($request->has('has_mobile')) {
            $query->whereNotNull('mobile')->where('mobile', '!=', '');
        }

        if ($request->has('has_course')) {
            $query->whereNotNull('course_interest_id');
        }

        if ($request->has('has_followup')) {
            $query->whereNotNull('next_followup_date');
        }


        $leads = $query->orderBy($sort, $direction)->paginate($limit)->withQueryString();


        return view('backend.leads.index', compact('leads', 'courses', 'users', 'statuses', 'sources', 'states', 'cities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::orderBy('name', 'desc')->pluck('name', 'id');
        $students = Student::orderBy('name', 'desc')->pluck('name', 'id');
        $users = User::orderBy('name', 'desc')->pluck('name', 'id');
        $statuses = Status::where('type', 'lead')->where('active', true)->pluck('label', 'key');

        return view('backend.leads.create-edit', compact('courses', 'students', 'users', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'mobile' => 'required|string|max:50',
            'address' => 'nullable|string|max:1000',
            'location' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'course_interest_id' => 'nullable|exists:courses,id',
            'status' => ['required', Rule::in(array_keys($this->statusOptions()))],
            'assigned_to' => 'nullable|exists:users,id',
            'student_id' => 'nullable|exists:students,id',
            'next_followup_date' => 'nullable|date',
            'converted_at' => 'nullable|date',
            'remarks' => 'nullable|string|max:2000',
        ]);

        Lead::create($validated);

        return redirect()->route('leads.index')->with(['status' => 'success', 'message' => 'Lead created successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Lead $lead)
    {
        return view('backend.leads.show', compact('lead'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lead $lead)
    {
        $courses = Course::orderBy('name', 'desc')->pluck('name', 'id');
        $students = Student::orderBy('name', 'desc')->pluck('name', 'id');
        $users = User::orderBy('name', 'desc')->pluck('name', 'id');
        $statuses = Status::where('type', 'lead')->where('active', true)->pluck('label', 'key');

        $lead->load('followups.user');

        return view('backend.leads.create-edit', compact('lead', 'courses', 'students', 'users', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lead $lead)
    {


        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'mobile' => 'required|string|max:50',
            'address' => 'nullable|string|max:1000',
            'location' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'course_interest_id' => 'nullable|exists:courses,id',
            'status' => ['required', Rule::in(array_keys($this->statusOptions()))],
            'assigned_to' => 'nullable|exists:users,id',
            'student_id' => 'nullable|exists:students,id',
            'next_followup_date' => 'nullable|date',
            'converted_at' => 'nullable|date',
            'remarks' => 'nullable|string|max:2000',
        ]);

        $lead->update($validated);

        return redirect()->route('leads.index')->with(['status' => 'success', 'message' => 'Lead updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()->route('leads.index')->with(['status' => 'success', 'message' => 'Lead deleted successfully.']);
    }

    /**
     * Process bulk lead actions.
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => ['required', Rule::in(['delete', 'assigned_to'])],
            'selected_ids' => ['required', 'array', 'min:1'],
            'selected_ids.*' => ['required', 'integer', 'exists:leads,id'],
            'assigned_to' => ['nullable', 'required_if:action,assigned_to', 'integer', 'exists:users,id'],
        ]);

        $selectedIds = $validated['selected_ids'];

        if ($validated['action'] === 'delete') {
            Lead::whereIn('id', $selectedIds)->delete();

            return redirect()->route('leads.index')->with([
                'status' => 'success',
                'message' => 'Selected leads deleted successfully.',
            ]);
        }

        Lead::whereIn('id', $selectedIds)->update([
            'assigned_to' => $validated['assigned_to'],
        ]);

        return redirect()->route('leads.index')->with([
            'status' => 'success',
            'message' => 'Selected leads were assigned successfully.',
        ]);
    }

    /**
     * Import leads from Excel file.
     */
    public function import(Request $request)
    {


        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:5120', // 5MB max
        ]);

        try {
            $file = $request->file('file');

            $fullPath = $file->getRealPath();

            if (! $fullPath || ! is_readable($fullPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Uploaded file is not accessible.',
                ], 422);
            }

            $importer = new LeadImport();
            $result = $importer->import($fullPath);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while importing: ' . $e->getMessage(),
            ], 422);
        }
    }

    private function statusOptions(): array
    {
        $options = Status::where('type', 'lead')->where('active', true)->pluck('label', 'key')->toArray();

        if (empty($options)) {
            return [
                'new' => 'New',
                'contacted' => 'Contacted',
                'interested' => 'Interested',
                'follow_up' => 'Follow Up',
                'demo_scheduled' => 'Demo Scheduled',
                'converted' => 'Converted',
                'lost' => 'Lost',
            ];
        }

        return $options;
    }
}

