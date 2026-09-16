@extends('layouts.backend')
@section('title', 'Generated Admit Cards')

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
                    <x-lucide-badge-check class="size-7 text-blue-600" />
                    <span>Generated Admit Cards</span>
                </h2>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Manage, search, re-print, and edit admit cards generated from the studio.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admitcard.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 text-white text-sm font-semibold rounded-md bg-blue-600 hover:bg-blue-700 shadow-sm transition-colors">
                    <x-lucide-plus class="size-4" />
                    <span>Open Admit Card Studio</span>
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
            <form class="max-w-md w-full sm:w-80" role="search" method="GET" action="{{ route('admitcard.records') }}">
                <div class="flex items-center gap-2.5 px-3 py-2.5 rounded-md bg-white dark:bg-neutral-800 border border-slate-300 dark:border-neutral-700 focus-within:border-blue-600 focus-within:ring-1 focus-within:ring-blue-600">
                    <label for="search" class="sr-only">Search</label>
                    <input type="search" id="search" name="search" placeholder="Search student, roll, course..." value="{{ request('search') }}"
                           class="text-sm text-slate-900 dark:text-slate-50 w-full outline-none bg-transparent" />
                    <button type="submit" class="text-slate-400 hover:text-slate-600">
                        <x-lucide-search class="size-4" />
                    </button>
                </div>
            </form>

            <div class="flex items-center gap-3 ml-auto">
                <form method="GET" action="{{ route('admitcard.records') }}" id="limitForm">
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
                            <a href="{{ route('admitcard.records', array_merge($queryParams, ['sort' => 'id', 'direction' => $nextDirection('id')])) }}" class="flex items-center gap-1">
                                ID
                                @if ($currentSort === 'id')
                                    {!! $currentDirection === 'asc' ? '<x-lucide-chevron-up class="size-3 text-blue-600"/>' : '<x-lucide-chevron-down class="size-3 text-blue-600"/>' !!}
                                @else
                                    <x-lucide-chevrons-up-down class="size-3 text-slate-400" />
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-4 py-3.5">Candidate</th>
                        <th scope="col" class="px-4 py-3.5">Roll No</th>
                        <th scope="col" class="px-4 py-3.5">Enrollment No</th>
                        <th scope="col" class="px-4 py-3.5">Course / Batch</th>
                        <th scope="col" class="px-4 py-3.5">Exam Centre</th>
                        <th scope="col" class="px-4 py-3.5">Generated Date</th>
                        <th scope="col" class="px-4 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-200 dark:divide-neutral-700 bg-white dark:bg-neutral-900">
                    @forelse($cards as $card)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-neutral-800/40 transition-colors">
                            <td class="px-4 py-3.5 font-semibold text-slate-500 text-xs">
                                #{{ $card->id }}
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $card->photo ?: asset('admitcard-studio/assets/student-photo.png') }}"
                                         alt="{{ $card->student_name }}"
                                         class="w-9 h-11 object-cover rounded border border-slate-200 dark:border-neutral-700 shrink-0 shadow-sm" />
                                    <div>
                                        <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $card->student_name }}</div>
                                        @if($card->parent_name)
                                            <div class="text-xs text-slate-500">S/D of {{ $card->parent_name }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 font-medium text-slate-800 dark:text-slate-200">
                                <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-neutral-800 font-mono text-xs">
                                    {{ $card->roll_no ?: '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 font-mono text-xs text-slate-600 dark:text-slate-400">
                                {{ $card->enrollment_no ?: '-' }}
                            </td>
                            <td class="px-4 py-3.5 text-slate-800 dark:text-slate-200">
                                <div class="font-medium text-xs sm:text-sm line-clamp-1" title="{{ $card->course_name }}">{{ $card->course_name ?: '-' }}</div>
                                <div class="text-xs text-slate-500">{{ $card->batch }} ({{ $card->pass_year }})</div>
                            </td>
                            <td class="px-4 py-3.5 text-xs text-slate-600 dark:text-slate-400 max-w-xs truncate" title="{{ $card->exam_centre }}">
                                {{ $card->exam_centre ?: '-' }}
                            </td>
                            <td class="px-4 py-3.5 text-xs text-slate-500 whitespace-nowrap">
                                {{ $card->created_at ? $card->created_at->format('M d, Y') : '-' }}
                            </td>
                            <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Print Standalone -->
                                    <a href="{{ route('admitcard.print', ['id' => $card->id]) }}" target="_blank"
                                       class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-md bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 font-medium text-xs transition-colors"
                                       title="Print Admit Card">
                                        <x-lucide-printer class="size-3.5" />
                                        <span>Print</span>
                                    </a>

                                    <!-- Edit in Studio -->
                                    <a href="{{ route('admitcard.index', ['id' => $card->id]) }}"
                                       class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-md bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-neutral-800 dark:text-slate-300 font-medium text-xs transition-colors"
                                       title="Edit in Studio">
                                        <x-lucide-pencil class="size-3.5" />
                                        <span>Edit</span>
                                    </a>

                                    <!-- Delete -->
                                    <form action="{{ route('admitcard.destroy', $card->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this Admit Card?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center p-1.5 rounded-md text-red-500 hover:bg-red-50 dark:hover:bg-red-950/40 transition-colors"
                                                title="Delete Admit Card">
                                            <x-lucide-trash-2 class="size-3.5" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-slate-500 dark:text-slate-400">
                                <x-lucide-inbox class="size-10 mx-auto text-slate-400 mb-2" />
                                <p class="text-base font-semibold">No Admit Cards Found</p>
                                <p class="text-xs text-slate-400 mt-1">Generate your first admit card from the Admit Card Studio.</p>
                                <a href="{{ route('admitcard.index') }}" class="inline-flex items-center gap-1.5 mt-4 px-3.5 py-2 text-xs font-semibold rounded-md bg-blue-600 text-white hover:bg-blue-700">
                                    <x-lucide-plus class="size-3.5" /> Open Studio
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($cards->hasPages())
            <div class="mt-6">
                {{ $cards->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
