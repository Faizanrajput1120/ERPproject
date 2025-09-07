<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\ChartOfAccountController;
use App\Http\Controllers\ManageMigration;
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

Route::prefix('ERPLive')->group(function () {



  // Home route
    Route::get('', function () {
        return view('full-width-light.index');
    });
    Route::get('migrate',[  ManageMigration::class,'index'])->name('migrate');
    // Custom print route (always place BEFORE resource)
    Route::get('levels/print', [LevelController::class, 'printTree'])->name('levels.print');

    // Resource route (skip show if not needed)
    Route::resource('levels', LevelController::class)->except(['show']);

    // Other resource routes
    Route::resource('chart-of-accounts', ChartOfAccountController::class);


});
require __DIR__.'/auth.php';
