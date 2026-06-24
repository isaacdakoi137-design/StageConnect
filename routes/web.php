<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\CompanyDashboardController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->hasRole('Admin')) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->hasRole('Entreprise')) {
        return redirect()->route('company.dashboard');
    }

    if ($user->hasRole('Etudiant')) {
        return redirect()->route('student.dashboard');
    }

    return redirect()->route('offers.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:Etudiant'])->group(function () {
    Route::get('/student/profile', [StudentController::class, 'create'])
        ->name('student.profile');
    Route::post('/student/profile', [StudentController::class, 'store'])
        ->name('student.profile.store');

    Route::post('/offers/{offer}/apply',
        [ApplicationController::class, 'store'])
        ->name('applications.store');
    Route::get('/applications', [ApplicationController::class, 'index'])
        ->name('applications.index');

    Route::get('/student/dashboard',
        [StudentDashboardController::class, 'index'])
        ->name('student.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/offers/create', [OfferController::class, 'create'])
        ->middleware('role:Entreprise|Admin')
        ->name('offers.create');
    Route::post('/offers', [OfferController::class, 'store'])
        ->middleware('role:Entreprise|Admin')
        ->name('offers.store');
    Route::get('/offers', [OfferController::class, 'index'])
        ->name('offers.index');

    Route::get('/offers/{offer}', [OfferController::class, 'show'])
        ->name('offers.show');

    Route::get('/admin/dashboard',
        [AdminController::class, 'dashboard'])
        ->middleware(['auth', 'role:Admin'])
        ->name('admin.dashboard');
});
Route::middleware(['auth', 'role:Admin'])->group(function () {

    Route::get('/admin/applications',
        [App\Http\Controllers\AdminController::class, 'applications'])
        ->name('admin.applications');

    Route::patch('/admin/applications/{application}',
        [App\Http\Controllers\AdminController::class, 'updateApplicationStatus'])
        ->name('admin.applications.update');

});
Route::middleware(['auth', 'role:Entreprise'])
    ->group(function () {

        Route::get(
            '/company/dashboard',
            [CompanyDashboardController::class, 'index']
        )->name('company.dashboard');

        Route::get(
            '/company/applications',
            [CompanyDashboardController::class, 'applications']
        )->name('company.applications');
        Route::patch(
            '/company/applications/{application}',
            [CompanyDashboardController::class, 'updateApplicationStatus']
        )->name('company.applications.update');
        Route::get(
            '/company/candidate/{user}',
            [CompanyDashboardController::class, 'showCandidate']
        )->name('company.candidate.show');

});
Route::middleware(['auth'])->group(function () {

    Route::get(
        '/stages',
        [StageController::class, 'index']
    )->name('stages.index');

});
require __DIR__.'/auth.php';
