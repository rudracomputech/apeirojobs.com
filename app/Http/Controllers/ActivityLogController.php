<?php

namespace App\Http\Controllers;

use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with(['causer', 'subject']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($sub) use ($search) {
                $sub->where('description', 'like', "%{$search}%")
                    ->orWhere('log_name', 'like', "%{$search}%")
                    ->orWhere('subject_type', 'like', "%{$search}%")
                    ->orWhere('causer_type', 'like', "%{$search}%");
            });
        }

        $activityLogs = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('backend.activity-logs.index', compact('activityLogs'));
    }

    public function show(Request $request, int $id)
    {
        $activity = Activity::findOrFail($id);
    
        $activity->load(['causer', 'subject']);



        return view('backend.activity-logs.show', compact('activity'));
    }
}
