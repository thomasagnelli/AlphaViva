<?php
/**
 * Quadras
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuadrasController;

Route::get('/quadras', [QuadrasController::class, 'index'])
->middleware('auth');
Route::get('/quadras/paginate', [QuadrasController::class, 'paginate'])
->middleware('auth');
Route::get('/quadras/{id}', [QuadrasController::class, 'viewItem'])->where('id', '[0-9]+')
->middleware('auth');

Route::post('/quadra', [QuadrasController::class, 'create'])
->middleware('auth');
Route::patch('/quadra', [QuadrasController::class, 'update'])
->middleware('auth');
Route::delete('/quadra', [QuadrasController::class, 'delete'])
->middleware('auth');



