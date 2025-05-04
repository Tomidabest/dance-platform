<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadPageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StudioController;
use App\Http\Controllers\LogInController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ProfileController;

Route::get('/', [LeadPageController::class, 'index'])->name('landing');


Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');


Route::get('/login', [LogInController::class, 'index'])->name('login');
Route::post('/login', [LogInController::class, 'login'])->name('login.auth');
Route::get('/logout', [LogInController::class, 'logout'])->name('login.out');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});


Route::get('/search', [SearchController::class, 'index'])->name('search');


Route::get('/studios', [StudioController::class, 'index'])->name('studios.all');

Route::group(['middleware' => 'admin'], function () {
    Route::get('/studios/create', [StudioController::class, 'create'])->name('studios.create');
    Route::post('/studios/store', [StudioController::class, 'store'])->name('studios.store');
    Route::get('/studios/{studio}/edit', [StudioController::class, 'edit'])->name('studios.edit');
    Route::put('/studios/{studio}', [StudioController::class, 'update'])->name('studios.update');
    Route::delete('/studios/images/{id}', [StudioController::class, 'deleteImage'])->name('studios.deleteImage');
});

Route::get('/studios/{studio}', [StudioController::class, 'single'])->name('studios.single');


Route::post('/classes/{class}/book', [StudioController::class, 'book'])->name('class.book');

Route::get('/instructors/{id}', [InstructorController::class, 'show'])->name('instructors.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');
    Route::delete('/booking/{booking}/cancel', [DashboardController::class, 'cancelBooking'])->name('booking.cancel');
});


Route::group(['middleware' => 'admin'], function ()
{
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');

    Route::prefix('/class')->group(function () {
        
        Route::get('/{class}/bookings', [ClassController::class, 'showBookings'])->name('class.bookings');

        Route::get('/create', [ClassController::class, 'create'])->name('class.create');
        Route::post('/store', [ClassController::class, 'store'])->name('class.store');

        Route::get('/{class}/edit', [ClassController::class, 'edit'])->name('class.edit');
        Route::put('/{class}/update', [ClassController::class, 'update'])->name('class.update');

        Route::delete('/{class}/delete', [ClassController::class, 'destroy'])->name('class.delete');

        Route::patch('/{class}/toggle', [ClassController::class, 'toggleStatus'])->name('class.toggle');

        Route::post('/{class}/assign-instructor', [ClassController::class, 'assignInstructor'])->name('class.assignInstructor');

        Route::delete('/class/booking/{booking}', [ClassController::class, 'destroyBooking'])
        ->name('booking.destroy')
        ->middleware(['auth']);
    });

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'view'])->name('profile.view');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/upload-image', [ProfileController::class, 'uploadImage'])->name('profile.uploadImage');

    Route::get('/instructor/setup/{id}', [InstructorController::class, 'setup'])->name('instructor.setup');
    Route::post('/instructor/setup/{id}', [InstructorController::class, 'storeSetup'])->name('instructor.storeSetup');
    Route::get('/instructor/dashboard', [DashboardController::class, 'instructorDashboard'])->name('instructor.dashboard');
    Route::post('/instructors/assign', [InstructorController::class, 'assignToStudio'])->name('instructors.assign');
});
