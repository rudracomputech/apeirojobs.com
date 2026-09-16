@extends('layouts.backend')
@section('title', 'Installment Details')

@section('content')
<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-50">Installment #{{ $installment->id }}</h2>
            <p class="text-sm text-slate-600 dark:text-slate-400">Invoice installment details and linked payment history.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('installments.edit', $installment->id) }}" class="inline-flex items-center rounded-md bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Edit</a>
            <a href="{{ route('installments.index') }}" class="inline-flex items-center rounded-md border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-900 hover:bg-slate-50">Back</a>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        <div class="rounded-lg border border-slate-200 p-4">
            <p class="text-xs font-semibold uppercase text-slate-500">Invoice</p>
            <p class="mt-2 text-base font-semibold text-slate-900">{{ $installment->invoice->invoice_no ?? '-' }}</p>
        </div>
        <div class="rounded-lg border border-slate-200 p-4">
            <p class="text-xs font-semibold uppercase text-slate-500">Student</p>
            <p class="mt-2 text-base font-semibold text-slate-900">{{ $installment->student->name ?? $installment->student->first_name ?? '-' }}</p>
        </div>
        <div class="rounded-lg border border-slate-200 p-4">
            <p class="text-xs font-semibold uppercase text-slate-500">Status</p>
            <p class="mt-2 text-base font-semibold text-slate-900">{{ ucfirst($installment->status) }}</p>
        </div>
        <div class="rounded-lg border border-slate-200 p-4">
            <p class="text-xs font-semibold uppercase text-slate-500">Amount</p>
            <p class="mt-2 text-base font-semibold text-slate-900">{{ number_format($installment->amount, 2) }}</p>
        </div>
        <div class="rounded-lg border border-slate-200 p-4">
            <p class="text-xs font-semibold uppercase text-slate-500">Paid Amount</p>
            <p class="mt-2 text-base font-semibold text-slate-900">{{ number_format($installment->paid_amount ?? 0, 2) }}</p>
        </div>
        <div class="rounded-lg border border-slate-200 p-4">
            <p class="text-xs font-semibold uppercase text-slate-500">Due Date</p>
            <p class="mt-2 text-base font-semibold text-slate-900">{{ $installment->due_date?->format('Y-m-d') ?? '-' }}</p>
        </div>
    </div>

    <div class="mt-8">
        <h3 class="mb-4 text-lg font-semibold text-slate-900">Linked Payments</h3>
        <div class="overflow-x-auto rounded-lg border border-slate-200">
            <table class="min-w-full divide-y divide-slate-200 text-sm text-left text-slate-700">
                <thead class="bg-slate-50 text-slate-900">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Amount</th>
                        <th class="px-4 py-3">Method</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($installment->payments as $payment)
                        <tr>
                            <td class="px-4 py-3">{{ $payment->id }}</td>
                            <td class="px-4 py-3">{{ number_format($payment->amount, 2) }}</td>
                            <td class="px-4 py-3">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                            <td class="px-4 py-3">{{ $payment->payment_date?->format('Y-m-d H:i') ?? '-' }}</td>
                            <td class="px-4 py-3">{{ ucfirst($payment->status) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-slate-500">No linked payments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
