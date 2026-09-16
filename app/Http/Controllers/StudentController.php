<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Course;
use App\Models\CountryState;
use App\Models\Installment;
use App\Models\Invoice;
use App\Models\Student;
use App\Models\StudentDocument;
use App\Models\StudentQualification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $allowedSorts = ['id', 'student_code', 'first_name', 'last_name', 'email', 'mobile', 'gender', 'dob', 'admission_date', 'status', 'created_at', 'updated_at'];
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

        $query = Student::query()
            ->withSum('invoices as invoice_total_sum', 'total_amount')
            ->withSum('invoices as invoice_discount_sum', 'discount')
            ->withSum('invoices as invoice_tax_sum', 'tax')
            ->withSum('invoices as invoice_paid_sum', 'paid_amount')
            ->withSum('invoices as invoice_due_sum', 'due_amount')
            ->withCount('courses as courses_count')
            ->withCount(['courses as completed_courses_count' => function ($query) {
                $query->where('enrollments.status', 'completed');
            }]);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('student_code', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->input('gender'));
        }

        if ($request->filled('state')) {
            $query->where('state', $request->input('state'));
        }

        if ($request->filled('course')) {
            $query->whereHas('courses', fn($q) => $q->where('courses.id', $request->input('course')));
        }

        if ($request->filled('admission_date_from')) {
            $query->whereDate('admission_date', '>=', $request->input('admission_date_from'));
        }

        if ($request->filled('admission_date_to')) {
            $query->whereDate('admission_date', '<=', $request->input('admission_date_to'));
        }

        $students = $query->orderBy($sort, $direction)->paginate($limit)->withQueryString();

        $courses = Course::orderBy('name')->pluck('name', 'id');
        $states = Student::select('state')->distinct()->whereNotNull('state')->pluck('state');
        $genders = ['male' => 'Male', 'female' => 'Female', 'other' => 'Other'];
        $statusOptions = [1 => 'Active', 0 => 'Inactive'];

        return view('backend.students.index', compact('students', 'courses', 'states', 'genders', 'statusOptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $authUser = auth()->user();
        if ($authUser->hasRole('Admin')) {
            $users = User::orderBy('name')->pluck('name', 'id');
        } elseif ($authUser->hasRole('Team Manager') || $authUser->hasRole('team manager') || $authUser->hasRole(5)) {
            $users = User::where('created_by', $authUser->id)
                ->orWhere('id', $authUser->id)
                ->orderBy('name')
                ->pluck('name', 'id');
        } else {
            $users = User::where('id', $authUser->id)->pluck('name', 'id');
        }
        $courseDetails = Course::orderBy('name')->get(['id', 'name', 'price', 'discount_price']);
        $courses = $courseDetails->pluck('name', 'id');
        $batches = Batch::orderBy('name')->pluck('name', 'id');
        $states = CountryState::orderBy('default_name')->pluck('default_name', 'default_name');

        return view('backend.students.create-edit', compact('users', 'courses', 'batches','courseDetails', 'states'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_code' => 'required|string|max:255|unique:students,student_code',
            'course_ids' => 'nullable|array',
            'course_ids.*' => 'exists:courses,id',
            'course_discounts' => 'nullable|array',
            'course_discounts.*' => 'nullable|numeric|min:0',
            'course_statuses' => 'nullable|array',
            'course_statuses.*' => ['nullable', Rule::in(['active', 'completed', 'dropped', 'pending'])],
            'alternate_mobile' => 'nullable|string|max:50',
            'name' => 'nullable|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'mobile' => 'required|string|max:50',
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'dob' => 'nullable|date',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:2000',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|exists:country_states,default_name',
            'country' => 'nullable|string|max:255',
            'admission_date' => 'nullable|date',
            'status' => 'nullable|boolean',
            'created_by' => 'nullable|exists:users,id',
            'installment_count' => 'nullable|integer|min:0|max:10',
            'installments' => 'nullable|array',
            'installments.*.amount' => 'nullable|numeric|min:0',
            'installments.*.due_date' => 'nullable|date',
            'installments.*.status' => ['nullable', Rule::in(['pending', 'partial', 'paid', 'overdue'])],
            'document_count' => 'nullable|integer|min:0|max:20',
            'documents' => 'nullable|array',
            'documents.*.name' => 'nullable|string|max:255',
            'documents.*.file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'qualification_count' => 'nullable|integer|min:0|max:10',
            'qualifications' => 'nullable|array',
            'qualifications.*.name' => 'nullable|string|max:255',
            'qualifications.*.type' => ['nullable', Rule::in(['diploma', 'graduation', 'post_graduation', 'phd', 'other'])],
        ]);

        if (! filled($validated['first_name'] ?? null) && filled($validated['name'] ?? null)) {
            [$validated['first_name'], $validated['last_name']] = $this->splitName($validated['name']);
        }

        $student = Student::create(collect($validated)->except([
            'course_ids',
            'course_discounts',
            'course_statuses',
            'installment_count',
            'installments',
            'document_count',
            'documents',
            'qualification_count',
            'qualifications',
        ])->toArray());

        $this->syncStudentDocuments($student, $request, $validated['documents'] ?? []);

        $courseIds = $validated['course_ids'] ?? [];
        $courseDiscounts = $this->selectedCourseDiscounts($courseIds, $validated['course_discounts'] ?? []);
        $courseStatuses = $this->selectedCourseStatuses($courseIds, $validated['course_statuses'] ?? []);
        $coursePrices = Course::whereIn('id', $courseIds)->pluck('price', 'id');
        $syncData = collect($courseIds)->mapWithKeys(function ($courseId) use ($coursePrices, $courseDiscounts, $courseStatuses) {
            return [$courseId => [
                'enrollment_date' => now()->format('Y-m-d'),
                'fee_amount' => (float) ($coursePrices[$courseId] ?? 0),
                'discount_amount' => $courseDiscounts[$courseId] ?? 0,
                'status' => $courseStatuses[$courseId] ?? 'active',
                'batch_id' => null,
            ]];
        })->all();

        $student->courses()->sync($syncData);

        // Generate invoices for the newly created enrollments
        if (! empty($courseIds)) {
            $installmentRows = collect($validated['installments'] ?? [])->filter(function ($row) {
                return isset($row['amount']) && $row['amount'] > 0;
            })->values()->all();

            $this->createInvoicesForEnrollments($student->id, $courseIds, $installmentRows);

            // notify admins if requested
            if ($request->filled('notify_admins')) {
                $added = $courseIds;
                $enrollments = DB::table('enrollments')
                    ->where('student_id', $student->id)
                    ->whereIn('course_id', $added)
                    ->get();

                $admins = User::whereHas('roles', function ($q) { $q->where('name', 'Admin'); })->get();
                foreach ($admins as $admin) {
                    foreach ($enrollments as $enrollment) {
                        DB::table('notifications')->insert([
                            'id' => (string) Str::uuid(),
                            'type' => 'student.course_assigned',
                            'notifiable_type' => User::class,
                            'notifiable_id' => $admin->id,
                            'data' => json_encode([
                                'student_id' => $student->id,
                                'course_id' => $enrollment->course_id,
                                'enrollment_id' => $enrollment->id,
                                'message' => 'Student assigned to course',
                            ]),
                            'read_at' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }

        return redirect()->route('students.index')->with(['status' => 'success', 'message' => 'Student created successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        return view('backend.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $authUser = auth()->user();
        if ($authUser->hasRole('Admin')) {
            $users = User::orderBy('name')->pluck('name', 'id');
        } elseif ($authUser->hasRole('Team Manager') || $authUser->hasRole('team manager') || $authUser->hasRole(5)) {
            $users = User::where('created_by', $authUser->id)
                ->orWhere('id', $authUser->id)
                ->orderBy('name')
                ->pluck('name', 'id');
        } else {
            $users = User::where('id', $authUser->id)->pluck('name', 'id');
        }
        $courseDetails = Course::orderBy('name')->get(['id', 'name', 'price', 'discount_price']);
        $courses = $courseDetails->pluck('name', 'id');
        $batches = Batch::orderBy('name')->pluck('name', 'id');
       
        $states = CountryState::orderBy('default_name')->pluck('default_name', 'default_name');

        $student->load([
            'courses',
            'installments' => function ($query) {
                $query->orderBy('id');
            },
            'documents' => function ($query) {
                $query->withTrashed();
            },
        ]);

        $this->syncExistingInstallmentPayments($student);

        // Load invoices for the student's enrollments to compute paid/due per enrollment
        $enrollmentIds = $student->courses->pluck('pivot.id')->filter()->all();
        $enrollmentInvoices = [];

        if (! empty($enrollmentIds)) {
            $invoices = \App\Models\Invoice::with('payments')->whereIn('enrollment_id', $enrollmentIds)->get();
            foreach ($invoices as $inv) {
                $enrollmentInvoices[$inv->enrollment_id] = $inv;
            }
        }

        return view('backend.students.create-edit', compact('student', 'users','batches', 'courses', 'courseDetails', 'states', 'enrollmentInvoices'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'student_code' => ['required', 'string', 'max:255', Rule::unique('students', 'student_code')->ignore($student->id)],
            'course_ids' => 'nullable|array',
            'course_ids.*' => 'exists:courses,id',
            'course_discounts' => 'nullable|array',
            'course_discounts.*' => 'nullable|numeric|min:0',
            'course_statuses' => 'nullable|array',
            'course_statuses.*' => ['nullable', Rule::in(['active', 'completed', 'dropped', 'pending'])],
            'alternate_mobile' => 'nullable|string|max:50',
            'name' => 'nullable|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'mobile' => 'required|string|max:50',
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'dob' => 'nullable|date',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:2000',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|exists:country_states,default_name',
            'country' => 'nullable|string|max:255',
            'admission_date' => 'nullable|date',
            'status' => 'nullable|boolean',
            'created_by' => 'nullable|exists:users,id',
            'installment_count' => 'nullable|integer|min:0|max:10',
            'installments' => 'nullable|array',
            'installments.*.amount' => 'nullable|numeric|min:0',
            'installments.*.due_date' => 'nullable|date',
            'installments.*.status' => ['nullable', Rule::in(['pending', 'partial', 'paid', 'overdue'])],
            'document_count' => 'nullable|integer|min:0|max:20',
            'documents' => 'nullable|array',
            'documents.*.id' => 'nullable|integer|exists:student_documents,id',
            'documents.*.name' => 'nullable|string|max:255',
            'documents.*.file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        if (! filled($validated['first_name'] ?? null) && filled($validated['name'] ?? null)) {
            [$validated['first_name'], $validated['last_name']] = $this->splitName($validated['name']);
        }

        $student->update(collect($validated)->except([
            'course_ids',
            'course_discounts',
            'course_statuses',
            'installment_count',
            'installments',
            'document_count',
            'documents',
        ])->toArray());

        $this->syncStudentDocuments($student, $request, $validated['documents'] ?? []);

        $courseIds = $validated['course_ids'] ?? [];
        $courseDiscounts = $this->selectedCourseDiscounts($courseIds, $validated['course_discounts'] ?? []);
        $courseStatuses = $this->selectedCourseStatuses($courseIds, $validated['course_statuses'] ?? []);
        $existingEnrollments = DB::table('enrollments')
            ->where('student_id', $student->id)
            ->whereIn('course_id', $courseIds)
            ->get()
            ->keyBy('course_id');
        $coursePrices = Course::whereIn('id', $courseIds)->pluck('price', 'id');
        $syncData = collect($courseIds)->mapWithKeys(function ($courseId) use ($coursePrices, $courseDiscounts, $courseStatuses, $existingEnrollments) {
            $existing = $existingEnrollments->get((int) $courseId);

            return [$courseId => [
                'enrollment_date' => $existing->enrollment_date ?? now()->format('Y-m-d'),
                'fee_amount' => (float) ($coursePrices[$courseId] ?? 0),
                'discount_amount' => $courseDiscounts[$courseId] ?? 0,
                'status' => $courseStatuses[$courseId] ?? $existing->status ?? 'active',
                'batch_id' => $existing->batch_id ?? null,
            ]];
        })->all();

        $student->courses()->sync($syncData);

        $installmentRows = collect($validated['installments'] ?? [])->filter(function ($row) {
            return isset($row['amount']) && $row['amount'] > 0;
        })->values()->all();

        if (! empty($courseIds)) {
            $this->createInvoicesForEnrollments($student->id, $courseIds, $installmentRows);

            // notify admins if requested
            if ($request->filled('notify_admins')) {
                $enrollments = DB::table('enrollments')
                    ->where('student_id', $student->id)
                    ->whereIn('course_id', $courseIds)
                    ->get();

                $admins = User::whereHas('roles', function ($q) { $q->where('name', 'Admin'); })->get();
                foreach ($admins as $admin) {
                    foreach ($enrollments as $enrollment) {
                        DB::table('notifications')->insert([
                            'id' => (string) Str::uuid(),
                            'type' => 'student.course_assigned',
                            'notifiable_type' => User::class,
                            'notifiable_id' => $admin->id,
                            'data' => json_encode([
                                'student_id' => $student->id,
                                'course_id' => $enrollment->course_id,
                                'enrollment_id' => $enrollment->id,
                                'message' => 'Student assigned to course',
                            ]),
                            'read_at' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }

        return redirect()->route('students.index')->with(['status' => 'success', 'message' => 'Student updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }

    private function splitName(string $name): array
    {
        $parts = preg_split('/\s+/', trim($name));

        if (count($parts) === 1) {
            return [$parts[0], ''];
        }

        $firstName = array_shift($parts);
        $lastName = implode(' ', $parts);

        return [$firstName, $lastName];
    }

    private function selectedCourseDiscounts(array $courseIds, array $discounts): array
    {
        return collect($courseIds)
            ->mapWithKeys(function ($courseId) use ($discounts) {
                return [(int) $courseId => max(0, (float) ($discounts[$courseId] ?? 0))];
            })
            ->all();
    }

    private function selectedCourseStatuses(array $courseIds, array $statuses): array
    {
        return collect($courseIds)
            ->mapWithKeys(function ($courseId) use ($statuses) {
                return [(int) $courseId => in_array($statuses[$courseId] ?? 'active', ['active', 'completed', 'dropped', 'pending'], true) ? $statuses[$courseId] : 'active'];
            })
            ->all();
    }

    protected function calculateInvoiceBreakdown(float $fee, float $courseDiscount, float $studentDiscount, ?float $invoiceDiscount = null, ?float $invoicePaid = null): array
    {
        $baseFee = max(0.0, $fee);
      
        $rowTotal = max(0.0, $baseFee - $studentDiscount);
     //  die("Discount total: ".$studentDiscount);
        $paid = max(0.0, (float) ($invoicePaid ?? 0));

        return [
            'fee' => $baseFee,
            'discount' => $studentDiscount,
            'rowTotal' => $rowTotal,
            'paid' => $paid,
            'pending' => max(0.0, $rowTotal - $paid),
        ];
    }

    private function createInvoicesForEnrollments(int $studentId, array $courseIds, array $installmentRows = []): void
    {
        // Fetch enrollments for this student and the provided course IDs
        $enrollments = DB::table('enrollments')
            ->where('student_id', $studentId)
            ->whereIn('course_id', $courseIds)
            ->get();

        foreach ($enrollments as $enrollment) {
            $course = Course::find($enrollment->course_id);
            if (! $course) {
                continue;
            }

            $total = (float) ($enrollment->fee_amount ?? 0);
            if ($total <= 0) {
                $total = (float) $course->price;
            }

            $price = (float) $course->price;
            $courseDiscount = 0.0;
            if ($course->discount_price && $course->discount_price > 0) {
                $courseDiscount = max(0.0, $price - (float) $course->discount_price);
            }

            $studentDiscount = max(0.0, (float) ($enrollment->discount_amount ?? 0));
            $invoice = Invoice::where('enrollment_id', $enrollment->id)->first();
            $paid = $invoice ? (float) $invoice->payments()->where('status', 'success')->sum('amount') : 0.0;
            $invoiceDiscount = $invoice ? (float) ($invoice->discount ?? 0) : null;
            $breakdown = $this->calculateInvoiceBreakdown($total, $courseDiscount, $studentDiscount, $invoiceDiscount, $paid);
           
         
           
            $discount = $breakdown['discount'];
            $rowAmount = $breakdown['rowTotal'];
            $tax = (float) ($invoice->tax ?? 0);
            $due = max(0.0, $rowAmount - $paid + $tax);

            if ($invoice) {
                $invoice->update([
                    'student_id' => $studentId,
                    'total_amount' => $rowAmount,
                    'discount' => $discount,
                    'paid_amount' => $paid,
                    'due_amount' => $due,
                    'status' => $due <= 0 ? 'paid' : ($paid > 0 ? 'partially_paid' : 'due'),
                ]);
            } else {
                $latest = Invoice::latest('id')->first();
                $next = $latest ? $latest->id + 1 : 1;
                $invoiceNo = 'INV' . date('Ymd') . str_pad($next, 5, '0', STR_PAD_LEFT);

                $invoice = Invoice::create([
                    'invoice_no' => $invoiceNo,
                    'student_id' => $studentId,
                    'enrollment_id' => $enrollment->id,
                    'total_amount' => $rowAmount,
                    'discount' => $discount,
                    'tax' => 0,
                    'paid_amount' => $paid,
                    'due_amount' => $due,
                    'status' => $due <= 0 ? 'paid' : 'due',
                    'due_date' => now()->addDays(30)->format('Y-m-d'),
                ]);
            }

            if (! empty($installmentRows)) {
                $this->syncInstallmentsForInvoice($invoice, $studentId, $installmentRows);
            }
        }
    }

    private function syncExistingInstallmentPayments(Student $student): void
    {
        foreach ($student->installments as $installment) {
            if ((float) ($installment->paid_amount ?? 0) <= 0 && ! in_array($installment->status, ['paid', 'partial'], true)) {
                continue;
            }

            $existingPayment = $installment->payments()
                ->where('invoice_id', $installment->invoice_id)
                ->where('student_id', $student->id)
                ->where('status', 'success')
                ->first();

            if (! $existingPayment) {
                $amount = (float) ($installment->paid_amount > 0 ? $installment->paid_amount : $installment->amount);
                $installment->payments()->create([
                    'invoice_id' => $installment->invoice_id,
                    'student_id' => $student->id,
                    'amount' => $amount,
                    'payment_method' => 'cash',
                    'payment_date' => now(),
                    'status' => 'success',
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
            }

            $paidAmount = (float) $installment->payments()->where('status', 'success')->sum('amount');
            $installment->paid_amount = $paidAmount;
            $installment->status = $paidAmount >= (float) $installment->amount ? 'paid' : ($paidAmount > 0 ? 'partial' : $installment->status);
            $installment->save();
        }
    }

    private function syncStudentDocuments(Student $student, Request $request, array $documents = []): void
    {
        $submittedDocumentIds = collect($documents)->pluck('id')->filter()->values()->all();
        $preservedDocumentIds = $submittedDocumentIds;

        foreach ($documents as $index => $documentData) {
            $documentId = $documentData['id'] ?? null;
            $studentDocument = null;

            if ($documentId) {
                $studentDocument = $student->documents()->withTrashed()->find($documentId);
                if ($studentDocument && $studentDocument->trashed()) {
                    $studentDocument->restore();
                }
            }

            $name = trim($documentData['name'] ?? '');
            $file = $request->file("documents.$index.file");

            if ($studentDocument && $name !== '') {
                $studentDocument->update(['name' => $name]);
            }

            if (! $file) {
                continue;
            }

            if (! $studentDocument) {
                $studentDocument = $student->documents()->create([
                    'name' => $name ?: $file->getClientOriginalName(),
                ]);
                $preservedDocumentIds[] = $studentDocument->id;
            } elseif ($name !== '') {
                $studentDocument->update(['name' => $name]);
            }

            $studentDocument->addMedia($file)->toMediaCollection('document');
        }

        if (! empty($preservedDocumentIds)) {
            $student->documents()->whereNotIn('id', $preservedDocumentIds)->delete();
        }
    }

    private function syncInstallmentsForInvoice(Invoice $invoice, int $studentId, array $installmentRows): void
    {
        $existingInstallments = $invoice->installments()->orderBy('id')->get();

        foreach (array_values($installmentRows) as $index => $row) {
            if (empty($row['amount']) || $row['amount'] <= 0) {
                continue;
            }

            $status = in_array($row['status'] ?? '', ['pending', 'partial', 'paid', 'overdue'], true) ? $row['status'] : 'pending';
            $attributes = [
                'invoice_id' => $invoice->id,
                'student_id' => $studentId,
                'amount' => $row['amount'],
                'paid_amount' => 0,
                'due_date' => !empty($row['due_date']) ? $row['due_date'] : null,
                'status' => $status,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ];

            $installment = isset($existingInstallments[$index]) ? $existingInstallments[$index] : null;

            if (! $installment) {
                $installment = Installment::create($attributes);
            } else {
                $installment->update($attributes);
            }

            if (in_array($status, ['paid', 'partial'], true)) {
                $existingPayment = $installment->payments()
                    ->where('invoice_id', $invoice->id)
                    ->where('student_id', $studentId)
                    ->where('status', 'success')
                    ->first();

                if (! $existingPayment) {
                    $installment->payments()->create([
                        'invoice_id' => $invoice->id,
                        'student_id' => $studentId,
                        'amount' => (float) $row['amount'],
                        'payment_method' => 'cash',
                        'payment_date' => now(),
                        'status' => 'success',
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);
                }
            }

            $paidAmount = (float) $installment->payments()->where('status', 'success')->sum('amount');
            $installment->paid_amount = $paidAmount;
            $installment->status = $paidAmount >= (float) $installment->amount ? 'paid' : ($paidAmount > 0 ? 'partial' : $status);
            $installment->save();
        }

        if ($existingInstallments->count() > count($installmentRows)) {
            $extras = $existingInstallments->slice(count($installmentRows));
            foreach ($extras as $extra) {
                if ($extra->payments()->count() === 0) {
                    $extra->delete();
                }
            }
        }
    }
}
