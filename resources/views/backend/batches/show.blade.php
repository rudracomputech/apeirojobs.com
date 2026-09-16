@extends('layouts.backend')
@section('title', 'Batch Details')

@section('content')
<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-3xl font-semibold text-slate-900 dark:text-slate-50">Batch Details</h2>
                <p class="text-sm text-slate-600 dark:text-slate-400">Review batch information and schedule.</p>
            </div>
            <a href="{{ route('batches.edit', $batch->id) }}" class="inline-flex items-center gap-2 rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Edit Batch</a>
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                <p class="  font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Batch Name</p>
                <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-slate-50">{{ $batch->name }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                <p class="  font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Course</p>
                <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-slate-50">{{ $batch->course->name ?? '-' }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                <p class="  font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Instructor</p>
                <p class="mt-2 text-slate-900 dark:text-slate-50">{{ $batch->instructor->name ?? '-' }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                <p class="  font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Status</p>
                <p class="mt-2 text-slate-900 dark:text-slate-50">{{ ucfirst($batch->status) }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                <p class="  font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Start Date</p>
                <p class="mt-2 text-slate-900 dark:text-slate-50">{{ $batch->start_date?->format('M d, Y') }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                <p class="  font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">End Date</p>
                <p class="mt-2 text-slate-900 dark:text-slate-50">{{ $batch->end_date?->format('M d, Y') ?? '-' }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-900 sm:col-span-2">
                <h3 class="text-base font-semibold text-slate-900 dark:text-slate-50">Details</h3>
                <dl class="mt-4 space-y-4 text-sm text-slate-700 dark:text-slate-300">
                    <div><dt class="font-medium">Capacity</dt><dd>{{ $batch->capacity ?? '-' }}</dd></div>
                    <div><dt class="font-medium">Created At</dt><dd>{{ $batch->created_at?->format('M d, Y H:i') ?? '-' }}</dd></div>
                    <div><dt class="font-medium">Updated At</dt><dd>{{ $batch->updated_at?->format('M d, Y H:i') ?? '-' }}</dd></div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
