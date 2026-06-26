<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\CompanyDashboardController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CvBuilderController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\SchoolDashboardController;
use App\Http\Controllers\NetworkController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ApiController;
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

    if ($user->hasRole('Ecole')) {
        return redirect()->route('school.dashboard');
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

    Route::get('/student/cv', [CvBuilderController::class, 'index'])
        ->name('student.cv');
    Route::get('/student/cv/print', [CvBuilderController::class, 'print'])
        ->name('student.cv.print');
    Route::get('/student/cover-letter', [CvBuilderController::class, 'coverLetterForm'])
        ->name('student.cover-letter');
    Route::post('/student/cover-letter/generate', [CvBuilderController::class, 'generateCoverLetter'])
        ->name('student.cover-letter.generate');

    Route::get('/student/quizzes/{quiz}/take', [QuizController::class, 'take'])
        ->name('quizzes.take');
    Route::post('/student/quizzes/{quiz}/submit', [QuizController::class, 'submit'])
        ->name('quizzes.submit');
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

    Route::get('/quizzes', [QuizController::class, 'index'])
        ->name('quizzes.index');
    Route::get('/quizzes/create', [QuizController::class, 'create'])
        ->middleware('role:Entreprise|Admin')
        ->name('quizzes.create');
    Route::post('/quizzes', [QuizController::class, 'store'])
        ->middleware('role:Entreprise|Admin')
        ->name('quizzes.store');

    Route::get('/interviews', [InterviewController::class, 'index'])
        ->name('interviews.index');
    Route::post('/interviews', [InterviewController::class, 'store'])
        ->middleware('role:Entreprise')
        ->name('interviews.store');
    Route::get('/interviews/{interview}', [InterviewController::class, 'show'])
        ->name('interviews.show');
    Route::patch('/company/interviews/{interview}/report', [InterviewController::class, 'saveReport'])
        ->middleware('role:Entreprise')
        ->name('interviews.report');

    Route::get('/network', [NetworkController::class, 'index'])
        ->name('network.index');
    Route::post('/network/post', [NetworkController::class, 'storePost'])
        ->name('network.post.store');
    Route::post('/network/post/{post}/like', [NetworkController::class, 'likePost'])
        ->name('network.post.like');
    Route::post('/network/connect/{user}', [NetworkController::class, 'connect'])
        ->name('network.connect');
    Route::post('/network/connect/{connection}/accept', [NetworkController::class, 'acceptConnection'])
        ->name('network.connect.accept');

    Route::get('/chat', [ChatController::class, 'index'])
        ->name('chat.index');
    Route::get('/chat/fetch/{user}', [ChatController::class, 'fetchMessages'])
        ->name('chat.fetch');
    Route::post('/chat/send/{user}', [ChatController::class, 'sendMessage'])
        ->name('chat.send');

    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');

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
    Route::get('/stages', [StageController::class, 'index'])
        ->name('stages.index');
    Route::get('/stages/{stage}', [StageController::class, 'show'])
        ->name('stages.show');
    Route::post('/stages/{stage}/weekly-report', [StageController::class, 'storeWeeklyReport'])
        ->name('stages.weekly-report.store');
    Route::post('/stages/{stage}/weekly-report/{weeklyReport}/validate', [StageController::class, 'validateWeeklyReport'])
        ->name('stages.weekly-report.validate');
    Route::post('/stages/{stage}/report', [StageController::class, 'uploadReport'])
        ->name('stages.upload-report');
    Route::post('/stages/{stage}/assign-supervisor', [StageController::class, 'assignSupervisor'])
        ->name('stages.assign-supervisor');
    Route::post('/stages/{stage}/validate-convention', [StageController::class, 'validateConvention'])
        ->name('stages.validate-convention');
});

Route::middleware(['auth', 'role:Ecole|Admin'])->group(function () {
    Route::get('/school/dashboard', [SchoolDashboardController::class, 'index'])
        ->name('school.dashboard');
    Route::post('/school/stages/{stage}/schedule-defense', [SchoolDashboardController::class, 'scheduleDefense'])
        ->name('stages.schedule-defense');
    Route::post('/school/stages/{stage}/grade-defense', [SchoolDashboardController::class, 'gradeStage'])
        ->name('stages.grade-defense');
});

Route::prefix('api')->group(function () {
    Route::get('/offers', [ApiController::class, 'offers']);
    Route::get('/students', [ApiController::class, 'students']);
    Route::get('/companies', [ApiController::class, 'companies']);
    Route::get('/applications', [ApiController::class, 'applications']);
});

require __DIR__.'/auth.php';
