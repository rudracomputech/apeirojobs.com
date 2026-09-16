@extends('layouts.backend')
@section('title', 'Course Details')

@section('content')
<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-3xl font-semibold text-slate-900 dark:text-slate-50">Course Details</h2>
                <p class="text-sm text-slate-600 dark:text-slate-400">Review course information.</p>
            </div>
            <a href="{{ route('courses.edit', $course->id) }}" class="inline-flex items-center gap-2 rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Edit Course</a>
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                <p class="  font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Name</p>
                <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-slate-50">{{ $course->name }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                <p class="  font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Slug</p>
                <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-slate-50">{{ $course->slug }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-neutral-700 dark:bg-neutral-900 sm:col-span-2">
                <p class="  font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Description</p>
                <p class="mt-2 text-slate-900 dark:text-slate-50">{{ $course->description ?? '-' }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                <p class="  font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Duration</p>
                <p class="mt-2 text-slate-900 dark:text-slate-50">{{ $course->duration ?? '-' }} {{ $course->duration_type ?? '' }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                <p class="  font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Price</p>
                <p class="mt-2 text-slate-900 dark:text-slate-50">{{ number_format($course->price, 2) }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                <p class="  font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Discount Price</p>
                <p class="mt-2 text-slate-900 dark:text-slate-50">{{ $course->discount_price !== null ? number_format($course->discount_price, 2) : '-' }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                <p class="  font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Status</p>
                <p class="mt-2 text-slate-900 dark:text-slate-50">{{ $course->status ? 'Active' : 'Inactive' }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                <p class="  font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Created By</p>
                <p class="mt-2 text-slate-900 dark:text-slate-50">{{ $course->createdBy->name ?? '-' }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-900 sm:col-span-2">
                <h3 class="text-base font-semibold text-slate-900 dark:text-slate-50">Timestamps</h3>
                <dl class="mt-4 space-y-4 text-sm text-slate-700 dark:text-slate-300">
                    <div><dt class="font-medium">Created At</dt><dd>{{ $course->created_at?->format('M d, Y H:i') ?? '-' }}</dd></div>
                    <div><dt class="font-medium">Updated At</dt><dd>{{ $course->updated_at?->format('M d, Y H:i') ?? '-' }}</dd></div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
