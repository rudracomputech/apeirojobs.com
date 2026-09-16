<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadFollowup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LeadFollowupController extends Controller
{
    public function store(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'followup_type' => ['required', Rule::in(['call', 'whatsapp', 'email', 'meeting'])],
            'call_duration' => 'nullable|integer|min:0',
            'note' => 'required|string|max:2000',
            'followup_date' => 'required|date',
            'next_followup_date' => 'nullable|date|after_or_equal:followup_date',
        ]);

        $lead->followups()->create([
            'user_id' => Auth::id(),
            'followup_type' => $validated['followup_type'],
            'call_duration' => $validated['call_duration'] ?? null,
            'note' => $validated['note'],
            'followup_date' => $validated['followup_date'],
            'next_followup_date' => $validated['next_followup_date'] ?? null,
        ]);

        return redirect()->back()
            ->with(['status' => 'success', 'message' => 'Follow up saved successfully.']);
    }
}
