@extends('layouts.backend')
@section('title', isset($lead) ? 'Edit Lead' : 'Create Lead')

@section('content')

<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-slate-900 mb-1 md:text-2xl dark:text-slate-50">
            {{ isset($lead) ? 'Edit Lead' : 'Create Lead' }}
        </h2>
        <p class="text-base leading-relaxed text-slate-600 dark:text-slate-400">
            Use this form to add or update leads and capture their interest details.
        </p>
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

    <form class="space-y-6" method="POST" action="{{ isset($lead) ? route('leads.update', $lead->id) : route('leads.store') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($lead))
            @method('PUT')
        @endif

        <div class="grid gap-6 lg:grid-cols-2">
    

            <div>
                <label for="name" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $lead->name ?? '') }}"
                    class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
                <label for="email" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $lead->email ?? '') }}"
                    class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
                <label for="mobile" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Mobile</label>
                <input type="text" id="mobile" name="mobile" value="{{ old('mobile', $lead->mobile ?? '') }}"
                    class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
                <label for="location" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Location</label>
                <input type="text" id="location" name="location" value="{{ old('location', $lead->location ?? '') }}"
                    class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
                <label for="city" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">City</label>
                <input type="text" id="city" name="city" value="{{ old('city', $lead->city ?? '') }}"
                    class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
                <label for="state" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">State</label>
                <input type="text" id="state" name="state" value="{{ old('state', $lead->state ?? '') }}"
                    class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div class="lg:col-span-2">
                <label for="address" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Address</label>
                <textarea id="address" name="address" rows="2"
                    class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">{{ old('address', $lead->address ?? '') }}</textarea>
            </div>

            <div>
                <label for="source" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Source</label>
                <input type="text" id="source" name="source" value="{{ old('source', $lead->source ?? '') }}"
                    class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
                <label for="course_interest_id" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Course Interest</label>
                <select data-tags="true" id="course_interest_id" name="course_interest_id"
                    class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    <option value="">Select course</option>
                    @foreach($courses as $id => $name)
                        <option value="{{ $id }}"{{ old('course_interest_id', $lead->course_interest_id ?? '') == $id ? ' selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Status</label>
                <select data-tags="true" id="status" name="status"
                    class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    <option value="">Select status</option>
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}"{{ old('status', $lead->status ?? '') === (string) $value ? ' selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="assigned_to" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Assigned To</label>
                <select data-tags="true" id="assigned_to" name="assigned_to"
                    class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    <option value="">Select user</option>
                    @foreach($users as $id => $name)
                        <option value="{{ $id }}"{{ old('assigned_to', $lead->assigned_to ?? '') == $id ? ' selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="student_id" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Student</label>
                <select data-tags="true" id="student_id" name="student_id"
                    class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    <option value="">Select student</option>
                    @foreach($students as $id => $name)
                        <option value="{{ $id }}"{{ old('student_id', $lead->student_id ?? '') == $id ? ' selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="next_followup_date" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Next Follow Up</label>
                <input type="date" id="next_followup_date" name="next_followup_date" value="{{ old('next_followup_date', isset($lead) && $lead->next_followup_date ? $lead->next_followup_date->format('Y-m-d') : '') }}"
                    class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
                <label for="converted_at" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Converted At</label>
                <input type="datetime-local" id="converted_at" name="converted_at" value="{{ old('converted_at', isset($lead) && $lead->converted_at ? $lead->converted_at->format('Y-m-d\TH:i') : '') }}"
                    class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div class="lg:col-span-2">
                <label for="remarks" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Remarks</label>
                <textarea id="remarks" name="remarks" rows="4"
                    class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">{{ old('remarks', $lead->remarks ?? '') }}</textarea>
            </div>
        </div>

        <button type="submit"
            class="inline-flex items-center justify-center rounded-md bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            {{ isset($lead) ? 'Update Lead' : 'Create Lead' }}
        </button>
    </form>

    @if(isset($lead))
        <div class="mt-10 grid gap-6 lg:grid-cols-2">
            <div class="bg-slate-50 rounded-lg border border-slate-200 p-6 dark:bg-slate-900 dark:border-slate-700">
                <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Add Follow Up</h3>
                <form method="POST" action="{{ route('leads.followups.store', $lead->id) }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="followup_type" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Follow Up Type</label>
                            <select data-tags="true" id="followup_type" name="followup_type" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                                <option value="">Select type</option>
                                <option value="call"{{ old('followup_type') === 'call' ? ' selected' : '' }}>Call</option>
                                <option value="whatsapp"{{ old('followup_type') === 'whatsapp' ? ' selected' : '' }}>WhatsApp</option>
                                <option value="email"{{ old('followup_type') === 'email' ? ' selected' : '' }}>Email</option>
                                <option value="meeting"{{ old('followup_type') === 'meeting' ? ' selected' : '' }}>Meeting</option>
                            </select>
                        </div>

                        <div>
                            <label for="followup_date" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Follow Up Date</label>
                            <input type="datetime-local" id="followup_date" name="followup_date" value="{{ old('followup_date') }}" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
                        </div>

                        <div>
                            <label for="next_followup_date" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Next Follow Up Date</label>
                            <input type="datetime-local" id="next_followup_date" name="next_followup_date" value="{{ old('next_followup_date') }}" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
                        </div>

                        <div>
                            <label for="call_duration" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Call Duration (minutes)</label>
                            <input type="number" id="call_duration" name="call_duration" min="0" value="{{ old('call_duration') }}" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
                        </div>

                        <div>
                            <label for="note" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Note</label>
                            <textarea id="note" name="note" rows="4" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">{{ old('note') }}</textarea>
                        </div>

                        <button type="submit" class="inline-flex items-center justify-center rounded-md bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            Save Follow Up
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-slate-50 rounded-lg border border-slate-200 p-6 dark:bg-slate-900 dark:border-slate-700">
                <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Follow Up History</h3>
                @if($lead->followups->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm text-left dark:divide-slate-700">
                            <thead class="bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                                <tr>
                                    <th class="px-4 py-3 font-medium">Date</th>
                                    <th class="px-4 py-3 font-medium">Type</th>
                                    <th class="px-4 py-3 font-medium">Duration</th>
                                    <th class="px-4 py-3 font-medium">Next Follow Up</th>
                                    <th class="px-4 py-3 font-medium">Note</th>
                                    <th class="px-4 py-3 font-medium">User</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-700 dark:bg-slate-950">
                                @foreach($lead->followups as $followup)
                                    <tr>
                                        <td class="px-4 py-3 text-slate-700 dark:text-slate-200">{{ $followup->followup_date->format('Y-m-d H:i') }}</td>
                                        <td class="px-4 py-3 text-slate-700 dark:text-slate-200">{{ ucfirst($followup->followup_type) }}</td>
                                        <td class="px-4 py-3 text-slate-700 dark:text-slate-200">{{ $followup->call_duration ? $followup->call_duration.' min' : '—' }}</td>
                                        <td class="px-4 py-3 text-slate-700 dark:text-slate-200">{{ $followup->next_followup_date ? $followup->next_followup_date->format('Y-m-d H:i') : '—' }}</td>
                                        <td class="px-4 py-3 text-slate-700 dark:text-slate-200 break-words">{{ $followup->note }}</td>
                                        <td class="px-4 py-3 text-slate-700 dark:text-slate-200">{{ optional($followup->user)->name ?? 'System' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="rounded-lg border border-dashed border-slate-300 bg-white p-6 text-sm text-slate-600 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-300">
                        No follow ups have been added for this lead yet.
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

@endsection

@section('page-js')
@endsection
