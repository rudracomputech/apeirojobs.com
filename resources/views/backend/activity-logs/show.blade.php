@extends('layouts.backend')
@section('title', 'Activity Entry')

@section('content')
<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-50">Activity #{{ $activity->id }}</h1>
        <p class="text-sm text-slate-600 dark:text-slate-400">Detailed view of the activity log entry.</p>
    </div>

    <div class="">
        <div class="rounded-lg border border-slate-200 bg-slate-50 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Main Details</h2>
            <p><strong>Log Name:</strong> {{ $activity->log_name }}</p>
            <p><strong>Description:</strong> {{ $activity->description }}</p>
            <p><strong>Subject:</strong> {{ class_basename($activity->subject_type) }} {{ $activity->subject_id ?? '—' }}</p>
            <p><strong>Causer:</strong> {{ optional($activity->causer)->name ?? optional($activity->causer)->email ?? 'System' }}</p>
            <p><strong>Event:</strong> {{ ucfirst($activity->event) }}</p>
            <p><strong>Created At:</strong> {{ $activity->created_at?->format('Y-m-d H:i') ?? '—' }}</p>
        </div>

        <div class="rounded-lg border border-slate-200 bg-slate-50 p-6">
    @php
    $changes = $activity->attribute_changes ?? [];
    $old = $changes['old'] ?? [];
    $new = $changes['attributes'] ?? [];
@endphp

@if(count($new))
    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr>
                    <th>Field</th>
                    <th>Old Value</th>
                    <th>New Value</th>
                </tr>
            </thead>
            <tbody>
                @foreach($new as $field => $value)
                    <tr>
                        <td class="font-semibold">{{ Str::headline($field) }}</td>
                        <td class="max-w-md break-words">
                         
                       @if(isset($old[$field]) && ($field === 'updated_at' || $field === 'created_at' || strtotime($old[$field]) !== false))
                                {{ \Carbon\Carbon::parse($old[$field])->format('Y-m-d H:i:s') }}
                            @else
                                {{ $old[$field] ?? '' }}
                            @endif
                        </td>
                        <td class="max-w-md break-words">

                     @if(isset($old[$field]) && ($field === 'updated_at' || $field === 'created_at' || strtotime($old[$field]) !== false))
    {{ \Carbon\Carbon::parse($old[$field])->format('Y-m-d H:i:s') }}
@else
    {{ $old[$field] ?? '' }}
@endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif       
    </div>
    </div>
</div>
@endsection
