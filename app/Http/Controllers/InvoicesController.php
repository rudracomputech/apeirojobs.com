<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class InvoicesController extends Controller
{
    public function index(Request $request)
    {
        $allowedSorts = ['id', 'invoice_no', 'student_id', 'total_amount', 'status', 'due_date', 'created_at', 'updated_at'];
        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction') === 'desc' ? 'desc' : 'asc';

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'id';
        }

        $query = Invoice::with(['student']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                    ->orWhereHas('student', function ($sq) use ($search) {
                        $sq->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('mobile', 'like', "%{$search}%");
                    });
            });
        }

        $invoices = $query->orderBy($sort, $direction)->paginate(10)->withQueryString();

        return view('backend.invoices.index', compact('invoices'));
    }

    public function create()
    {
        $students = Student::orderBy('first_name')->get();

        return view('backend.invoices.create-edit', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_no' => 'required|string|unique:invoices,invoice_no',
            'student_id' => 'required|exists:students,id',
            'enrollment_id' => 'nullable|exists:enrollments,id',
            'total_amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'due_amount' => 'nullable|numeric|min:0',
            'status' => ['required', Rule::in(['paid','partially_paid','due','overdue'])],
            'due_date' => 'nullable|date',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        Invoice::create($validated);

        return redirect()->route('invoices.index')->with(['status' => 'success', 'message' => 'Invoice created successfully.']);
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('payments', 'student');
        return view('backend.invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $students = Student::orderBy('first_name')->get();

        return view('backend.invoices.create-edit', compact('invoice', 'students'));
    }

    public function update(Request $request, Invoice $invoice)
    {

   
        $validated = $request->validate([
            'invoice_no' => ['required', 'string', Rule::unique('invoices','invoice_no')->ignore($invoice->id)],
            'student_id' => 'required|exists:students,id',
            'enrollment_id' => 'nullable|exists:enrollments,id',
            'total_amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'due_amount' => 'nullable|numeric|min:0',
            'status' => ['required', Rule::in(['paid','partially_paid','due','overdue'])],
            'due_date' => 'nullable|date',
        ]);

        $validated['updated_by'] = Auth::id();

        $invoice->update($validated);

        return redirect()->route('invoices.index')->with(['status' => 'success', 'message' => 'Invoice updated successfully.']);
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()->route('invoices.index')->with(['status' => 'success', 'message' => 'Invoice deleted successfully.']);
    }
}
