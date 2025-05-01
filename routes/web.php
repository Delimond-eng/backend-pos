<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StockController;
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
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get("/users",[HomeController::class, 'usersManage'])->name("users");

    Route::post("/user.create", [AdminController::class, 'createUser']);
    Route::get("/users.all", [AdminController::class, 'allUser']);

    // Création de produit
    Route::post('/products', [StockController::class, 'createProduct'])->name('product.create');
    
    Route::get('/categories', [HomeController::class, 'getCategories'])->name('categories.all');
    Route::post('/category.create', [AdminController::class, 'createCategory'])->name('category.create');
    
    Route::view('/view.categories', "categories")->name('view.categories');

    // Enregistrement d'un achat (approvisionnement)
    Route::post('/purchases', [StockController::class, 'storePurchase'])->name('purchase.store');

    // Enregistrement d'une vente
    Route::post('/sales', [StockController::class, 'storeSale'])->name('sale.store');

    // Enregistrement d'une dépense
    Route::post('/expenses', [StockController::class, 'storeExpense'])->name('expense.store');

    // Démarrer un inventaire
    Route::post('/inventories.start', [StockController::class, 'startInventory'])->name('inventory.start');

    // Valider un inventaire
    Route::post('/inventories.validate', [StockController::class, 'validateInventory'])->name('inventory.validate');

    // Rapport de stock
    Route::get('/reports.stock', [StockController::class, 'reportStock'])->name('report.stock');

    // Rapport des ventes
    Route::get('/reports.sales', [StockController::class, 'reportSales'])->name('report.sales');

    // Rapport des dépenses
    Route::get('/reports.expenses', [StockController::class, 'reportExpenses'])->name('report.expenses');

    // Rapport des mouvements de stock
    Route::get('/reports.stock-movements', [StockController::class, 'reportStockMovements'])->name('report.stock_movements');


    Route::post('/data.delete', [\App\Http\Controllers\PublicController::class, 'triggerDelete']);
});