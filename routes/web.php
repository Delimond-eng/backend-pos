<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Auth::routes();
Route::middleware(['auth'])->group(function () {
    Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/clients', [\App\Http\Controllers\HomeController::class, 'clientsManage'])->name('clients');
    Route::get('/factures', [\App\Http\Controllers\HomeController::class, 'facturesManage'])->name('factures');
    Route::get('/paiements', [\App\Http\Controllers\HomeController::class, 'paiementsManage'])->name('paiements');
    Route::get('/users', [\App\Http\Controllers\HomeController::class, 'usersManage'])->name('users');
    Route::get('/accounting', [\App\Http\Controllers\HomeController::class, 'accountingManage'])->name('accounting');
    Route::get('/invoice', [\App\Http\Controllers\HomeController::class, 'invoiceCreate'])->name('invoice');
    Route::get('/invoicepreview/{id}', [\App\Http\Controllers\HomeController::class, 'invoicePreview'])->name('invoicepreview');
    Route::get('/stockage', [\App\Http\Controllers\HomeController::class, 'stockageManage'])->name('stockage');
    Route::get('/configuration', [\App\Http\Controllers\HomeController::class, 'configManage'])->name('configuration');
    Route::get('/inventories', [\App\Http\Controllers\HomeController::class, 'inventoriesManage'])->name('inventories');


    /**
     * Including routes
    */
    include __DIR__ . '/core/routes.php';

});