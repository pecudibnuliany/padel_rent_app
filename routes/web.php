<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\ScheduleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// routes/web.php
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);

// Halaman register
Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);

// Logout
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    // Ambil data lapangan dari FieldController
    $fields = \App\Models\Field::all();

    // Ambil data cuaca dari WeatherController
    $weatherController = app(\App\Http\Controllers\WeatherController::class);
    $weatherData = $weatherController->showWeather(); // Menampilkan data cuaca

    // Gabungkan data lapangan dan cuaca
    return view('landing-page.index', array_merge(['fields' => $fields], $weatherData));
})->name('index');

Route::get('/getSchedules', function () {
    return view('landing-page.index');
})->name('user.bookings.create');



// Rute untuk halaman dashboard yang hanya bisa diakses oleh pengguna yang terautentikasi
Route::middleware(['auth', 'verified'])->group(function () {
    // Rute untuk mendapatkan jadwal
    Route::get('/user/bookings/getSchedules', [BookingController::class, 'getSchedules'])->name('user.bookings.getSchedules');

    // Landing Page
    Route::get('/landing-page', [BookingController::class, 'showLandingPage'])->name('landing-page');

    // Dashboard User
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Rute untuk halaman administrasi user
    Route::get('user/administration', [BookingController::class, 'indexBookingsUser'])->name('user.administration.index');

    // Rute untuk membatalkan booking
    Route::post('user/bookings/cancel/{bookingId}', [BookingController::class, 'cancel'])->name('user.bookings.cancel');

    Route::get('/user/bookings/scheduleDetails/{scheduleId}', [BookingController::class, 'scheduleDetails']);

    // Rute untuk bookings oleh user
    Route::prefix('user/bookings')->name('user.bookings.')->group(function () {
        Route::get('/create', [BookingController::class, 'create'])->name('create');
        Route::post('/store', [BookingController::class, 'store'])->name('store');
        Route::post('/cancel/{bookingId}', [BookingController::class, 'cancel'])->name('cancel');
        Route::post('/cancel/expired/{bookingId}', [BookingController::class, 'cancelExpiredBooking'])->name('bookings.cancelExpired');
    });

    // Rute untuk melihat pembayaran user
    Route::get('user/payments', [PaymentController::class, 'userPayments'])->name('user.payments.index');

    // Rute untuk membuat pembayaran (hanya untuk user)
    Route::get('user/payments/create/{bookingId}', [PaymentController::class, 'create'])->name('user.payments.create');
    Route::post('user/payments/store/{bookingId}', [PaymentController::class, 'store'])->name('user.payments.store');
});


// Rute Admin dengan middleware auth dan admin
Route::middleware('admin')->prefix('admin')->group(function () {
    // Dashboard dan cuaca
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/weather/{city}', [WeatherController::class, 'showWeather']);
    Route::get('bookings/getSchedules', [BookingController::class, 'getSchedules'])->name('admin.bookings.getSchedules');
    Route::get('user/administration', function () {
        return view('user.administration.index');
    })->name('administration.index');

    // Rute manual untuk fields
    Route::get('fields', [FieldController::class, 'index'])->name('admin.fields.index');
    Route::get('fields/create', [FieldController::class, 'create'])->name('admin.fields.create');
    Route::post('fields', [FieldController::class, 'store'])->name('admin.fields.store');
    Route::get('fields/{field}', [FieldController::class, 'show'])->name('admin.fields.show');
    Route::get('fields/{field}/edit', [FieldController::class, 'edit'])->name('admin.fields.edit');
    Route::put('fields/{field}', [FieldController::class, 'update'])->name('admin.fields.update');
    Route::delete('fields/{field}', [FieldController::class, 'destroy'])->name('admin.fields.destroy');

    // Rute manual untuk schedules
    Route::get('schedules', [ScheduleController::class, 'index'])->name('admin.schedules.index');
    Route::get('schedules/create', [ScheduleController::class, 'create'])->name('admin.schedules.create');
    Route::post('schedules', [ScheduleController::class, 'store'])->name('admin.schedules.store');
    Route::get('schedules/{schedule}', [ScheduleController::class, 'show'])->name('admin.schedules.show');
    Route::get('schedules/{schedule}/edit', [ScheduleController::class, 'edit'])->name('admin.schedules.edit');
    Route::put('schedules/{schedule}', [ScheduleController::class, 'update'])->name('admin.schedules.update');
    Route::delete('schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('admin.schedules.destroy');

    // Rute manual untuk bookings
    Route::get('bookings', [BookingController::class, 'index'])->name('admin.bookings.index');
    Route::get('bookings/create', [BookingController::class, 'create'])->name('admin.bookings.create');
    Route::post('bookings', [BookingController::class, 'store'])->name('admin.bookings.store');
    Route::get('bookings/{booking}', [BookingController::class, 'show'])->name('admin.bookings.show');
    Route::get('bookings/{booking}/edit', [BookingController::class, 'edit'])->name('admin.bookings.edit');
    Route::put('bookings/{booking}', [BookingController::class, 'update'])->name('admin.bookings.update');
    Route::delete('bookings/{booking}', [BookingController::class, 'destroy'])->name('admin.bookings.destroy');

    // Rute manual untuk users
    Route::get('users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('users/{user}', [UserController::class, 'show'])->name('admin.users.show');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    // Rute manual untuk payments
    Route::get('payments', [PaymentController::class, 'index'])->name('admin.payments.index');

    Route::post('payments/{bookingId}', [PaymentController::class, 'store'])->name('admin.payments.store');
    Route::get('payments/{payment}/edit', [PaymentController::class, 'edit'])->name('admin.payments.edit');

    // Rute manual untuk payments
    Route::get('payments/create/{bookingId}', [PaymentController::class, 'create'])->name('admin.payments.create');
    Route::post('payments/store/{bookingId}', [PaymentController::class, 'store'])->name('admin.payments.store');

    Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('admin.payments.show');
    Route::put('payments/{payment}', [PaymentController::class, 'update'])->name('admin.payments.update');
    Route::delete('payments/{payment}', [PaymentController::class, 'destroy'])->name('admin.payments.destroy');
});

