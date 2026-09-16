<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\Invoice;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class InstallmentController extends Controller
{
    public function index(Request $request)
    {
        $allowedSorts = ['id', 'invoice_id', 'student_id', 'amount', 'paid_amount', 'due_date', 'status', 'created_at', 'updated_at'];
        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction') === 'desc' ? 'desc' : 'asc';

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'id';
        }

        $query = Installment::with(['invoice', 'student']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('invoice', function ($invoiceQuery) use ($search) {
                    $invoiceQuery->where('invoice_no', 'like', "%{$search}%");
                })
                ->orWhereHas('student', function ($studentQuery) use ($search) {
                    $studentQuery->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('mobile', 'like', "%{$search}%");
                });
            });
        }

        $installments = $query->orderBy($sort, $direction)->paginate(15)->withQueryString();

        return view('backend.installments.index', compact('installments'));
    }

    public function create()
    {
        $students = Student::orderBy('first_name')->get();
        $invoices = Invoice::orderBy('invoice_no')->pluck('invoice_no', 'id');

        return view('backend.installments.create-edit', compact('students', 'invoices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'due_date' => 'nullable|date',
            'status' => ['required', Rule::in(['pending', 'partial', 'paid', 'overdue'])],
        ]);

        $invoice = Invoice::findOrFail($validated['invoice_id']);
        if ($invoice->student_id !== (int) $validated['student_id']) {
            return back()->withErrors(['student_id' => 'Selected student does not match the invoice student.'])->withInput();
        }

        $validated['paid_amount'] = $validated['paid_amount'] ?? 0;
        $installment = Installment::create($validated + [
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('installments.index')->with(['status' => 'success', 'message' => 'Installment created successfully.']);
    }

    public function edit(Installment $installment)
    {
        $students = Student::orderBy('first_name')->get();
        $invoices = Invoice::orderBy('invoice_no')->pluck('invoice_no', 'id');

        return view('backend.installments.create-edit', compact('installment', 'students', 'invoices'));
    }

    public function show(Installment $installment)
    {
        $installment->load(['invoice', 'student', 'payments']);

        return view('backend.installments.show', compact('installment'));
    }

    public function update(Request $request, Installment $installment)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'due_date' => 'nullable|date',
            'status' => ['required', Rule::in(['pending', 'partial', 'paid', 'overdue'])],
        ]);

        $invoice = Invoice::findOrFail($validated['invoice_id']);
        if ($invoice->student_id !== (int) $validated['student_id']) {
            return back()->withErrors(['student_id' => 'Selected student does not match the invoice student.'])->withInput();
        }

        $validated['paid_amount'] = $validated['paid_amount'] ?? 0;
        $installment->update($validated + ['updated_by' => Auth::id()]);

        return redirect()->route('installments.index')->with(['status' => 'success', 'message' => 'Installment updated successfully.']);
    }

    public function destroy(Installment $installment)
    {
        $installment->delete();

        return redirect()->route('installments.index')->with(['status' => 'success', 'message' => 'Installment deleted successfully.']);
    }
}
