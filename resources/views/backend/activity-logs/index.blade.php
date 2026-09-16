@extends('layouts.backend')
@section('title', 'Activity Log')

@section('content')
<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-50">Activity Log</h2>
            <p class="text-sm text-slate-600 dark:text-slate-400">View application activity logged in the {{ config('activitylog.table_name') }} table.</p>
        </div>
        <form method="GET" action="{{ route('activity-logs.index') }}" class="flex gap-2">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search logs" class="rounded-md border border-slate-200 bg-white px-4 py-2 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200" />
            <button type="submit" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Search</button>
        </form>
    </div>

    <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white">
        <table class="min-w-full divide-y divide-slate-200 text-sm text-left text-slate-700">
            <thead class="bg-slate-50 text-slate-900">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Log Name</th>
                    <th class="px-4 py-3">Description</th>
                    <th class="px-4 py-3">Subject</th>
                    <th class="px-4 py-3">Causer</th>
                    <th class="px-4 py-3">Created At</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                @forelse ($activityLogs as $log)
                    <tr>
                        <td class="px-4 py-3">{{ $log->id }}</td>
                        <td class="px-4 py-3">{{ $log->log_name }}</td>
                        <td class="px-4 py-3">{{ $log->description }}</td>
                        <td class="px-4 py-3">{{ class_basename($log->subject_type) }} {{ $log->subject_id ?? '' }}</td>
                        <td class="px-4 py-3">{{ optional($log->causer)->name ?? optional($log->causer)->email ?? 'System' }}</td>
                        <td class="px-4 py-3">{{ $log->created_at?->format('Y-m-d H:i') ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('activity-logs.show', $log->id) }}" class="text-blue-600 hover:text-blue-800">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-slate-500">No activity log entries found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $activityLogs->links() }}
    </div>
</div>
@endsection
