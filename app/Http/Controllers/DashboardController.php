<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Course;
use App\Models\Client;
use App\Models\Student;
use App\Models\Payments;
use App\Models\Installment;
use App\Models\User;
use App\Models\VerifyPassword;
use App\Mail\VerifyResetPassword;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $leaderboardsQuery = User::select(
            'users.id',
            'users.name',
            \DB::raw('COUNT(leads.id) as converted_leads')
        )
        ->leftJoin('leads', function ($join) {
            $join->on('users.id', '=', 'leads.assigned_to')
                ->whereNotNull('leads.converted_at')
                ->whereMonth('leads.converted_at', now()->month)
                ->whereYear('leads.converted_at', now()->year);
        });

        if (! $user->hasRole('Admin')) {
            if ($user->hasRole('Team Manager') || $user->hasRole('team manager') || $user->hasRole(5)) {
                $teamMemberIds = User::where('created_by', $user->id)->pluck('id')->push($user->id);
                $leaderboardsQuery->whereIn('users.id', $teamMemberIds);
            } else {
                $leaderboardsQuery->where('users.id', $user->id);
            }
        }

        $leaderboards = $leaderboardsQuery
            ->groupBy('users.id', 'users.name')
            ->having('converted_leads', '>', 0)
            ->orderByDesc('converted_leads')
            ->take(10)
            ->get();

        $latestCourses = Course::orderByDesc('created_at')
            ->take(10)
            ->get(['id', 'name', 'created_at']);

        $latestClients = Client::orderByDesc('created_at')
            ->take(10)
            ->get(['id', 'client_name', 'created_at']);

        $latestStudents = Student::orderByDesc('created_at')
            ->take(10)
            ->get(['id', 'name', 'created_at']);

        $latestPaymentsQuery = Payments::with('student');
        if (! $user->hasRole('Admin')) {
            if ($user->hasRole('Team Manager') || $user->hasRole('team manager') || $user->hasRole(5)) {
                $teamMemberIds = User::where('created_by', $user->id)->pluck('id')->push($user->id);
                $latestPaymentsQuery->whereHas('student', function ($q) use ($teamMemberIds) {
                    $q->whereIn('created_by', $teamMemberIds);
                });
            } else {
                $latestPaymentsQuery->whereHas('student', function ($q) use ($user) {
                    $q->where('created_by', $user->id);
                });
            }
        }
        $latestPayments = $latestPaymentsQuery
            ->orderByDesc('created_at')
            ->take(10)
            ->get(['id', 'student_id', 'amount', 'payment_date', 'created_at']);

        $upcomingInstallmentsQuery = Installment::with('student')
            ->whereBetween('due_date', [now(), now()->addDays(10)]);
        if (! $user->hasRole('Admin')) {
            if ($user->hasRole('Team Manager') || $user->hasRole('team manager') || $user->hasRole(5)) {
                $teamMemberIds = User::where('created_by', $user->id)->pluck('id')->push($user->id);
                $upcomingInstallmentsQuery->whereHas('student', function ($q) use ($teamMemberIds) {
                    $q->whereIn('created_by', $teamMemberIds);
                });
            } else {
                $upcomingInstallmentsQuery->whereHas('student', function ($q) use ($user) {
                    $q->where('created_by', $user->id);
                });
            }
        }
        $upcomingInstallments = $upcomingInstallmentsQuery
            ->orderBy('due_date')
            ->take(10)
            ->get(['id', 'invoice_id', 'student_id', 'due_date', 'amount', 'paid_amount']);

        return view('backend.dashboard', compact(
            'leaderboards',
            'latestCourses',
            'latestClients',
            'latestStudents',
            'latestPayments',
            'upcomingInstallments'
        ));
    }
}