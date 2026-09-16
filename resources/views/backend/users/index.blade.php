   @extends('layouts.backend')
   @section('title', 'Users')


   @section('content')


   <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">



       <div class="max-w-7xl mx-auto">
           @php
           $currentSort = request('sort', 'id');
           $currentDirection = request('direction', 'asc');
           $queryParams = request()->except('page');
           $nextDirection = fn($column) => $currentSort === $column && $currentDirection === 'asc' ? 'desc' : 'asc';
           @endphp

           <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-50">Users</h2>
           <p class="text-sm text-slate-600 dark:text-slate-400 mb-6">Manage your users and their permissions.</p>
           <div class="flex flex-wrap items-center gap-6 mb-6">
               <!-- searchbar -->
               <form class="max-w-xs" role="search" method="GET" action="{{ route('users.index') }}">
                   <input type="hidden" name="limit" value="{{ request('limit', 10) }}" />
                   <input type="hidden" name="sort" value="{{ request('sort', 'id') }}" />
                   <input type="hidden" name="direction" value="{{ request('direction', 'asc') }}" />
                   <input type="hidden" name="role" value="{{ request('role') }}" />
                   <input type="hidden" name="status" value="{{ request('status') }}" />
                   <input type="hidden" name="email" value="{{ request('email') }}" />
                   <div
                       class="flex items-center gap-2.5 px-3 py-2.5 rounded-md bg-white dark:bg-neutral-800 outline-1 -outline-offset-1 outline-slate-300 dark:outline-neutral-700 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-blue-600">
                       <label for="search" class="sr-only">Search</label>
                       <input type="search" id="search" name="search" placeholder="Search..." value="{{ request('search') }}"
                           class="text-sm text-slate-900 dark:text-slate-50 w-full outline-none" />

                       <button type="submit" class="flex-shrink-0 hover:opacity-75">
                          <x-lucide-search class="h-4 w-4" />
                       </button>
                   </div>
               </form>

               <!-- Action Buttons -->
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


                   <a href="{{ route('users.create') }}"
                       class="flex items-center gap-2 px-3.5 py-2 text-white text-sm font-semibold rounded-md cursor-pointer bg-blue-600 hover:bg-blue-700 border border-blue-600 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                      <x-lucide-plus class="size-4" />
                       Add user</a>
               </div>
           </div>

           <!-- User Table -->
           <div class="overflow-x-auto">
               <table class="w-full">
                   <thead
                       class="text-slate-900 dark:text-slate-50 text-left text-sm font-semibold whitespace-nowrap bg-gray-50 border-b border-slate-300 dark:border-neutral-700 dark:bg-neutral-800">
                       <tr>
                           <th scope="col" aria-sort="none" class="w-8 pl-3 py-3.5">
                               <label class="group has-[input:checked]:text-slate-900 inline-block">
                                   <input type="checkbox" class="sr-only" id="master-checkbox" />
                                   <span class="flex h-4 w-4 shrink-0 items-center justify-center rounded outline-1 outline-slate-300 dark:outline-neutral-700
                              bg-white dark:bg-neutral-800
                              group-has-[input:checked]:bg-blue-600
                              group-has-[input:checked]:outline-blue-600
                              group-focus-within:outline-2
                              group-focus-within:outline-blue-600" aria-hidden="true">
                                       <!-- Checkmark -->
                                       <svg class="size-3 text-white opacity-0 group-has-[input:checked]:opacity-100"
                                           viewBox="0 0 12 10" fill="none" stroke="currentColor" stroke-width="2">
                                           <path d="M1 5l3 3 7-7" />
                                       </svg>
                                   </span>
                               </label>
                           </th>
                           <th scope="col" aria-sort="none" class="px-3 py-3.5">
                               <a href="{{ route('users.index', array_merge($queryParams, ['sort' => 'id', 'direction' => $nextDirection('id')])) }}"
                                   class="flex items-center gap-1 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded"
                                   aria-label="Sort by ID">
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

                           <th scope="col" aria-sort="none" class="px-3 py-3.5">
                               <a href="{{ route('users.index', array_merge($queryParams, ['sort' => 'first_name', 'direction' => $nextDirection('first_name')])) }}"
                                   class="flex items-center gap-1 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded"
                                   aria-label="Sort by first name">
                                   First Name
                                   @if ($currentSort === 'first_name')
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
                           <th scope="col" aria-sort="none" class="px-3 py-3.5">
                               <a href="{{ route('users.index', array_merge($queryParams, ['sort' => 'last_name', 'direction' => $nextDirection('last_name')])) }}"
                                   class="flex items-center gap-1 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded"
                                   aria-label="Sort by last name">
                                   Last Name
                                   @if ($currentSort === 'last_name')
                                   @if ($currentDirection === 'asc')
                                   <x-lucide-chevron-up class="size-3 fill-blue-400" />
                                   @else
                                   <x-lucide-chevron-down class="size-3 fill-blue-400" />
                                   @endif
                                   @else
                                   <x-lucide-chevrons-up-down class="size-3 fill-slate-400" />
                                   @endif
                               </a>
                           </th>
                           <th scope="col" aria-sort="none" class="px-3 py-3.5">
                               <a href="{{ route('users.index', array_merge($queryParams, ['sort' => 'email', 'direction' => $nextDirection('email')])) }}"
                                   class="flex items-center gap-1 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded"
                                   aria-label="Sort by email">
                                   Email
                                   @if ($currentSort === 'email')
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
                           <th scope="col" aria-sort="none" class="px-3 py-3.5">
                               <a href="{{ route('users.index', array_merge($queryParams, ['sort' => 'role', 'direction' => $nextDirection('role')])) }}"
                                   class="flex items-center gap-1 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded"
                                   aria-label="Sort by role">
                                   Role
                                   @if ($currentSort === 'role')
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
                           <th scope="col" aria-sort="none" class="px-3 py-3.5">
                               <a href="{{ route('users.index', array_merge($queryParams, ['sort' => 'status', 'direction' => $nextDirection('status')])) }}"
                                   class="flex items-center gap-1 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded"
                                   aria-label="Sort by status">
                                   Status
                                   @if ($currentSort === 'status')
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
                           <th scope="col" aria-sort="none" class="px-3 py-3.5">
                               <a href="{{ route('users.index', array_merge($queryParams, ['sort' => 'created_at', 'direction' => $nextDirection('created_at')])) }}"
                                   class="flex items-center gap-1 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded"
                                   aria-label="Sort by created date">
                                   Created at
                                   @if ($currentSort === 'created_at')
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
                           <th scope="col" aria-sort="none" class="px-3 py-3.5">
                               <a href="{{ route('users.index', array_merge($queryParams, ['sort' => 'updated_at', 'direction' => $nextDirection('updated_at')])) }}"
                                   class="flex items-center gap-1 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded"
                                   aria-label="Sort by updated date">
                                   Updated at
                                   @if ($currentSort === 'updated_at')
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
                           <th scope="col" class="px-3 py-3.5">Actions</th>
                       </tr>
                   </thead>

                   <!-- Table Body -->
                   <tbody class="text-sm divide-y divide-slate-200 dark:divide-neutral-700">

                       @forelse($users as $user)
                       <tr class="has-[:checked]:bg-blue-50/50 dark:has-[:checked]:bg-blue-900/10">
                           <td class="w-8 pl-3 py-4">
                               <label class="group has-[input:checked]:text-slate-900 inline-block">
                                   <input type="checkbox" class="sr-only row-checkbox" />
                                   <span class="flex h-4 w-4 shrink-0 items-center justify-center rounded outline-1 outline-slate-300 dark:outline-neutral-700
                              bg-white dark:bg-neutral-800
                              group-has-[input:checked]:bg-blue-600
                              group-has-[input:checked]:outline-blue-600
                              group-focus-within:outline-2
                              group-focus-within:outline-blue-600" aria-hidden="true">
                                       <!-- Checkmark -->
                                       <svg class="size-3 text-white opacity-0 group-has-[input:checked]:opacity-100"
                                           viewBox="0 0 12 10" fill="none" stroke="currentColor" stroke-width="2">
                                           <path d="M1 5l3 3 7-7" />
                                       </svg>
                                   </span>
                               </label>
                           </td>
                           <td class="px-3 py-3.5 text-sm text-slate-900 dark:text-slate-50">{{ $user->id }}</td>
                           <td class="px-3 py-3.5 text-sm text-slate-900 dark:text-slate-50">{{ $user->first_name ?? '-' }}</td>
                           <td class="px-3 py-3.5 text-sm text-slate-900 dark:text-slate-50">{{ $user->last_name ?? '-' }}</td>
                           <td class="px-3 py-3.5 text-sm text-slate-900 dark:text-slate-50">{{ $user->email }}</td>
                           <td class="px-3 py-3.5 text-sm text-slate-900 dark:text-slate-50">{{ $user->roles->pluck('name')->join(', ') ?: '-' }}</td>
                           <td class="px-3 py-3.5 text-sm">
                               <span class="inline-flex items-center px-2.5 py-0.5 rounded-full   font-medium {{ $user->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                   {{ $user->status ? 'Active' : 'Inactive' }}
                               </span>
                           </td>
                           <td class="px-3 py-3.5 text-sm text-slate-900 dark:text-slate-50">{{ $user->created_at->format('M d, Y') }}</td>
                           <td class="px-3 py-3.5 text-sm text-slate-900 dark:text-slate-50">{{ $user->updated_at->format('M d, Y') }}</td>
                           <td class="px-3 py-3.5 text-sm">
                               <div class="flex gap-2">
                                   <a href="{{ route('users.edit', $user->id) }}" type="button"
                                       class="flex items-center gap-1.5 rounded-md   font-medium text-blue-700 bg-blue-50 border border-blue-200 px-2.5 py-1.5 cursor-pointer dark:bg-blue-900/20 dark:border-blue-900/40 dark:text-blue-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500"><x-lucide-pencil class="h-4 w-4" />Edit</a>
                                   <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                                       @csrf
                                       @method('DELETE')
                                       <button type="submit" class="flex items-center gap-1.5 rounded-md   font-medium text-red-700 bg-red-50 border border-red-200 px-2.5 py-1.5 cursor-pointer dark:bg-red-900/20 dark:border-red-900/40 dark:text-red-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500"><x-lucide-trash-2 class="h-4 w-4" />Delete</button>
                                   </form>
                               </div>
                           </td>
                       </tr>
                       @empty
                       <tr>
                           <td colspan="9" class="px-3 py-3.5 text-center text-slate-500 dark:text-slate-400">
                               No users found
                           </td>
                       </tr>
                       @endforelse
                   </tbody>
               </table>
           </div>

           <!-- pagination -->
           <div class="flex items-center justify-between flex-wrap gap-6 mx-auto mt-6">
               <div class="text-sm text-slate-600 dark:text-slate-400">
                   Showing
                   <span class="font-medium">{{ $users->firstItem() ?? 0 }}</span>
                   to
                   <span class="font-medium">{{ $users->lastItem() ?? 0 }}</span>
                   of
                   <span class="font-medium">{{ $users->total() }}</span>
                   results
               </div>

               <nav aria-label="Pagination"
                   class="flex items-center w-max rounded-md bg-white border border-slate-300 divide-x divide-slate-300 dark:bg-neutral-800 dark:border-neutral-700 dark:divide-neutral-700">

                   @if ($users->onFirstPage())
                   <button disabled
                       class="flex items-center justify-center shrink-0 w-9 h-9 rounded-l-[5px] text-slate-400 cursor-not-allowed dark:text-slate-600">
                       <x-lucide-chevron-left class="h-5 w-5" />
                   </button>
                   @else
                   <a href="{{ $users->previousPageUrl() }}" aria-label="Previous page"
                       class="flex items-center justify-center shrink-0 w-9 h-9 rounded-l-[5px] hover:bg-gray-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:bg-neutral-700">
                       <x-lucide-chevron-left class="h-5 w-5" />
                   </a>
                   @endif

                   {{-- Pagination Elements --}}
                   @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                   @if ($page == $users->currentPage())
                   <a aria-current="page"
                       class="flex items-center justify-center shrink-0 text-sm font-semibold text-white w-9 h-9 bg-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                       {{ $page }}
                   </a>
                   @else
                   <a href="{{ $url }}"
                       class="flex items-center justify-center shrink-0 text-sm font-semibold text-slate-900 w-9 h-9 hover:bg-gray-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:text-slate-50 dark:hover:bg-neutral-700">
                       {{ $page }}
                   </a>
                   @endif
                   @endforeach

                   @if ($users->hasMorePages())
                   <a href="{{ $users->nextPageUrl() }}" aria-label="Next page"
                       class="flex items-center justify-center shrink-0 w-9 h-9 rounded-r-[5px] hover:bg-gray-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:text-slate-50 dark:hover:bg-neutral-700">
                       <x-lucide-chevron-right class="h-5 w-5" />
                   </a>
                   @else
                   <button disabled
                       class="flex items-center justify-center shrink-0 w-9 h-9 rounded-r-[5px] text-slate-400 cursor-not-allowed dark:text-slate-600">
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
            <form id="filter-form" method="GET" action="{{ route('users.index') }}" class="flex flex-col space-y-4">
                <input type="hidden" name="limit" value="{{ request('limit', 10) }}" />
                <input type="hidden" name="sort" value="{{ request('sort', 'id') }}" />
                <input type="hidden" name="direction" value="{{ request('direction', 'asc') }}" />
                <div>
                    <label for="search" class="block text-sm font-medium mb-1">Search</label>
                    <input type="text" id="search" name="search" placeholder="Search users..." value="{{ request('search') }}" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" id="email" name="email" placeholder="Search by email..." value="{{ request('email') }}" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="role" class="block text-sm font-medium mb-1">Role</label>
                    <select id="role" name="role" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All</option>
                        @foreach($roles as $id => $name)
                        <option value="{{ $id }}" {{ request('role') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium mb-1">Status</label>
                    <select id="status" name="status" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="border-t pt-4 flex gap-2">
            <button type="button" onclick="document.getElementById('filter-form').submit()" class="flex-1 px-4 py-2 text-white text-sm font-medium rounded-md bg-blue-600 hover:bg-blue-700">
                Apply
            </button>
            <a href="{{ route('users.index') }}" class="flex-1 px-4 py-2 text-center text-slate-900 text-sm font-medium rounded-md border border-gray-300 hover:bg-gray-50">
                Reset
            </a>
        </div>
    </div>
</div>


   <script type="text/javascript">
       document.addEventListener("DOMContentLoaded", () => {
           const filterBtn = document.getElementById('filterBtn');
           const offcanvas = document.getElementById('offcanvas-right');
           const backdrop = document.getElementById('canvas-backdrop');
           const closeCanvas = document.getElementById('close-canvas');
           const masterCheckbox = document.getElementById('master-checkbox');
           const rowCheckboxes = document.querySelectorAll('.row-checkbox');

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

           masterCheckbox?.addEventListener('change', () => {
               const isChecked = masterCheckbox.checked;
               rowCheckboxes.forEach(checkbox => {
                   checkbox.checked = isChecked;
               });
           });

           rowCheckboxes.forEach(checkbox => {
               checkbox.addEventListener('change', () => {
                   if (!checkbox.checked) {
                       masterCheckbox.checked = false;
                   } else {
                       const allChecked = Array.from(rowCheckboxes).every(cb => cb.checked);
                       masterCheckbox.checked = allChecked;
                   }
               });
           });

           document.querySelectorAll('#limit').forEach(limitSelect => {
               limitSelect.addEventListener('change', function () {
                   const url = new URL(window.location.href);
                   url.searchParams.set('limit', this.value);
                   url.searchParams.delete('page');
                   window.location.href = url.toString();
               });
           });

           const importBtn = document.getElementById('importBtn');
           if (importBtn) {
               importBtn.addEventListener('click', function() {
                   const input = document.createElement('input');
                   input.type = 'file';
                   input.accept = '.csv,.xlsx';
                   input.onchange = e => {
                       const file = e.target.files[0];
                       alert('Import file: ' + file.name + '\nPlease implement backend import functionality.');
                   };
                   input.click();
               });
           }
       });

       function exportToExcel() {
           const table = document.querySelector('table');
           const rows = table.querySelectorAll('tr');
           let csvContent = "data:text/csv;charset=utf-8,";

           const checkedRows = document.querySelectorAll('.row-checkbox:checked');
           const dataRows = checkedRows.length > 0 ?
               Array.from(checkedRows).map(cb => cb.closest('tr')) :
               Array.from(rows).slice(1);

           const headerCells = table.querySelectorAll('thead th');
           const headerRow = Array.from(headerCells)
               .slice(1)
               .map(cell => `"${cell.textContent.trim()}"`)
               .join(',');
           csvContent += encodeURIComponent(headerRow + "\n");

           dataRows.forEach(row => {
               const cells = row.querySelectorAll('td');
               const dataRow = Array.from(cells)
                   .slice(1)
                   .map((cell, index) => {
                       if (index === Array.from(cells).length - 2) return "";
                       return `"${cell.textContent.trim()}"`;
                   })
                   .slice(0, -1)
                   .join(',');
               csvContent += encodeURIComponent(dataRow + "\n");
           });

           const link = document.createElement('a');
           link.setAttribute('href', csvContent);
           link.setAttribute('download', `users_${new Date().toISOString().split('T')[0]}.csv`);
           link.click();
       }
   </script>

   @endsection