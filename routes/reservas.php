<?php
/**
 * Reservas
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservasController;

Route::get('/reservas/paginate', [ReservasController::class, 'paginate'])
->middleware('auth');
Route::get('/reservas/nopaginate', [ReservasController::class, 'noPaginate'])
->middleware('auth');
Route::get('/reservas/estatisticas', [ReservasController::class, 'estatisticas'])
->middleware('auth');

Route::get('/reserva/{loteId}', [ReservasController::class, 'getReserva'])->where('loteId', '[0-9]+')
->middleware('auth');
Route::post('/reserva', [ReservasController::class, 'create'])
->middleware('auth');
Route::patch('/reserva', [ReservasController::class, 'setAsSold'])
->middleware('auth');
Route::delete('/reserva', [ReservasController::class, 'delete'])
->middleware('auth');




