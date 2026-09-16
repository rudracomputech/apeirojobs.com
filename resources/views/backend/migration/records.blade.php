@extends('layouts.backend')
@section('title', 'Generated Migration Certificates')

@section('content')
<div class="bg-white dark:bg-neutral-900 overflow-hidden shadow-xl sm:rounded-lg p-6 sm:p-8">
    <div class="mx-auto">
        @php
            $currentSort = request('sort', 'id');
            $currentDirection = request('direction', 'desc');
            $queryParams = request()->except('page');
            $nextDirection = fn($column) => $currentSort === $column && $currentDirection === 'asc' ? 'desc' : 'asc';
        @endphp

        <!-- Top Header & Summary -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-50 flex items-center gap-2">
                    <x-lucide-scroll class="size-7 text-emerald-600" />
                    <span>Generated Migration Certificates</span>
                </h2>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Manage, search, re-print, and edit migration certificates generated from the studio.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('migration.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 text-white text-sm font-semibold rounded-md bg-emerald-600 hover:bg-emerald-700 shadow-sm transition-colors">
                    <x-lucide-plus class="size-4" />
                    <span>Open Migration Studio</span>
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-2">
                <x-lucide-check-circle class="size-5 text-emerald-600 shrink-0" />
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Filter & Search Bar -->
        <div class="flex flex-wrap items-center gap-4 mb-6">
            <form class="max-w-md w-full sm:w-80" role="search" method="GET" action="{{ route('migration.records') }}">
                <div class="flex items-center gap-2.5 px-3 py-2.5 rounded-md bg-white dark:bg-neutral-800 border border-slate-300 dark:border-neutral-700 focus-within:border-emerald-600 focus-within:ring-1 focus-within:ring-emerald-600">
                    <label for="search" class="sr-only">Search</label>
                    <input type="search" id="search" name="search" placeholder="Search student, serial, course..." value="{{ request('search') }}"
                           class="text-sm text-slate-900 dark:text-slate-50 w-full outline-none bg-transparent" />
                    <button type="submit" class="text-slate-400 hover:text-slate-600">
                        <x-lucide-search class="size-4" />
                    </button>
                </div>
            </form>

            <div class="flex items-center gap-3 ml-auto">
                <form method="GET" action="{{ route('migration.records') }}" id="limitForm">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    <select name="limit" onchange="document.getElementById('limitForm').submit()"
                            class="text-xs sm:text-sm font-medium rounded-md px-3 py-2 bg-white dark:bg-neutral-800 border border-slate-300 dark:border-neutral-700 text-slate-700 dark:text-slate-300 outline-none">
                        <option value="15" {{ request('limit', 15) == 15 ? 'selected' : '' }}>15 per page</option>
                        <option value="30" {{ request('limit') == 30 ? 'selected' : '' }}>30 per page</option>
                        <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50 per page</option>
                        <option value="all" {{ request('limit') == 'all' ? 'selected' : '' }}>All Records</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto rounded-lg border border-slate-200 dark:border-neutral-700">
            <table class="w-full text-left border-collapse">
                <thead class="text-xs uppercase font-semibold text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-neutral-800 border-b border-slate-200 dark:border-neutral-700">
                    <tr>
                        <th scope="col" class="px-4 py-3.5 w-16">
                            <a href="{{ route('migration.records', array_merge($queryParams, ['sort' => 'id', 'direction' => $nextDirection('id')])) }}" class="flex items-center gap-1">
                                ID
                                @if ($currentSort === 'id')
                                    {!! $currentDirection === 'asc' ? '<x-lucide-chevron-up class="size-3 text-emerald-600"/>' : '<x-lucide-chevron-down class="size-3 text-emerald-600"/>' !!}
                                @else
                                    <x-lucide-chevrons-up-down class="size-3 text-slate-400" />
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-4 py-3.5">Student</th>
                        <th scope="col" class="px-4 py-3.5">Serial No</th>
                        <th scope="col" class="px-4 py-3.5">Enrollment No</th>
                        <th scope="col" class="px-4 py-3.5">Course Name</th>
                        <th scope="col" class="px-4 py-3.5">Year of Passing</th>
                        <th scope="col" class="px-4 py-3.5">Place / Date</th>
                        <th scope="col" class="px-4 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-200 dark:divide-neutral-700 bg-white dark:bg-neutral-900">
                    @forelse($certificates as $cert)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-neutral-800/40 transition-colors">
                            <td class="px-4 py-3.5 font-semibold text-slate-500 text-xs">
                                #{{ $cert->id }}
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="font-semibold text-slate-900 dark:text-slate-100">
                                    {{ $cert->student_prefix }} {{ $cert->student_name }}
                                </div>
                                @if($cert->parent_name)
                                    <div class="text-xs text-slate-500">{{ $cert->sd_prefix ?: 'S/D of' }} {{ $cert->parent_name }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 font-medium text-slate-800 dark:text-slate-200">
                                <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300 font-mono text-xs font-semibold">
                                    {{ $cert->serial_no ?: '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 font-mono text-xs text-slate-600 dark:text-slate-400">
                                {{ $cert->enrollment_no ?: '-' }}
                            </td>
                            <td class="px-4 py-3.5 text-slate-800 dark:text-slate-200">
                                <div class="font-medium text-xs sm:text-sm line-clamp-1" title="{{ $cert->course_name }}">{{ $cert->course_name ?: '-' }}</div>
                                <div class="text-xs text-slate-500">{{ $cert->inst_name ?: 'All India Council for Vocational & Paramedical Science' }}</div>
                            </td>
                            <td class="px-4 py-3.5 text-xs font-semibold text-slate-700 dark:text-slate-300">
                                {{ $cert->pass_year ?: '-' }}
                            </td>
                            <td class="px-4 py-3.5 text-xs text-slate-500 whitespace-nowrap">
                                <div>{{ $cert->place_name ?: 'Delhi' }}</div>
                                <div class="text-[11px] text-slate-400">{{ $cert->cert_date ?: ($cert->created_at ? $cert->created_at->format('d-m-Y') : '-') }}</div>
                            </td>
                            <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Print Standalone -->
                                    <a href="{{ route('migration.print', ['id' => $cert->id]) }}" target="_blank"
                                       class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-md bg-emerald-50 text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400 font-medium text-xs transition-colors"
                                       title="Print Migration Certificate">
                                        <x-lucide-printer class="size-3.5" />
                                        <span>Print</span>
                                    </a>

                                    <!-- Edit in Studio -->
                                    <a href="{{ route('migration.index', ['id' => $cert->id]) }}"
                                       class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-md bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-neutral-800 dark:text-slate-300 font-medium text-xs transition-colors"
                                       title="Edit in Studio">
                                        <x-lucide-pencil class="size-3.5" />
                                        <span>Edit</span>
                                    </a>

                                    <!-- Delete -->
                                    <form action="{{ route('migration.destroy', $cert->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this Migration Certificate?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center p-1.5 rounded-md text-red-500 hover:bg-red-50 dark:hover:bg-red-950/40 transition-colors"
                                                title="Delete Migration Certificate">
                                            <x-lucide-trash-2 class="size-3.5" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-slate-500 dark:text-slate-400">
                                <x-lucide-scroll class="size-10 mx-auto text-slate-400 mb-2" />
                                <p class="text-base font-semibold">No Migration Certificates Found</p>
                                <p class="text-xs text-slate-400 mt-1">Generate your first migration certificate from the Migration Studio.</p>
                                <a href="{{ route('migration.index') }}" class="inline-flex items-center gap-1.5 mt-4 px-3.5 py-2 text-xs font-semibold rounded-md bg-emerald-600 text-white hover:bg-emerald-700">
                                    <x-lucide-plus class="size-3.5" /> Open Studio
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($certificates->hasPages())
            <div class="mt-6">
                {{ $certificates->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
