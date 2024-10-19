<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WatchListController;
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

Route::post('/register', [EnrollmentController::class, 'register'])->name('enrollment.register');
Route::post('/deregister', [EnrollmentController::class, 'deregister'])->name('enrollment.deregister');
Route::get('indexOne', [CourseController::class, 'indexOne'])->name('filter.indexOne');
require __DIR__.'/auth.php';
