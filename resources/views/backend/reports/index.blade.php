@extends('layouts.backend')
@section('title', $title)

@section('content')
<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-50">{{ $title }}</h2>
            <p class="text-sm text-slate-600 dark:text-slate-400">View CRM payment {{ strtolower(str_replace('Report', '', $title)) }} history and totals.</p>
        </div>

        <form method="GET" action="{{ url()->current() }}" class="flex gap-2">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search payments" class="rounded-md border border-slate-200 bg-white px-4 py-2 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200" />
            <button type="submit" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Search</button>
        </form>
    </div>

    <div class="grid gap-4 md:grid-cols-3 mb-8">
        <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
            <p class="  uppercase tracking-wide text-slate-500">Total {{ strtolower(str_replace('Report', '', $title)) }}</p>
            <p class="mt-2 text-3xl font-semibold text-slate-900">{{ number_format($totalAmount, 2) }}</p>
        </div>
        <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
            <p class="  uppercase tracking-wide text-slate-500">Transactions</p>
            <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $totalCount }}</p>
        </div>
        <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
            <p class="  uppercase tracking-wide text-slate-500">Status</p>
            <p class="mt-2 text-3xl font-semibold text-slate-900">{{ ucfirst($status) }}</p>
        </div>
    </div>

    <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white">
        <table class="min-w-full divide-y divide-slate-200 text-sm text-left text-slate-700">
            <thead class="bg-slate-50 text-slate-900">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Invoice</th>
                    <th class="px-4 py-3">Student</th>
                    <th class="px-4 py-3">Amount</th>
                    <th class="px-4 py-3">Method</th>
                    <th class="px-4 py-3">Payment Date</th>
                    <th class="px-4 py-3">Transaction ID</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                @forelse ($payments as $payment)
                    <tr>
                        <td class="px-4 py-3">{{ $payment->id }}</td>
                        <td class="px-4 py-3">{{ $payment->invoice->invoice_no ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $payment->student->name ?? $payment->student->first_name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ number_format($payment->amount, 2) }}</td>
                        <td class="px-4 py-3">{{ ucfirst($payment->payment_method) }}</td>
                        <td class="px-4 py-3">{{ $payment->payment_date?->format('Y-m-d H:i') ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $payment->transaction_id ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('payments.show', $payment->id) }}" class="text-blue-600 hover:text-blue-800">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-slate-500">No payments found for this report.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $payments->links() }}
    </div>
</div>
@endsection
