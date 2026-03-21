<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ComboController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LearningController;
use App\Http\Controllers\PreCheckoutController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

// Giới thiệu
Route::view('/gioi-thieu', 'about')->name('about');

Route::view('/feedback', 'feedback')->name('feedback');
Route::view('/kien-thuc-makeup', 'knowledge')->name('knowledge');

// Danh sách khóa học
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');

// Chi tiết combo khóa học
Route::get('/combos/{combo:slug}', [ComboController::class, 'show'])->name('combos.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Pre-Checkout
    Route::get('/pre-checkout/{course:slug}', [PreCheckoutController::class, 'show'])->name('pre-checkout.show');
    Route::post('/pre-checkout/{course:slug}/validate-coupon', [PreCheckoutController::class, 'validateCoupon'])->name('pre-checkout.validate-coupon');
    Route::post('/pre-checkout/update-phone', [PreCheckoutController::class, 'updatePhone'])->name('pre-checkout.update-phone');

    // Checkout
    Route::get('/checkout/{course:slug}', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::get('/orders/{order}/status', [CheckoutController::class, 'checkStatus'])->name('orders.status');

    // Learning
    Route::get('/learning/{course:slug}', [LearningController::class, 'show'])->name('learning.show');
    Route::get('/learning/{course:slug}/{lesson}', [LearningController::class, 'lesson'])->name('learning.lesson');
});

require __DIR__.'/auth.php';
