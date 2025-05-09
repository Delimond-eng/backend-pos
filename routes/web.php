<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StockController;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
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

    // === Accueil ===
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::view('/sale.portal', "sales_home")->name('sale.portal');

    // === Gestion des utilisateurs ===
    Route::get("/users", [HomeController::class, 'usersManage'])->name("users");
    Route::post("/user.create", [AdminController::class, 'createUser']);
    Route::get("/users.all", [AdminController::class, 'allUser']);

    // === Gestion des produits ===
    Route::post('/product.create', [StockController::class, 'createProduct'])->name('product.create');
    Route::get('/products', [StockController::class, 'getProducts'])->name('product.all');
    Route::view("/view.products.add", "products_add")->name("view.products.add");
    Route::view("/view.products", "products")->name("view.products");

    // === Gestion des catégories ===
    Route::get('/categories', [HomeController::class, 'getCategories'])->name('categories.all');
    Route::post('/category.create', [AdminController::class, 'createCategory'])->name('category.create');
    Route::view('/view.categories', "categories")->name('view.categories');

    // === Gestion des types de dépenses et dépenses ===
    Route::get('/expense_types', [HomeController::class, 'getExpenseTypes'])->name('expense_types.all');
    Route::post('/expense_type.create', [AdminController::class, 'createExpenseType'])->name('expense_type.create');
    Route::get('/expenses', [HomeController::class, "getExpenses"])->name('expenses');
    Route::post('/expense.create', [AdminController::class, 'createExpense'])->name('expense.create');
    Route::post('/expenses', [StockController::class, 'storeExpense'])->name('expense.store');
    Route::view('/view.expense_types', "expense_types")->name('view.expense_types');
    Route::view('/view.expenses', "expenses")->name('view.expenses');

    // === Gestion des achats (approvisionnements) ===
    Route::post('/purchases', [StockController::class, 'storePurchase'])->name('purchase.store');
    Route::get("/appro.all", [StockController::class, 'getApproStories'])->name("appro.all");
    Route::view("/appro.stories", "mvt_stories")->name("appro.stories");
    Route::view("/appro.add", "stock_approv")->name("appro.add");

    // === Gestion des ventes ===
    Route::post('/sales.store', [StockController::class, 'storeSale'])->name('sale.store');
    Route::get("/day.sale.count", [StockController::class, "getDaySum"])->name("day.sale.count");
    Route::post("/sale.return", [StockController::class, 'returnProduct'])->name("sale.return");
    Route::post("/sale.delete", [StockController::class, 'deleteSale'])->name("sale.delete");
    Route::get("/sales.returns", [StockController::class, "getReturnStories"])->name("sales.returns");
    Route::view("/view.sales", 'sales')->name("view.sales");
    Route::view("/view.sales.return", 'sales_return')->name("view.sales.return");

    // === Gestion des inventaires ===
    Route::post('/inventories.start', [StockController::class, 'startInventory'])->name('inventory.start');
    Route::post('/inventories.validate', [StockController::class, 'validateInventory'])->name('inventory.validate');
    Route::post('/inventory.cancel', [StockController::class, 'deleteInventory'])->name('inventory.cancel');
    Route::get('/inventories.current', [StockController::class, 'getCurrentInventory'])->name('inventories.current');
    Route::get('/inventories.all', [StockController::class, 'getInventories'])->name('inventories.all');
    Route::view("/view.inventories.stories", 'inventories_stories')->name("inventories.stories");
    Route::view("/view.inventories", 'inventories')->name("view.inventories");

    // === Rapports ===
    Route::get('/reports.stock', [StockController::class, 'reportStock'])->name('report.stock');
    Route::get('/reports.sales', [StockController::class, 'reportSales'])->name('report.sales');
    Route::get('/reports.expenses', [StockController::class, 'reportExpenses'])->name('report.expenses');
    Route::get('/reports.stock-movements', [StockController::class, 'reportStockMovements'])->name('report.stock_movements');
    Route::get('/reports.adjustments', [StockController::class, 'reportStockAdjustments'])->name('report.adjustments');
    Route::view("/stock.adjustments", 'adjustment_reports')->name("stock.adjustments");
    Route::view("/stock.reports", "stock_reports")->name("stock.reports");
    Route::view("/stock.global.reports", "stock_global_reports")->name("stock.global.reports");
    Route::get("/stock.global", [StockController::class, "reportStockGlobal"])->name("stock.global");
    Route::view("/sale.reports", "sale_reports")->name("sale.reports");
    Route::view("/expense.reports", "expense_reports")->name("expense.reports");
    Route::view("/purchase.reports", "mvt_stories")->name("purchase.reports");

    // === TDB Reports ===
    Route::get("/reports.{name}", [HomeController::class, "getReports"])->name("report.{name}");

    // === Suppression de données ===
    Route::post('/data.delete', [\App\Http\Controllers\PublicController::class, 'triggerDelete']);

    Route::post('/approv.delete', function(Request $request){
        Product::find($request->id)->decrement("stock", (int)$request->qte);
        StockMovement::where("product_id", $request->input("id"))->delete();
        PurchaseItem::where("id", $request->item_id)->delete();
        return response()->json([
            "status" => "success",
            "result" => "deleted successfully!"
        ]);
    });


    // === PDF Reports ===
    Route::get("/sales.reports.export", [ExportController::class, "downloadSalesReport"])->name("sales.reports.export");
    Route::get("/purchases.reports.export", [ExportController::class, "downloadApproStories"])->name("purchases.reports.export");
});
