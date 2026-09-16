@extends('layouts.backend')
@section('title', 'Edit Client')

@section('content')

<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
    <div class="mx-auto">
        <div class="mb-6">
            <a href="{{ route('clients.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 text-sm font-medium mb-4">
                <x-lucide-arrow-left class="h-4 w-4" />Back to Clients
            </a>
            <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-50">Edit Client</h2>
            <p class="text-sm text-slate-600 dark:text-slate-400">Update client information and details.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4 dark:bg-red-900/20 dark:border-red-900/40">
                <h3 class="text-sm font-medium text-red-800 dark:text-red-200 mb-2">Please fix the following errors:</h3>
                <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-300">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('clients.update', $client) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Date & Client Name Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="date" class="block text-sm font-medium text-slate-900 dark:text-slate-50 mb-2">Date</label>
                    <input type="date" id="date" name="date" value="{{ old('date', $client->date?->format('Y-m-d')) }}" class="w-full px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    @error('date')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="client_name" class="block text-sm font-medium text-slate-900 dark:text-slate-50 mb-2">Client Name <span class="text-red-600">*</span></label>
                    <input type="text" id="client_name" name="client_name" value="{{ old('client_name', $client->client_name) }}" required class="w-full px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    @error('client_name')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Contact Person & Contact Number -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="contact_person_name" class="block text-sm font-medium text-slate-900 dark:text-slate-50 mb-2">Contact Person Name</label>
                    <input type="text" id="contact_person_name" name="contact_person_name" value="{{ old('contact_person_name', $client->contact_person_name) }}" class="w-full px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    @error('contact_person_name')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="contact_number" class="block text-sm font-medium text-slate-900 dark:text-slate-50 mb-2">Contact Number</label>
                    <input type="text" id="contact_number" name="contact_number" value="{{ old('contact_number', $client->contact_number) }}" class="w-full px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    @error('contact_number')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Mobile & Email -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="mobile_no" class="block text-sm font-medium text-slate-900 dark:text-slate-50 mb-2">Mobile No.</label>
                    <input type="text" id="mobile_no" name="mobile_no" value="{{ old('mobile_no', $client->mobile_no) }}" class="w-full px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    @error('mobile_no')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email_id" class="block text-sm font-medium text-slate-900 dark:text-slate-50 mb-2">Email</label>
                    <input type="email" id="email_id" name="email_id" value="{{ old('email_id', $client->email_id) }}" class="w-full px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    @error('email_id')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Area, City, State -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="area" class="block text-sm font-medium text-slate-900 dark:text-slate-50 mb-2">Area</label>
                    <input type="text" id="area" name="area" value="{{ old('area', $client->area) }}" class="w-full px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    @error('area')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="city" class="block text-sm font-medium text-slate-900 dark:text-slate-50 mb-2">City</label>
                    <input type="text" id="city" name="city" value="{{ old('city', $client->city) }}" class="w-full px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    @error('city')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="state" class="block text-sm font-medium text-slate-900 dark:text-slate-50 mb-2">State</label>
                    <input type="text" id="state" name="state" value="{{ old('state', $client->state) }}" class="w-full px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    @error('state')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Client Details & Feedback -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="client_details" class="block text-sm font-medium text-slate-900 dark:text-slate-50 mb-2">Client Details</label>
                    <textarea id="client_details" name="client_details" rows="4" class="w-full px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">{{ old('client_details', $client->client_details) }}</textarea>
                    @error('client_details')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="feedback" class="block text-sm font-medium text-slate-900 dark:text-slate-50 mb-2">Feedback</label>
                    <textarea id="feedback" name="feedback" rows="4" class="w-full px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">{{ old('feedback', $client->feedback) }}</textarea>
                    @error('feedback')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status Fields -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="calling_status" class="block text-sm font-medium text-slate-900 dark:text-slate-50 mb-2">Calling Status</label>
                    <input type="text" id="calling_status" name="calling_status" value="{{ old('calling_status', $client->calling_status) }}" placeholder="e.g., Contacted, Pending" class="w-full px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    @error('calling_status')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="vacancy_status" class="block text-sm font-medium text-slate-900 dark:text-slate-50 mb-2">Vacancy Status</label>
                    <input type="text" id="vacancy_status" name="vacancy_status" value="{{ old('vacancy_status', $client->vacancy_status) }}" placeholder="e.g., Open, Closed" class="w-full px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    @error('vacancy_status')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="requirement_status" class="block text-sm font-medium text-slate-900 dark:text-slate-50 mb-2">Requirement Status</label>
                    <input type="text" id="requirement_status" name="requirement_status" value="{{ old('requirement_status', $client->requirement_status) }}" placeholder="e.g., Active, Inactive" class="w-full px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    @error('requirement_status')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Proposal, Empannel, Internship Payment -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="proposal_status" class="block text-sm font-medium text-slate-900 dark:text-slate-50 mb-2">Proposal Status</label>
                    <input type="text" id="proposal_status" name="proposal_status" value="{{ old('proposal_status', $client->proposal_status) }}" placeholder="e.g., Sent, Accepted" class="w-full px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    @error('proposal_status')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="empannel" class="block text-sm font-medium text-slate-900 dark:text-slate-50 mb-2">Empannel</label>
                    <input type="text" id="empannel" name="empannel" value="{{ old('empannel', $client->empannel) }}" placeholder="e.g., Yes, No" class="w-full px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    @error('empannel')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="internship_payment" class="block text-sm font-medium text-slate-900 dark:text-slate-50 mb-2">Internship Payment</label>
                    <input type="text" id="internship_payment" name="internship_payment" value="{{ old('internship_payment', $client->internship_payment) }}" placeholder="e.g., Paid, Unpaid" class="w-full px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    @error('internship_payment')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Final Remark -->
            <div>
                <label for="final_remark" class="block text-sm font-medium text-slate-900 dark:text-slate-50 mb-2">Final Remark</label>
                <textarea id="final_remark" name="final_remark" rows="4" class="w-full px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">{{ old('final_remark', $client->final_remark) }}</textarea>
                @error('final_remark')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex gap-3 justify-end pt-6 border-t border-slate-300 dark:border-neutral-700">
                <a href="{{ route('clients.index') }}" class="px-4 py-2.5 text-slate-900 text-sm font-semibold rounded-md border border-slate-300 bg-white hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:text-slate-50 dark:bg-neutral-700 dark:border-neutral-600 dark:hover:bg-neutral-600">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2.5 text-white text-sm font-semibold rounded-md bg-blue-600 hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                    <span class="flex items-center gap-2">
                        <x-lucide-save class="h-4 w-4" />
                        Update Client
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
