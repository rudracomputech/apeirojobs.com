@extends('layouts.backend')
@section('title', isset($student) ? 'Edit Student' : 'Create Student')

@section('content')
<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-slate-900 mb-1 md:text-2xl dark:text-slate-50">
            {{ isset($student) ? 'Edit Student' : 'Create Student' }}
        </h2>
        <p class="text-base leading-relaxed text-slate-600 dark:text-slate-400">
            Add or update student details here.
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <strong class="block font-semibold">Please fix the following errors:</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ isset($student) ? route('students.update', $student->id) : route('students.store') }}" class="space-y-6" novalidate enctype="multipart/form-data">
        @csrf
        @if(isset($student))
            @method('PUT')
        @endif

        <div class="grid gap-6 lg:grid-cols-2">
            @if(isset($student))
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">ID</label>
                    <div class="rounded-md border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-slate-200">{{ $student->id }}</div>
                </div>
            @endif

            <div>
                <label for="student_code" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Student Code</label>
                <input type="text" id="student_code" name="student_code" value="{{ old('student_code', $student->student_code ?? '') }}" required class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
                <label for="name" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $student->name ?? '') }}" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
                <label for="first_name" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">First Name</label>
                <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $student->first_name ?? '') }}" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
                <label for="last_name" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $student->last_name ?? '') }}" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
                <label for="email" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $student->email ?? '') }}" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
                <label for="mobile" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Mobile</label>
                <input type="text" id="mobile" name="mobile" value="{{ old('mobile', $student->mobile ?? '') }}" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

              <div>
                <label for="alternate_mobile" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Alternate Mobile</label>
                <input type="text" id="alternate_mobile" name="alternate_mobile" value="{{ old('alternate_mobile', $student->alternate_mobile ?? '') }}" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div class="lg:col-span-2">
                <label for="course_ids" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Courses</label>
                <select id="course_ids" name="course_ids[]" multiple class="h-32 px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    @php
                        $selectedCourses = old('course_ids', isset($student) ? $student->courses->pluck('id')->toArray() : []);
                        $studentCourseDiscounts = old('course_discounts', isset($student) ? $student->courses->mapWithKeys(fn($course) => [$course->id => $course->pivot->discount_amount ?? 0])->toArray() : []);
                        $studentCourseStatuses = old('course_statuses', isset($student) ? $student->courses->mapWithKeys(fn($course) => [$course->id => $course->pivot->status ?? 'active'])->toArray() : []);
                    @endphp
                    @foreach($courses as $id => $name)
                        <option value="{{ $id }}"{{ in_array($id, (array) $selectedCourses) ? ' selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                <p class="mt-2   text-slate-500 dark:text-slate-400">Select one or more courses to assign this student.</p>
            </div>

            <div id="studentDiscountSection" class="lg:col-span-2 rounded-lg border border-slate-200 p-4 dark:border-neutral-700">
                <h3 class="mb-3 text-base font-semibold text-slate-900 dark:text-slate-50">Student wise discounts</h3>
                <div class="space-y-3">
                    @foreach($courseDetails ?? collect() as $course)
                        @php
                            $coursePrice = (float) ($course->price ?? 0);
                            $courseDiscount = ($course->discount_price !== null && (float) $course->discount_price > 0)
                                ? max(0, $coursePrice - (float) $course->discount_price)
                                : 0;
                        @endphp
                        <div data-course-discount-row="{{ $course->id }}" class="{{ in_array($course->id, (array) $selectedCourses) ? '' : 'hidden' }} grid gap-4 rounded-md bg-slate-50 p-3 md:grid-cols-4 md:items-end dark:bg-neutral-800">
                            <div class="md:col-span-2">
                                <p class="text-sm font-semibold text-slate-900 dark:text-slate-50">{{ $course->name }}</p>
                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Fee: {{ number_format($coursePrice, 2) }} | Course discount: {{ number_format($courseDiscount, 2) }}</p>
                            </div>
                            <div>
                                <label for="course_discounts_{{ $course->id }}" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Student Discount</label>
                                <input type="number" step="0.01" min="0" id="course_discounts_{{ $course->id }}" name="course_discounts[{{ $course->id }}]" value="{{ $studentCourseDiscounts[$course->id] ?? 0 }}" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
                            </div>
                            <div>
                                <label for="course_statuses_{{ $course->id }}" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Enrollment Status</label>
                                <select id="course_statuses_{{ $course->id }}" name="course_statuses[{{ $course->id }}]" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                                    <option value="active"{{ ( $studentCourseStatuses[$course->id] ?? 'active') === 'active' ? ' selected' : '' }}>Active</option>
                                    <option value="completed"{{ ( $studentCourseStatuses[$course->id] ?? 'active') === 'completed' ? ' selected' : '' }}>Completed</option>
                                    <option value="dropped"{{ ( $studentCourseStatuses[$course->id] ?? 'active') === 'dropped' ? ' selected' : '' }}>Dropped</option>
                                    <option value="pending"{{ ( $studentCourseStatuses[$course->id] ?? 'active') === 'pending' ? ' selected' : '' }}>Pending</option>
                                </select>
                            </div>
                            <div class="text-sm text-slate-600 dark:text-slate-300">
                                Extra discount for this student only
                            </div>
                        </div>
                    @endforeach
                    <p id="studentDiscountEmpty" class="{{ count((array) $selectedCourses) ? 'hidden' : '' }} text-sm text-slate-500 dark:text-slate-400">Select a course above to add a student-specific discount.</p>
                </div>
            </div>

            <div class="lg:col-span-2">
                <label class="inline-flex items-center space-x-2">
                    <input type="checkbox" name="notify_admins" value="1" {{ old('notify_admins', '1') ? 'checked' : '' }} class="rounded text-blue-600 focus:ring-blue-500" />
                    <span class="text-sm text-slate-700 dark:text-slate-300">Notify admins about course assignment</span>
                </label>
                <p class="mt-2   text-slate-500 dark:text-slate-400">When checked, admin users will receive a notification for newly assigned courses.</p>
            </div>

            @php
                $installmentsOld = old('installments', []);
                if (empty($installmentsOld) && isset($student)) {
                    $installmentsOld = $student->installments->map(function ($installment) {
                        return [
                            'amount' => (string) $installment->amount,
                            'due_date' => $installment->due_date ? $installment->due_date->format('Y-m-d') : '',
                            'status' => $installment->status ?? 'pending',
                        ];
                    })->values()->all();
                }
                $installmentCount = old('installment_count', count($installmentsOld));
                $installmentCount = is_numeric($installmentCount) ? min(max((int) $installmentCount, 0), 25) : min(count($installmentsOld), 25);
            @endphp
            <div class="lg:col-span-2 border-t border-slate-200 pt-6 dark:border-neutral-700">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h3 class="mb-4 text-base font-semibold text-slate-900 dark:text-slate-50">Installment schedule for newly generated invoices</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Select the number of installments and fill the generated installment rows. Rows will be posted as <code class="rounded bg-slate-100 px-1 py-0.5  ">installments[n]</code>.</p>
                    </div>
                    <div class="min-w-[220px]">
                        <label for="installment_count" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">No. of installments</label>
                        <select id="installment_count" name="installment_count" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                            @for($i = 0; $i <= 25; $i++)
                                <option value="{{ $i }}"{{ $installmentCount === $i ? ' selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div id="installmentRowsContainer" class="mt-4 space-y-4">
                    @if(!empty($installmentsOld))
                        @for($i = 0; $i < count($installmentsOld); $i++)
                            @php
                                $installment = $installmentsOld[$i] ?? ['due_date' => '', 'amount' => '', 'status' => 'pending'];
                            @endphp
                            <div class="grid gap-4 md:grid-cols-3">
                                <div>
                                    <label for="installments_{{ $i }}_due_date" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Installment {{ $i + 1 }} Due Date</label>
                                    <input type="date" id="installments_{{ $i }}_due_date" name="installments[{{ $i }}][due_date]" value="{{ old("installments.$i.due_date", $installment['due_date']) }}" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
                                </div>
                                <div>
                                    <label for="installments_{{ $i }}_amount" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Installment {{ $i + 1 }} Amount</label>
                                    <input type="number" step="0.01" min="0" id="installments_{{ $i }}_amount" name="installments[{{ $i }}][amount]" value="{{ old("installments.$i.amount", $installment['amount']) }}" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
                                </div>
                                <div>
                                    <label for="installments_{{ $i }}_status" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Installment {{ $i + 1 }} Status</label>
                                    <select id="installments_{{ $i }}_status" name="installments[{{ $i }}][status]" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                                        <option value="pending" {{ (old("installments.$i.status", $installment['status']) === 'pending') ? 'selected' : '' }}>Pending</option>
                                        <option value="partial" {{ (old("installments.$i.status", $installment['status']) === 'partial') ? 'selected' : '' }}>Partial</option>
                                        <option value="paid" {{ (old("installments.$i.status", $installment['status']) === 'paid') ? 'selected' : '' }}>Paid</option>
                                        <option value="overdue" {{ (old("installments.$i.status", $installment['status']) === 'overdue') ? 'selected' : '' }}>Overdue</option>
                                    </select>
                                </div>
                            </div>
                        @endfor
                    @else
                        <p class="text-sm text-slate-500 dark:text-slate-400">No installment rows generated. Select a number above to generate rows.</p>
                    @endif
                </div>
            </div>

            @php
                $documentsOld = old('documents', isset($student) ? $student->documents->map(function($d){ return ['id' => $d->id, 'name' => $d->name, 'url' => $d->getFirstMediaUrl('document')]; })->toArray() : []);
                $qualificationsOld = old('qualifications', isset($student) ? $student->qualifications->toArray() : []);
            @endphp

            <div class="lg:col-span-2 border-t border-slate-200 pt-6 dark:border-neutral-700">
                <div class="flex items-center justify-between">
                    <h3 class="mb-2 text-base font-semibold text-slate-900 dark:text-slate-50">Documents</h3>
                    <button type="button" id="addDocumentBtn" class="inline-flex items-center rounded-md bg-white px-3 py-1 text-sm border">Add document</button>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400">Add name and attach a file for each document. Files accepted: pdf,doc,docx,jpg,jpeg,png (max 10MB).</p>
                <div id="documentsContainer" class="mt-4 space-y-4"></div>
            </div>

            <div class="lg:col-span-2 border-t border-slate-200 pt-6 dark:border-neutral-700 mt-6">
                <div class="flex items-center justify-between">
                    <h3 class="mb-2 text-base font-semibold text-slate-900 dark:text-slate-50">Qualifications</h3>
                    <button type="button" id="addQualificationBtn" class="inline-flex items-center rounded-md bg-white px-3 py-1 text-sm border">Add qualification</button>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400">Add qualification name and choose a type.</p>
                <div id="qualificationsContainer" class="mt-4 space-y-4"></div>
            </div>

            <div>
                <label for="gender" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Gender</label>
                <select id="gender" name="gender" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    <option value="">Select gender</option>
                    <option value="male"{{ old('gender', $student->gender ?? '') === 'male' ? ' selected' : '' }}>Male</option>
                    <option value="female"{{ old('gender', $student->gender ?? '') === 'female' ? ' selected' : '' }}>Female</option>
                    <option value="other"{{ old('gender', $student->gender ?? '') === 'other' ? ' selected' : '' }}>Other</option>
                </select>
            </div>

            <div>
                <label for="dob" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Date of Birth</label>
                <input type="date" id="dob" name="dob" value="{{ old('dob', isset($student) && $student->dob ? $student->dob->format('Y-m-d') : '') }}" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
                <label for="father_name" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Father Name</label>
                <input type="text" id="father_name" name="father_name" value="{{ old('father_name', $student->father_name ?? '') }}" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
                <label for="mother_name" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Mother Name</label>
                <input type="text" id="mother_name" name="mother_name" value="{{ old('mother_name', $student->mother_name ?? '') }}" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div class="lg:col-span-2">
                <label for="address" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Address</label>
                <textarea id="address" name="address" rows="3" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">{{ old('address', $student->address ?? '') }}</textarea>
            </div>

            <div>
                <label for="city" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">City</label>
                <input type="text" id="city" name="city" value="{{ old('city', $student->city ?? '') }}" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
                <label for="state" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">State</label>
                <select id="state" name="state" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    <option value="">Select state</option>
                    @foreach($states as  $stateName)
                        <option value="{{ $stateName }}"{{ old('state', $student->state ?? '') === $stateName ? ' selected' : '' }}>{{ $stateName }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="country" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Country</label>
                <input type="text" id="country" name="country" value="{{ old('country', $student->country ?? '') }}" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
                <label for="admission_date" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Admission Date</label>
                <input type="date" id="admission_date" name="admission_date" value="{{ old('admission_date', isset($student) && $student->admission_date ? $student->admission_date->format('Y-m-d') : '') }}" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
                <label for="status" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Status</label>
                <select id="status" name="status" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    <option value="1"{{ old('status', $student->status ?? 1) ? ' selected' : '' }}>Active</option>
                    <option value="0"{{ old('status', $student->status ?? 1) ? '' : ' selected' }}>Inactive</option>
                </select>
            </div>

                <div>
                    <label for="branch_id" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Branch</label>
                    <select id="branch_id" name="branch_id" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                        <option value="">Select branch</option>
                        @foreach($batches as $id => $name)
                            <option value="{{ $id }}"{{ old('branch_id', $student->branch_id ?? '') == $id ? ' selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
               
 @if(auth()->user()->hasRole('Admin'))
           
            <div>
                <label for="created_by" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Created By</label>
                <select id="created_by" name="created_by" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    <option value="">Select user</option>
                    @foreach($users as $id => $name)
                        <option value="{{ $id }}"{{ old('created_by', $student->created_by ?? '') == $id ? ' selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            @endif

            @if(isset($student))
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Created At</label>
                    <div class="rounded-md border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-slate-200">{{ $student->created_at?->format('M d, Y H:i') ?? '-' }}</div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Updated At</label>
                    <div class="rounded-md border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-slate-200">{{ $student->updated_at?->format('M d, Y H:i') ?? '-' }}</div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Deleted At</label>
                    <div class="rounded-md border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-slate-200">{{ $student->deleted_at?->format('M d, Y H:i') ?? '-' }}</div>
                </div>
            @endif
        </div>

        <button type="submit" class="inline-flex items-center justify-center rounded-md bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            {{ isset($student) ? 'Update Student' : 'Create Student' }}
        </button>
    </form>
    
</div>

@if(isset($student) && $student->courses->isNotEmpty())
        @php
            $grandTotal = 0;
            $grandPaid = 0;
        @endphp
        <div class="mt-10 bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4">Assigned Courses & Fees</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm text-left">
                    <thead class="bg-slate-100 text-slate-700">
                        <tr>
                            <th class="px-4 py-2">Course</th>
                            <th class="px-4 py-2">Enrollment Date</th>
                            <th class="px-4 py-2">Fee</th>
                            <th class="px-4 py-2">Discount</th>
                            <th class="px-4 py-2">Row Total</th>
                            <th class="px-4 py-2">Paid</th>
                            <th class="px-4 py-2">Pending</th>
                            <th class="px-4 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($student->courses as $course)
                            @php
                                $pivot = $course->pivot;
                                $fee = (float) ($pivot->fee_amount ?? 0);
                                if ($fee === 0.0) {
                                    $fee = (float) ($course->price ?? 0);
                                }
                              
                                $studentDiscount = (float) ($pivot->discount_amount ?? 0);
                                $invoice = $enrollmentInvoices[$pivot->id] ?? null;
                                $invoiceDiscount = $invoice ? (float) ($invoice->discount ?? 0) : null;
                                $discount = $studentDiscount;
                                $rowTotal =  $fee - $discount;

                                
                                $paid = 0.0;
                                if ($invoice) {
                                    $paymentsSum = (float) ($invoice->payments->where('status', 'success')->sum('amount') ?? 0);
                                    $paid = $paymentsSum > 0 ? $paymentsSum : (float) ($invoice->paid_amount ?? 0);
                                }
                                $pending = max(0, $rowTotal - $paid);
                                $grandTotal += $rowTotal;
                                $grandPaid += $paid;
                                $status = 'Pending';
                                if ($invoice) {
                                    $status = $invoice->due_amount <= 0 ? 'Submitted' : ($invoice->paid_amount > 0 ? 'Partially Paid' : 'Pending');
                                }
                            @endphp
                            <tr>
                                <td class="px-4 py-2"><a href="{{ route('courses.edit', $course->id) }}" class="text-blue-500 hover:underline">{{ $course->name ?? $course->name }}</a></td>
                                <td class="px-4 py-2">{{ isset($pivot->enrollment_date) ? \Carbon\Carbon::parse($pivot->enrollment_date)->format('Y-m-d') : '-' }}</td>
                                <td class="px-4 py-2 items-center ">
                                    <div class="items-center inline-flex">
                                        <x-lucide-indian-rupee class="size-[14px]  overflow-visible" />
                                        {{ number_format($fee, 2) }} 
                                    </div>
                                </td>
                                <td class="px-4 py-2  "><div class="items-center inline-flex"><x-lucide-indian-rupee class="size-[14px]  overflow-visible" />{{ number_format($discount, 2) }}</div></td>
                                <td class="px-4 py-2 "><div class="items-center inline-flex"><x-lucide-indian-rupee class="size-[14px]  overflow-visible" />{{ number_format($rowTotal, 2) }}</div></td>
                                <td class="px-4 py-2 "><div class="items-center inline-flex"><x-lucide-indian-rupee class="size-[14px]  overflow-visible" />{{ number_format($paid, 2) }}</div></td>
                                <td class="px-4 py-2  "><div class="items-center inline-flex"><x-lucide-indian-rupee class="size-[14px]  overflow-visible" />{{ number_format($pending, 2) }}</div></td>
                                <td class="px-4 py-2">{{ $status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-50 font-semibold">
                        <tr>
                            <td class="px-4 py-2" colspan="4">Totals</td>
                            <td class="px-4 py-2 "><div class="items-center inline-flex"><x-lucide-indian-rupee class="size-[14px]  overflow-visible" />{{ number_format($grandTotal, 2) }}</div></td>
                            <td class="px-4 py-2  "><div class="items-center inline-flex"><x-lucide-indian-rupee class="size-[14px]  overflow-visible" />{{ number_format($grandPaid, 2) }}</div></td>
                            <td class="px-4 py-2 "><div class="items-center inline-flex"><x-lucide-indian-rupee class="size-[14px]  overflow-visible" />{{ number_format(max(0, $grandTotal - $grandPaid), 2) }}</div></td>
                            <td class="px-4 py-2">&nbsp;</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @endif

 @endsection
 
 @section('page-js')
 
<script type="module">
    $(function() {
        const $container = $('#installmentRowsContainer');
        const $countSelect = $('#installment_count');
        const $courseSelect = $('#course_ids');
        const $studentDiscountEmpty = $('#studentDiscountEmpty');

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function refreshStudentDiscountRows() {
            const selectedCourseIds = ($courseSelect.val() || []).map(String);
            $('[data-course-discount-row]').each(function () {
                const $row = $(this);
                const isSelected = selectedCourseIds.includes(String($row.data('course-discount-row')));
                $row.toggleClass('hidden', !isSelected);
            });
            $studentDiscountEmpty.toggleClass('hidden', selectedCourseIds.length > 0);
        }

        if ($courseSelect.length) {
            $courseSelect.on('change', refreshStudentDiscountRows);
            refreshStudentDiscountRows();
        }

        if ($container.length && $countSelect.length) {
            const existingInstallments = @json(array_values($installmentsOld));

            function buildRow(index, values = {}) {
                const amount = escapeHtml(values.amount ?? '');
                const dueDate = escapeHtml(values.due_date ?? '');
                const status = values.status ?? 'pending';

                return `
                    <div class="grid gap-6 lg:grid-cols-3 mb-4 items-end">
                        <div>
                            <label for="installments[${index}][amount]" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Installment ${index + 1} Amount</label>
                            <input type="number" step="0.01" id="installments[${index}][amount]" name="installments[${index}][amount]" value="${amount}" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
                        </div>
                        <div>
                            <label for="installments[${index}][due_date]" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Due Date</label>
                            <input type="date" id="installments[${index}][due_date]" name="installments[${index}][due_date]" value="${dueDate}" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
                        </div>
                        <div>
                            <label for="installments[${index}][status]" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Status</label>
                            <select id="installments[${index}][status]" name="installments[${index}][status]" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                                <option value="pending" ${status === 'pending' ? 'selected' : ''}>Pending</option>
                                <option value="partial" ${status === 'partial' ? 'selected' : ''}>Partial</option>
                                <option value="paid" ${status === 'paid' ? 'selected' : ''}>Paid</option>
                                <option value="overdue" ${status === 'overdue' ? 'selected' : ''}>Overdue</option>
                            </select>
                        </div>
                    </div>
                `;
            }

            function renderRows(count) {
                $container.html('');
                for (let index = 0; index < count; index += 1) {
                    const values = existingInstallments[index] || {};
                    const rowHtml = buildRow(index, values);
                    $container.append(rowHtml);
                }
            }

            $countSelect.on('change', function () {
                renderRows(parseInt($(this).val(), 10) || 0);
            });

            renderRows(parseInt($countSelect.val(), 10) || 0);
        }

        // Documents
        const $documentsContainer = $('#documentsContainer');
        const $addDocumentBtn = $('#addDocumentBtn');
        const existingDocuments = @json(array_values($documentsOld));

        function buildDocumentRow(index, values = {}) {
            const name = escapeHtml(values.name || '');
            const url = values.url || '';
            return `
                <div class="grid gap-4 md:grid-cols-3 items-end mb-3">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-900">Name</label>
                        <input type="text" name="documents[${index}][name]" value="${name}" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-900">File</label>
                        <input type="file" name="documents[${index}][file]" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300" />
                        ${values.id ? `<input type="hidden" name="documents[${index}][id]" value="${values.id}" />` : ''}
                        ${url ? `<div class="  mt-1">Existing: <a href="${url}" target="_blank" class="text-blue-600">View</a></div>` : ''}
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" data-index="${index}" class="remove-document inline-flex items-center rounded-md bg-red-50 text-red-700 border px-3 py-1">Remove</button>
                    </div>
                </div>
            `;
        }

        function renderDocuments() {
            if (!$documentsContainer.length) return;
            $documentsContainer.html('');
            for (let i = 0; i < existingDocuments.length; i++) {
                const values = existingDocuments[i] || {};
                $documentsContainer.append(buildDocumentRow(i, values));
            }
        }

        if ($addDocumentBtn.length && $documentsContainer.length) {
            $addDocumentBtn.on('click', function () {
                const index = $documentsContainer.children('div').length;
                $documentsContainer.append(buildDocumentRow(index, {}));
            });

            $documentsContainer.on('click', '.remove-document', function (e) {
                e.preventDefault();
                $(this).closest('div.grid').remove();
            });

            if ((existingDocuments || []).length === 0) {
                $addDocumentBtn.click();
            } else {
                renderDocuments();
            }
        }

        // Qualifications
        const $qualificationsContainer = $('#qualificationsContainer');
        const $addQualificationBtn = $('#addQualificationBtn');
        const existingQualifications = @json(array_values($qualificationsOld));

        function buildQualificationRow(index, values = {}) {
            const name = escapeHtml(values.name || '');
            const type = values.type || 'graduation';
            return `
                <div class="grid gap-4 md:grid-cols-3 items-end mb-3">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-900">Name</label>
                        <input type="text" name="qualifications[${index}][name]" value="${name}" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-900">Type</label>
                        <select name="qualifications[${index}][type]" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300">
                            <option value="diploma" ${type === 'diploma' ? 'selected' : ''}>Diploma</option>
                            <option value="graduation" ${type === 'graduation' ? 'selected' : ''}>Graduation</option>
                            <option value="post_graduation" ${type === 'post_graduation' ? 'selected' : ''}>Post Graduation</option>
                            <option value="phd" ${type === 'phd' ? 'selected' : ''}>PhD</option>
                            <option value="other" ${type === 'other' ? 'selected' : ''}>Other</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" data-index="${index}" class="remove-qualification inline-flex items-center rounded-md bg-red-50 text-red-700 border px-3 py-1">Remove</button>
                    </div>
                </div>
            `;
        }

        function renderQualifications() {
            if (!$qualificationsContainer.length) return;
            $qualificationsContainer.html('');
            for (let i = 0; i < existingQualifications.length; i++) {
                const values = existingQualifications[i] || {};
                $qualificationsContainer.append(buildQualificationRow(i, values));
            }
        }

        if ($addQualificationBtn.length && $qualificationsContainer.length) {
            $addQualificationBtn.on('click', function () {
                const index = $qualificationsContainer.children('div').length;
                $qualificationsContainer.append(buildQualificationRow(index, {}));
            });

            $qualificationsContainer.on('click', '.remove-qualification', function (e) {
                e.preventDefault();
                $(this).closest('div.grid').remove();
            });

            if ((existingQualifications || []).length === 0) {
                $addQualificationBtn.click();
            } else {
                renderQualifications();
            }
        }

    });
</script>
@endsection
