<?php $__env->startSection('title', 'Backend Settings'); ?>

<?php $__env->startSection('content'); ?>

<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-slate-900 mb-1 md:text-2xl dark:text-slate-50">
            Backend Settings
        </h2>
        <p class="text-base leading-relaxed text-slate-600 dark:text-slate-400">
            Customize your backend logo, sidebar styling, links, buttons, and text colors.
        </p>
    </div>

    <?php if($errors->any()): ?>
        <div class="mb-6 rounded border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <strong class="block font-semibold">Please fix the following errors:</strong>
            <ul class="mt-2 list-disc list-inside">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form class="space-y-8" method="POST" action="<?php echo e(route('settings.update')); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="lg:col-span-2">
                <label for="logo_image" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Logo Image</label>
                <div class="flex items-center gap-4">
                    <?php ($settings = $user->backend_settings ?? []); ?>
                    <?php if(!empty($settings['logo_image'])): ?>
                        <img src="<?php echo e($settings['logo_image']); ?>" alt="Backend Logo" class="h-16 max-w-48 rounded-md object-contain border border-slate-200">
                    <?php else: ?>
                        <div class="flex h-16 w-40 items-center justify-center rounded-md border border-slate-200 bg-slate-100 text-sm text-slate-500">No logo</div>
                    <?php endif; ?>
                    <input type="file" id="logo_image" name="logo_image" accept="image/*"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:border-neutral-600 dark:bg-neutral-700 dark:text-slate-50" />
                </div>
            </div>

            <div>
                <label for="sidebar_title" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Sidebar Title</label>
                <input type="text" id="sidebar_title" name="sidebar_title" value="<?php echo e(old('sidebar_title', $settings['sidebar_title'] ?? 'H.S Coaching')); ?>"
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:border-neutral-600 dark:bg-neutral-700 dark:text-slate-50" />
            </div>

            <div>
                <label for="sidebar_background_color" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Sidebar Background Color</label>
                <input type="color" id="sidebar_background_color" name="sidebar_background_color" value="<?php echo e(old('sidebar_background_color', $settings['sidebar_background_color'] ?? '#ffffff')); ?>"
                    class="h-11 w-full rounded-md border border-slate-300 bg-white p-1 dark:border-neutral-600 dark:bg-neutral-700" />
            </div>

            <div>
                <label for="mainbar_background_color" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Mainbar Background Color</label>
                <input type="color" id="mainbar_background_color" name="mainbar_background_color" value="<?php echo e(old('mainbar_background_color', $settings['mainbar_background_color'] ?? '#f8fafc')); ?>"
                    class="h-11 w-full rounded-md border border-slate-300 bg-white p-1 dark:border-neutral-600 dark:bg-neutral-700" />
            </div>

            <div>
                <label for="link_color" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Link Color</label>
                <input type="color" id="link_color" name="link_color" value="<?php echo e(old('link_color', $settings['link_color'] ?? '#2563eb')); ?>"
                    class="h-11 w-full rounded-md border border-slate-300 bg-white p-1 dark:border-neutral-600 dark:bg-neutral-700" />
            </div>

            <div>
                <label for="button_text_color" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Button Text Color</label>
                <input type="color" id="button_text_color" name="button_text_color" value="<?php echo e(old('button_text_color', $settings['button_text_color'] ?? '#ffffff')); ?>"
                    class="h-11 w-full rounded-md border border-slate-300 bg-white p-1 dark:border-neutral-600 dark:bg-neutral-700" />
            </div>

            <div>
                <label for="button_background_color" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Button Background Color</label>
                <input type="color" id="button_background_color" name="button_background_color" value="<?php echo e(old('button_background_color', $settings['button_background_color'] ?? '#0f766e')); ?>"
                    class="h-11 w-full rounded-md border border-slate-300 bg-white p-1 dark:border-neutral-600 dark:bg-neutral-700" />
            </div>

            <div>
                <label for="body_text_color" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Body Text Color</label>
                <input type="color" id="body_text_color" name="body_text_color" value="<?php echo e(old('body_text_color', $settings['body_text_color'] ?? '#334155')); ?>"
                    class="h-11 w-full rounded-md border border-slate-300 bg-white p-1 dark:border-neutral-600 dark:bg-neutral-700" />
            </div>
        </div>

        <button type="submit"
            class="inline-flex items-center justify-center rounded-md bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            Save Settings
        </button>
    </form>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-js'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/willpowe/domains/apeirojobs.com/resources/views/backend/settings/profile.blade.php ENDPATH**/ ?>