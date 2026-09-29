<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExitFormController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
	Route::get('/login', [AuthController::class, 'create'])->name('login');
	Route::post('/login', [AuthController::class, 'store'])->middleware('throttle:10,1')->name('login.store');
});

Route::middleware('auth')->group(function () {
	Route::get('/', [ExitFormController::class, 'index'])->name('exit-form');
	Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

	Route::get('/users', [ExitFormController::class, 'index'])
		->middleware('role:admin')->name('users.index');
	Route::get('/exit-clearance', [ExitFormController::class, 'index'])
		->middleware('role:admin')->name('exit-clearance.index');
	Route::get('/serah-terima', [ExitFormController::class, 'index'])->name('handover.index');

	Route::middleware('role:admin')->group(function () {
		Route::post('/users', [ExitFormController::class, 'storeUser'])->name('users.store');
		Route::patch('/users/{user}', [ExitFormController::class, 'updateUser'])->name('users.update');
		Route::delete('/users', [ExitFormController::class, 'destroyUsers'])->name('users.destroy');
		Route::delete('/serah-terima/{handoverForm}', [ExitFormController::class, 'destroyHandover'])->name('handover.destroy');
		Route::get('/serah-terima/{handoverForm}/preview', [ExitFormController::class, 'preview'])->name('handover.preview');
	});

	Route::post('/serah-terima', [ExitFormController::class, 'store'])
		->middleware('role:admin,employee')->name('handover.store');
	Route::patch('/serah-terima/{handoverForm}/status', [ExitFormController::class, 'updateStatus'])
		->middleware('role:approver')->name('handover.status');
	Route::get('/serah-terima/{handoverForm}/print', [ExitFormController::class, 'print'])
		->middleware('role:admin,approver')->name('handover.print');
});
