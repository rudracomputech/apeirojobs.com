<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::get('/register', 'register')->name('register');
    Route::get('/register/verify', 'registerVerify')->name('register-verify');
    Route::post('/register/handle', 'registerHandle')->name('register-handle');
    Route::post('/login/handle', 'handleLogin')->name('login-handle');
    Route::get('/forgot/password', 'forgotPassword')->name('forgot-password');
    Route::post('/forgot/password/verify', 'verifyForgotPassword')->name('verify-forgot-password');
    Route::get('/reset-password/verify', 'verifyResetPassword')->name('verify-reset-password');
    Route::post('/reset/password', 'resetPassword')->name('reset-password');
});

Route::middleware(['auth'])->group(function () {
    # Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', function () {
        return view('backend.settings.profile', ['user' => auth()->user()]);
    })->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/settings', [App\Http\Controllers\UserController::class, 'settings'])->name('settings.index');
    Route::post('/settings', [App\Http\Controllers\UserController::class, 'updateSettings'])->name('settings.update');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('users', App\Http\Controllers\UserController::class)->name('*', 'users');
    Route::post('leads/import', [App\Http\Controllers\LeadController::class, 'import'])->name('leads.import');
    Route::post('leads/{lead}/followups', [App\Http\Controllers\LeadFollowupController::class, 'store'])->name('leads.followups.store');
    Route::post('leads/bulk-action', [App\Http\Controllers\LeadController::class, 'bulkAction'])->name('leads.bulkAction');

    Route::resource('leads', App\Http\Controllers\LeadController::class)->name('*', 'leads');
    Route::resource('students', App\Http\Controllers\StudentController::class)->name('*', 'students');
    Route::resource('courses', App\Http\Controllers\CourseController::class)->name('*', 'courses');
    Route::resource('course-types', App\Http\Controllers\CourseTypeController::class)->except(['show'])->name('*', 'course-types');
    Route::resource('payments', App\Http\Controllers\PaymentsController::class)->name('*', 'payments');
    Route::get('reports/earnings', [App\Http\Controllers\PaymentsController::class, 'earnings'])->name('reports.earnings');
    Route::get('reports/refunds', [App\Http\Controllers\PaymentsController::class, 'refunds'])->name('reports.refunds');
    Route::resource('installments', App\Http\Controllers\InstallmentController::class)->name('*', 'installments');
    Route::resource('statuses', App\Http\Controllers\StatusController::class)->except(['show'])->name('*', 'statuses');
    Route::get('notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::resource('invoices', App\Http\Controllers\InvoicesController::class)->name('*', 'invoices');
    Route::resource('activity-logs', App\Http\Controllers\ActivityLogController::class)->only(['index', 'show'])->name('*', 'activity-logs');
    Route::resource('batches', App\Http\Controllers\BatchController::class)->name('*', 'batches');
    Route::get('clients/export', [App\Http\Controllers\ClientController::class, 'export'])->name('clients.export');
    Route::resource('clients', App\Http\Controllers\ClientController::class)->name('*', 'clients');
    Route::post('clients/bulk-action', [App\Http\Controllers\ClientController::class, 'bulkAction'])->name('clients.bulkAction');
    Route::post('clients/import', [App\Http\Controllers\ClientController::class, 'import'])->name('clients.import');


    Route::prefix('migration')->name('migration.')->middleware(['admin'])->group(function () {
        Route::get('/records', [App\Http\Controllers\MigrationCertificateController::class, 'records'])->name('records');
        Route::get('/', [App\Http\Controllers\MigrationCertificateController::class, 'index'])->name('index');
        Route::post('/save', [App\Http\Controllers\MigrationCertificateController::class, 'save'])->name('save');
        Route::match(['get', 'post'], '/print', [App\Http\Controllers\MigrationCertificateController::class, 'print'])->name('print');
        Route::delete('/{id}', [App\Http\Controllers\MigrationCertificateController::class, 'destroy'])->name('destroy');
        Route::get('/student/{id}', [App\Http\Controllers\MigrationCertificateController::class, 'studentData'])->name('student-data');
    });

    Route::prefix('admitcard')->name('admitcard.')->middleware(['admin'])->group(function () {
        Route::get('/records', [App\Http\Controllers\AdmitCardController::class, 'records'])->name('records');
        Route::get('/', [App\Http\Controllers\AdmitCardController::class, 'index'])->name('index');
        Route::post('/save', [App\Http\Controllers\AdmitCardController::class, 'save'])->name('save');
        Route::match(['get', 'post'], '/print', [App\Http\Controllers\AdmitCardController::class, 'print'])->name('print');
        Route::delete('/{id}', [App\Http\Controllers\AdmitCardController::class, 'destroy'])->name('destroy');
        Route::get('/student/{id}', [App\Http\Controllers\AdmitCardController::class, 'studentData'])->name('student-data');
    });

    Route::prefix('marksheet')->name('marksheet.')->middleware(['admin'])->group(function () {
        Route::get('/records', [App\Http\Controllers\MarksheetController::class, 'records'])->name('records');
        Route::get('/', [App\Http\Controllers\MarksheetController::class, 'index'])->name('index');
        Route::post('/generate', [App\Http\Controllers\MarksheetController::class, 'generate'])->name('generate');
        Route::delete('/{id}', [App\Http\Controllers\MarksheetController::class, 'destroy'])->name('destroy');
        Route::get('/student/{id}', [App\Http\Controllers\MarksheetController::class, 'studentData'])->name('student-data');
    });

    Route::prefix('diploma')->name('diploma.')->middleware(['admin'])->group(function () {
        Route::get('/records', [App\Http\Controllers\DiplomaController::class, 'records'])->name('records');
        Route::get('/', [App\Http\Controllers\DiplomaController::class, 'index'])->name('index');
        Route::post('/save', [App\Http\Controllers\DiplomaController::class, 'save'])->name('save');
        Route::match(['get', 'post'], '/print', [App\Http\Controllers\DiplomaController::class, 'print'])->name('print');
        Route::delete('/{id}', [App\Http\Controllers\DiplomaController::class, 'destroy'])->name('destroy');
        Route::get('/student/{id}', [App\Http\Controllers\DiplomaController::class, 'studentData'])->name('student-data');
    });

    Route::prefix('idcard')->name('idcard.')->middleware(['admin'])->group(function () {
        Route::get('/records', [App\Http\Controllers\IdCardController::class, 'records'])->name('records');
        Route::get('/', [App\Http\Controllers\IdCardController::class, 'index'])->name('index');
        Route::post('/save', [App\Http\Controllers\IdCardController::class, 'save'])->name('save');
        Route::match(['get', 'post'], '/print', [App\Http\Controllers\IdCardController::class, 'print'])->name('print');
        Route::delete('/{id}', [App\Http\Controllers\IdCardController::class, 'destroy'])->name('destroy');
        Route::get('/student/{id}', [App\Http\Controllers\IdCardController::class, 'studentData'])->name('student-data');
    });

});
