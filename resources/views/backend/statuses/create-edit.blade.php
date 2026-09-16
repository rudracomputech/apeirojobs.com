@extends('layouts.backend')
@section('title', isset($status) && $status->exists ? 'Edit Status' : 'Create Status')

@section('content')
<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-slate-900 mb-1 md:text-2xl dark:text-slate-50">{{ isset($status) && $status->exists ? 'Edit Status' : 'Create Status' }}</h2>
        <p class="text-base leading-relaxed text-slate-600 dark:text-slate-400">Create or update a reusable status option for your application.</p>
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

    <form method="POST" action="{{ isset($status) && $status->exists ? route('statuses.update', $status->id) : route('statuses.store') }}" class="space-y-6">
        @csrf
        @if(isset($status) && $status->exists)
            @method('PUT')
        @endif

        <div class="grid gap-6 lg:grid-cols-2">
            <div>
                <label for="key" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Key</label>
                <input id="key" name="key" value="{{ old('key', $status->key ?? '') }}" required class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
                <label for="label" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Label</label>
                <input id="label" name="label" value="{{ old('label', $status->label ?? '') }}" required class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
                <label for="type" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Type</label>
                <select id="type" name="type" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    <option value="">General</option>
                    @foreach($types as $key => $label)
                        <option value="{{ $key }}"{{ old('type', $status->type ?? '') === $key ? ' selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="active" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Active</label>
                <select id="active" name="active" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    <option value="1"{{ old('active', $status->active ?? true) ? ' selected' : '' }}>Yes</option>
                    <option value="0"{{ ! old('active', $status->active ?? true) ? ' selected' : '' }}>No</option>
                </select>
            </div>

            <div class="lg:col-span-2">
                <label for="description" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Description</label>
                <textarea id="description" name="description" rows="4" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">{{ old('description', $status->description ?? '') }}</textarea>
            </div>
        </div>

        <button type="submit" class="inline-flex items-center justify-center rounded-md bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ isset($status) && $status->exists ? 'Update Status' : 'Create Status' }}</button>
    </form>
</div>
@endsection
