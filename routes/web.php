<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\ChartOfAccountController;
use App\Http\Controllers\ManageMigration;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\Permissionscontroller;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CashController;
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

 


  Route::middleware(['auth'])->group(function () {

    // Home route
    Route::get('/dashboard', function () {
        return view('full-width-light.index');
    })->name('dashboard');

    // Migration route
    Route::get('migrate', [ManageMigration::class, 'index'])->name('migrate');

    // Custom print route (always place BEFORE resource)
    Route::get('levels/print', [LevelController::class, 'printTree'])->name('levels.print');

    // Resource routes
    Route::resource('levels', LevelController::class)->except(['show']);
    Route::resource('chart-of-accounts', ChartOfAccountController::class);

    
  Route::get('/workspace', [WorkspaceController::class, 'index'])->name('workspace.index');
  Route::get('/workspace/create', [WorkspaceController::class, 'create'])->name('workspace.create');
  Route::post('/workspace', [WorkspaceController::class, 'store'])->name('workspace.stores');

    // Permissions management
    Route::resource('permissions', Permissionscontroller::class)->only(['index', 'edit', 'update']);

    // Workspace user creation routes
    Route::get('/workspace/create-user', [WorkspaceController::class, 'showCreateUserForm'])->name('workspace.showCreateUserForm');
    Route::post('/workspace/create-user', [WorkspaceController::class, 'createUser'])->name('workspace.createUser');
    

     // Payment routes
  Route::resource('payments', PaymentController::class)->except(['show']);
  Route::get('payments/reports', [PaymentController::class, 'reports'])->name('payments.reports');
  Route::delete('payments/{id}/delete', [PaymentController::class, 'delete'])->name('payments.delete');

  // Cash routes
  Route::resource('cash', CashController::class)->except(['show']);
  Route::get('cash/reports', [CashController::class, 'reports'])->name('cash.reports');
  Route::delete('cash/{id}/delete', [CashController::class, 'delete'])->name('cash.delete');


    

  });

});
require __DIR__.'/auth.php';
