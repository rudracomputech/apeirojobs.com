@extends('layouts.backend')
@section('title', 'Courses')

@section('content')
<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
    <div class="max-w-7xl mx-auto">
        @php
            $queryParams = request()->except('page');
        @endphp

        <div class="mb-6 w-full">
            <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-50">Courses</h2>
            <p class="text-sm text-slate-600 dark:text-slate-400">Manage courses, pricing and durations.</p>
    </div>
        <div class="flex flex-wrap items-center gap-6 mb-6">
         

            <form class="max-w-xs" role="search" method="GET" action="{{ route('courses.index') }}">
                <div class="flex items-center gap-2.5 px-3 py-2.5 rounded-md bg-white dark:bg-neutral-800 outline-1 -outline-offset-1 outline-slate-300 dark:outline-neutral-700 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-blue-600">
                    <label for="search" class="sr-only">Search</label>
                    <input type="search" id="search" name="search" placeholder="Search courses..." value="{{ request('search') }}" class="text-sm text-slate-900 dark:text-slate-50 w-full outline-none" />
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
                <a href="{{ route('courses.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-white text-sm font-semibold rounded-md bg-blue-600 hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                    <x-lucide-plus class="h-4 w-4" /> Add Course
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead class="bg-gray-50 border-b border-slate-300 dark:bg-neutral-800 dark:border-neutral-700 text-slate-900 dark:text-slate-50">
                    <tr>
                        <th class="px-3 py-3.5">ID</th>
                        <th class="px-3 py-3.5">Name</th>
                        <th class="px-3 py-3.5">Slug</th>
                        <th class="px-3 py-3.5">Duration</th>
                        <th class="px-3 py-3.5">Price</th>
                        <th class="px-3 py-3.5">Status</th>
                        <th class="px-3 py-3.5">Created By</th>
                        <th class="px-3 py-3.5">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-neutral-700 text-slate-900 dark:text-slate-50">
                    @forelse($courses as $course)
                        <tr>
                            <td class="px-3 py-3.5">{{ $course->id }}</td>
                            <td class="px-3 py-3.5">{{ $course->name }}</td>
                            <td class="px-3 py-3.5">{{ $course->slug }}</td>
                            <td class="px-3 py-3.5">{{ $course->duration ?? '-' }} {{ $course->duration_type ?? '' }}</td>
                            <td class="px-3 py-3.5">{{ number_format($course->price, 2) }}</td>
                            <td class="px-3 py-3.5">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5   font-semibold {{ $course->status ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                    {{ $course->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-3 py-3.5">{{ $course->createdBy->name ?? '-' }}</td>
                            <td class="px-3 py-3.5 space-x-2">
                                <a href="{{ route('courses.edit', $course->id) }}" class="inline-flex items-center gap-1 rounded-md bg-blue-50 px-2 py-1   font-semibold text-blue-700 hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-200">Edit</a>
                                <form action="{{ route('courses.destroy', $course->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this course?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 rounded-md bg-red-50 px-2 py-1   font-semibold text-red-700 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-200">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-3 py-5 text-center text-slate-500 dark:text-slate-400">No courses found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between flex-wrap gap-6 mt-6">
            <div class="text-sm text-slate-600 dark:text-slate-400">Showing <span class="font-medium">{{ $courses->firstItem() ?? 0 }}</span> to <span class="font-medium">{{ $courses->lastItem() ?? 0 }}</span> of <span class="font-medium">{{ $courses->total() }}</span> results</div>
            <div>{{ $courses->links() }}</div>
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
            <form id="filter-form" method="GET" action="{{ route('courses.index') }}" class="flex flex-col space-y-4">
                <input type="hidden" name="limit" value="{{ request('limit', 10) }}" />
                <input type="hidden" name="sort" value="{{ request('sort', 'id') }}" />
                <input type="hidden" name="direction" value="{{ request('direction', 'asc') }}" />
                <div>
                    <label for="search" class="block text-sm font-medium mb-1">Search</label>
                    <input type="text" id="search" name="search" placeholder="Search courses..." value="{{ request('search') }}" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium mb-1">Status</label>
                    <select id="status" name="status" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div>
                    <label for="created_by" class="block text-sm font-medium mb-1">Created By</label>
                    <select id="created_by" name="created_by" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All</option>
                        @foreach($users as $id => $name)
                            <option value="{{ $id }}" {{ request('created_by') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="duration_min" class="block text-sm font-medium mb-1">Min Duration</label>
                        <input type="number" step="1" id="duration_min" name="duration_min" value="{{ request('duration_min') }}" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="duration_max" class="block text-sm font-medium mb-1">Max Duration</label>
                        <input type="number" step="1" id="duration_max" name="duration_max" value="{{ request('duration_max') }}" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="price_min" class="block text-sm font-medium mb-1">Min Price</label>
                        <input type="number" step="0.01" id="price_min" name="price_min" value="{{ request('price_min') }}" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="price_max" class="block text-sm font-medium mb-1">Max Price</label>
                        <input type="number" step="0.01" id="price_max" name="price_max" value="{{ request('price_max') }}" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="border-t pt-4 flex gap-2">
            <button type="button" onclick="document.getElementById('filter-form').submit()" class="flex-1 px-4 py-2 text-white text-sm font-medium rounded-md bg-blue-600 hover:bg-blue-700">
                Apply
            </button>
            <a href="{{ route('courses.index') }}" class="flex-1 px-4 py-2 text-center text-slate-900 text-sm font-medium rounded-md border border-gray-300 hover:bg-gray-50">
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
