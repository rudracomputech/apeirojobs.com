@extends('layouts.backend')
@section('title', 'Dashboard')

@section('content')

<section class="p-1">
   <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-50">
      Dashboard
   </h2>
   <p class="mt-2 mb-4 text-sm text-slate-600 dark:text-slate-400">
      Welcome back, {{ auth()->user()->name }}!
   </p>

@if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Team Manager') || auth()->user()->hasRole('team manager') || auth()->user()->hasRole(5))

   <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div class="bg-white px-4 sm:p-6 border border-slate-200 shadow-sm rounded-lg dark:bg-neutral-800 dark:border-neutral-700">
         <div>
            <h3 class="text-slate-900 text-base font-semibold dark:text-slate-50">
               Leader Board
            </h3>

            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
               🏆 Top performers for {{ now()->format('F Y') }}
            </p>
         </div>

         <div class="mt-6">
            <div class="divide-y divide-slate-200 dark:divide-neutral-700">

               @forelse($leaderboards as $index => $leaderboard)

               @php
               $badge = match($index) {
               0 => '🥇',
               1 => '🥈',
               2 => '🥉',
               default => '#'.($index + 1),
               };
               @endphp



               <div class="flex items-center justify-between py-4">
                  <div class="flex items-center gap-3">

                     <div class="w-8 text-center font-bold">
                        {{ $badge }}
                     </div>

                     <div class="avatar placeholder">
                        <div class="bg-primary text-primary-content rounded-full w-10">
                           <span>
                              {{ strtoupper(substr($leaderboard->name, 0, 1)) }}
                           </span>
                        </div>
                     </div>

                     <div>
                        <p class="font-semibold text-slate-900 dark:text-slate-50">
                           {{ $leaderboard->name }}
                        </p>

                        <p class="  text-slate-500">
                           Rank #{{ $index + 1 }}
                        </p>
                     </div>
                  </div>

                  <span class="badge badge-success">
                     {{ $leaderboard->converted_leads }}
                     {{ Str::plural('Lead', $leaderboard->converted_leads) }}
                  </span>
               </div>

               @empty

               <div class="py-6 text-center text-slate-500">
                  No conversions this month.
               </div>

               @endforelse

            </div>
         </div>
      </div>
      <div class="bg-white px-4 sm:p-6 border border-slate-200 shadow-sm rounded-lg dark:bg-neutral-800 dark:border-neutral-700">
         <div>
            <h3 class="text-slate-900 text-base font-semibold dark:text-slate-50">
               <a href="{{ route('courses.index') }}" class="hover:text-primary">Latest 10 Courses</a>
            </h3>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Most recently added courses with creation dates.</p>
         </div>
         <div class="mt-4 space-y-3 text-sm text-slate-700 dark:text-slate-300">
            @forelse($latestCourses as $course)
               <div class="flex items-center justify-between rounded-lg bg-slate-50 dark:bg-neutral-900 p-3">
                  <div>
                     <a href="{{ route('courses.show', $course) }}" class="font-medium text-slate-900 dark:text-slate-100 hover:text-primary">{{ $course->name ?? "Course #{$course->id}" }}</a>
                  </div>
                  <div class="text-xs text-slate-500 dark:text-slate-400">{{ optional($course->created_at)->format('M d, Y') }}</div>
               </div>
            @empty
               <div class="rounded-lg bg-slate-50 dark:bg-neutral-900 p-3 text-slate-500">No courses added yet.</div>
            @endforelse
         </div>
      </div>
      <div class="bg-white px-4 sm:p-6 border border-slate-200 shadow-sm rounded-lg dark:bg-neutral-800 dark:border-neutral-700">
         <div>
            <h3 class="text-slate-900 text-base font-semibold dark:text-slate-50">
               <a href="{{ route('clients.index') }}" class="hover:text-primary">Latest 10 Clients</a>
            </h3>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Most recently added clients with their date.</p>
         </div>
         <div class="mt-4 space-y-3 text-sm text-slate-700 dark:text-slate-300">
            @forelse($latestClients as $client)
               <div class="flex items-center justify-between rounded-lg bg-slate-50 dark:bg-neutral-900 p-3">
                  <div>
                     <a href="{{ route('clients.show', $client) }}" class="font-medium text-slate-900 dark:text-slate-100 hover:text-primary">{{ $client->client_name ?? "Client #{$client->id}" }}</a>
                  </div>
                  <div class="text-xs text-slate-500 dark:text-slate-400">{{ optional($client->created_at)->format('M d, Y') }}</div>
               </div>
            @empty
               <div class="rounded-lg bg-slate-50 dark:bg-neutral-900 p-3 text-slate-500">No clients added yet.</div>
            @endforelse
         </div>
      </div>

      <div class="bg-white px-4 sm:p-6 border border-slate-200 shadow-sm rounded-lg dark:bg-neutral-800 dark:border-neutral-700">
         <div>
            <h3 class="text-slate-900 text-base font-semibold dark:text-slate-50">
               <a href="{{ route('students.index') }}" class="hover:text-primary">Latest 10 Students</a>
            </h3>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Most recently added students with admission dates.</p>
         </div>
         <div class="mt-4 space-y-3 text-sm text-slate-700 dark:text-slate-300">
            @forelse($latestStudents as $student)
               <div class="flex items-center justify-between rounded-lg bg-slate-50 dark:bg-neutral-900 p-3">
                  <div>
                     <a href="{{ optional($student)->id ? route('students.show', ['student' => $student->id]) : '#' }}" class="font-medium text-slate-900 dark:text-slate-100 hover:text-primary">{{ $student->name ?? "Student #{$student->id}" }}</a>
                  </div>
                  <div class="text-xs text-slate-500 dark:text-slate-400">{{ optional($student->created_at)->format('M d, Y') }}</div>
               </div>
            @empty
               <div class="rounded-lg bg-slate-50 dark:bg-neutral-900 p-3 text-slate-500">No students added yet.</div>
            @endforelse
         </div>
      </div>
      <div class="bg-white px-4 sm:p-6 border border-slate-200 shadow-sm rounded-lg dark:bg-neutral-800 dark:border-neutral-700">
         <div>
            <h3 class="text-slate-900 text-base font-semibold dark:text-slate-50">
               <a href="{{ route('payments.index') }}" class="hover:text-primary">Latest 10 Payments</a>
            </h3>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Recent payments with student names and payment dates.</p>
         </div>
         <div class="mt-4 space-y-3 text-sm text-slate-700 dark:text-slate-300">
            @forelse($latestPayments as $payment)
               <div class="flex flex-col gap-2 rounded-lg bg-slate-50 dark:bg-neutral-900 p-3">
                  <div class="flex items-center justify-between gap-3">
                     <div>
                        <a href="{{ route('payments.show', $payment) }}" class="font-medium text-slate-900 dark:text-slate-100 hover:text-primary">Payment #{{ $payment->id }}</a>
                     </div>
                     <div class="text-xs text-slate-500 dark:text-slate-400">{{ optional($payment->payment_date ?? $payment->created_at)->format('M d, Y') }}</div>
                  </div>
                  <div class="flex items-center justify-between gap-3 text-sm text-slate-600 dark:text-slate-400">
                     <span>Amount: ₹{{ number_format($payment->amount, 2) }}</span>
                     <span>Student: <a href="{{ optional($payment->student)->id ? route('students.show', ['student' => $payment->student->id]) : '#' }}" class="hover:text-primary">{{ optional($payment->student)->name ?? 'Unknown' }}</a></span>
                  </div>
               </div>
            @empty
               <div class="rounded-lg bg-slate-50 dark:bg-neutral-900 p-3 text-slate-500">No payments recorded yet.</div>
            @endforelse
         </div>
      </div>
      <div class="bg-white px-4 sm:p-6 border border-slate-200 shadow-sm rounded-lg dark:bg-neutral-800 dark:border-neutral-700">
         <div>
            <h3 class="text-slate-900 text-base font-semibold dark:text-slate-50">
               <a href="{{ route('installments.index') }}" class="hover:text-primary">Upcoming Installments</a>
            </h3>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Installments due in the next 10 days, with dates.</p>
         </div>
         <div class="mt-4 space-y-3 text-sm text-slate-700 dark:text-slate-300">
            @forelse($upcomingInstallments as $installment)
               <div class="rounded-lg bg-slate-50 dark:bg-neutral-900 p-3">
                  <div class="flex items-center justify-between gap-3">
                     <div>
                        <div>
                           <a href="{{ route('installments.show', $installment) }}" class="font-medium text-slate-900 dark:text-slate-100 hover:text-primary">Installment #{{ $installment->id }}</a>
                        </div>
                        <div class="text-slate-500 dark:text-slate-400 text-xs">
                           Student: <a href="{{ optional($installment->student)->id ? route('students.show', ['student' => $installment->student->id]) : '#' }}" class="hover:text-primary">{{ optional($installment->student)->name ?? 'Unknown' }}</a> (ID: {{ $installment->student_id }}) · Invoice ID: {{ $installment->invoice_id }}
                        </div>
                     </div>
                     <div class="text-right">
                        <div class="text-xs text-slate-500 dark:text-slate-400">Due</div>
                        <div class="font-medium">{{ optional($installment->due_date)->format('M d, Y') }}</div>
                     </div>
                  </div>
                  <div class="mt-2 text-xs text-slate-500 dark:text-slate-400">Amount due: ₹{{ number_format($installment->amount - ($installment->paid_amount ?? 0), 2) }}</div>
               </div>
            @empty
               <div class="rounded-lg bg-slate-50 dark:bg-neutral-900 p-3 text-slate-500">No installments due in the next 10 days.</div>
            @endforelse
         </div>
      </div>
   </div>

   @endif

</section>



@endsection