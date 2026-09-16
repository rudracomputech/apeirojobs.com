@extends('layouts.backend')
@section('title', 'Notifications')

@section('content')
<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-50">Notifications</h2>
            <p class="text-sm text-slate-600 dark:text-slate-400">View your latest admin notifications.</p>
        </div>
        <form method="POST" action="{{ route('notifications.markAllRead') }}">
            @csrf
            <button type="submit" class="inline-flex items-center rounded-md bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Mark all as read</button>
        </form>
    </div>

    <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white">
        <table class="min-w-full divide-y divide-slate-200 text-sm text-left text-slate-700">
            <thead class="bg-slate-50 text-slate-900">
                <tr>
                    <th class="px-4 py-3">Notification</th>
                    <th class="px-4 py-3">Type</th>
                    <th class="px-4 py-3">Received</th>
                    <th class="px-4 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                @forelse($notifications as $notification)
                    @php $payload = json_decode($notification->data, true); @endphp
                    <tr class="{{ is_null($notification->read_at) ? 'bg-slate-50 dark:bg-neutral-900' : '' }}">
                        <td class="px-4 py-3">{{ $payload['message'] ?? 'No message' }}</td>
                        <td class="px-4 py-3">{{ ucfirst(str_replace('.', ' ', $notification->type)) }}</td>
                        <td class="px-4 py-3">{{ $notification->created_at?->diffForHumans() ?? '-' }}</td>
                        <td class="px-4 py-3">{{ is_null($notification->read_at) ? 'Unread' : 'Read' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-slate-500">No notifications found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
