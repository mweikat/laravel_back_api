<?php

use App\Http\Controllers\AuthLaravelController;
//Route::post('register', [RegisterController::class,'register'])->name('verification.verify');
//Route::post('login', [RegisterController::class,'login']);

//Route::post('register', [AuthLaravelController::class,'register']);
Route::post('login',[AuthLaravelController::class,'login']);
/*Route::get('email/verify/{id}', [AuthLaravelController::class,'emailVerificationNotice'])
        ->name('verification.verify');

//forgot password
Route::post('password/email',  [AuthLaravelController::class,'forgotPassEmail'])->name('password.reset');
Route::post('password/reset', [AuthLaravelController::class,'resetPassword']);*/