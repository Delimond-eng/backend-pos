<?php
use App\Models\Operation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
 /**
     * Routes metiers
    */
     /**
     * ADMIN
    */
    Route::post("/item.create", [\App\Http\Controllers\AdminController::class, 'createItem']);
    Route::post("/currencie.create", [\App\Http\Controllers\AdminController::class, 'createCurrencie']);
    Route::post("/compte.create", [\App\Http\Controllers\AdminController::class, 'createAccount']);
    /**
     * Synchronisation
    */
    Route::post("/sync.in", [\App\Http\Controllers\JsonDataController::class, 'syncIn']);
    Route::get("/sync.out", [\App\Http\Controllers\JsonDataController::class, 'syncOut']);

    Route::post("/user.create", [\App\Http\Controllers\AdminController::class, 'createUser']);
    Route::post("/user.login", [\App\Http\Controllers\UserController::class, 'login']);
    Route::get("/users.all", [\App\Http\Controllers\AdminController::class, 'allUser']);
    Route::get("/user.check/{userId}", [\App\Http\Controllers\UserController::class, 'checkUserAccess']);
    Route::get("/dashboard.all", [\App\Http\Controllers\DashboardController::class, 'dashCounter']);
    Route::get("/configs.all", [\App\Http\Controllers\PublicController::class, 'viewAllConfigs']);
    /**
     * Public
    */
    Route::post("/client.create", [\App\Http\Controllers\PublicController::class, 'createClient']);
    Route::get("/clients.all", [\App\Http\Controllers\PublicController::class, 'viewAllClients']);

    Route::post("/facture.create", [\App\Http\Controllers\PublicController::class, 'createFacture']);
    Route::get("/factures.view/{key}", [\App\Http\Controllers\PublicController::class, 'viewAllFactures']);
    Route::post("/facture.pay", [\App\Http\Controllers\PublicController::class, 'makePayment']);
    Route::get("/payments/{key}/{date?}", [\App\Http\Controllers\PublicController::class, 'allPaiements']);
    Route::get("/payment.details/{factureId}", [\App\Http\Controllers\PublicController::class, 'getPaiementDetails']);
    Route::get("/inventories.load/{key}/{keyValue?}",[\App\Http\Controllers\AdminController::class, 'loadInventories'] );
    Route::get("/inventory.details/{date}",[\App\Http\Controllers\AdminController::class, 'viewInventoryDetails'] );
    Route::get("/operations.all/{yearMonth}", function ($yearMonth){
        list($month, $year) = explode('-', $yearMonth);
        $datas = \App\Models\Operation::all();
       $groupeDatas = $datas = \App\Models\Operation::with('compte')
           ->whereYear('operation_create_At', (int)$year)
           ->whereMonth('operation_create_At', (int)$month)
           ->get();

        $groupedData = $datas->groupBy(function ($item) {
            return $item->operation_create_At->format('Y-m');
        })->map(function ($group) {
            return [
                'operation_create_At' => $group->first()->operation_create_At->format('m/Y'),
                'total_amount' => $group->sum('operation_montant'),
                'devise' => $group->first()->operation_devise,
            ];
        })->values();
        return response()->json([
            "datas"=>$groupedData
        ]);

    });
    Route::post('/data.delete', [\App\Http\Controllers\PublicController::class, 'triggerDelete']);
    Route::post('/data.disable', [\App\Http\Controllers\PublicController::class, 'disableData']);

    /**
     * STOCK MANAGE ROUTES
    */
    Route::get('stocks.view', [\App\Http\Controllers\AdminController::class, 'viewAllStock']);
    Route::post('product.create', [\App\Http\Controllers\AdminController::class, 'createProduct']);
    Route::post('stock.append', [\App\Http\Controllers\AdminController::class, 'createEntree']);
    Route::post('stock.reduce', [\App\Http\Controllers\AdminController::class, 'createSortie']);

    /*Inventories routes for administration*/
    Route::get('/inventories/{key}/{val?}', [\App\Http\Controllers\AdminController::class, 'viewInventories']);