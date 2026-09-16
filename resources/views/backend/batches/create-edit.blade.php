@extends('layouts.backend')
@section('title', isset($batch) ? 'Edit Batch' : 'Create Batch')

@section('content')
<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-slate-900 mb-1 md:text-2xl dark:text-slate-50">{{ isset($batch) ? 'Edit Batch' : 'Create Batch' }}</h2>
        <p class="text-base leading-relaxed text-slate-600 dark:text-slate-400">Add or update batch details for your courses.</p>
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

    <form method="POST" action="{{ isset($batch) ? route('batches.update', $batch->id) : route('batches.store') }}" class="space-y-6">
        @csrf
        @if(isset($batch))
            @method('PUT')
        @endif

        <div class="grid gap-6 lg:grid-cols-2">
            @if(isset($batch))
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">ID</label>
                    <div class="rounded-md border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-slate-200">{{ $batch->id }}</div>
                </div>
            @endif

            <div>
                <label for="course_id" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Course</label>
                <select id="course_id" name="course_id" required class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    <option value="">Select course</option>
                    @foreach($courses as $id => $title)
                        <option value="{{ $id }}"{{ old('course_id', $batch->course_id ?? '') == $id ? ' selected' : '' }}>{{ $title }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="name" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Batch Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $batch->name ?? '') }}" required class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
                <label for="start_date" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Start Date</label>
                <input type="date" id="start_date" name="start_date" value="{{ old('start_date', isset($batch) && $batch->start_date ? $batch->start_date->format('Y-m-d') : '') }}" required class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
                <label for="end_date" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">End Date</label>
                <input type="date" id="end_date" name="end_date" value="{{ old('end_date', isset($batch) && $batch->end_date ? $batch->end_date->format('Y-m-d') : '') }}" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
                <label for="capacity" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Capacity</label>
                <input type="number" id="capacity" name="capacity" value="{{ old('capacity', $batch->capacity ?? '') }}" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
                <label for="instructor_id" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Instructor</label>
                <select id="instructor_id" name="instructor_id" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    <option value="">Select instructor</option>
                    @foreach($users as $id => $name)
                        <option value="{{ $id }}"{{ old('instructor_id', $batch->instructor_id ?? '') == $id ? ' selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Status</label>
                <select id="status" name="status" required class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}"{{ old('status', $batch->status ?? 'upcoming') === $key ? ' selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            @if(isset($batch))
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Created At</label>
                    <div class="rounded-md border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-slate-200">{{ $batch->created_at?->format('M d, Y H:i') ?? '-' }}</div>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Updated At</label>
                    <div class="rounded-md border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-slate-200">{{ $batch->updated_at?->format('M d, Y H:i') ?? '-' }}</div>
                </div>
            @endif
        </div>

        <button type="submit" class="inline-flex items-center justify-center rounded-md bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ isset($batch) ? 'Update Batch' : 'Create Batch' }}</button>
    </form>
</div>
@endsection
