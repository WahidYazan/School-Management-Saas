<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('home');
// })->name('home');
// Route::get('/', function () {
//     return view('home');
// })->name('home');

Route::get('/features', function () {
    return view('features');
})->name('features');

Route::get('/pricing', function () {
    return view('pricing');
})->name('pricing');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

Route::get('/', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
});

Route::middleware(['auth', 'verified', 'role:super_admin,school_admin,teacher,parent'])->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/{class}/{date}', [AttendanceController::class, 'show'])->name('attendance.show');
});

Route::middleware(['auth', 'verified', 'role:super_admin,teacher'])->group(function () {
    Route::get('/assignments/create', [AssignmentController::class, 'create'])->name('assignments.create');
    Route::post('/assignments', [AssignmentController::class, 'store'])->name('assignments.store');
    Route::get('/assignments/{assignment}/edit', [AssignmentController::class, 'edit'])->name('assignments.edit');
    Route::match(['put', 'patch'], '/assignments/{assignment}', [AssignmentController::class, 'update'])->name('assignments.update');
    Route::delete('/assignments/{assignment}', [AssignmentController::class, 'destroy'])->name('assignments.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/assignments/{assignment}', [AssignmentController::class, 'show'])->name('assignments.show');
    Route::post('/assignments/{assignment}/submit', [AssignmentController::class, 'submit'])->name('assignments.submit');
    Route::get('/assignments/{assignment}/download/{submission}', [AssignmentController::class, 'download'])->name('assignments.download');
});

Route::middleware(['auth', 'verified', 'role:super_admin,school_admin'])->group(function () {
    Route::resource('students', StudentController::class)->except(['index', 'show', 'edit', 'update']);
    Route::resource('teachers', TeacherController::class);
    Route::resource('classes', SchoolClassController::class)->except(['show']);
    Route::resource('majors', MajorController::class)->except(['show', 'create', 'edit', 'update']);
    Route::resource('subjects', SubjectController::class)->except(['show', 'create', 'edit', 'update']);
    Route::resource('announcements', AnnouncementController::class)->only(['create', 'store', 'destroy']);
});

Route::middleware(['auth', 'verified', 'role:super_admin,school_admin,teacher'])->group(function () {
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::match(['put', 'patch'], '/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::get('/submissions', [SubmissionController::class, 'index'])->name('submissions.index');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
});

Route::middleware(['auth', 'verified', 'role:super_admin'])->group(function () {
    Route::resource('schools', SchoolController::class)->except(['show']);
    Route::post('/superadmin/switch-school', [SuperAdminController::class, 'switchSchool'])->name('superadmin.switch-school');
    Route::post('/superadmin/clear-school', [SuperAdminController::class, 'clearSchool'])->name('superadmin.clear-school');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
