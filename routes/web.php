<?php

use App\Http\Controllers\LevelController;
use App\Http\Controllers\ChartOfAccountController;
use Illuminate\Support\Facades\Route;

Route::prefix('prbox')->group(function () {

    // Home route
    Route::get('', function () {
        return view('full-width-light.index');
    });

    // Custom print route (always place BEFORE resource)
    Route::get('levels/print', [LevelController::class, 'printTree'])->name('levels.print');

    // Resource route (skip show if not needed)
    Route::resource('levels', LevelController::class)->except(['show']);

    // Other resource routes
    Route::resource('chart-of-accounts', ChartOfAccountController::class);

});
