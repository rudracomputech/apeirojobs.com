@extends('layouts.backend')
@section('title', 'Payment Details')

@section('content')
<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-50">Payment #{{ $payment->id }}</h1>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-lg border border-slate-200 bg-slate-50 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Payment Information</h2>
            <p><strong>Invoice:</strong> {{ $payment->invoice->invoice_no ?? '—' }}</p>
            <p><strong>Student:</strong> {{ $payment->student->name ?? '—' }}</p>
            <p><strong>Amount:</strong> {{ number_format($payment->amount, 2) }}</p>
            <p><strong>Method:</strong> {{ ucfirst($payment->payment_method) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($payment->status) }}</p>
            <p><strong>Payment Date:</strong> {{ $payment->payment_date?->format('Y-m-d H:i') ?? '—' }}</p>
        </div>

        <div class="rounded-lg border border-slate-200 bg-slate-50 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Transaction Details</h2>
            <p><strong>Transaction ID:</strong> {{ $payment->transaction_id ?? '—' }}</p>
            <p><strong>Created By:</strong> {{ $payment->createdBy->name ?? '—' }}</p>
            <p><strong>Updated By:</strong> {{ $payment->updatedBy->name ?? '—' }}</p>
            <p><strong>Gateway Response:</strong></p>
            <pre class="mt-2 overflow-auto rounded bg-white p-3   text-slate-700">{{ json_encode($payment->gateway_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
        </div>
    </div>
</div>
@endsection
