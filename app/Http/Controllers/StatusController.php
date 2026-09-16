<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StatusController extends Controller
{
    public function index()
    {
        $statuses = Status::orderBy('type')->orderBy('label')->get();
        return view('backend.statuses.index', compact('statuses'));
    }

    public function create()
    {
        return view('backend.statuses.create-edit', [
            'status' => new Status(),
            'types' => $this->statusTypes(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255|unique:statuses,key',
            'label' => 'required|string|max:255',
            'type' => ['nullable', Rule::in(array_keys($this->statusTypes()))],
            'description' => 'nullable|string|max:1000',
            'active' => 'boolean',
        ]);

        Status::create($validated);

        return redirect()->route('statuses.index')->with(['status' => 'success', 'message' => 'Status created successfully.']);
    }

    public function edit(Status $status)
    {
        return view('backend.statuses.create-edit', [
            'status' => $status,
            'types' => $this->statusTypes(),
        ]);
    }

    public function update(Request $request, Status $status)
    {
        $validated = $request->validate([
            'key' => ['required', 'string', 'max:255', Rule::unique('statuses', 'key')->ignore($status->id)],
            'label' => 'required|string|max:255',
            'type' => ['nullable', Rule::in(array_keys($this->statusTypes()))],
            'description' => 'nullable|string|max:1000',
            'active' => 'boolean',
        ]);

        $status->update($validated);

        return redirect()->route('statuses.index')->with(['status' => 'success', 'message' => 'Status updated successfully.']);
    }

    public function destroy(Status $status)
    {
        $status->delete();

        return redirect()->route('statuses.index')->with('success', 'Status deleted successfully.');
    }

    private function statusTypes(): array
    {
        return [
            'lead' => 'Lead',
            'invoice' => 'Invoice',
            'payment' => 'Payment',
            'installment' => 'Installment',
            'batch' => 'Batch',
            'user' => 'User',
            'student' => 'Student',
        ];
    }
}
