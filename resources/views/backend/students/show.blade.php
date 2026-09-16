@extends('layouts.backend')
@section('title', 'Student Details')

@section('content')
<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-3xl font-semibold text-slate-900 dark:text-slate-50">Student Details</h2>
                <p class="text-sm text-slate-600 dark:text-slate-400">Review student information and history.</p>
            </div>
            <a href="{{ route('students.edit', $student->id) }}" class="inline-flex items-center gap-2 rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Edit Student</a>
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                <p class="  font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Student Code</p>
                <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-slate-50">{{ $student->student_code }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                <p class="  font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Name</p>
                <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-slate-50">{{ $student->name }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                <p class="  font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Email</p>
                <p class="mt-2 text-slate-900 dark:text-slate-50">{{ $student->email ?? '-' }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                <p class="  font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Mobile</p>
                <p class="mt-2 text-slate-900 dark:text-slate-50">{{ $student->mobile }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                <p class="  font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Alternate Mobile</p>
                <p class="mt-2 text-slate-900 dark:text-slate-50">{{ $student->alternate_mobile ?? '-' }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                <p class="  font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Status</p>
                <p class="mt-2 text-slate-900 dark:text-slate-50">{{ $student->status ? 'Active' : 'Inactive' }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                <p class="  font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Admission Date</p>
                <p class="mt-2 text-slate-900 dark:text-slate-50">{{ $student->admission_date?->format('M d, Y') ?? '-' }}</p>
            </div>
        </div>

        <div class="mt-8 grid gap-6 md:grid-cols-2">
            <div class="rounded-lg border border-slate-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-900">
                <h3 class="text-base font-semibold text-slate-900 dark:text-slate-50">Contact</h3>
                <dl class="mt-4 space-y-4 text-sm text-slate-700 dark:text-slate-300">
                    <div><dt class="font-medium">Father Name</dt><dd>{{ $student->father_name ?? '-' }}</dd></div>
                    <div><dt class="font-medium">Mother Name</dt><dd>{{ $student->mother_name ?? '-' }}</dd></div>
                    <div><dt class="font-medium">Address</dt><dd>{{ $student->address ?? '-' }}</dd></div>
                    <div><dt class="font-medium">City</dt><dd>{{ $student->city ?? '-' }}</dd></div>
                    <div><dt class="font-medium">State</dt><dd>{{ $student->state ?? '-' }}</dd></div>
                    <div><dt class="font-medium">Country</dt><dd>{{ $student->country ?? '-' }}</dd></div>
                </dl>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-900">
                <h3 class="text-base font-semibold text-slate-900 dark:text-slate-50">Timestamps</h3>
                <dl class="mt-4 space-y-4 text-sm text-slate-700 dark:text-slate-300">
                    <div><dt class="font-medium">Created At</dt><dd>{{ $student->created_at?->format('M d, Y H:i') ?? '-' }}</dd></div>
                    <div><dt class="font-medium">Updated At</dt><dd>{{ $student->updated_at?->format('M d, Y H:i') ?? '-' }}</dd></div>
                    <div><dt class="font-medium">Deleted At</dt><dd>{{ $student->deleted_at?->format('M d, Y H:i') ?? '-' }}</dd></div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
