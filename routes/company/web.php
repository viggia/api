<?php

use Illuminate\Support\Facades\Route;
use App\UI\Http\Auth\Controllers\NewPasswordController;
use App\UI\Http\Auth\Controllers\VerifyEmailController;
use App\UI\Http\Auth\Controllers\RegisteredUserController;
use App\UI\Http\Auth\Controllers\PasswordResetLinkController;
use App\UI\Http\Auth\Controllers\AuthenticatedSessionController;
use App\UI\Http\Auth\Controllers\EmailVerificationNotificationController;


Route::group(['prefix'=> 'auth'], function () {
	// route domain.example/auth/register
	Route::post('/register', [RegisteredUserController::class, 'store'])
					->middleware('guest')
					->name('register');

	// route domain.example/auth/login
	Route::post('/login', [AuthenticatedSessionController::class, 'store'])
					->middleware('guest')
					->name('login');

	// route domain.example/auth/forgot-password
	Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
					->middleware('guest')
					->name('password.email');

	// route domain.example/auth/reset-password
	Route::post('/reset-password', [NewPasswordController::class, 'store'])
					->middleware('guest')
					->name('password.update');

	// route domain.example/auth/verify-email/{id}/{hash}
	Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
					->middleware(['auth', 'signed', 'throttle:6,1'])
					->name('verification.verify');

	// route domain.example/auth/email/verification-notification
	Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
					->middleware(['auth', 'throttle:6,1'])
					->name('verification.send');

	// route domain.example/auth/logout
	Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
					->middleware('auth')
					->name('logout');
}); // end group