<?php
/**
 * Lotes
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LotesController;

Route::get('/lotes', [LotesController::class, 'index'])
->middleware('auth');
Route::get('/lotes/paginate', [LotesController::class, 'paginate'])
->middleware('auth');
Route::get('/lotes/nopaginate', [LotesController::class, 'noPaginate'])
->middleware('auth');
Route::get('/lotes/{id}', [LotesController::class, 'viewItem'])->where('id', '[0-9]+')
->middleware('auth');

Route::post('/lote', [LotesController::class, 'create'])
->middleware('auth');
Route::patch('/lote', [LotesController::class, 'update'])
->middleware('auth');
Route::delete('/lote', [LotesController::class, 'delete'])
->middleware('auth');



