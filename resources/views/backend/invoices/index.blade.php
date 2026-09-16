@extends('layouts.backend')
@section('title', 'Invoices')

@section('content')
<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold">Invoices</h2>
         <div class="flex flex-wrap gap-4 ml-auto">
                    <div class="w-[150px]">
                <select id="limit" name="limit" class="w-full inline-flex items-center gap-2 px-3.5 py-2 text-slate-900 text-sm font-semibold rounded-md cursor-pointer bg-white hover:bg-gray-100 border border-slate-300 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-slate-50 dark:hover:bg-neutral-700">
                    <option value="10"{{ request('limit') == 10 ? ' selected' : '' }}>10 per page</option>
                    <option value="25"{{ request('limit') == 25 ? ' selected' : '' }}>25 per page</option>
                    <option value="50"{{ request('limit') == 50 ? ' selected' : '' }}>50 per page</option>
                    <option value="100"{{ request('limit') == 100 ? ' selected' : '' }}>100 per page</option>
                <option value="all"{{ request('limit') == 'all' ? ' selected' : '' }}>All </option>
                </select>
                </div>

                   <button type="button" id="filterBtn"
                       class="flex items-center gap-2 px-3.5 py-2 text-slate-900 text-sm font-semibold rounded-md cursor-pointer bg-white hover:bg-gray-100 border border-slate-300 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-slate-50 dark:hover:bg-neutral-700">
                      <x-lucide-funnel class="size-4" />
                       Filter</button>
        <a href="{{ route('invoices.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded">Create Invoice</a>
    </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-100 text-slate-700">
                <tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Invoice No</th>
                    <th class="px-4 py-2">Student</th>
                    <th class="px-4 py-2">Total</th>
                    <th class="px-4 py-2">Paid</th>
                    <th class="px-4 py-2">Due</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-100">
                @foreach($invoices as $invoice)
                <tr>
                    <td class="px-4 py-2">{{ $invoice->id }}</td>
                    <td class="px-4 py-2">{{ $invoice->invoice_no }}</td>
                    <td class="px-4 py-2">{{ $invoice->student?->getNameAttribute() ?? $invoice->student?->first_name }}</td>
                    <td class="px-4 py-2">{{ number_format($invoice->total_amount, 2) }}</td>
                    <td class="px-4 py-2">{{ number_format($invoice->paid_amount, 2) }}</td>
                    <td class="px-4 py-2">{{ number_format($invoice->due_amount, 2) }}</td>
                    <td class="px-4 py-2">{{ ucfirst($invoice->status) }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ route('invoices.show', $invoice->id) }}" class="text-blue-600">View</a>
                        <a href="{{ route('invoices.edit', $invoice->id) }}" class="ml-3 text-amber-600">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $invoices->links() }}</div>
</div>
@endsection


@section('page-js')
<!-- Backdrop Overlay (Hidden by default via pointer-events-none and opacity-0) -->
<div id="canvas-backdrop" class="fixed inset-0 bg-black/50 z-40 opacity-0 pointer-events-none transition-opacity duration-300 ease-in-out"></div>

<!-- Offcanvas Panel (Shifted completely off-screen to the right using translate-x-full) -->
<div id="offcanvas-right" class="overflow-y-scroll fixed top-0 right-0 z-50 h-full w-100 bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out p-6 flex flex-col justify-between">
    <div>
        <!-- Header -->
        <div class="flex items-center justify-between border-b pb-4 mb-4">
            <h5 class="text-xl font-semibold text-gray-800">Filter</h5>
            <button id="close-canvas" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <!-- Body Content -->
        <div class="space-y-4 text-gray-600">
            <form id="filter-form" method="GET" action="{{ route('invoices.index') }}" class="flex flex-col space-y-4">
                <input type="hidden" name="limit" value="{{ request('limit', 10) }}" />
                <input type="hidden" name="sort" value="{{ request('sort', 'id') }}" />
                <input type="hidden" name="direction" value="{{ request('direction', 'asc') }}" />
                <div>
                    <label for="search" class="block text-sm font-medium mb-1">Search</label>
                    <input type="text" id="search" name="search" placeholder="Search invoices..." value="{{ request('search') }}" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="border-t pt-4 flex gap-2">
            <button type="button" onclick="document.getElementById('filter-form').submit()" class="flex-1 px-4 py-2 text-white text-sm font-medium rounded-md bg-blue-600 hover:bg-blue-700">
                Apply
            </button>
            <a href="{{ route('invoices.index') }}" class="flex-1 px-4 py-2 text-center text-slate-900 text-sm font-medium rounded-md border border-gray-300 hover:bg-gray-50">
                Reset
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterBtn = document.getElementById('filterBtn');
        const offcanvas = document.getElementById('offcanvas-right');
        const backdrop = document.getElementById('canvas-backdrop');
        const closeCanvas = document.getElementById('close-canvas');

        const openCanvas = () => {
            offcanvas.classList.remove('translate-x-full');
            backdrop.classList.remove('opacity-0', 'pointer-events-none');
        };
        const closeOffcanvas = () => {
            offcanvas.classList.add('translate-x-full');
            backdrop.classList.add('opacity-0', 'pointer-events-none');
        };

        filterBtn?.addEventListener('click', openCanvas);
        closeCanvas?.addEventListener('click', closeOffcanvas);
        backdrop?.addEventListener('click', closeOffcanvas);
    });
</script>
@endsection
