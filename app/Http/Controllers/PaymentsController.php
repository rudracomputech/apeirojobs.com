<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payments;
use App\Models\Student;
use App\Models\Installment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PaymentsController extends Controller
{
    public function index(Request $request)
    {
        $allowedSorts = ['id', 'invoice_id', 'student_id', 'amount', 'payment_method', 'payment_date', 'status', 'created_at', 'updated_at'];
        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction') === 'desc' ? 'desc' : 'asc';

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'id';
        }

        $query = Payments::with(['invoice', 'student', 'createdBy', 'updatedBy']);

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
                })
                ->orWhere('transaction_id', 'like', "%{$search}%");
            });
        }

        $payments = $query->orderBy($sort, $direction)->paginate(10)->withQueryString();

        return view('backend.payments.index', compact('payments'));
    }

    public function earnings(Request $request)
    {
        return $this->renderReport($request, 'success', 'Earnings Report');
    }

    public function refunds(Request $request)
    {
        return $this->renderReport($request, 'refunded', 'Refunds Report');
    }

    private function renderReport(Request $request, string $status, string $title)
    {
        $allowedSorts = ['id', 'invoice_id', 'student_id', 'amount', 'payment_method', 'payment_date', 'status', 'created_at', 'updated_at'];
        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction') === 'desc' ? 'desc' : 'asc';

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'id';
        }

        $query = Payments::with(['invoice', 'student', 'createdBy', 'updatedBy'])
            ->where('status', $status);

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
                })
                ->orWhere('transaction_id', 'like', "%{$search}%");
            });
        }

        $totalAmount = (clone $query)->sum('amount');
        $totalCount = (clone $query)->count();

        $payments = $query->orderBy($sort, $direction)->paginate(10)->withQueryString();

        return view('backend.reports.index', compact('payments', 'title', 'status', 'totalAmount', 'totalCount'));
    }

    public function create()
    {
        $students = Student::orderBy('first_name')->get();
        $invoices = Invoice::orderBy('invoice_no')->pluck('invoice_no', 'id');
        $installments = Installment::orderBy('due_date')->get();

        return view('backend.payments.create-edit', compact('students', 'invoices', 'installments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'student_id' => 'required|exists:students,id',
            'installment_id' => 'nullable|exists:installments,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => ['required', Rule::in(['cash', 'upi', 'card', 'bank_transfer', 'razorpay', 'stripe'])],
            'transaction_id' => 'nullable|string|max:255',
            'gateway_response' => 'nullable',
            'payment_date' => 'required|date',
            'status' => ['required', Rule::in(['pending', 'success', 'failed', 'refunded'])],
        ]);

        $invoice = Invoice::findOrFail($validated['invoice_id']);
        if ($invoice->student_id !== (int) $validated['student_id']) {
            return back()->withErrors(['student_id' => 'Selected student does not match the invoice student.'])->withInput();
        }
        if ($error = $this->validateInstallmentSelection($validated)) {
            return back()->withErrors(['installment_id' => $error])->withInput();
        }

        if ($request->filled('gateway_response')) {
            $validated['gateway_response'] = json_decode($validated['gateway_response'], true);
        }

        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        $payment = Payments::create($validated);

        if (! empty($validated['installment_id'])) {
            $this->refreshInstallmentTotals((int) $validated['installment_id']);
        }

        // Recalculate invoice totals after recording payment
        $invoice->refresh();
        $invoice->recalculateTotals();

        return redirect()->route('payments.index')->with(['status' => 'success', 'message' => 'Payment recorded successfully.']);
    }

    public function show(Payments $payment)
    {
        return view('backend.payments.show', compact('payment'));
    }

    public function edit(Payments $payment)
    {
        $students = Student::orderBy('first_name')->get();
        $invoices = Invoice::orderBy('invoice_no')->pluck('invoice_no', 'id');
        $installments = Installment::where('invoice_id', $payment->invoice_id)->orderBy('due_date')->get();

        return view('backend.payments.create-edit', compact('payment', 'students', 'invoices', 'installments'));
    }

    public function update(Request $request, Payments $payment)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'student_id' => 'required|exists:students,id',
            'installment_id' => 'nullable|exists:installments,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => ['required', Rule::in(['cash', 'upi', 'card', 'bank_transfer', 'razorpay', 'stripe'])],
            'transaction_id' => 'nullable|string|max:255',
            'gateway_response' => 'nullable|json',
            'payment_date' => 'required|date',
            'status' => ['required', Rule::in(['pending', 'success', 'failed', 'refunded'])],
        ]);

        $invoice = Invoice::findOrFail($validated['invoice_id']);
        if ($invoice->student_id !== (int) $validated['student_id']) {
            return back()->withErrors(['student_id' => 'Selected student does not match the invoice student.'])->withInput();
        }
        if ($error = $this->validateInstallmentSelection($validated)) {
            return back()->withErrors(['installment_id' => $error])->withInput();
        }

        if ($request->filled('gateway_response')) {
            $validated['gateway_response'] = json_decode($validated['gateway_response'], true);
        }

        $originalInstallmentId = $payment->installment_id;
        $validated['updated_by'] = Auth::id();

        $payment->update($validated);

        if ($originalInstallmentId && (int) $originalInstallmentId !== (int) ($validated['installment_id'] ?? 0)) {
            $this->refreshInstallmentTotals($originalInstallmentId);
        }

        if (! empty($validated['installment_id'])) {
            $this->refreshInstallmentTotals($validated['installment_id']);
        }

        // Recalculate invoice totals after payment update
        $invoice->refresh();
        $invoice->recalculateTotals();

        return redirect()->route('payments.index')->with(['status' => 'success', 'message' => 'Payment updated successfully.']);
    }

    private function validateInstallmentSelection(array $validated): ?string
    {
        if (empty($validated['installment_id'])) {
            return null;
        }

        $installment = Installment::find($validated['installment_id']);
        if (! $installment) {
            return 'Selected installment was not found.';
        }

        if ((int) $installment->invoice_id !== (int) $validated['invoice_id']) {
            return 'Selected installment does not match the selected invoice.';
        }

        if ((int) $installment->student_id !== (int) $validated['student_id']) {
            return 'Selected installment does not match the selected student.';
        }

        return null;
    }

    private function refreshInstallmentTotals(?int $installmentId): void
    {
        if (! $installmentId) {
            return;
        }

        $inst = Installment::find($installmentId);
        if (! $inst) {
            return;
        }

        $paid = Payments::where('installment_id', $inst->id)->where('status', 'success')->sum('amount');
        $inst->paid_amount = $paid;
        if ($paid >= $inst->amount) {
            $inst->status = 'paid';
        } elseif ($paid > 0) {
            $inst->status = 'partial';
        } else {
            $inst->status = 'pending';
        }
        $inst->updated_by = Auth::id();
        $inst->save();

        if ($paid > 0) {
            $admins = User::whereHas('roles', function ($q) { $q->where('name', 'Admin'); })->get();
            foreach ($admins as $admin) {
                DB::table('notifications')->insert([
                    'id' => (string) Str::uuid(),
                    'type' => 'installment.updated',
                    'notifiable_type' => User::class,
                    'notifiable_id' => $admin->id,
                    'data' => json_encode([
                        'installment_id' => $inst->id,
                        'paid_amount' => (float) $inst->paid_amount,
                        'status' => $inst->status,
                        'message' => 'Installment updated after payment modification',
                    ]),
                    'read_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function destroy(Payments $payment)
    {
        $installmentId = $payment->installment_id;
        $payment->delete();

        if ($installmentId) {
            $this->refreshInstallmentTotals($installmentId);
        }

        // Recalculate invoice totals after payment deletion
        if ($payment->invoice_id) {
            $inv = Invoice::find($payment->invoice_id);
            if ($inv) {
                $inv->recalculateTotals();
            }
        }

        return redirect()->route('payments.index')->with(['status' => 'success', 'message' => 'Payment deleted successfully.']);
    }
}
