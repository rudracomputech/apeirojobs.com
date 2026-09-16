<?php

namespace App\Providers;

use App\Models\Batch;
use App\Models\Course;
use App\Models\Lead;
use App\Models\Notification;
use App\Models\Payments;
use App\Models\Student;
use App\Models\User;
use App\Policies\OwnershipPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
      //  Gate::policy(User::class, OwnershipPolicy::class);
        Gate::policy(Batch::class, OwnershipPolicy::class);
        Gate::policy(Lead::class, OwnershipPolicy::class);
        Gate::policy(Student::class, OwnershipPolicy::class);
        Gate::policy(Course::class, OwnershipPolicy::class);
      //  Gate::policy(Payments::class, OwnershipPolicy::class);

        View::composer('layouts.backend', function ($view) {
            if (Auth::check()) {
                $notifications = Notification::where('notifiable_type', Auth::user()::class)
                    ->where('notifiable_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();

                $unreadCount = Notification::where('notifiable_type', Auth::user()::class)
                    ->where('notifiable_id', Auth::id())
                    ->whereNull('read_at')
                    ->count();

                $view->with([
                    'notificationItems' => $notifications,
                    'notificationUnreadCount' => $unreadCount,
                ]);
            }
        });
    }
}
