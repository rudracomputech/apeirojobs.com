@extends('layouts.backend')
@section('title', 'Statuses')

@section('content')
<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-50">Statuses</h2>
            <p class="text-sm text-slate-600 dark:text-slate-400">Manage reusable status values for multiple models.</p>
        </div>
        <a href="{{ route('statuses.create') }}" class="inline-flex items-center rounded-md bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Add Status</a>
    </div>

    <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white">
        <table class="min-w-full divide-y divide-slate-200 text-sm text-left text-slate-700">
            <thead class="bg-slate-50 text-slate-900">
                <tr>
                    <th class="px-4 py-3">Key</th>
                    <th class="px-4 py-3">Label</th>
                    <th class="px-4 py-3">Type</th>
                    <th class="px-4 py-3">Active</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                @forelse($statuses as $status)
                    <tr>
                        <td class="px-4 py-3">{{ $status->key }}</td>
                        <td class="px-4 py-3">{{ $status->label }}</td>
                        <td class="px-4 py-3">{{ ucfirst($status->type ?? 'general') }}</td>
                        <td class="px-4 py-3">{{ $status->active ? 'Yes' : 'No' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('statuses.edit', $status->id) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                <form action="{{ route('statuses.destroy', $status->id) }}" method="POST" onsubmit="return confirm('Delete this status?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-slate-500">No statuses found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
