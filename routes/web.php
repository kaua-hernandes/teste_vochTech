<?php

use Illuminate\Support\Facades\Route;


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


Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth'])->group(function () {
    Route::view('/bandeiras', 'bandeira')->name('bandeiras.index');
    Route::view('/unidades', 'unidade')->name('unidades.index');
    Route::view('/colaboradores', 'colaborador')->name('colaboradores.index');
    Route::view('/', 'dashboard')->name('dashboard.index');
});




require __DIR__.'/auth.php';
