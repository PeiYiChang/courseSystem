<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WatchListController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\UserController;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// routes to /finder and /regiser
Route::get('/finder', function (){
    return Inertia::render('Finder');
})->name('finder');
Route::get('/user', [UserController::class, 'index']);

Route::post('/finder', [CourseController::class, 'filter'])->name('course.filter');
Route::post('/course-register', [CourseController::class, 'register'])->name('course.register');
Route::post('/course-drop', [CourseController::class, 'drop'])->name('course.drop');

// retrun course-register view
Route::get('/course-register', function () {
    return Inertia::render('CourseRegister'); 
})->name('course-register');

Route::get('/watchListManagement', function () {
    return Inertia::render('watchListManagement'); 
})->name('watchListManagement');

Route::post('/watchlist/add', [WatchListController::class, 'store'])->name('watchlist.store');
Route::post('/watchlist/remove', [WatchListController::class, 'drop'])->name('watchlist.remove');
Route::get('/watchlist', [WatchListController::class, 'index'])->name('watchlist.index');

Route::get('indexOne', [CourseController::class, 'indexOne'])->name('filter.indexOne');

Route::post('/enrollment-register', [EnrollmentController::class, 'register'])->name('enrollment.store');
Route::post('/deregister', [EnrollmentController::class, 'deregister'])->name('enrollment.remove');
Route::get('/enrollment', [EnrollmentController::class, 'index'])->name('enrollment.index');
Route::get('/enrollmentAll', [EnrollmentController::class, 'enrolledAll'])->name('enrollment.all');
Route::post('/user/add-credit', [UserController::class, 'addCredit'])->name('user.addCredit');
Route::post('/user/delete-credit', [UserController::class, 'deleteCredit'])->name('user.deleteCredit');
require __DIR__.'/auth.php';
