@extends('layouts.backend')
@section('title', isset($invoice) ? 'Edit Invoice' : 'Create Invoice')

@section('content')
<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
    <h2 class="text-xl font-semibold mb-4">{{ isset($invoice) ? 'Edit Invoice' : 'Create Invoice' }}</h2>

    @if ($errors->any())
        <div class="mb-4 text-red-600">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ isset($invoice) ? route('invoices.update', $invoice->id) : route('invoices.store') }}">
        @csrf
        @if(isset($invoice)) @method('PUT') @endif

        <div class="grid gap-4 lg:grid-cols-2">
            <div>
                <label class="block mb-1">Invoice No</label>
                <input type="text" name="invoice_no" value="{{ old('invoice_no', $invoice->invoice_no ?? '') }}" class="w-full px-3 py-2 border rounded" />
            </div>

            <div>
                <label class="block mb-1">Student</label>
                <select name="student_id" class="w-full px-3 py-2 border rounded">
                    <option value="">Select student</option>
                    @foreach($students as $s)
                        <option value="{{ $s->id }}"{{ old('student_id', $invoice->student_id ?? '') == $s->id ? ' selected' : '' }}>{{ $s->getNameAttribute() }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1">Total Amount</label>
                <input type="number" step="0.01" name="total_amount" value="{{ old('total_amount', $invoice->total_amount ?? '') }}" class="w-full px-3 py-2 border rounded" />
            </div>

            <div>
                <label class="block mb-1">Discount</label>
                <input type="number" step="0.01" name="discount" value="{{ old('discount', $invoice->discount ?? 0) }}" class="w-full px-3 py-2 border rounded" />
            </div>

            <div>
                <label class="block mb-1">Tax</label>
                <input type="number" step="0.01" name="tax" value="{{ old('tax', $invoice->tax ?? 0) }}" class="w-full px-3 py-2 border rounded" />
            </div>

            <div>
                <label class="block mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border rounded">
                    <option value="due"{{ old('status', $invoice->status ?? 'due') === 'due' ? ' selected' : '' }}>Due</option>
                    <option value="partially_paid"{{ old('status', $invoice->status ?? '') === 'partially_paid' ? ' selected' : '' }}>Partially Paid</option>
                    <option value="paid"{{ old('status', $invoice->status ?? '') === 'paid' ? ' selected' : '' }}>Paid</option>
                    <option value="overdue"{{ old('status', $invoice->status ?? '') === 'overdue' ? ' selected' : '' }}>Overdue</option>
                </select>
            </div>

            <div>
                <label class="block mb-1">Due Date</label>
                <input type="date" name="due_date" value="{{ old('due_date', isset($invoice->due_date) ? $invoice->due_date->format('Y-m-d') : '') }}" class="w-full px-3 py-2 border rounded" />
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">{{ isset($invoice) ? 'Update' : 'Create' }}</button>
        </div>
    </form>
</div>
@endsection
