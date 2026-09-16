@extends('layouts.backend')
@section('title', isset($payment) ? 'Edit Payment' : 'New Payment')

@section('content')
<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
    @php $payment = $payment ?? null; @endphp
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-slate-900 mb-1 md:text-2xl dark:text-slate-50">{{ isset($payment) ? 'Edit Payment' : 'New Payment' }}</h2>
        <p class="text-base leading-relaxed text-slate-600 dark:text-slate-400">Record a payment against an invoice and student.</p>
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

    <form method="POST" action="{{ isset($payment) ? route('payments.update', $payment->id) : route('payments.store') }}" class="space-y-6">
        @csrf
        @if(isset($payment))
            @method('PUT')
        @endif

        <div class="grid gap-6 lg:grid-cols-2">
            <div>
                <label for="invoice_id" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Invoice</label>
                <select id="invoice_id" name="invoice_id" required class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    <option value="">Select invoice</option>
                    @foreach($invoices as $id => $label)
                        <option value="{{ $id }}"{{ old('invoice_id', optional($payment)->invoice_id ?? '') == $id ? ' selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="student_id" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Student</label>
                <select id="student_id" name="student_id" required class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    <option value="">Select student</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}"{{ old('student_id', optional($payment)->student_id ?? '') == $student->id ? ' selected' : '' }}>{{ $student->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="installment_id" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Installment (optional)</label>
                <select id="installment_id" name="installment_id" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    <option value="">No installment</option>
                    @if(!empty($installments))
                        @foreach($installments as $inst)
                            <option value="{{ $inst->id }}"{{ old('installment_id', optional($payment)->installment_id ?? '') == $inst->id ? ' selected' : '' }}>{{ $inst->due_date?->format('Y-m-d') ?? 'Due' }} — {{ number_format($inst->amount,2) }} ({{ ucfirst($inst->status) }})</option>
                        @endforeach
                    @endif
                </select>
                <p class="mt-2   text-slate-500 dark:text-slate-400">Choose an installment to apply this payment to that installment schedule. Leave blank for a full payment that applies directly to the invoice.</p>
            </div>

            <div>
                <label for="amount" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Amount</label>
                <input type="number" step="0.01" id="amount" name="amount" value="{{ old('amount', optional($payment)->amount ?? '') }}" required class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
                <label for="payment_method" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Payment Method</label>
                <select id="payment_method" name="payment_method" required class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    <option value="">Select method</option>
                    @foreach(['cash','upi','card','bank_transfer','razorpay','stripe'] as $method)
                        <option value="{{ $method }}"{{ old('payment_method', optional($payment)->payment_method ?? '') === $method ? ' selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $method)) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="transaction_id" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Transaction ID</label>
                <input type="text" id="transaction_id" name="transaction_id" value="{{ old('transaction_id', optional($payment)->transaction_id ?? '') }}" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div class="lg:col-span-2">
                <label for="gateway_response" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Remark</label>
                <textarea id="gateway_response" name="gateway_response" rows="4" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">{{ old('gateway_response', optional($payment)->gateway_response ? json_encode($payment->gateway_response, JSON_PRETTY_PRINT) : '') }}</textarea>
            </div>

            <div>
                <label for="payment_date" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Payment Date</label>
                <input type="datetime-local" id="payment_date" name="payment_date" value="{{ old('payment_date', optional($payment)->payment_date ? $payment->payment_date->format('Y-m-d\TH:i') : '') }}" required class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
                <label for="status" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Status</label>
                <select id="status" name="status" required class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    <option value="pending"{{ old('status', optional($payment)->status ?? '') === 'pending' ? ' selected' : '' }}>Pending</option>
                    <option value="success"{{ old('status', optional($payment)->status ?? '') === 'success' ? ' selected' : '' }}>Success</option>
                    <option value="failed"{{ old('status', optional($payment)->status ?? '') === 'failed' ? ' selected' : '' }}>Failed</option>
                    <option value="refunded"{{ old('status', optional($payment)->status ?? '') === 'refunded' ? ' selected' : '' }}>Refunded</option>
                </select>
            </div>
        </div>

        <button type="submit" class="inline-flex items-center justify-center rounded-md bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ isset($payment) ? 'Update Payment' : 'Create Payment' }}</button>
    </form>
</div>
@endsection
