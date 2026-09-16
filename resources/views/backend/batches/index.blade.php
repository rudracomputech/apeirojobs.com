@extends('layouts.backend')
@section('title', 'Batches')

@section('content')
<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
    <div class="max-w-7xl mx-auto">
    <div class="mb-6 w-full">
                <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-50">Batches</h2>
                <p class="text-sm text-slate-600 dark:text-slate-400">Manage batch schedules, instructors, and capacity.</p>
            </div>   
    @php
        $queryParams = request()->except('page');
    @endphp

    <div class="flex flex-wrap items-center gap-6 mb-6">
            

            <form class="max-w-xs" role="search" method="GET" action="{{ route('batches.index') }}">
                @foreach($queryParams as $key => $value)
                    @if($key !== 'search')
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
                <div class="flex items-center gap-2.5 px-3 py-2.5 rounded-md bg-white dark:bg-neutral-800 outline-1 -outline-offset-1 outline-slate-300 dark:outline-neutral-700 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-blue-600">
                    <label for="search" class="sr-only">Search</label>
                    <input type="search" id="search" name="search" placeholder="Search batches..." value="{{ request('search') }}" class="text-sm text-slate-900 dark:text-slate-50 w-full outline-none" />
                    <button type="submit" class="flex-shrink-0 hover:opacity-75">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192.904 192.904" class="size-4 fill-slate-400 ml-auto" aria-hidden="true">
                            <path d="m190.707 180.101-47.078-47.077c11.702-14.072 18.752-32.142 18.752-51.831C162.381 36.423 125.959 0 81.191 0 36.422 0 0 36.423 0 81.193c0 44.767 36.422 81.187 81.191 81.187 19.688 0 37.759-7.049 51.831-18.751l47.079 47.078a7.474 7.474 0 0 0 5.303 2.197 7.498 7.498 0 0 0 5.303-12.803zM15 81.193C15 44.694 44.693 15 81.191 15c36.497 0 66.189 29.694 66.189 66.193 0 36.496-29.692 66.187-66.189 66.187C44.693 147.38 15 117.689 15 81.193z" />
                        </svg>
                    </button>
                </div>
            </form>

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
                <a href="{{ route('batches.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-white text-sm font-semibold rounded-md bg-blue-600 hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                    <x-lucide-plus class="h-4 w-4" /> Add Batch
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead class="bg-gray-50 border-b border-slate-300 dark:bg-neutral-800 dark:border-neutral-700 text-slate-900 dark:text-slate-50">
                    <tr>
                        <th class="px-3 py-3.5">ID</th>
                        <th class="px-3 py-3.5">Batch Name</th>
                        <th class="px-3 py-3.5">Course</th>
                        <th class="px-3 py-3.5">Instructor</th>
                        <th class="px-3 py-3.5">Start Date</th>
                        <th class="px-3 py-3.5">End Date</th>
                        <th class="px-3 py-3.5">Capacity</th>
                        <th class="px-3 py-3.5">Status</th>
                        <th class="px-3 py-3.5">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-neutral-700 text-slate-900 dark:text-slate-50">
                    @forelse($batches as $batch)
                        <tr>
                            <td class="px-3 py-3.5">{{ $batch->id }}</td>
                            <td class="px-3 py-3.5">{{ $batch->name }}</td>
                            <td class="px-3 py-3.5">{{ $batch->course->name ?? '-' }}</td>
                            <td class="px-3 py-3.5">{{ $batch->instructor->name ?? '-' }}</td>
                            <td class="px-3 py-3.5">{{ $batch->start_date?->format('M d, Y') }}</td>
                            <td class="px-3 py-3.5">{{ $batch->end_date?->format('M d, Y') ?? '-' }}</td>
                            <td class="px-3 py-3.5">{{ $batch->capacity ?? '-' }}</td>
                            <td class="px-3 py-3.5"><span class="inline-flex items-center rounded-full px-2.5 py-0.5   font-semibold {{ $batch->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : ($batch->status === 'completed' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : ($batch->status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-slate-100 text-slate-800 dark:bg-neutral-700 dark:text-neutral-200')) }}">{{ ucfirst($batch->status) }}</span></td>
                            <td class="px-3 py-3.5 space-x-2">
                                <a href="{{ route('batches.edit', $batch->id) }}" class="inline-flex items-center gap-1 rounded-md bg-blue-50 px-2 py-1   font-semibold text-blue-700 hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-200">Edit</a>
                                <form action="{{ route('batches.destroy', $batch->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this batch?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 rounded-md bg-red-50 px-2 py-1   font-semibold text-red-700 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-200">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-3 py-5 text-center text-slate-500 dark:text-slate-400">No batches found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between flex-wrap gap-6 mt-6">
            <div class="text-sm text-slate-600 dark:text-slate-400">Showing <span class="font-medium">{{ $batches->firstItem() ?? 0 }}</span> to <span class="font-medium">{{ $batches->lastItem() ?? 0 }}</span> of <span class="font-medium">{{ $batches->total() }}</span> results</div>
            <div>{{ $batches->links() }}</div>
        </div>
    </div>
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
            <form id="filter-form" method="GET" action="{{ route('batches.index') }}" class="flex flex-col space-y-2">
                @foreach($queryParams as $key => $value)
                    @if($key !== 'page')
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
                <nav class="flex flex-col space-y-4">
                    <div>
                        <label for="search" class="block text-sm font-medium mb-1">Search</label>
                        <input type="text" id="search" name="search" placeholder="Search batches..." value="{{ request('search') }}" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="course" class="block text-sm font-medium mb-1">Course</label>
                        <select id="course" name="course" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All</option>
                            @foreach($courses as $key => $title)
                                <option value="{{ $key }}" {{ request('course') == $key ? 'selected' : '' }}>{{ $title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="instructor" class="block text-sm font-medium mb-1">Instructor</label>
                        <select id="instructor" name="instructor" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All</option>
                            @foreach($users as $key => $name)
                                <option value="{{ $key }}" {{ request('instructor') == $key ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium mb-1">Status</label>
                        <select id="status" name="status" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All</option>
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_date_from" class="block text-sm font-medium mb-1">Start Date From</label>
                            <input type="date" id="start_date_from" name="start_date_from" value="{{ request('start_date_from') }}" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="start_date_to" class="block text-sm font-medium mb-1">Start Date To</label>
                            <input type="date" id="start_date_to" name="start_date_to" value="{{ request('start_date_to') }}" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="end_date_from" class="block text-sm font-medium mb-1">End Date From</label>
                            <input type="date" id="end_date_from" name="end_date_from" value="{{ request('end_date_from') }}" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="end_date_to" class="block text-sm font-medium mb-1">End Date To</label>
                            <input type="date" id="end_date_to" name="end_date_to" value="{{ request('end_date_to') }}" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="capacity_min" class="block text-sm font-medium mb-1">Capacity Min</label>
                            <input type="number" id="capacity_min" name="capacity_min" value="{{ request('capacity_min') }}" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="capacity_max" class="block text-sm font-medium mb-1">Capacity Max</label>
                            <input type="number" id="capacity_max" name="capacity_max" value="{{ request('capacity_max') }}" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </nav>
            </form>
        </div>

        <!-- Footer -->
        <div class="border-t pt-4 flex gap-2">
            <button type="button" onclick="document.getElementById('filter-form').submit()" class="flex-1 px-4 py-2 text-white text-sm font-medium rounded-md bg-blue-600 hover:bg-blue-700">
                Apply
            </button>
            <a href="{{ route('batches.index') }}" class="flex-1 px-4 py-2 text-center text-slate-900 text-sm font-medium rounded-md border border-gray-300 hover:bg-gray-50">
                Reset
            </a>
        </div>
    </div>
</div>

<script>
    const openBtn = document.getElementById('filterBtn');
    const closeBtn = document.getElementById('close-canvas');
    const backdrop = document.getElementById('canvas-backdrop');
    const offcanvas = document.getElementById('offcanvas-right');

    function openOffcanvas() {
        offcanvas.classList.remove('translate-x-full');
        offcanvas.classList.add('translate-x-0');
        backdrop.classList.remove('opacity-0', 'pointer-events-none');
        backdrop.classList.add('opacity-100', 'pointer-events-auto');
        document.body.classList.add('overflow-hidden');
    }

    function closeOffcanvas() {
        offcanvas.classList.remove('translate-x-0');
        offcanvas.classList.add('translate-x-full');
        backdrop.classList.remove('opacity-100', 'pointer-events-auto');
        backdrop.classList.add('opacity-0', 'pointer-events-none');
        document.body.classList.remove('overflow-hidden');
    }

    openBtn?.addEventListener('click', openOffcanvas);
    closeBtn?.addEventListener('click', closeOffcanvas);
    backdrop?.addEventListener('click', closeOffcanvas);

    document.getElementById('limit')?.addEventListener('change', function() {
        const url = new URL(window.location.href);
        url.searchParams.set('limit', this.value);
        url.searchParams.delete('page');
        window.location.href = url.toString();
    });
</script>
@endsection
