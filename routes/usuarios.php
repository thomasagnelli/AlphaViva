<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Models\User;



/**
 * User
 */

Auth::routes();
Route::get('/logout', [LoginController::class, 'logout'])
->name('custom-logout')->middleware('auth');

Route::get('/usuarios', [UserController::class, 'index'])
->middleware('auth');
Route::get('/usuarios/paginate', [UserController::class, 'paginate'])
->middleware('auth');
Route::get('/usuarios/nopaginate', [UserController::class, 'noPaginate']
)->middleware('auth');

Route::get('/usuario/{id}', [UserController::class, 'getUser'])
->where('id', '[0-9]+')->middleware('auth');
Route::post('/usuario', [UserController::class, 'create'])
->middleware('auth');
Route::patch('/usuario', [UserController::class, 'update'])
->middleware('auth');
Route::delete('/usuario', [UserController::class, 'delete'])
->middleware('auth');







