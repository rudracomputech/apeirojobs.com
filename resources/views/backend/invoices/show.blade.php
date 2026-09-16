@extends('layouts.backend')
@section('title', 'Invoice')

@section('content')
<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
    <div class="flex items-start justify-between mb-4">
        <h2 class="text-xl font-semibold">Invoice {{ $invoice->invoice_no }}</h2>
        <div>
            <button type="button" onclick="printInvoice()" class="inline-flex items-center px-3 py-2 bg-white border rounded shadow-sm text-sm hover:bg-slate-50">
                Print
            </button>
        </div>
    </div>

    <div id="invoice-printable" class="grid grid-cols-2 gap-4">
        <div>
            <p><strong>Student:</strong> {{ $invoice->student?->getNameAttribute() }}</p>
            <p><strong>Total:</strong> {{ number_format($invoice->total_amount, 2) }}</p>
            <p><strong>Discount:</strong> {{ number_format($invoice->discount, 2) }}</p>
            <p><strong>Tax:</strong> {{ number_format($invoice->tax, 2) }}</p>
            <p><strong>Paid:</strong> {{ number_format($invoice->paid_amount, 2) }}</p>
            <p><strong>Due:</strong> {{ number_format($invoice->due_amount, 2) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($invoice->status) }}</p>
        </div>
        <div>
            <p><strong>Due Date:</strong> {{ $invoice->due_date?->format('Y-m-d') ?? '-' }}</p>
            <p><strong>Created:</strong> {{ $invoice->created_at?->format('Y-m-d H:i') }}</p>
            <p><strong>Updated:</strong> {{ $invoice->updated_at?->format('Y-m-d H:i') }}</p>
        </div>
    </div>

    <div class="mt-6">
        <h3 class="font-semibold mb-2">Payments</h3>
        <table class="min-w-full text-sm">
            <thead>
                <tr>
                    <th class="px-3 py-2">ID</th>
                    <th class="px-3 py-2">Amount</th>
                    <th class="px-3 py-2">Method</th>
                    <th class="px-3 py-2">Status</th>
                    <th class="px-3 py-2">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->payments as $p)
                <tr>
                    <td class="px-3 py-2">{{ $p->id }}</td>
                    <td class="px-3 py-2">{{ number_format($p->amount, 2) }}</td>
                    <td class="px-3 py-2">{{ $p->payment_method }}</td>
                    <td class="px-3 py-2">{{ $p->status }}</td>
                    <td class="px-3 py-2">{{ $p->payment_date?->format('Y-m-d H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script>
    function printInvoice() {
        const content = document.getElementById('invoice-printable');
        if (!content) return alert('Nothing to print.');

        const w = window.open('', '_blank', 'width=900,height=700');
        const style = `
            <style>
                body{ font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; padding:20px; color:#111827 }
                .grid { display:grid; grid-template-columns: 1fr 1fr; gap:16px }
                table { width:100%; border-collapse: collapse }
                th, td { padding:8px; border-bottom:1px solid #e5e7eb }
            </style>
        `;

        const invoiceNo = @json($invoice->invoice_no);
        w.document.write('<html><head><title>Invoice ' + invoiceNo + '</title>' + style + '</head><body>');
        w.document.write(content.innerHTML);
        w.document.write('</body></html>');
        w.document.close();
        w.focus();
        setTimeout(function(){
            w.print();
            // w.close();
        }, 300);
    }
</script>
@endsection
