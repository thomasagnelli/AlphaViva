<?php
/**
 * Clientes
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientesController;

Route::get('/clientes', [ClientesController::class, 'index'])
->middleware('auth');
Route::get('/clientes/paginate', [ClientesController::class, 'paginate'])
->middleware('auth');
Route::get('/clientes/nopaginate', [ClientesController::class, 'noPaginate'])
->middleware('auth');
Route::get('/clientes/{id}', [ClientesController::class, 'viewItem'])->where('id', '[0-9]+')
->middleware('auth');

Route::post('/cliente', [ClientesController::class, 'create'])
->middleware('auth');
Route::patch('/cliente', [ClientesController::class, 'update'])
->middleware('auth');
Route::delete('/cliente', [ClientesController::class, 'delete'])
->middleware('auth');



