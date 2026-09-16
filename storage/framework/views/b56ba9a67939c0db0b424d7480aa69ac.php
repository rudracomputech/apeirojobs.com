<?php $__env->startSection('title', 'Clients'); ?>

<?php $__env->startSection('content'); ?>

<div class=" bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
    <div class=" mx-auto">
        <?php
        $currentSort = request('sort', 'id');
        $currentDirection = request('direction', 'asc');
        $queryParams = request()->except('page');
        $nextDirection = fn($column) => $currentSort === $column && $currentDirection === 'asc' ? 'desc' : 'asc';
        ?>
        <div class="mb-6 w-full">
            <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-50">Clients</h2>
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-6">Manage your clients and their details.</p>
         <a href="/sample_client_sheet.xlsx" download class="inline-flex items-center gap-2 px-3.5 py-2 text-white text-sm font-semibold rounded-md cursor-pointer bg-blue-600 hover:bg-blue-700">Download Sample Import file</a>
       
        </div>
     
        <div class="flex flex-wrap items-center gap-6 mb-6">
            <form class="max-w-xs" role="search" method="GET" action="<?php echo e(route('clients.index')); ?>">
                <div class="flex items-center gap-2.5 px-3 py-2.5 rounded-md bg-white dark:bg-neutral-800 outline-1 -outline-offset-1 outline-slate-300 dark:outline-neutral-700 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-blue-600">
                    <label for="search" class="sr-only">Search</label>
                    <input type="search" id="search" name="search" placeholder="Search clients..." value="<?php echo e(request('search')); ?>"
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

                <form id="bulkActionForm" method="POST" action="<?php echo e(route('clients.bulkAction')); ?>" class="flex items-center gap-4">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="action_type" id="bulk_action_type" value="" />
                    <div id="bulk-action-ids"></div>
                    <div class="w-[150px]">
                        <select id="action" name="action" class="w-full inline-flex items-center gap-2 px-3.5 py-2 text-slate-900 text-sm font-semibold rounded-md cursor-pointer bg-white hover:bg-gray-100 border border-slate-300 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-slate-50 dark:hover:bg-neutral-700">
                            <option disabled selected>Mass Action</option>
                            <option value="delete">Delete</option>
                        </select>
                    </div>
                </form>

                <div class="w-[150px]">
                    <select id="limit" name="limit" class="w-full inline-flex items-center gap-2 px-3.5 py-2 text-slate-900 text-sm font-semibold rounded-md cursor-pointer bg-white hover:bg-gray-100 border border-slate-300 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-slate-50 dark:hover:bg-neutral-700">
                        <option value="10" <?php echo e(request('limit') == 10 ? ' selected' : ''); ?>>10 per page</option>
                        <option value="25" <?php echo e(request('limit') == 25 ? ' selected' : ''); ?>>25 per page</option>
                        <option value="50" <?php echo e(request('limit') == 50 ? ' selected' : ''); ?>>50 per page</option>
                        <option value="100" <?php echo e(request('limit') == 100 ? ' selected' : ''); ?>>100 per page</option>
                        <option value="all" <?php echo e(request('limit') == 'all' ? ' selected' : ''); ?>>All </option>
                    </select>
                </div>

                <button id="open-canvas" type="button" id="filterBtn"
                    class="flex items-center gap-2 px-3.5 py-2 text-slate-900 text-sm font-semibold rounded-md cursor-pointer bg-white hover:bg-gray-100 border border-slate-300 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-slate-50 dark:hover:bg-neutral-700">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-funnel'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                    Filter</button>

                <button type="button" id="exportBtn" onclick="exportToXlsx()"
                    class="flex items-center gap-2 px-3.5 py-2 text-slate-900 text-sm font-semibold rounded-md cursor-pointer bg-white hover:bg-gray-100 border border-slate-300 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-slate-50 dark:hover:bg-neutral-700">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-download'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                    Export</button>

                <button type="button" id="importBtn"
                    class="flex items-center gap-2 px-3.5 py-2 text-slate-900 text-sm font-semibold rounded-md cursor-pointer bg-white hover:bg-gray-100 border border-slate-300 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-slate-50 dark:hover:bg-neutral-700">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-upload'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                    Import</button>

                <a href="<?php echo e(route('clients.create')); ?>"
                    class="flex items-center gap-2 px-3.5 py-2 text-white text-sm font-semibold rounded-md cursor-pointer bg-blue-600 hover:bg-blue-700 border border-blue-600 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-plus'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                    Add Client</a>
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
                            <a href="<?php echo e(route('clients.index', array_merge($queryParams, ['sort' => 'id', 'direction' => $nextDirection('id')]))); ?>" class="flex items-center gap-1" aria-label="Sort by ID">
                                ID
                                <?php if($currentSort === 'id'): ?>
                                <?php if($currentDirection === 'asc'): ?>
                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-chevron-up'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-3 fill-blue-600']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                                <?php else: ?>
                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-chevron-down'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-3 fill-blue-600']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                                <?php endif; ?>
                                <?php else: ?>
                                <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-chevrons-up-down'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-3 fill-slate-400']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th class="px-3 py-3.5">Date</th>
                        <th class="px-3 py-3.5">Client Name</th>
                        <th class="px-3 py-3.5">Email</th>
                        <th class="px-3 py-3.5">Mobile</th>
                        <th class="px-3 py-3.5">City</th>
                        <th class="px-3 py-3.5">Calling Status</th>
                        <th class="px-3 py-3.5">Vacancy Status</th>
                        <th class="px-3 py-3.5">Proposal Status</th>
                        <th class="px-3 py-3.5">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-200 dark:divide-neutral-700">
                    <?php $__empty_1 = true; $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="has-[:checked]:bg-blue-50/50 dark:has-[:checked]:bg-blue-900/10">
                        <td class="w-8 pl-3 py-4">
                            <label class="group has-[input:checked]:text-slate-900 inline-block">
                                <input type="checkbox" class="sr-only row-checkbox" value="<?php echo e($client->id); ?>" />
                                <span class="flex h-4 w-4 shrink-0 items-center justify-center rounded outline-1 outline-slate-300 dark:outline-neutral-700 bg-white dark:bg-neutral-800 group-has-[input:checked]:bg-blue-600 group-has-[input:checked]:outline-blue-600 group-focus-within:outline-2 group-focus-within:outline-blue-600" aria-hidden="true">
                                    <svg class="size-3 text-white opacity-0 group-has-[input:checked]:opacity-100" viewBox="0 0 12 10" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 5l3 3 7-7" />
                                    </svg>
                                </span>
                            </label>
                        </td>
                        <td class="px-3 py-3.5 text-sm text-slate-900 dark:text-slate-50"><?php echo e($client->id); ?></td>
                        <td class="px-3 py-3.5 text-sm text-slate-900 dark:text-slate-50"><?php echo e($client->date ? $client->date->format('M d, Y') : '-'); ?></td>
                        <td class="px-3 py-3.5 text-sm text-slate-900 dark:text-slate-50 font-medium"><?php echo e($client->client_name); ?></td>
                        <td class="px-3 py-3.5 text-sm text-slate-900 dark:text-slate-50"><?php echo e($client->email_id ?? '-'); ?></td>
                        <td class="px-3 py-3.5 text-sm text-slate-900 dark:text-slate-50"><?php echo e($client->mobile_no ?? '-'); ?></td>
                        <td class="px-3 py-3.5 text-sm text-slate-900 dark:text-slate-50"><?php echo e($client->city ?? '-'); ?></td>
                        <td class="px-3 py-3.5 text-sm">
                            <?php if($client->calling_status): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium text-xs bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                <?php echo e($client->calling_status); ?>

                            </span>
                            <?php else: ?>
                            <span class="text-slate-400">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-3 py-3.5 text-sm">
                            <?php if($client->vacancy_status): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium text-xs bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                <?php echo e($client->vacancy_status); ?>

                            </span>
                            <?php else: ?>
                            <span class="text-slate-400">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-3 py-3.5 text-sm">
                            <?php if($client->proposal_status): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium text-xs bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                <?php echo e($client->proposal_status); ?>

                            </span>
                            <?php else: ?>
                            <span class="text-slate-400">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-3 py-3.5 text-sm">
                            <div class="flex gap-2 items-center">
                                <a href="<?php echo e(route('clients.edit', $client->id)); ?>" class="flex items-center gap-1.5 rounded-md font-medium text-blue-700 bg-blue-50 border border-blue-200 px-2.5 py-1.5 cursor-pointer dark:bg-blue-900/20 dark:border-blue-900/40 dark:text-blue-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-pencil'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-4 w-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>Edit
                                </a>
                                <form action="<?php echo e(route('clients.destroy', $client->id)); ?>" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this client?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="flex items-center gap-1.5 rounded-md font-medium text-red-700 bg-red-50 border border-red-200 px-2.5 py-1.5 cursor-pointer dark:bg-red-900/20 dark:border-red-900/40 dark:text-red-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500">
                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-trash-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-4 w-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="10" class="px-3 py-3.5 text-center text-slate-500 dark:text-slate-400">No clients found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between flex-wrap gap-6 mx-auto mt-6">
            <div class="text-sm text-slate-600 dark:text-slate-400">
                Showing
                <span class="font-medium"><?php echo e($clients->firstItem() ?? 0); ?></span>
                to
                <span class="font-medium"><?php echo e($clients->lastItem() ?? 0); ?></span>
                of
                <span class="font-medium"><?php echo e($clients->total()); ?></span>
                results
            </div>

            <nav aria-label="Pagination" class="flex items-center w-max rounded-md bg-white border border-slate-300 divide-x divide-slate-300 dark:bg-neutral-800 dark:border-neutral-700 dark:divide-neutral-700">
                <?php if($clients->onFirstPage()): ?>
                <button disabled class="flex items-center justify-center shrink-0 w-9 h-9 rounded-l-[5px] text-slate-400 cursor-not-allowed dark:text-slate-600">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-chevron-left'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-5 w-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                </button>
                <?php else: ?>
                <a href="<?php echo e($clients->previousPageUrl()); ?>" aria-label="Previous page" class="flex items-center justify-center shrink-0 w-9 h-9 rounded-l-[5px] hover:bg-gray-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:bg-neutral-700">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-chevron-left'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-5 w-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                </a>
                <?php endif; ?>

                <?php $__currentLoopData = $clients->getUrlRange(1, $clients->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($page == $clients->currentPage()): ?>
                <a aria-current="page" class="flex items-center justify-center shrink-0 text-sm font-semibold text-white w-9 h-9 bg-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500"><?php echo e($page); ?></a>
                <?php else: ?>
                <a href="<?php echo e($url); ?>" class="flex items-center justify-center shrink-0 text-sm font-semibold text-slate-900 w-9 h-9 hover:bg-gray-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:text-slate-50 dark:hover:bg-neutral-700"><?php echo e($page); ?></a>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php if($clients->hasMorePages()): ?>
                <a href="<?php echo e($clients->nextPageUrl()); ?>" aria-label="Next page" class="flex items-center justify-center shrink-0 w-9 h-9 rounded-r-[5px] hover:bg-gray-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:text-slate-50 dark:hover:bg-neutral-700">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-chevron-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-5 w-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                </a>
                <?php else: ?>
                <button disabled class="flex items-center justify-center shrink-0 w-9 h-9 rounded-r-[5px] text-slate-400 cursor-not-allowed dark:text-slate-600">
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-chevron-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-5 w-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                </button>
                <?php endif; ?>
            </nav>
        </div>
    </div>
</div>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-js'); ?>
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
            <form id="filter-form" method="GET" action="<?php echo e(route('clients.index')); ?>" class="flex flex-col space-y-2">
                <nav class="flex flex-col space-y-2">
                    <div>
                        <label for="search" class="block text-sm font-medium mb-1">Search</label>
                        <input type="text" id="search" name="search" placeholder="Search clients..." value="<?php echo e(request('search')); ?>" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="email_id" class="block text-sm font-medium mb-1">Email</label>
                        <input type="email" id="email_id" name="email_id" placeholder="Search by email..." value="<?php echo e(request('email_id')); ?>" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="mobile_no" class="block text-sm font-medium mb-1">Mobile No.</label>
                        <input type="text" id="mobile_no" name="mobile_no" placeholder="Search by mobile..." value="<?php echo e(request('mobile_no')); ?>" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="city" class="block text-sm font-medium mb-1">City</label>
                        <input type="text" id="city" name="city" placeholder="Search by city..." value="<?php echo e(request('city')); ?>" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <label for="calling_status" class="block text-sm font-medium mb-1 text-gray-700">Calling Status</label>
                        <input type="text" id="calling_status" name="calling_status" placeholder="Filter by calling status..." value="<?php echo e(request('calling_status')); ?>" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="vacancy_status" class="block text-sm font-medium mb-1 text-gray-700">Vacancy Status</label>
                        <input type="text" id="vacancy_status" name="vacancy_status" placeholder="Filter by vacancy status..." value="<?php echo e(request('vacancy_status')); ?>" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="proposal_status" class="block text-sm font-medium mb-1 text-gray-700">Proposal Status</label>
                        <input type="text" id="proposal_status" name="proposal_status" placeholder="Filter by proposal status..." value="<?php echo e(request('proposal_status')); ?>" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700"> Date Range</label>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label for="created_at_from" class="block   font-medium mb-1 text-gray-600">From</label>
                                <input type="date" id="created_at_from" name="created_at_from" value="<?php echo e(request('created_at_from')); ?>" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                            <div>
                                <label for="created_at_to" class="block   font-medium mb-1 text-gray-600">To</label>
                                <input type="date" id="created_at_to" name="created_at_to" value="<?php echo e(request('created_at_to')); ?>" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500" />
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
            <a href="<?php echo e(route('clients.index')); ?>" class="flex-1 px-4 py-2 text-center text-slate-900 text-sm font-medium rounded-md border border-gray-300 hover:bg-gray-50">
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

        <button type="button" aria-label="Close modal"
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
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="client_id" id="followup_client_id" value="" />
                            <div class="space-y-4">
                                <div>
                                    <label for="followup_type" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Follow Up Type</label>
                                    <select id="followup_type" name="followup_type" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                                        <option value="">Select type</option>
                                        <option value="call" <?php echo e(old('followup_type') === 'call' ? ' selected' : ''); ?>>Call</option>
                                        <option value="whatsapp" <?php echo e(old('followup_type') === 'whatsapp' ? ' selected' : ''); ?>>WhatsApp</option>
                                        <option value="email" <?php echo e(old('followup_type') === 'email' ? ' selected' : ''); ?>>Email</option>
                                        <option value="meeting" <?php echo e(old('followup_type') === 'meeting' ? ' selected' : ''); ?>>Meeting</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="followup_date" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Follow Up Date</label>
                                    <input type="datetime-local" id="followup_date" name="followup_date" value="<?php echo e(old('followup_date')); ?>" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
                                </div>

                                <div>
                                    <label for="next_followup_date" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Next Follow Up Date</label>
                                    <input type="datetime-local" id="next_followup_date" name="next_followup_date" value="<?php echo e(old('next_followup_date')); ?>" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
                                </div>

                                <div>
                                    <label for="call_duration" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Call Duration (minutes)</label>
                                    <input type="number" id="call_duration" name="call_duration" min="0" value="<?php echo e(old('call_duration')); ?>" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
                                </div>

                                <div>
                                    <label for="note" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Note</label>
                                    <textarea id="note" name="note" rows="4" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600"><?php echo e(old('note')); ?></textarea>
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
                            Select a client to view follow-up history.
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
            <h3 id="modal-title" class="text-slate-900 text-lg font-semibold flex-1 dark:text-slate-50">Assign Client
            </h3>

            <button type="button" aria-label="Close modal"
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


        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function() {
                        // ==================== DOM Elements ====================
                        const openBtn = document.getElementById('open-canvas');
                        const closeBtn = document.getElementById('close-canvas');
                        const backdrop = document.getElementById('canvas-backdrop');
                        const offcanvas = document.getElementById('offcanvas-right');
                        const importBtn = document.getElementById('importBtn');
                        const limitSelect = document.getElementById('limit');
                        const actionSelect = document.getElementById('action');
                        const masterCheckbox = document.getElementById('master-checkbox');
                        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
                        const bulkActionForm = document.getElementById('bulkActionForm');
                        const bulkAssignSubmit = document.getElementById('bulkAssignSubmit');
                        const overlay = document.getElementById("modalOverlay");
                        const dialog = overlay.querySelector("[role='dialog']");
                        const followupForm = document.getElementById('followupForm');
                        const followupClientIdInput = document.getElementById('followup_client_id');
                        const modalTitle = document.getElementById('modal-title');
                        const followupHistoryContainer = document.getElementById('followupHistoryContainer');

                        // ==================== Offcanvas Functions ====================
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

                        // Attach offcanvas listeners
                        openBtn?.addEventListener('click', openOffcanvas);
                        closeBtn?.addEventListener('click', closeOffcanvas);
                        backdrop?.addEventListener('click', closeOffcanvas);

                        // ==================== Export to XLSX ====================
                        window.exportToXlsx = function() {
                            const exportUrl = '<?php echo e(route('clients.export')); ?>';
                            const checkedRows = document.querySelectorAll('.row-checkbox:checked');
                            const selectedIds = Array.from(checkedRows).map(cb => cb.value);
                            const url = new URL(exportUrl, window.location.origin);

                            if (selectedIds.length > 0) {
                                selectedIds.forEach(id => url.searchParams.append('ids[]', id));
                            }

                            window.location.href = url.toString();
                        };

                        // ==================== Bulk Action Handler ====================
                        // ==================== Bulk Action Handler ====================
                        actionSelect?.addEventListener('change', function() {
                            const selectedAction = this.value;
                            const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;

                            if (checkedCount === 0) {
                                alert("Please select at least one client to perform this action.");
                                this.value = "";
                                return;
                            }

                            if (selectedAction === "delete") {
                                if (confirm("Are you sure you want to delete the selected clients? This action cannot be undone.")) {
                                    collectSelectedClientIds();
                                    bulkActionForm.submit();
                                } else {
                                    this.value = "";
                                }
                            }
                        });

                        // ==================== Helper Functions ====================
                        function collectSelectedClientIds() {
                            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
                            const container = document.getElementById('bulk-action-ids');
                            container.innerHTML = '';

                            checkedBoxes.forEach(checkbox => {
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'selected_ids[]';
                                input.value = checkbox.value;
                                container.appendChild(input);
                            });
                        }

                        function renderFollowupHistory(clientId) {
                            const followups = window.followupData?.[clientId] || [];
                            if (!followups.length) {
                                followupHistoryContainer.innerHTML = `
               <div class="rounded-lg border border-dashed border-slate-300 bg-white p-6 text-sm text-slate-600 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-300">
                   No follow ups have been added for this client yet.
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

                        // ==================== Import Functionality ====================
                        importBtn?.addEventListener('click', function() {
                            const input = document.createElement('input');
                            input.type = 'file';
                            input.accept = '.xlsx,.xls';
                            input.onchange = async (e) => {
                                const file = e.target.files[0];
                                if (!file) return;

                                if (file.size > 5 * 1024 * 1024) {
                                    alert('File size exceeds 5MB limit.');
                                    return;
                                }

                                const formData = new FormData();
                                formData.append('file', file);
                                formData.append('_token', '<?php echo e(csrf_token()); ?>');

                                try {
                                    const response = await fetch('<?php echo e(route('clients.import')); ?>', {
                                        method: 'POST',
                                        body: formData,
                                    });
                                    const data = await response.json();
                                    if (response.ok && data.success) {
                                        alert(data.message || 'Import completed successfully!');
                                        location.reload();
                                    } else {
                                        alert(data.message || 'Import failed!');
                                    }
                                } catch (error) {
                                    alert('Error uploading file: ' + error);
                                }
                            };
                            input.click();
                        });
                    });
        </script>

        <?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.backend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/willpowe/domains/apeirojobs.com/resources/views/backend/clients/index.blade.php ENDPATH**/ ?>