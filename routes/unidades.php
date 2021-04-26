<?php
/**
 * Unidades
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UnidadesController;

Route::get('/unidades', [UnidadesController::class, 'index'])
->middleware('auth');
Route::get('/unidades/paginate', [UnidadesController::class, 'paginate'])
->middleware('auth');

Route::get('/unidades/{id}', [UnidadesController::class, 'viewItem'])->where('id', '[0-9]+')
->middleware('auth');
Route::post('/unidade', [UnidadesController::class, 'create'])
->middleware('auth');
Route::patch('/unidade', [UnidadesController::class, 'update'])
->middleware('auth');
Route::delete('/unidade', [UnidadesController::class, 'delete'])
->middleware('auth');



