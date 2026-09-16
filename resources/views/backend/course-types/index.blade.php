@extends('layouts.backend')
@section('title', 'Course Types')

@section('content')
<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-50">Course Types</h2>
            <p class="text-sm text-slate-600 dark:text-slate-400">Manage course types for the course creation form.</p>
        </div>
        <a href="{{ route('course-types.create') }}" class="inline-flex items-center rounded-md bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Add Course Type</a>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded border border-green-200 bg-green-50 p-4 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white">
        <table class="min-w-full divide-y divide-slate-200 text-sm text-left text-slate-700">
            <thead class="bg-slate-50 text-slate-900">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Created</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                @forelse($courseTypes as $courseType)
                    <tr>
                        <td class="px-4 py-3">{{ $courseType->name }}</td>
                        <td class="px-4 py-3">{{ $courseType->status ? 'Active' : 'Inactive' }}</td>
                        <td class="px-4 py-3">{{ $courseType->created_at?->format('M d, Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('course-types.edit', $courseType->id) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                <form action="{{ route('course-types.destroy', $courseType->id) }}" method="POST" onsubmit="return confirm('Delete this course type?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-slate-500">No course types found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
