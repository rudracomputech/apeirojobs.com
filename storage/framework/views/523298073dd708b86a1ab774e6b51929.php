<?php ($settings = auth()->check() ? (auth()->user()->backend_settings ?? []) : []); ?>
<!doctype html>
<html lang="en" data-theme="light">

<head>
  <title><?php echo $__env->yieldContent('title'); ?> - HSCouching</title>
  <meta charset="UTF-8" />

  <meta name="keywords" content="HSCouching, healthcare, jobs" />
  <meta name="description" content="HSCouching is a platform for finding healthcare jobs." />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="<?php echo e(asset('images/favicon-dark.png')); ?>" media="(prefers-color-scheme: dark)" />
  <link rel="shortcut icon" href="<?php echo e(asset('images/favicon-light.png')); ?>" media="(prefers-color-scheme: light)" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">



  <?php echo app('Illuminate\Foundation\Vite')('resources/css/backend.css'); ?>

  <?php echo $__env->yieldContent('page-css'); ?>



</head>

<body style="color: <?php echo e($settings['body_text_color'] ?? '#334155'); ?>; background-color: <?php echo e($settings['mainbar_background_color'] ?? '#f8fafc'); ?>;">
  <main data-simplebar  class="h-screen ">
    <div class="flex items-start h-full">


      <?php echo $__env->make('layouts.backend.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

      <div class="w-full  overflow-x-hidden">
        <?php echo $__env->make('layouts.backend.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="m-6 h-full ">
          <div class="space-y-6 px-4  my-6">
            <?php echo $__env->make('layouts.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
          </div>
          <?php echo $__env->yieldContent('content'); ?>

        </div>
     

      </div>

    </div>

  </main>
  
  

  <?php echo app('Illuminate\Foundation\Vite')(['resources/js/backend.js']); ?>

  

  
  <?php echo $__env->yieldContent('page-js'); ?>
</body>

</html><?php /**PATH /home/willpowe/domains/apeirojobs.com/resources/views/layouts/backend.blade.php ENDPATH**/ ?>