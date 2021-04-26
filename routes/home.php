<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Home
 */

Route::get('/', [HomeController::class, 'index'])
->name('home')
->middleware('auth');



/*
Route::get('/cliente', [HomeController::class, 'client'])
->name('cliente')
->middleware('auth:client', );

Route::get('/parceiro', [HomeController::class, 'partner'])
->name('parceiro')
->middleware('auth:partner');
*/


