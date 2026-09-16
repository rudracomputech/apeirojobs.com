@extends('layouts.backend')
@section('title', 'Leads')

@section('content')

<div class=" bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
    <div class=" mx-auto">
        @php
        $currentSort = request('sort', 'id');
        $currentDirection = request('direction', 'asc');
        $queryParams = request()->except('page');
        $nextDirection = fn($column) => $currentSort === $column && $currentDirection === 'asc' ? 'desc' : 'asc';
        @endphp
        <div class="mb-6 w-full">
            <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-50">Leads</h2>
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-6">Manage your leads, follow-up dates, and conversion status.</p>
            <a href="/sample_upload_sheet.xlsx" download class="inline-flex items-center gap-2 px-3.5 py-2 text-white text-sm font-semibold rounded-md cursor-pointer bg-blue-600 hover:bg-blue-700">Download Sample Import file</a>
        </div>
        <div class="flex flex-wrap items-center gap-6 mb-6">
            <form class="max-w-xs" role="search" method="GET" action="{{ route('leads.index') }}">
                <div class="flex items-center gap-2.5 px-3 py-2.5 rounded-md bg-white dark:bg-neutral-800 outline-1 -outline-offset-1 outline-slate-300 dark:outline-neutral-700 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-blue-600">
                    <label for="search" class="sr-only">Search</label>
                    <input type="search" id="search" name="search" placeholder="Search leads..." value="{{ request('search') }}"
                        class="text-sm text-slate-900 dark:text-slate-50 w-full outline-none" />
                    <button type="submit" class="flex-shrink-0 hover:opacity-75">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192.904 192.904" class="size-4 fill-slate-400 ml-auto" aria-hidden="true">
                            <path d="m190.707 180.101-47.078-47.077c11.702-14.072 18.752-32.142 18.752-51.831C162.381 36.423 125.959 0 81.191 0 36.422 0 0 36.423 0 81.193c0 44.767 36.422 81.187 81.191 81.187 19.688 0 37.759-7.049 51.831-18.751l47.079 47.078a7.474 7.474 0 0 0 5.303 2.197 7.498 7.498 0 0 0 5.303-12.803zM15 81.193C15 44.694 44.693 15 81.191 15c36.497 0 66.189 29.694 66.189 66.193 0 36.496-29.692 66.187-66.189 66.187C44.693 147.38 15 117.689 15 81.193z" />
                        </svg>
                    </button>
                </div>
            </form>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-4 ml-auto">

                <form id="bulkActionForm" method="POST" action="{{ route('leads.bulkAction') }}" class="flex items-center gap-4">
                    @csrf
                    <input type="hidden" name="assigned_to" id="bulk_assigned_to" value="" />
                    <div id="bulk-action-ids"></div>
                    <div class="w-[150px]">
                        <select id="action" name="action" class="w-full inline-flex items-center gap-2 px-3.5 py-2 text-slate-900 text-sm font-semibold rounded-md cursor-pointer bg-white hover:bg-gray-100 border border-slate-300 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-slate-50 dark:hover:bg-neutral-700">
                            <option disabled selected>Mass Action</option>
                            <option value="assigned_to">Assigned To</option>
                            <option value="delete">Delete</option>
                        </select>
                    </div>
                </form>

                <div class="w-[150px]">
                    <select id="limit" name="limit" class="w-full inline-flex items-center gap-2 px-3.5 py-2 text-slate-900 text-sm font-semibold rounded-md cursor-pointer bg-white hover:bg-gray-100 border border-slate-300 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-slate-50 dark:hover:bg-neutral-700">
                        <option value="10" {{ request('limit') == 10 ? ' selected' : '' }}>10 per page</option>
                        <option value="25" {{ request('limit') == 25 ? ' selected' : '' }}>25 per page</option>
                        <option value="50" {{ request('limit') == 50 ? ' selected' : '' }}>50 per page</option>
                        <option value="100" {{ request('limit') == 100 ? ' selected' : '' }}>100 per page</option>
                        <option value="all" {{ request('limit') == 'all' ? ' selected' : '' }}>All </option>
                    </select>
                </div>

                <button id="open-canvas" type="button" id="filterBtn"
                    class="flex items-center gap-2 px-3.5 py-2 text-slate-900 text-sm font-semibold rounded-md cursor-pointer bg-white hover:bg-gray-100 border border-slate-300 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-slate-50 dark:hover:bg-neutral-700">
                    <x-lucide-funnel class="size-4" />
                    Filter</button>

                <button type="button" id="exportBtn" onclick="exportToExcel()"
                    class="flex items-center gap-2 px-3.5 py-2 text-slate-900 text-sm font-semibold rounded-md cursor-pointer bg-white hover:bg-gray-100 border border-slate-300 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-slate-50 dark:hover:bg-neutral-700">
                    <x-lucide-download class="size-4" />
                    Export</button>

                <button type="button" id="importBtn"
                    class="flex items-center gap-2 px-3.5 py-2 text-slate-900 text-sm font-semibold rounded-md cursor-pointer bg-white hover:bg-gray-100 border border-slate-300 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-slate-50 dark:hover:bg-neutral-700">
                    <x-lucide-upload class="size-4" />
                    Import</button>

                <a href="{{ route('leads.create') }}"
                    class="flex items-center gap-2 px-3.5 py-2 text-white text-sm font-semibold rounded-md cursor-pointer bg-blue-600 hover:bg-blue-700 border border-blue-600 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                    <x-lucide-plus class="size-4" />
                    Add lead</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="text-slate-900 dark:text-slate-50 text-left text-sm font-semibold whitespace-nowrap bg-gray-50 border-b border-slate-300 dark:border-neutral-700 dark:bg-neutral-800">
                    <tr>
                        <th scope="col" class="w-8 pl-3 py-3.5">
                            <label class="group has-[input:checked]:text-slate-900 inline-block">
                                <input type="checkbox" class="sr-only" id="master-checkbox" />
                                <span class="flex h-4 w-4 shrink-0 items-center justify-center rounded outline-1 outline-slate-300 dark:outline-neutral-700 bg-white dark:bg-neutral-800 group-has-[input:checked]:bg-blue-600 group-has-[input:checked]:outline-blue-600 group-focus-within:outline-2 group-focus-within:outline-blue-600" aria-hidden="true">
                                    <svg class="size-3 text-white opacity-0 group-has-[input:checked]:opacity-100" viewBox="0 0 12 10" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 5l3 3 7-7" />
                                    </svg>
                                </span>
                            </label>
                        </th>
                        <th class="px-3 py-3.5">
                            <a href="{{ route('leads.index', array_merge($queryParams, ['sort' => 'id', 'direction' => $nextDirection('id')])) }}" class="flex items-center gap-1" aria-label="Sort by ID">
                                ID
                                @if ($currentSort === 'id')
                                @if ($currentDirection === 'asc')
                                <x-lucide-chevron-up class="size-3 fill-blue-600" />
                                @else
                                <x-lucide-chevron-down class="size-3 fill-blue-600" />
                                @endif
                                @else
                                <x-lucide-chevrons-up-down class="size-3 fill-slate-400" />
                                @endif
                            </a>
                        </th>
                        <th class="px-3 py-3.5">Name</th>
                        <th class="px-3 py-3.5">Email</th>
                        <th class="px-3 py-3.5">Mobile</th>
                        <th class="px-3 py-3.5">Source</th>
                        <th class="px-3 py-3.5">Course</th>
                        <th class="px-3 py-3.5">Assigned</th>
                        <th class="px-3 py-3.5">Status</th>
                        <th class="px-3 py-3.5">Follow-up</th>
                        <th class="px-3 py-3.5">Converted</th>
                        <th class="px-3 py-3.5">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-200 dark:divide-neutral-700">
                    @forelse($leads as $lead)
                    <tr class="has-[:checked]:bg-blue-50/50 dark:has-[:checked]:bg-blue-900/10">
                        <td class="w-8 pl-3 py-4">
                            <label class="group has-[input:checked]:text-slate-900 inline-block">
                                <input type="checkbox" class="sr-only row-checkbox" value="{{ $lead->id }}" />
                                <span class="flex h-4 w-4 shrink-0 items-center justify-center rounded outline-1 outline-slate-300 dark:outline-neutral-700 bg-white dark:bg-neutral-800 group-has-[input:checked]:bg-blue-600 group-has-[input:checked]:outline-blue-600 group-focus-within:outline-2 group-focus-within:outline-blue-600" aria-hidden="true">
                                    <svg class="size-3 text-white opacity-0 group-has-[input:checked]:opacity-100" viewBox="0 0 12 10" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 5l3 3 7-7" />
                                    </svg>
                                </span>
                            </label>
                        </td>
                        <td class="px-3 py-3.5 text-sm text-slate-900 dark:text-slate-50">{{ $lead->id }}</td>
                        <td class="px-3 py-3.5 text-sm text-slate-900 dark:text-slate-50">{{ $lead->name }}</td>
                        <td class="px-3 py-3.5 text-sm text-slate-900 dark:text-slate-50">{{ $lead->email ?? '-' }}</td>
                        <td class="px-3 py-3.5 text-sm text-slate-900 dark:text-slate-50">{{ $lead->mobile }}</td>
                        <td class="px-3 py-3.5 text-sm text-slate-900 dark:text-slate-50">{{ $lead->source ?? '-' }}</td>
                        <td class="px-3 py-3.5 text-sm text-slate-900 dark:text-slate-50">{{ $lead->courseInterest->name ?? '-' }}</td>
                        <td class="px-3 py-3.5 text-sm text-slate-900 dark:text-slate-50">{{ $lead->assignedTo->name ?? '-' }}</td>
                        <td class="px-3 py-3.5 text-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full   font-medium {{ $lead->status === 'converted' ? 'bg-green-100 text-green-800' : ($lead->status === 'lost' ? 'bg-red-100 text-red-800' : 'bg-slate-100 text-slate-800') }} dark:{{ $lead->status === 'converted' ? 'bg-green-900 text-green-200' : ($lead->status === 'lost' ? 'bg-red-900 text-red-200' : 'bg-neutral-700 text-neutral-200') }}">
                                {{ ucfirst(str_replace('_', ' ', $lead->status)) }}
                            </span>
                        </td>
                        <td class="px-3 py-3.5 text-sm text-slate-900 dark:text-slate-50">{{ $lead->next_followup_date ? $lead->next_followup_date->format('M d, Y') : '-' }}</td>
                        <td class="px-3 py-3.5 text-sm text-slate-900 dark:text-slate-50">{{ $lead->converted_at ? $lead->converted_at->format('M d, Y H:i') : '-' }}</td>
                        <td class="px-3 py-3.5 text-sm">
                            <div class="flex gap-2 items-center">
                                <a href="tel:{{ $lead->mobile }}" title="phone" target="_blank">
                                    <x-lucide-phone class="size-6" />
                                </a>
                                <a href="https://api.whatsapp.com/send?text=Hello&phone={{ $lead->mobile }}" title="WhatsApp" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30px" height="30px" viewBox="0 0 32 32" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M16 31C23.732 31 30 24.732 30 17C30 9.26801 23.732 3 16 3C8.26801 3 2 9.26801 2 17C2 19.5109 2.661 21.8674 3.81847 23.905L2 31L9.31486 29.3038C11.3014 30.3854 13.5789 31 16 31ZM16 28.8462C22.5425 28.8462 27.8462 23.5425 27.8462 17C27.8462 10.4576 22.5425 5.15385 16 5.15385C9.45755 5.15385 4.15385 10.4576 4.15385 17C4.15385 19.5261 4.9445 21.8675 6.29184 23.7902L5.23077 27.7692L9.27993 26.7569C11.1894 28.0746 13.5046 28.8462 16 28.8462Z" fill="#BFC8D0" />
                                        <path d="M28 16C28 22.6274 22.6274 28 16 28C13.4722 28 11.1269 27.2184 9.19266 25.8837L5.09091 26.9091L6.16576 22.8784C4.80092 20.9307 4 18.5589 4 16C4 9.37258 9.37258 4 16 4C22.6274 4 28 9.37258 28 16Z" fill="url(#paint0_linear_87_7264)" />
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M16 30C23.732 30 30 23.732 30 16C30 8.26801 23.732 2 16 2C8.26801 2 2 8.26801 2 16C2 18.5109 2.661 20.8674 3.81847 22.905L2 30L9.31486 28.3038C11.3014 29.3854 13.5789 30 16 30ZM16 27.8462C22.5425 27.8462 27.8462 22.5425 27.8462 16C27.8462 9.45755 22.5425 4.15385 16 4.15385C9.45755 4.15385 4.15385 9.45755 4.15385 16C4.15385 18.5261 4.9445 20.8675 6.29184 22.7902L5.23077 26.7692L9.27993 25.7569C11.1894 27.0746 13.5046 27.8462 16 27.8462Z" fill="white" />
                                        <path d="M12.5 9.49989C12.1672 8.83131 11.6565 8.8905 11.1407 8.8905C10.2188 8.8905 8.78125 9.99478 8.78125 12.05C8.78125 13.7343 9.52345 15.578 12.0244 18.3361C14.438 20.9979 17.6094 22.3748 20.2422 22.3279C22.875 22.2811 23.4167 20.0154 23.4167 19.2503C23.4167 18.9112 23.2062 18.742 23.0613 18.696C22.1641 18.2654 20.5093 17.4631 20.1328 17.3124C19.7563 17.1617 19.5597 17.3656 19.4375 17.4765C19.0961 17.8018 18.4193 18.7608 18.1875 18.9765C17.9558 19.1922 17.6103 19.083 17.4665 19.0015C16.9374 18.7892 15.5029 18.1511 14.3595 17.0426C12.9453 15.6718 12.8623 15.2001 12.5959 14.7803C12.3828 14.4444 12.5392 14.2384 12.6172 14.1483C12.9219 13.7968 13.3426 13.254 13.5313 12.9843C13.7199 12.7145 13.5702 12.305 13.4803 12.05C13.0938 10.953 12.7663 10.0347 12.5 9.49989Z" fill="white" />
                                        <defs>
                                            <linearGradient id="paint0_linear_87_7264" x1="26.5" y1="7" x2="4" y2="28" gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#5BD066" />
                                                <stop offset="1" stop-color="#27B43E" />
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                </a>

                                <a href="mailto:{{ $lead->email }}subject={{config('app.name')}}&body=Hi,I found this website and thought you might like it {{ config('app.url') }}" title="Email" target="_blank">
                                    <x-lucide-mail class="size-6 text-slate-400 hover:text-slate-600" />
                                </a>

                                <button type="button"  data-modal-open="#modalOverlay" class="open-followup-modal flex items-center gap-1.5 rounded-md   font-medium text-orange-700 bg-orange-50 border border-orange-200 px-2.5 py-1.5 cursor-pointer dark:bg-orange-900/20 dark:border-orange-900/40 dark:text-orange-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-500" data-lead-id="{{ $lead->id }}" data-lead-name="{{ $lead->name }}">
                                    <x-lucide-info class="h-4 w-4" />Follow Up
                                </button>

                                <a href="{{ route('leads.edit', $lead->id) }}" class="flex items-center gap-1.5 rounded-md   font-medium text-blue-700 bg-blue-50 border border-blue-200 px-2.5 py-1.5 cursor-pointer dark:bg-blue-900/20 dark:border-blue-900/40 dark:text-blue-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                    <x-lucide-pencil class="h-4 w-4" />Edit
                                </a>
                                <form action="{{ route('leads.destroy', $lead->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this lead?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex items-center gap-1.5 rounded-md   font-medium text-red-700 bg-red-50 border border-red-200 px-2.5 py-1.5 cursor-pointer dark:bg-red-900/20 dark:border-red-900/40 dark:text-red-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500">
                                        <x-lucide-trash-2 class="h-4 w-4" />Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="px-3 py-3.5 text-center text-slate-500 dark:text-slate-400">No leads found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between flex-wrap gap-6 mx-auto mt-6">
            <div class="text-sm text-slate-600 dark:text-slate-400">
                Showing
                <span class="font-medium">{{ $leads->firstItem() ?? 0 }}</span>
                to
                <span class="font-medium">{{ $leads->lastItem() ?? 0 }}</span>
                of
                <span class="font-medium">{{ $leads->total() }}</span>
                results
            </div>

            <nav aria-label="Pagination" class="flex items-center w-max rounded-md bg-white border border-slate-300 divide-x divide-slate-300 dark:bg-neutral-800 dark:border-neutral-700 dark:divide-neutral-700">
                @if ($leads->onFirstPage())
                <button disabled class="flex items-center justify-center shrink-0 w-9 h-9 rounded-l-[5px] text-slate-400 cursor-not-allowed dark:text-slate-600">
                    <x-lucide-chevron-left class="h-5 w-5" />
                </button>
                @else
                <a href="{{ $leads->previousPageUrl() }}" aria-label="Previous page" class="flex items-center justify-center shrink-0 w-9 h-9 rounded-l-[5px] hover:bg-gray-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:bg-neutral-700">
                    <x-lucide-chevron-left class="h-5 w-5" />
                </a>
                @endif

                @foreach ($leads->getUrlRange(1, $leads->lastPage()) as $page => $url)
                @if ($page == $leads->currentPage())
                <a aria-current="page" class="flex items-center justify-center shrink-0 text-sm font-semibold text-white w-9 h-9 bg-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">{{ $page }}</a>
                @else
                <a href="{{ $url }}" class="flex items-center justify-center shrink-0 text-sm font-semibold text-slate-900 w-9 h-9 hover:bg-gray-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:text-slate-50 dark:hover:bg-neutral-700">{{ $page }}</a>
                @endif
                @endforeach

                @if ($leads->hasMorePages())
                <a href="{{ $leads->nextPageUrl() }}" aria-label="Next page" class="flex items-center justify-center shrink-0 w-9 h-9 rounded-r-[5px] hover:bg-gray-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:text-slate-50 dark:hover:bg-neutral-700">
                    <x-lucide-chevron-right class="h-5 w-5" />
                </a>
                @else
                <button disabled class="flex items-center justify-center shrink-0 w-9 h-9 rounded-r-[5px] text-slate-400 cursor-not-allowed dark:text-slate-600">
                    <x-lucide-chevron-right class="h-5 w-5" />
                </button>
                @endif
            </nav>
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
            <form id="filter-form" method="GET" action="{{ route('leads.index') }}" class="flex flex-col space-y-2">
                <nav class="flex flex-col space-y-2">
                    <div>
                        <label for="search" class="block text-sm font-medium mb-1">Search</label>
                        <input type="text" id="search" name="search" placeholder="Search leads..." value="{{ request('search') }}" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium mb-1">Email</label>
                        <input type="email" id="email" name="email" placeholder="Search by email..." value="{{ request('email') }}" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="course" class="block text-sm font-medium mb-1">Course Interest</label>
                        <select id="course" name="course" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All</option>
                            @forelse($courses as $key => $course)
                            <option value="{{ $key }}" {{ request('course') == $key ? 'selected' : '' }}>{{ $course }}</option>
                            @empty
                            <option disabled>No courses available</option>
                            @endforelse
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium mb-1">Status</label>
                        <select id="status" name="status" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All</option>
                            @forelse($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                            @empty
                            <option disabled>No statuses available</option>
                            @endforelse
                        </select>
                    </div>
                    <div>
                        <label for="source" class="block text-sm font-medium mb-1">Source</label>
                        <select id="source" name="source" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All</option>
                            @forelse($sources as $source)
                            <option value="{{ $source }}" {{ request('source') == $source ? 'selected' : '' }}>{{ ucfirst($source) }}</option>
                            @empty
                            <option disabled>No sources available</option>
                            @endforelse
                        </select>
                    </div>

                    <div>
                        <label for="assigned_to" class="block text-sm font-medium mb-1">Assigned To</label>
                        <select id="assigned_to" name="assigned_to" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All</option>
                             <option value="null">Unassigned</option>
                            @forelse($users as $key => $user)
                            <option value="{{ $key }}" {{ request('assigned_to') == $key ? 'selected' : '' }}>{{ $user }}</option>
                            @empty
                            <option disabled>No users available</option>
                            @endforelse
                        </select>
                    </div>

                    <div>
                        <label for="state" class="block text-sm font-medium mb-1">State</label>
                        <select id="state" name="state" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All</option>
                            @forelse($states as $state)
                            <option value="{{ $state }}" {{ request('state') == $state ? 'selected' : '' }}>{{ $state }}</option>
                            @empty
                            <option disabled>No states available</option>
                            @endforelse
                        </select>
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-medium mb-1">City</label>
                        <select id="city" name="city" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All</option>
                            @forelse($cities as $city)
                            <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                            @empty
                            <option disabled>No city available</option>
                            @endforelse
                        </select>
                    </div>

                    <div class=" border-gray-200 pt-4 mt-4">
                        <label for="converted_at" class="block text-sm font-medium mb-1 text-gray-700">Converted</label>
                    <select id="converted_at" name="converted_at" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All</option>
                        <option value="1" {{ request('converted_at') === '1' ? 'selected' : '' }}>Converted</option>
                        <option value="0" {{ request('converted_at') === '0' ? 'selected' : '' }}>Not Converted</option>
                    </select>
                    </div>
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <label class="block text-sm font-medium mb-2 text-gray-700">Follow-up Date Range</label>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label for="followup_date_from" class="block   font-medium mb-1 text-gray-600">From</label>
                                <input type="date" id="followup_date_from" name="followup_date_from" value="{{ request('followup_date_from') }}" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                            <div>
                                <label for="followup_date_to" class="block   font-medium mb-1 text-gray-600">To</label>
                                <input type="date" id="followup_date_to" name="followup_date_to" value="{{ request('followup_date_to') }}" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700"> Date Range</label>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label for="created_at_from" class="block   font-medium mb-1 text-gray-600">From</label>
                                <input type="date" id="created_at_from" name="created_at_from" value="{{ request('created_at_from') }}" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                            <div>
                                <label for="created_at_to" class="block   font-medium mb-1 text-gray-600">To</label>
                                <input type="date" id="created_at_to" name="created_at_to" value="{{ request('created_at_to') }}" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500" />
                            </div>
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
            <a href="{{ route('leads.index') }}" class="flex-1 px-4 py-2 text-center text-slate-900 text-sm font-medium rounded-md border border-gray-300 hover:bg-gray-50">
                Reset
            </a>
        </div>
    </div>
</div>

<!-- Follow-up Modal -->
<div id="modalOverlay"
    class="hidden modal-overlay fixed inset-0 p-4 flex flex-wrap justify-center items-center w-full h-full z-[1000] before:fixed before:inset-0 before:w-full before:h-full before:bg-[rgba(0,0,0,0.5)]"
    aria-hidden="true">

    <div role="dialog" aria-modal="true" aria-labelledby="modal-title" tabindex="-1"
        class="w-full max-w-xl bg-white border border-slate-100 shadow-lg rounded-lg relative max-h-[95vh] overflow-y-auto outline-none p-4 md:p-6 dark:bg-neutral-800 dark:border-neutral-700">

        <button type="button"  aria-label="Close modal"
            class="modal-close flex items-center absolute top-6 right-6 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="size-3 cursor-pointer fill-slate-500 hover:fill-red-600 dark:fill-slate-400 dark:hover:fill-red-500"
                aria-hidden="true" viewBox="0 0 329.269 329">
                <path
                    d="M194.8 164.77 323.013 36.555c8.343-8.34 8.343-21.825 0-30.164-8.34-8.34-21.825-8.34-30.164 0L164.633 134.605 36.422 6.391c-8.344-8.34-21.824-8.34-30.164 0-8.344 8.34-8.344 21.824 0 30.164l128.21 128.215L6.259 292.984c-8.344 8.34-8.344 21.825 0 30.164a21.27 21.27 0 0 0 15.082 6.25c5.46 0 10.922-2.09 15.082-6.25l128.21-128.214 128.216 128.214a21.27 21.27 0 0 0 15.082 6.25c5.46 0 10.922-2.09 15.082-6.25 8.343-8.34 8.343-21.824 0-30.164zm0 0" />
            </svg>
        </button>



        <div class="mt-6">
            <div class="space-y-4">
                <div class="mt-10">
                    <div class="bg-slate-50 rounded-lg border border-slate-200 p-6 dark:bg-slate-900 dark:border-slate-700">
                        <h3 id="modal-title" class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Add Follow Up</h3>
                        <form method="POST" action="#" id="followupForm">
                            @csrf
                            <input type="hidden" name="lead_id" id="followup_lead_id" value="" />
                            <div class="space-y-4">
                                <div>
                                    <label for="followup_type" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Follow Up Type</label>
                                    <select id="followup_type" name="followup_type" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                                        <option value="">Select type</option>
                                        <option value="call" {{ old('followup_type') === 'call' ? ' selected' : '' }}>Call</option>
                                        <option value="whatsapp" {{ old('followup_type') === 'whatsapp' ? ' selected' : '' }}>WhatsApp</option>
                                        <option value="email" {{ old('followup_type') === 'email' ? ' selected' : '' }}>Email</option>
                                        <option value="meeting" {{ old('followup_type') === 'meeting' ? ' selected' : '' }}>Meeting</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="followup_date" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Follow Up Date</label>
                                    <input type="datetime-local" id="followup_date" name="followup_date" value="{{ old('followup_date') }}" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
                                </div>

                                <div>
                                    <label for="next_followup_date" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Next Follow Up Date</label>
                                    <input type="datetime-local" id="next_followup_date" name="next_followup_date" value="{{ old('next_followup_date') }}" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
                                </div>

                                <div>
                                    <label for="call_duration" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Call Duration (minutes)</label>
                                    <input type="number" id="call_duration" name="call_duration" min="0" value="{{ old('call_duration') }}" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
                                </div>

                                <div>
                                    <label for="note" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Note</label>
                                    <textarea id="note" name="note" rows="4" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">{{ old('note') }}</textarea>
                                </div>

                                <button type="submit" class="inline-flex items-center justify-center rounded-md bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    Save Follow Up
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="bg-slate-50 mt-4 rounded-lg border border-slate-200 p-6 dark:bg-slate-900 dark:border-slate-700">
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Follow Up History</h3>
                        <div id="followupHistoryContainer" class="text-sm text-slate-600 dark:text-slate-300">
                            Select a lead to view follow-up history.
                        </div>
                    </div>
                </div>
            </div>





        </div>
    </div>
</div>



<div id="modalAssignToOverlay"
   class="hidden modal-overlay fixed inset-0 p-4 flex flex-wrap justify-center items-center w-full h-full z-[1000] before:fixed before:inset-0 before:w-full before:h-full before:bg-[rgba(0,0,0,0.5)]">
   
   <div role="dialog" aria-modal="true" aria-labelledby="modal-title" tabindex="-1"
      class="w-full max-w-lg bg-white border border-slate-100 shadow-lg rounded-lg relative max-h-[95vh] overflow-y-auto outline-none p-4 md:p-6 dark:bg-neutral-800 dark:border-neutral-700">
      <div class="flex items-center pb-3 border-b border-slate-300 dark:border-neutral-700">
         <h3 id="modal-title" class="text-slate-900 text-lg font-semibold flex-1 dark:text-slate-50">Assign Lead
         </h3>

         <button type="button"  aria-label="Close modal"
            class="modal-close ml-auto flex items-center focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded">
            <svg xmlns="http://www.w3.org/2000/svg"
               class="size-3 cursor-pointer fill-slate-500 hover:fill-red-600 dark:fill-slate-400 dark:hover:fill-red-500"
               aria-hidden="true" viewBox="0 0 329.269 329">
               <path
                  d="M194.8 164.77 323.013 36.555c8.343-8.34 8.343-21.825 0-30.164-8.34-8.34-21.825-8.34-30.164 0L164.633 134.605 36.422 6.391c-8.344-8.34-21.824-8.34-30.164 0-8.344 8.34-8.344 21.824 0 30.164l128.21 128.215L6.259 292.984c-8.344 8.34-8.344 21.825 0 30.164a21.27 21.27 0 0 0 15.082 6.25c5.46 0 10.922-2.09 15.082-6.25l128.21-128.214 128.216 128.214a21.27 21.27 0 0 0 15.082 6.25c5.46 0 10.922-2.09 15.082-6.25 8.343-8.34 8.343-21.824 0-30.164zm0 0" />
            </svg>
         </button>
      </div>

      <div class="my-6">
        <div class="space-y-4">
               <div>
                        <label for="bulk_assigned_to_select" class="block text-sm font-medium mb-1">Assigned To</label>
                        <select id="bulk_assigned_to_select" name="assigned_to" class="select2 w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="0">Unassigned</option>
                            @forelse($users as $key => $user)
                            <option value="{{ $key }}">{{ $user }}</option>
                            @empty
                            <option disabled>No users available</option>
                            @endforelse
                        </select>
                    </div>
      </div>

      <div class="border-t border-slate-300 pt-4 flex justify-end gap-4 md:pt-6 dark:border-neutral-700">
         <button type="button" 
            class="cancelBtn px-3.5 py-2 text-slate-900 text-sm font-semibold rounded-md cursor-pointer bg-white border border-slate-300 transition-colors hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:text-slate-50 dark:bg-neutral-700 dark:hover:bg-neutral-600 dark:border-neutral-600">
            Cancel</button>
         <button type="button" id="bulkAssignSubmit"
            class="px-3.5 py-2 text-white text-sm font-semibold rounded-md cursor-pointer bg-blue-600 border border-blue-600 transition-colors hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
            Submit</button>
      </div>
   </div>
</div>


@php
$followupData = $leads->mapWithKeys(function ($lead) {
return [$lead->id => $lead->followups->map(function ($followup) {
return [
'followup_date' => optional($followup->followup_date)->format('Y-m-d H:i'),
'followup_type' => $followup->followup_type,
'call_duration' => $followup->call_duration,
'next_followup_date' => optional($followup->next_followup_date)->format('Y-m-d H:i'),
'note' => $followup->note,
'user' => optional($followup->user)->name,
];
})->toArray()];
})->toArray();
@endphp

<script type="module">
   

    // Dom element selectors
    const openBtn = document.getElementById('open-canvas');
    const closeBtn = document.getElementById('close-canvas');
    const backdrop = document.getElementById('canvas-backdrop');
    const offcanvas = document.getElementById('offcanvas-right');

    // Function to open the drawer
    function openOffcanvas() {
        // Slide panel in from right side
        offcanvas.classList.remove('translate-x-full');
        offcanvas.classList.add('translate-x-0');

        // Fade in the dark backdrop screen overlay
        backdrop.classList.remove('opacity-0', 'pointer-events-none');
        backdrop.classList.add('opacity-100', 'pointer-events-auto');

        // Prevent the background content layer from scrolling
        document.body.classList.add('overflow-hidden');
    }

    // Function to close the drawer
    function closeOffcanvas() {
        // Slide panel back out to the right side
        offcanvas.classList.remove('translate-x-0');
        offcanvas.classList.add('translate-x-full');

        // Fade out the dark backdrop screen overlay
        backdrop.classList.remove('opacity-100', 'pointer-events-auto');
        backdrop.classList.add('opacity-0', 'pointer-events-none');

        // Re-enable background scrolling behavior
        document.body.classList.remove('overflow-hidden');
    }

    // Attach interactive event listeners
    openBtn.addEventListener('click', openOffcanvas);
    closeBtn.addEventListener('click', closeOffcanvas);
    backdrop.addEventListener('click', closeOffcanvas); // Closes when clicking outside the panel


    // Excel Export functionality
    function exportToExcel() {
        const table = document.querySelector('table');
        const rows = table.querySelectorAll('tr');
        let csvContent = "data:text/csv;charset=utf-8,";

        // Get checked rows or all rows
        const checkedRows = document.querySelectorAll('.row-checkbox:checked');
        const dataRows = checkedRows.length > 0 ?
            Array.from(checkedRows).map(cb => cb.closest('tr')) :
            Array.from(rows).slice(1); // Skip header

        // Add header
        const headerCells = table.querySelectorAll('thead th');
        const headerRow = Array.from(headerCells)
            .slice(1) // Skip checkbox column
            .map(cell => `"${cell.textContent.trim()}"`)
            .join(",");
        csvContent += encodeURIComponent(headerRow + "\n");

        // Add data rows
        dataRows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const dataRow = Array.from(cells)
                .slice(1) // Skip checkbox column
                .map((cell, index) => {
                    // Skip actions column
                    if (index === Array.from(cells).length - 2) return "";
                    return `"${cell.textContent.trim()}"`;
                })
                .slice(0, -1) // Remove last empty element
                .join(",");
            csvContent += encodeURIComponent(dataRow + "\n");
        });

        // Trigger download
        const link = document.createElement("a");
        link.setAttribute("href", csvContent);
        link.setAttribute("download", `users_${new Date().toISOString().split('T')[0]}.csv`);
        link.click();
    }

     window.collectSelectedLeadIds = function () {
        const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
        const container = document.getElementById('bulk-action-ids');
        container.innerHTML = '';

        const ids = Array.from(checkedBoxes).map(cb => cb.value);

        ids.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'selected_ids[]';
            input.value = id;
            container.appendChild(input);
        });

        return ids;
    }

    // Import functionality
    document.getElementById('importBtn').addEventListener('click', function() {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = '.xlsx,.xls';
        input.onchange = e => {
            const file = e.target.files[0];
            if (!file) return;

            // Validate file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size exceeds 5MB limit.');
                return;
            }

            // Create form data
            const formData = new FormData();
            formData.append('file', file);
            formData.append('_token', '{{ csrf_token() }}');

            // Submit via AJAX
            fetch('{{ route('leads.import') }}', {
                        method: 'POST',
                        body: formData,
                    })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message || 'Import completed successfully!');
                        location.reload();
                    } else {
                        alert(data.message || 'Import failed!');
                    }
                })
                .catch(error => {
                    alert('Error uploading file: ' + error);
                });
        };
        input.click();
    });

    $(document).on("change", "#limit", function() {
        const url = new URL(window.location.href);
        url.searchParams.set("limit", $(this).val());
        url.searchParams.delete("page");

        window.location.href = url.toString();
    });




    const followupButtons = document.querySelectorAll('.open-followup-modal');
   
    const overlay = document.getElementById("modalOverlay");
    const dialog = overlay.querySelector("[role='dialog']");
    const followupForm = document.getElementById('followupForm');
    const followupLeadIdInput = document.getElementById('followup_lead_id');
    const modalTitle = document.getElementById('modal-title');
    const followupHistoryContainer = document.getElementById('followupHistoryContainer');
    let activeFollowupButton = null;
    const followupRouteBase = "{{ url('leads') }}";
    const followupData = @json($followupData);

    function renderFollowupHistory(leadId) {
        const followups = followupData[leadId] || [];
        if (!followups.length) {
            followupHistoryContainer.innerHTML = `
               <div class="rounded-lg border border-dashed border-slate-300 bg-white p-6 text-sm text-slate-600 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-300">
                   No follow ups have been added for this lead yet.
               </div>
           `;
            return;
        }

        const rows = followups.map(f => `
           <tr>
               <td class="px-4 py-3 text-slate-700 dark:text-slate-200">${f.followup_date || '—'}</td>
               <td class="px-4 py-3 text-slate-700 dark:text-slate-200">${f.followup_type ? f.followup_type.charAt(0).toUpperCase() + f.followup_type.slice(1) : '—'}</td>
               <td class="px-4 py-3 text-slate-700 dark:text-slate-200">${f.call_duration ? `${f.call_duration} min` : '—'}</td>
               <td class="px-4 py-3 text-slate-700 dark:text-slate-200">${f.next_followup_date || '—'}</td>
               <td class="px-4 py-3 text-slate-700 dark:text-slate-200 break-words">${f.note || '—'}</td>
               <td class="px-4 py-3 text-slate-700 dark:text-slate-200">${f.user || 'System'}</td>
           </tr>
       `).join('');

        followupHistoryContainer.innerHTML = `
           <div class="overflow-x-auto">
               <table class="min-w-full divide-y divide-slate-200 text-sm text-left dark:divide-slate-700">
                   <thead class="bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                       <tr>
                           <th class="px-4 py-3 font-medium">Date</th>
                           <th class="px-4 py-3 font-medium">Type</th>
                           <th class="px-4 py-3 font-medium">Duration</th>
                           <th class="px-4 py-3 font-medium">Next Follow Up</th>
                           <th class="px-4 py-3 font-medium">Note</th>
                           <th class="px-4 py-3 font-medium">User</th>
                       </tr>
                   </thead>
                   <tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-700 dark:bg-slate-950">
                       ${rows}
                   </tbody>
               </table>
           </div>
       `;
    }

    // Open modal and lock body scroll
    followupButtons.forEach(button => {
        button.addEventListener('click', () => {
            const leadId = button.dataset.leadId;
            const leadName = button.dataset.leadName;
            followupForm.action = `${followupRouteBase}/${leadId}/followups`;
            followupLeadIdInput.value = leadId;
            modalTitle.textContent = `Add Follow Up for ${leadName} (#${leadId})`;
            renderFollowupHistory(leadId);
            overlay.classList.remove("hidden");
            overlay.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = "hidden";
            dialog.focus();
            activeFollowupButton = button;
        });
    });

   




    $("#action").on("change", function() {
        const selectedAction = $(this).val();

        if ($(".row-checkbox:checked").length === 0) {
            alert("Please select at least one lead to perform this action.");
            $(this).val(""); // Reset action selection
            return;
        }

        if (selectedAction === "delete") {
            if (confirm("Are you sure you want to delete the selected leads? This action cannot be undone.")) {
                collectSelectedLeadIds();
                document.getElementById('bulk_assigned_to').value = '';
                $("#bulkActionForm").submit();
            } else {
                $(this).val(""); // Reset action selection
            }
        }

        if (selectedAction === "assigned_to") {
            $("#modalAssignToOverlay").removeClass("hidden");
            $("#modalAssignToOverlay").attr("aria-hidden", "false");
            document.body.style.overflow = "hidden";
            $("#modalAssignToOverlay [role='dialog']").focus();
        }
    });

    $(function () {

    // Limit
    $(document).on('change', '#limit', function () {
        const url = new URL(location.href);
        url.searchParams.set('limit', $(this).val());
        url.searchParams.delete('page');
        location.href = url;
    });

    // Modal open
    $(document).on('click', '[data-modal-open]', function () {
       const modal = $($(this).data('modal-open'));

        modal.removeClass('hidden');
            
            modal.find('select').each(function () {

            if ($(this).hasClass('select2')) {
                return;
            }

            $(this).select2({
                theme: 'tailwindcss-4',
                width: '100%',
                dropdownParent: modal
            });
        });
    });

    // Modal close
    $(document).on('click', '.modal-close', function () {
        $(this).closest('.modal-overlay').addClass('hidden');
        document.body.style.overflow = '';
        $('#action').val('').trigger('change');
    });

    // Cancel button
    $(document).on('click', '.cancelBtn', function () {
        $(this).closest('.modal-overlay').addClass('hidden');
        document.body.style.overflow = '';
        $('#action').val('').trigger('change');
    });

   

    $('#bulkAssignSubmit').on('click', function () {
        const selectedIds = collectSelectedLeadIds();
        const assignedToValue = document.getElementById('bulk_assigned_to_select').value;

        if (selectedIds.length === 0) {
            alert('Please select at least one lead before assigning.');
            $('#action').val('').trigger('change');
            $('#modalAssignToOverlay').addClass('hidden');
            return;
        }

        if (!assignedToValue) {
            alert('Please select a user to assign the selected leads to.');
            return;
        }

        document.getElementById('bulk_assigned_to').value = assignedToValue;
        $('#bulkActionForm').submit();
    });

    // Master checkbox
    $(document).on('change', '#master-checkbox', function () {
        $('.row-checkbox').prop('checked', this.checked);
    });

    // Row checkbox
    $(document).on('change', '.row-checkbox', function () {
        $('#master-checkbox').prop(
            'checked',
            $('.row-checkbox').length === $('.row-checkbox:checked').length
        );
    });

});
</script>
@endsection