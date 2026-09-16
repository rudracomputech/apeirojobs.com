<!-- sidebar -->
<aside class="sm:w-0   overflow-hidden opacity-100 transition-all duration-300 ease-in-out" id="sidebar"
   aria-label="Sidebar navigation">
   <div id="sidebar-inner"
      class="fixed top-0 left-0 w-[264px] h-full flex flex-col overflow-auto py-6 px-4 border-r border-slate-300 dark:border-neutral-700"
      style="background-color: <?php echo e($settings['sidebar_background_color'] ?? '#ffffff'); ?>; color: <?php echo e($settings['body_text_color'] ?? '#334155'); ?>;">

      <div class="mb-8 justify-center flex items-center gap-2">
         <a href="#"
            class="min-h-9 inline-flex gap-2 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded">
            <?php if(isset($settings['logo_image']) && $settings['logo_image']): ?>
               <img src="<?php echo e($settings['logo_image']); ?>" alt="Logo" class="h-9 w-auto">
               <span
                  class="text-2xl  font-bold bg-gradient-to-r from-primary to-black bg-clip-text text-transparent"><?php echo e($settings['sidebar_title'] ?? 'H.S Coaching'); ?></span>

            <?php else: ?>
               <span
                  class="text-3xl font-bold bg-gradient-to-r from-primary to-black bg-clip-text text-transparent"><?php echo e($settings['sidebar_title'] ?? 'H.S Coaching'); ?></span>
            <?php endif; ?>
         </a>
      </div>


      <nav class="flex-1" aria-label="Primary sidebar navigation">

         <ul class="space-y-2 text-sm text-slate-800 dark:text-slate-400 font-medium">
            <li>
               <a href="<?php echo e(route('dashboard')); ?>"
                  class="<?php echo e(request()->routeIs('dashboard') ? ' bg-slate-100 ' : ''); ?> flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                  <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-layout-dashboard'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-[18px] fill-current overflow-visible']); ?>
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
                  <span style="color: <?php echo e($settings['link_color'] ?? '#2563eb'); ?>;">Dashboard</span>
               </a>
            </li>

            <?php if(!auth()->user()->hasRole('Student')): ?>
               <div>
                  <button type="button" aria-controls="sub-menu-1"
                     aria-expanded="<?php echo e(request()->routeIs('leads.*') || request()->routeIs('statuses.*') ? 'true' : 'false'); ?>"
                     class="flex items-center gap-2.5 cursor-pointer w-full text-sm text-slate-800 dark:text-slate-400 font-medium text-left group collapsible-toggle hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                     <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-indian-rupee'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-[18px]  overflow-visible']); ?>
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

                     <span class="flex-1">Leads</span>

                     <svg xmlns="http://www.w3.org/2000/svg"
                        class="arrow size-3 fill-current transition-all <?php echo e(request()->routeIs('leads.*') || request()->routeIs('statuses.*') ? '' : '-rotate-90'); ?>"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path
                           d="M12.016 18a1.5 1.5 0 0 1-1.065-.434l-9-9a1.506 1.506 0 0 1 2.13-2.13l7.935 7.95L19.95 6.45a1.5 1.5 0 0 1 2.115 2.115l-9 9a1.5 1.5 0 0 1-1.05.435"
                           data-name="16" data-original="#000000" />
                     </svg>
                  </button>

                  <ul class="space-y-1 text-sm text-slate-600 dark:text-slate-400 font-medium px-3 my-1 max-h-0 overflow-hidden transition-all duration-300"
                     style="<?php echo e(request()->routeIs('leads.*') || request()->routeIs('statuses.*') ? 'max-height: 156px;' : 'max-height: 0;'); ?>">


                     <li>
                        <a href="<?php echo e(route('leads.index')); ?>"
                           class="<?php echo e(request()->routeIs('leads.*') ? ' bg-slate-100 ' : ''); ?> flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">

                           <span style="color: <?php echo e($settings['link_color'] ?? '#2563eb'); ?>;">Leads</span>
                        </a>
                     </li>
                     <?php if(auth()->user()->hasRole('Admin')): ?>
                        <li>
                           <a href="<?php echo e(route('statuses.index')); ?>"
                              class="<?php echo e(request()->routeIs('statuses.*') ? ' bg-slate-100 ' : ''); ?> flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">

                              <span style="color: <?php echo e($settings['link_color'] ?? '#2563eb'); ?>;">Lead Status</span>
                           </a>
                        </li>
                     <?php endif; ?>
                  </ul>
               </div>
            <?php endif; ?>

            <?php if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Team Manager')): ?>

               <li>
                  <a href="<?php echo e(route('users.index')); ?>"
                     class="<?php echo e(request()->routeIs('users.*') ? ' bg-slate-100 ' : ''); ?> flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                     <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-user'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-[18px]  overflow-visible']); ?>
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
                     <span>User</span>
                  </a>
               </li>



               <li>
                  <a href="<?php echo e(route('students.index')); ?>"
                     class="<?php echo e(request()->routeIs('students.*') ? ' bg-slate-100 ' : ''); ?> flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                     <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-users'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-[18px]  overflow-visible']); ?>
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
                     <span>Student</span>
                  </a>
               </li>

               <?php if(auth()->user()->hasRole('Admin')): ?>
                   <!-- Migration Certificate -->
                   <div>
                      <button type="button" aria-controls="sub-menu-migration"
                         aria-expanded="<?php echo e(request()->routeIs('migration.*') ? 'true' : 'false'); ?>"
                         class="flex items-center gap-2.5 cursor-pointer w-full text-sm text-slate-800 dark:text-slate-400 font-medium text-left group collapsible-toggle hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                         <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-scroll'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-[18px] overflow-visible text-emerald-600']); ?>
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
                         <span class="flex-1">Migration</span>
                         <svg xmlns="http://www.w3.org/2000/svg"
                            class="arrow size-3 fill-current transition-all <?php echo e(request()->routeIs('migration.*') ? '' : '-rotate-90'); ?>"
                            viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12.016 18a1.5 1.5 0 0 1-1.065-.434l-9-9a1.506 1.506 0 0 1 2.13-2.13l7.935 7.95L19.95 6.45a1.5 1.5 0 0 1 2.115 2.115l-9 9a1.5 1.5 0 0 1-1.05.435" />
                         </svg>
                      </button>
                      <ul class="space-y-1 text-sm text-slate-600 dark:text-slate-400 font-medium px-3 my-1 max-h-0 overflow-hidden transition-all duration-300"
                         style="<?php echo e(request()->routeIs('migration.*') ? 'max-height: 156px;' : 'max-height: 0;'); ?>">
                         <li>
                            <a href="<?php echo e(route('migration.records')); ?>"
                               class="<?php echo e(request()->routeIs('migration.records') ? ' bg-slate-100 ' : ''); ?> flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                               <span>All Certificates</span>
                            </a>
                         </li>
                         <li>
                            <a href="<?php echo e(route('migration.index')); ?>"
                               class="<?php echo e(request()->routeIs('migration.index') ? ' bg-slate-100 ' : ''); ?> flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                               <span>Migration Studio</span>
                            </a>
                         </li>
                      </ul>
                   </div>

                   <!-- Admit Card -->
                   <div>
                      <button type="button" aria-controls="sub-menu-admitcard"
                         aria-expanded="<?php echo e(request()->routeIs('admitcard.*') ? 'true' : 'false'); ?>"
                         class="flex items-center gap-2.5 cursor-pointer w-full text-sm text-slate-800 dark:text-slate-400 font-medium text-left group collapsible-toggle hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                         <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-badge-check'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-[18px] overflow-visible text-blue-600']); ?>
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
                         <span class="flex-1">Admit Card</span>
                         <svg xmlns="http://www.w3.org/2000/svg"
                            class="arrow size-3 fill-current transition-all <?php echo e(request()->routeIs('admitcard.*') ? '' : '-rotate-90'); ?>"
                            viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12.016 18a1.5 1.5 0 0 1-1.065-.434l-9-9a1.506 1.506 0 0 1 2.13-2.13l7.935 7.95L19.95 6.45a1.5 1.5 0 0 1 2.115 2.115l-9 9a1.5 1.5 0 0 1-1.05.435" />
                         </svg>
                      </button>
                      <ul class="space-y-1 text-sm text-slate-600 dark:text-slate-400 font-medium px-3 my-1 max-h-0 overflow-hidden transition-all duration-300"
                         style="<?php echo e(request()->routeIs('admitcard.*') ? 'max-height: 156px;' : 'max-height: 0;'); ?>">
                         <li>
                            <a href="<?php echo e(route('admitcard.records')); ?>"
                               class="<?php echo e(request()->routeIs('admitcard.records') ? ' bg-slate-100 ' : ''); ?> flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                               <span>All Admit Cards</span>
                            </a>
                         </li>
                         <li>
                            <a href="<?php echo e(route('admitcard.index')); ?>"
                               class="<?php echo e(request()->routeIs('admitcard.index') ? ' bg-slate-100 ' : ''); ?> flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                               <span>Admit Card Studio</span>
                            </a>
                         </li>
                      </ul>
                   </div>

                   <!-- Marksheet -->
                   <div>
                      <button type="button" aria-controls="sub-menu-marksheet"
                         aria-expanded="<?php echo e(request()->routeIs('marksheet.*') ? 'true' : 'false'); ?>"
                         class="flex items-center gap-2.5 cursor-pointer w-full text-sm text-slate-800 dark:text-slate-400 font-medium text-left group collapsible-toggle hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                         <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-file-text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-[18px] overflow-visible text-cyan-600']); ?>
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
                         <span class="flex-1">Marksheet</span>
                         <svg xmlns="http://www.w3.org/2000/svg"
                            class="arrow size-3 fill-current transition-all <?php echo e(request()->routeIs('marksheet.*') ? '' : '-rotate-90'); ?>"
                            viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12.016 18a1.5 1.5 0 0 1-1.065-.434l-9-9a1.506 1.506 0 0 1 2.13-2.13l7.935 7.95L19.95 6.45a1.5 1.5 0 0 1 2.115 2.115l-9 9a1.5 1.5 0 0 1-1.05.435" />
                         </svg>
                      </button>
                      <ul class="space-y-1 text-sm text-slate-600 dark:text-slate-400 font-medium px-3 my-1 max-h-0 overflow-hidden transition-all duration-300"
                         style="<?php echo e(request()->routeIs('marksheet.*') ? 'max-height: 156px;' : 'max-height: 0;'); ?>">
                         <li>
                            <a href="<?php echo e(route('marksheet.records')); ?>"
                               class="<?php echo e(request()->routeIs('marksheet.records') ? ' bg-slate-100 ' : ''); ?> flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                               <span>All Marksheets</span>
                            </a>
                         </li>
                         <li>
                            <a href="<?php echo e(route('marksheet.index')); ?>"
                               class="<?php echo e(request()->routeIs('marksheet.index') ? ' bg-slate-100 ' : ''); ?> flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                               <span>Marksheet Studio</span>
                            </a>
                         </li>
                      </ul>
                   </div>

                   <!-- Diploma -->
                   <div>
                      <button type="button" aria-controls="sub-menu-diploma"
                         aria-expanded="<?php echo e(request()->routeIs('diploma.*') ? 'true' : 'false'); ?>"
                         class="flex items-center gap-2.5 cursor-pointer w-full text-sm text-slate-800 dark:text-slate-400 font-medium text-left group collapsible-toggle hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                         <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-award'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-[18px] overflow-visible text-amber-600']); ?>
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
                         <span class="flex-1">Diploma</span>
                         <svg xmlns="http://www.w3.org/2000/svg"
                            class="arrow size-3 fill-current transition-all <?php echo e(request()->routeIs('diploma.*') ? '' : '-rotate-90'); ?>"
                            viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12.016 18a1.5 1.5 0 0 1-1.065-.434l-9-9a1.506 1.506 0 0 1 2.13-2.13l7.935 7.95L19.95 6.45a1.5 1.5 0 0 1 2.115 2.115l-9 9a1.5 1.5 0 0 1-1.05.435" />
                         </svg>
                      </button>
                      <ul class="space-y-1 text-sm text-slate-600 dark:text-slate-400 font-medium px-3 my-1 max-h-0 overflow-hidden transition-all duration-300"
                         style="<?php echo e(request()->routeIs('diploma.*') ? 'max-height: 156px;' : 'max-height: 0;'); ?>">
                         <li>
                            <a href="<?php echo e(route('diploma.records')); ?>"
                               class="<?php echo e(request()->routeIs('diploma.records') ? ' bg-slate-100 ' : ''); ?> flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                               <span>All Diplomas</span>
                            </a>
                         </li>
                         <li>
                            <a href="<?php echo e(route('diploma.index')); ?>"
                               class="<?php echo e(request()->routeIs('diploma.index') ? ' bg-slate-100 ' : ''); ?> flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                               <span>Diploma Studio</span>
                            </a>
                         </li>
                      </ul>
                   </div>

                   <!-- ID Card -->
                   <div>
                      <button type="button" aria-controls="sub-menu-idcard"
                         aria-expanded="<?php echo e(request()->routeIs('idcard.*') ? 'true' : 'false'); ?>"
                         class="flex items-center gap-2.5 cursor-pointer w-full text-sm text-slate-800 dark:text-slate-400 font-medium text-left group collapsible-toggle hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                         <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-id-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-[18px] overflow-visible text-indigo-600']); ?>
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
                         <span class="flex-1">ID Card</span>
                         <svg xmlns="http://www.w3.org/2000/svg"
                            class="arrow size-3 fill-current transition-all <?php echo e(request()->routeIs('idcard.*') ? '' : '-rotate-90'); ?>"
                            viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12.016 18a1.5 1.5 0 0 1-1.065-.434l-9-9a1.506 1.506 0 0 1 2.13-2.13l7.935 7.95L19.95 6.45a1.5 1.5 0 0 1 2.115 2.115l-9 9a1.5 1.5 0 0 1-1.05.435" />
                         </svg>
                      </button>
                      <ul class="space-y-1 text-sm text-slate-600 dark:text-slate-400 font-medium px-3 my-1 max-h-0 overflow-hidden transition-all duration-300"
                         style="<?php echo e(request()->routeIs('idcard.*') ? 'max-height: 156px;' : 'max-height: 0;'); ?>">
                         <li>
                            <a href="<?php echo e(route('idcard.records')); ?>"
                               class="<?php echo e(request()->routeIs('idcard.records') ? ' bg-slate-100 ' : ''); ?> flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                               <span>All ID Cards</span>
                            </a>
                         </li>
                         <li>
                            <a href="<?php echo e(route('idcard.index')); ?>"
                               class="<?php echo e(request()->routeIs('idcard.index') ? ' bg-slate-100 ' : ''); ?> flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                               <span>ID Card Studio</span>
                            </a>
                         </li>
                      </ul>
                   </div>
                <?php endif; ?>

               <?php if(auth()->user()->hasRole('Admin')): ?>
                     <li>
                        <a href="<?php echo e(route('clients.index')); ?>"
                           class="<?php echo e(request()->routeIs('clients.*') ? ' bg-slate-100 ' : ''); ?> flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                           <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-user-star'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-[18px]  overflow-visible']); ?>
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
                           <span>Clients</span>
                        </a>
                     </li>

                     <div>
                        <button type="button" aria-controls="sub-menu-1"
                           aria-expanded="<?php echo e(request()->routeIs('courses.*') || request()->routeIs('course-types.*') ? 'true' : 'false'); ?>"
                           class="flex items-center gap-2.5 cursor-pointer w-full text-sm text-slate-800 dark:text-slate-400 font-medium text-left group collapsible-toggle hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                           <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-book-open'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-[18px]  overflow-visible']); ?>
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


                           <span class="flex-1">Courses</span>

                           <svg xmlns="http://www.w3.org/2000/svg"
                              class="arrow size-3 fill-current transition-all <?php echo e(request()->routeIs('courses.*') || request()->routeIs('course-types.*') ? '' : '-rotate-90'); ?>"
                              viewBox="0 0 24 24" aria-hidden="true">
                              <path
                                 d="M12.016 18a1.5 1.5 0 0 1-1.065-.434l-9-9a1.506 1.506 0 0 1 2.13-2.13l7.935 7.95L19.95 6.45a1.5 1.5 0 0 1 2.115 2.115l-9 9a1.5 1.5 0 0 1-1.05.435"
                                 data-name="16" data-original="#000000" />
                           </svg>
                        </button>

                        <ul class="space-y-1 text-sm text-slate-600 dark:text-slate-400 font-medium px-3 my-1 max-h-0 overflow-hidden transition-all duration-300"
                           style="<?php echo e(request()->routeIs('courses.*') || request()->routeIs('course-types.*') ? 'max-height: 156px;' : 'max-height: 0;'); ?>">


                           <li>
                              <a href="<?php echo e(route('courses.index')); ?>"
                                 class="<?php echo e(request()->routeIs('courses.*') ? ' bg-slate-100 ' : ''); ?> flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                                 Courses
                              </a>


                           </li>
                           <li>

                              <a href="<?php echo e(route('course-types.index')); ?>"
                                 class="<?php echo e(request()->routeIs('course-types.*') ? ' bg-slate-100 ' : ''); ?> flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                                 Course Types
                              </a>

                           </li>
                        </ul>
                     </div>

                     <li>
                        <a href="<?php echo e(route('batches.index')); ?>"
                           class="<?php echo e(request()->routeIs('batches.*') ? ' bg-slate-100 ' : ''); ?> flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                           <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-calendar-days'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-[18px]  overflow-visible']); ?>
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
                           <span>Batch</span>
                        </a>
                     </li>




                  </ul>


                  <div>
                     <button type="button" aria-controls="sub-menu-1"
                        aria-expanded="<?php echo e(request()->routeIs('invoices.*') || request()->routeIs('payments.*') || request()->routeIs('installments.*') ? 'true' : 'false'); ?>"
                        class="flex items-center gap-2.5 cursor-pointer w-full text-sm text-slate-800 dark:text-slate-400 font-medium text-left group collapsible-toggle hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-indian-rupee'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-[18px]  overflow-visible']); ?>
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

                        <span class="flex-1">Finance</span>

                        <svg xmlns="http://www.w3.org/2000/svg"
                           class="arrow size-3 fill-current transition-all <?php echo e(request()->routeIs('invoices.*') || request()->routeIs('payments.*') || request()->routeIs('installments.*') ? '' : '-rotate-90'); ?>"
                           viewBox="0 0 24 24" aria-hidden="true">
                           <path
                              d="M12.016 18a1.5 1.5 0 0 1-1.065-.434l-9-9a1.506 1.506 0 0 1 2.13-2.13l7.935 7.95L19.95 6.45a1.5 1.5 0 0 1 2.115 2.115l-9 9a1.5 1.5 0 0 1-1.05.435"
                              data-name="16" data-original="#000000" />
                        </svg>
                     </button>

                     <ul class="space-y-1 text-sm text-slate-600 dark:text-slate-400 font-medium px-3 my-1 max-h-0 overflow-hidden transition-all duration-300"
                        style="<?php echo e(request()->routeIs('invoices.*') || request()->routeIs('payments.*') || request()->routeIs('installments.*') ? 'max-height: 156px;' : 'max-height: 0;'); ?>">
                        <li>
                           <a href="<?php echo e(route('invoices.index')); ?>"
                              class="<?php echo e(request()->routeIs('invoices.*') ? ' bg-slate-100 ' : ''); ?> block hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                              Invoices
                           </a>
                        </li>
                        <li>
                           <a href="<?php echo e(route('payments.index')); ?>"
                              class="<?php echo e(request()->routeIs('payments.*') ? ' bg-slate-100 ' : ''); ?> block hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                              Payments
                           </a>
                        </li>
                        <li>
                           <a href="<?php echo e(route('installments.index')); ?>"
                              class="<?php echo e(request()->routeIs('installments.*') ? ' bg-slate-100 ' : ''); ?> block hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                              Installments
                           </a>
                        </li>


                        <!--     <li>
                            <a href="#"
                               class="block hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                               Expenses
                            </a>
                         </li>
                         <li>
                            <a href="#"
                               class="block hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                               Estimates
                            </a>
                         </li> -->


                     </ul>
                  </div>

                  <div>
                     <button type="button" aria-controls="sub-menu-1"
                        aria-expanded="<?php echo e(request()->routeIs('activity-logs.*') || request()->routeIs('reports.*') ? 'true' : 'false'); ?>"
                        class="flex items-center gap-2.5 cursor-pointer w-full text-sm text-slate-800 dark:text-slate-400 font-medium text-left group collapsible-toggle hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-chart-line'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-[18px]  overflow-visible']); ?>
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


                        <span class="flex-1">Reports</span>

                        <svg xmlns="http://www.w3.org/2000/svg"
                           class="arrow size-3  fill-current transition-all <?php echo e(request()->routeIs('activity-logs.*') || request()->routeIs('reports.*') ? '' : '-rotate-90'); ?>"
                           viewBox="0 0 24 24" aria-hidden="true">
                           <path
                              d="M12.016 18a1.5 1.5 0 0 1-1.065-.434l-9-9a1.506 1.506 0 0 1 2.13-2.13l7.935 7.95L19.95 6.45a1.5 1.5 0 0 1 2.115 2.115l-9 9a1.5 1.5 0 0 1-1.05.435"
                              data-name="16" data-original="#000000" />
                        </svg>
                     </button>

                     <ul class="space-y-1 text-sm text-slate-600 dark:text-slate-400 font-medium px-3 my-1 max-h-0 overflow-hidden transition-all duration-300"
                        style="<?php echo e(request()->routeIs('activity-logs.*') || request()->routeIs('reports.*') ? 'max-height: 192px;' : 'max-height: 0;'); ?>">
                        <li>
                           <a href="<?php echo e(route('reports.earnings')); ?>"
                              class="block <?php echo e(request()->routeIs('reports.earnings') ? ' bg-slate-100 ' : ''); ?> hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                              Earnings
                           </a>
                        </li>
                        <li>
                           <a href="<?php echo e(route('reports.refunds')); ?>"
                              class="block <?php echo e(request()->routeIs('reports.refunds') ? ' bg-slate-100 ' : ''); ?> hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                              Refunds
                           </a>
                        </li>

                        <li>
                           <a href="<?php echo e(route('activity-logs.index')); ?>"
                              class="block <?php echo e(request()->routeIs('activity-logs.*') ? ' bg-slate-100 ' : ''); ?> hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                              Activity Log
                           </a>
                        </li>

                     </ul>
                  </div>



               <?php endif; ?>
            <?php endif; ?>

      </nav>

      <a href="#"
         class="flex flex-wrap items-center gap-4 rounded-md mt-6 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
         <img src="<?php echo e(Auth::user()->getFirstMediaUrl('avatars')); ?>"
            class="w-10 h-10 rounded-md border border-slate-300 dark:border-neutral-700" alt="User avatar" />
         <div>
            <p class="text-sm text-slate-800 dark:text-slate-400 font-medium"><?php echo e(Auth::user()->name); ?></p>
            <p class="text-xs text-slate-500 mt-0.5"><?php echo e(Auth::user()->roles->pluck('name')->join(', ')); ?></p>
         </div>
      </a>
   </div>
</aside><?php /**PATH /home/willpowe/domains/apeirojobs.com/resources/views/layouts/backend/sidebar.blade.php ENDPATH**/ ?>