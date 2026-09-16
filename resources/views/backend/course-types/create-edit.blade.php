@extends('layouts.backend')
@section('title', isset($courseType) && $courseType->exists ? 'Edit Course Type' : 'Create Course Type')

@section('content')
<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-slate-900 mb-1 md:text-2xl dark:text-slate-50">{{ isset($courseType) && $courseType->exists ? 'Edit Course Type' : 'Create Course Type' }}</h2>
        <p class="text-base leading-relaxed text-slate-600 dark:text-slate-400">Create or update a course type record used by courses.</p>
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

    <form method="POST" action="{{ isset($courseType) && $courseType->exists ? route('course-types.update', $courseType->id) : route('course-types.store') }}" class="space-y-6">
        @csrf
        @if(isset($courseType) && $courseType->exists)
            @method('PUT')
        @endif

        <div class="grid gap-6 lg:grid-cols-2">
            <div>
                <label for="name" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Name</label>
                <input id="name" name="name" value="{{ old('name', $courseType->name ?? '') }}" required class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
                <label for="status" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Status</label>
                <select id="status" name="status" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    <option value="1"{{ old('status', $courseType->status ?? true) ? ' selected' : '' }}>Active</option>
                    <option value="0"{{ ! old('status', $courseType->status ?? true) ? ' selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        <button type="submit" class="inline-flex items-center justify-center rounded-md bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ isset($courseType) && $courseType->exists ? 'Update Course Type' : 'Create Course Type' }}</button>
    </form>
</div>
@endsection
