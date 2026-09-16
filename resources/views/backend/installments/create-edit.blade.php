@extends('layouts.backend')
@section('title', isset($installment) ? 'Edit Installment' : 'New Installment')

@section('content')
<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
    @php $installment = $installment ?? null; @endphp
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-slate-900 mb-1 md:text-2xl dark:text-slate-50">{{ isset($installment) ? 'Edit Installment' : 'New Installment' }}</h2>
        <p class="text-base leading-relaxed text-slate-600 dark:text-slate-400">Create or update an installment record for a student invoice.</p>
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

    <form method="POST" action="{{ isset($installment) ? route('installments.update', $installment->id) : route('installments.store') }}" class="space-y-6">
        @csrf
        @if(isset($installment))
            @method('PUT')
        @endif

        <div class="grid gap-6 lg:grid-cols-2">
            <div>
                <label for="invoice_id" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Invoice</label>
                <select id="invoice_id" name="invoice_id" required class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    <option value="">Select invoice</option>
                    @foreach($invoices as $id => $label)
                        <option value="{{ $id }}"{{ old('invoice_id', optional($installment)->invoice_id ?? '') == $id ? ' selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="student_id" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Student</label>
                <select id="student_id" name="student_id" required class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    <option value="">Select student</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}"{{ old('student_id', optional($installment)->student_id ?? '') == $student->id ? ' selected' : '' }}>{{ $student->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="amount" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Amount</label>
                <input type="number" step="0.01" id="amount" name="amount" value="{{ old('amount', optional($installment)->amount ?? '') }}" required class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>
            <div>
                <label for="paid_amount" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Paid Amount</label>
                <input type="number" step="0.01" id="paid_amount" name="paid_amount" value="{{ old('paid_amount', optional($installment)->paid_amount ?? 0) }}" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
                <label for="due_date" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Due Date</label>
                <input type="date" id="due_date" name="due_date" value="{{ old('due_date', optional($installment)->due_date ? $installment->due_date->format('Y-m-d') : '') }}" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>
            <div>
                <label for="status" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Status</label>
                <select id="status" name="status" required class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    @foreach(['pending', 'partial', 'paid', 'overdue'] as $statusOption)
                        <option value="{{ $statusOption }}"{{ old('status', optional($installment)->status ?? 'pending') === $statusOption ? ' selected' : '' }}>{{ ucfirst($statusOption) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <button type="submit" class="inline-flex items-center justify-center rounded-md bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ isset($installment) ? 'Update Installment' : 'Create Installment' }}</button>
    </form>
</div>
@endsection
