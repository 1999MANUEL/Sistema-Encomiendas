<?php

use App\Http\Controllers\EncomiendaPdfController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManfiestoPdfController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/encomiendas/{encomienda}/pdf', [EncomiendaPdfController::class, 'show'])->name('encomienda.pdf');
Route::get('/manifiestos/{manifiesto}/pdf', [ManfiestoPdfController::class, 'show'])
    ->name('manifiesto.pdf');