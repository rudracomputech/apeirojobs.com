@extends('layouts.backend')
@section('title', isset($course) ? 'Edit Course' : 'Create Course')

@section('content')
<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-slate-900 mb-1 md:text-2xl dark:text-slate-50">{{ isset($course) ? 'Edit Course' : 'Create Course' }}</h2>
        <p class="text-base leading-relaxed text-slate-600 dark:text-slate-400">Use this form to create or update a course.</p>
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

    <form method="POST" action="{{ isset($course) ? route('courses.update', $course->id) : route('courses.store') }}" class="space-y-6">
        @csrf
        @if(isset($course))
            @method('PUT')
        @endif

        <div class="grid gap-6 lg:grid-cols-2">
        

            <div>
                <label for="course_type" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Course Type</label>
                <select id="course_type" name="course_type_id" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    <option value="">Select course type</option>
                    @foreach($courseTypes as $key => $type)
                        <option value="{{ $key }}"{{ old('course_type_id', $course->course_type_id ?? '') === $key ? ' selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="name" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $course->name ?? '') }}" required class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

         

            <div class="lg:col-span-2">
                <label for="description" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Description</label>
                <textarea id="description" name="description" rows="4" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">{{ old('description', $course->description ?? '') }}</textarea>
            </div>

            <div>
                <label for="duration" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Duration</label>
                <input type="number" id="duration" name="duration" value="{{ old('duration', $course->duration ?? '') }}" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
                <label for="duration_type" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Duration Type</label>
                <select id="duration_type" name="duration_type" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    <option value="">Select type</option>
                    <option value="days"{{ old('duration_type', $course->duration_type ?? '') === 'days' ? ' selected' : '' }}>Days</option>
                    <option value="weeks"{{ old('duration_type', $course->duration_type ?? '') === 'weeks' ? ' selected' : '' }}>Weeks</option>
                    <option value="months"{{ old('duration_type', $course->duration_type ?? '') === 'months' ? ' selected' : '' }}>Months</option>
                </select>
            </div>

            <div>
                <label for="price" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Price</label>
                <input type="number" step="0.01" id="price" name="price" value="{{ old('price', $course->price ?? '0.00') }}" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

      

            <div>
                <label for="status" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Status</label>
                <select id="status" name="status" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    <option value="1"{{ old('status', $course->status ?? 1) ? ' selected' : '' }}>Active</option>
                    <option value="0"{{ old('status', $course->status ?? 1) ? '' : ' selected' }}>Inactive</option>
                </select>
            </div>

            <div>
                <label for="created_by" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Created By</label>
                <select id="created_by" name="created_by" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    <option value="">Select user</option>
                    @foreach($users as $id => $name)
                        <option value="{{ $id }}"{{ old('created_by', $course->created_by ?? '') == $id ? ' selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            @if(isset($course))
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Created At</label>
                    <div class="rounded-md border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-slate-200">{{ $course->created_at?->format('M d, Y H:i') ?? '-' }}</div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Updated At</label>
                    <div class="rounded-md border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-slate-200">{{ $course->updated_at?->format('M d, Y H:i') ?? '-' }}</div>
                </div>
            @endif
        </div>

        <button type="submit" class="inline-flex items-center justify-center rounded-md bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ isset($course) ? 'Update Course' : 'Create Course' }}</button>
    </form>
</div>
@endsection
