<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>

<section class="p-1">
   <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-50">
      Dashboard
   </h2>
   <p class="mt-2 mb-4 text-sm text-slate-600 dark:text-slate-400">
      Welcome back, <?php echo e(auth()->user()->name); ?>!
   </p>

<?php if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Team Manager') || auth()->user()->hasRole('team manager') || auth()->user()->hasRole(5)): ?>

   <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div class="bg-white px-4 sm:p-6 border border-slate-200 shadow-sm rounded-lg dark:bg-neutral-800 dark:border-neutral-700">
         <div>
            <h3 class="text-slate-900 text-base font-semibold dark:text-slate-50">
               Leader Board
            </h3>

            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
               🏆 Top performers for <?php echo e(now()->format('F Y')); ?>

            </p>
         </div>

         <div class="mt-6">
            <div class="divide-y divide-slate-200 dark:divide-neutral-700">

               <?php $__empty_1 = true; $__currentLoopData = $leaderboards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $leaderboard): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

               <?php
               $badge = match($index) {
               0 => '🥇',
               1 => '🥈',
               2 => '🥉',
               default => '#'.($index + 1),
               };
               ?>



               <div class="flex items-center justify-between py-4">
                  <div class="flex items-center gap-3">

                     <div class="w-8 text-center font-bold">
                        <?php echo e($badge); ?>

                     </div>

                     <div class="avatar placeholder">
                        <div class="bg-primary text-primary-content rounded-full w-10">
                           <span>
                              <?php echo e(strtoupper(substr($leaderboard->name, 0, 1))); ?>

                           </span>
                        </div>
                     </div>

                     <div>
                        <p class="font-semibold text-slate-900 dark:text-slate-50">
                           <?php echo e($leaderboard->name); ?>

                        </p>

                        <p class="  text-slate-500">
                           Rank #<?php echo e($index + 1); ?>

                        </p>
                     </div>
                  </div>

                  <span class="badge badge-success">
                     <?php echo e($leaderboard->converted_leads); ?>

                     <?php echo e(Str::plural('Lead', $leaderboard->converted_leads)); ?>

                  </span>
               </div>

               <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

               <div class="py-6 text-center text-slate-500">
                  No conversions this month.
               </div>

               <?php endif; ?>

            </div>
         </div>
      </div>
      <div class="bg-white px-4 sm:p-6 border border-slate-200 shadow-sm rounded-lg dark:bg-neutral-800 dark:border-neutral-700">
         <div>
            <h3 class="text-slate-900 text-base font-semibold dark:text-slate-50">
               <a href="<?php echo e(route('courses.index')); ?>" class="hover:text-primary">Latest 10 Courses</a>
            </h3>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Most recently added courses with creation dates.</p>
         </div>
         <div class="mt-4 space-y-3 text-sm text-slate-700 dark:text-slate-300">
            <?php $__empty_1 = true; $__currentLoopData = $latestCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
               <div class="flex items-center justify-between rounded-lg bg-slate-50 dark:bg-neutral-900 p-3">
                  <div>
                     <a href="<?php echo e(route('courses.show', $course)); ?>" class="font-medium text-slate-900 dark:text-slate-100 hover:text-primary"><?php echo e($course->name ?? "Course #{$course->id}"); ?></a>
                  </div>
                  <div class="text-xs text-slate-500 dark:text-slate-400"><?php echo e(optional($course->created_at)->format('M d, Y')); ?></div>
               </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
               <div class="rounded-lg bg-slate-50 dark:bg-neutral-900 p-3 text-slate-500">No courses added yet.</div>
            <?php endif; ?>
         </div>
      </div>
      <div class="bg-white px-4 sm:p-6 border border-slate-200 shadow-sm rounded-lg dark:bg-neutral-800 dark:border-neutral-700">
         <div>
            <h3 class="text-slate-900 text-base font-semibold dark:text-slate-50">
               <a href="<?php echo e(route('clients.index')); ?>" class="hover:text-primary">Latest 10 Clients</a>
            </h3>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Most recently added clients with their date.</p>
         </div>
         <div class="mt-4 space-y-3 text-sm text-slate-700 dark:text-slate-300">
            <?php $__empty_1 = true; $__currentLoopData = $latestClients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
               <div class="flex items-center justify-between rounded-lg bg-slate-50 dark:bg-neutral-900 p-3">
                  <div>
                     <a href="<?php echo e(route('clients.show', $client)); ?>" class="font-medium text-slate-900 dark:text-slate-100 hover:text-primary"><?php echo e($client->client_name ?? "Client #{$client->id}"); ?></a>
                  </div>
                  <div class="text-xs text-slate-500 dark:text-slate-400"><?php echo e(optional($client->created_at)->format('M d, Y')); ?></div>
               </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
               <div class="rounded-lg bg-slate-50 dark:bg-neutral-900 p-3 text-slate-500">No clients added yet.</div>
            <?php endif; ?>
         </div>
      </div>

      <div class="bg-white px-4 sm:p-6 border border-slate-200 shadow-sm rounded-lg dark:bg-neutral-800 dark:border-neutral-700">
         <div>
            <h3 class="text-slate-900 text-base font-semibold dark:text-slate-50">
               <a href="<?php echo e(route('students.index')); ?>" class="hover:text-primary">Latest 10 Students</a>
            </h3>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Most recently added students with admission dates.</p>
         </div>
         <div class="mt-4 space-y-3 text-sm text-slate-700 dark:text-slate-300">
            <?php $__empty_1 = true; $__currentLoopData = $latestStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
               <div class="flex items-center justify-between rounded-lg bg-slate-50 dark:bg-neutral-900 p-3">
                  <div>
                     <a href="<?php echo e(optional($student)->id ? route('students.show', ['student' => $student->id]) : '#'); ?>" class="font-medium text-slate-900 dark:text-slate-100 hover:text-primary"><?php echo e($student->name ?? "Student #{$student->id}"); ?></a>
                  </div>
                  <div class="text-xs text-slate-500 dark:text-slate-400"><?php echo e(optional($student->created_at)->format('M d, Y')); ?></div>
               </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
               <div class="rounded-lg bg-slate-50 dark:bg-neutral-900 p-3 text-slate-500">No students added yet.</div>
            <?php endif; ?>
         </div>
      </div>
      <div class="bg-white px-4 sm:p-6 border border-slate-200 shadow-sm rounded-lg dark:bg-neutral-800 dark:border-neutral-700">
         <div>
            <h3 class="text-slate-900 text-base font-semibold dark:text-slate-50">
               <a href="<?php echo e(route('payments.index')); ?>" class="hover:text-primary">Latest 10 Payments</a>
            </h3>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Recent payments with student names and payment dates.</p>
         </div>
         <div class="mt-4 space-y-3 text-sm text-slate-700 dark:text-slate-300">
            <?php $__empty_1 = true; $__currentLoopData = $latestPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
               <div class="flex flex-col gap-2 rounded-lg bg-slate-50 dark:bg-neutral-900 p-3">
                  <div class="flex items-center justify-between gap-3">
                     <div>
                        <a href="<?php echo e(route('payments.show', $payment)); ?>" class="font-medium text-slate-900 dark:text-slate-100 hover:text-primary">Payment #<?php echo e($payment->id); ?></a>
                     </div>
                     <div class="text-xs text-slate-500 dark:text-slate-400"><?php echo e(optional($payment->payment_date ?? $payment->created_at)->format('M d, Y')); ?></div>
                  </div>
                  <div class="flex items-center justify-between gap-3 text-sm text-slate-600 dark:text-slate-400">
                     <span>Amount: ₹<?php echo e(number_format($payment->amount, 2)); ?></span>
                     <span>Student: <a href="<?php echo e(optional($payment->student)->id ? route('students.show', ['student' => $payment->student->id]) : '#'); ?>" class="hover:text-primary"><?php echo e(optional($payment->student)->name ?? 'Unknown'); ?></a></span>
                  </div>
               </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
               <div class="rounded-lg bg-slate-50 dark:bg-neutral-900 p-3 text-slate-500">No payments recorded yet.</div>
            <?php endif; ?>
         </div>
      </div>
      <div class="bg-white px-4 sm:p-6 border border-slate-200 shadow-sm rounded-lg dark:bg-neutral-800 dark:border-neutral-700">
         <div>
            <h3 class="text-slate-900 text-base font-semibold dark:text-slate-50">
               <a href="<?php echo e(route('installments.index')); ?>" class="hover:text-primary">Upcoming Installments</a>
            </h3>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Installments due in the next 10 days, with dates.</p>
         </div>
         <div class="mt-4 space-y-3 text-sm text-slate-700 dark:text-slate-300">
            <?php $__empty_1 = true; $__currentLoopData = $upcomingInstallments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $installment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
               <div class="rounded-lg bg-slate-50 dark:bg-neutral-900 p-3">
                  <div class="flex items-center justify-between gap-3">
                     <div>
                        <div>
                           <a href="<?php echo e(route('installments.show', $installment)); ?>" class="font-medium text-slate-900 dark:text-slate-100 hover:text-primary">Installment #<?php echo e($installment->id); ?></a>
                        </div>
                        <div class="text-slate-500 dark:text-slate-400 text-xs">
                           Student: <a href="<?php echo e(optional($installment->student)->id ? route('students.show', ['student' => $installment->student->id]) : '#'); ?>" class="hover:text-primary"><?php echo e(optional($installment->student)->name ?? 'Unknown'); ?></a> (ID: <?php echo e($installment->student_id); ?>) · Invoice ID: <?php echo e($installment->invoice_id); ?>

                        </div>
                     </div>
                     <div class="text-right">
                        <div class="text-xs text-slate-500 dark:text-slate-400">Due</div>
                        <div class="font-medium"><?php echo e(optional($installment->due_date)->format('M d, Y')); ?></div>
                     </div>
                  </div>
                  <div class="mt-2 text-xs text-slate-500 dark:text-slate-400">Amount due: ₹<?php echo e(number_format($installment->amount - ($installment->paid_amount ?? 0), 2)); ?></div>
               </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
               <div class="rounded-lg bg-slate-50 dark:bg-neutral-900 p-3 text-slate-500">No installments due in the next 10 days.</div>
            <?php endif; ?>
         </div>
      </div>
   </div>

   <?php endif; ?>

</section>



<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.backend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/willpowe/domains/apeirojobs.com/resources/views/backend/dashboard.blade.php ENDPATH**/ ?>