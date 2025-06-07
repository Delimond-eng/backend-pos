<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Expense;
use App\Models\StockMovement;
use App\Models\Inventory;
use App\Models\InventoryLine;
use App\Models\PurchaseItem;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockController extends Controller
{
    /**
     * Crée un nouveau produit.
     *
     * @param Request $request
     * @return mixed
     */
    public function createProduct(Request $request)
    {
        try{
            $validated = $request->validate([
                'name' => 'required|string',
                'category_id' => 'required|exists:product_categories,id',
                'unit_price' => 'required|numeric|min:0',
                'code_barre' => 'nullable|string',
                'stock_supplier_name'=>'nullable|string',
                'stock_quantity'=>'nullable|integer',
                'stock_unit_price'=>'nullable|numeric',
                'stock_date'=> 'nullable|date'
            ]);
            $product = Product::updateOrCreate(["id"=>$request->product_id],$validated);
            if(isset($validated["stock_quantity"])){
                $data = [
                    "supplier_name"=>$validated["stock_supplier_name"],
                    "date"=>$validated["stock_date"] ?? Carbon::now(),
                    "item"=>[
                        "quantity"=>$validated["stock_quantity"],
                        "unit_price"=>$validated["stock_unit_price"],
                        "product_id"=>$product->id
                    ]
                ];
                $this->addStock($data);
            }
    
            return response()->json([
                'status' => 'success',
                'result' => "Produit créé avec succès !",
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return response()->json(['errors' => $errors ]);
        }
        catch (\Illuminate\Database\QueryException $e){
            return response()->json(['errors' => $e->getMessage() ]);
        }
        
    }

    /**
     * Ajoute un nouveau stock à un nouveau Produit
     * 
     * @param mixed $data
    */
    private function addStock($data){
        $purchase = Purchase::create([
            'supplier_name' => $data['supplier_name'],
            'date' => $data['date'],
            'user_id' => Auth::id(),
            'total_amount' => 0
        ]);

        $total = 0;

        $purchase->items()->create($data["item"]);
        Product::find($data["item"]['product_id'])->increment('stock', $data["item"]['quantity']);
        $total += $data["item"]['quantity'] * $data["item"]['unit_price'];

        StockMovement::create([
            'product_id' =>$data["item"]['product_id'],
            'quantity' => $data["item"]['quantity'],
            'type' => 'purchase',
        ]);

        $purchase->update(['total_amount' => $total]);
    }

    /**
     * Liste les produits
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProducts()
    {
        $products = Product::with('category')->withSum('movements as stock_global', 'quantity')->orderByDesc('id')->get();
        return response()->json(['products'=> $products]);
    }

    /**
     * Enregistre un approvisionnement (achat).
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function storePurchase(Request $request)
    {
        try {
            $data = $request->validate([
                'purchase_id' => 'nullable|exists:purchases,id',
                'supplier_name' => 'nullable|string',
                'date' => 'nullable|date',
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|int',
                'unit_price' => 'required|numeric',
                'pu' => 'nullable|numeric',
            ]);
            DB::beginTransaction();

            $purchase = Purchase::updateOrCreate(
                ['id' => $data['purchase_id'] ?? null],
                [
                    'supplier_name' => $data['supplier_name'],
                    'date' => $data['date'] ?? Carbon::now(),
                    'user_id' => Auth::id(),
                    'total_amount'=>0
                ]
            );

            // On vérifie si l'article existe déjà dans l'achat
            $item = $purchase->items()->where('product_id', $data['product_id'])->first();
            $previousQty = $item ? $item->quantity : 0;
            $newQty = $data['quantity'];

            // Mise à jour ou création de l'item
            $purchase->items()->updateOrCreate(
                ['product_id' => $data['product_id']],
                [
                    'quantity' => $newQty,
                    'unit_price' => $data['unit_price']
                ]
            );

            // Ajustement du stock
            $product = Product::findOrFail($data['product_id']);
            $product->increment('stock', $newQty - $previousQty); // ajustement réel

            // Enregistrement du mouvement
            StockMovement::create([
                'product_id' => $data['product_id'],
                'quantity' => $newQty - $previousQty,
                'type' => 'purchase'
            ]);

            // Mise à jour du prix unitaire du produit si fourni
            if (isset($data['pu'])) {
                $product->unit_price = $data['pu'];
                $product->save();
            }

            // Recalcul du montant total de l'achat
            $total = $purchase->items->sum(function ($item) {
                return $item->quantity * $item->unit_price;
            });

            $purchase->total_amount = $total;
            $purchase->save();

            DB::commit();

            return response()->json([
                "status" => "success",
                "result" => "Achat enregistré avec succès.",
            ]);
        } 
        catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()->all()]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => $e->getMessage()]);
        }
    }



    public function getApproStories(Request $request){
        $queryDate = $request->query("date");
        
        $stories = PurchaseItem::with(["purchase.user", "product"])
            ->when($queryDate, function ($query, $queryDate) {
                $query->whereDate('created_at', $queryDate);
            })
            ->orderByDesc("id")
            ->get();
    
        return response()->json(["purchases" => $stories]);
    }


    

    /**
     * Enregistre une vente de produits.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeSale(Request $request)
    {
        try{
            $validated = $request->validate([
                'client_name' => 'nullable|string',
                'date' => 'nullable|date',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.unit_price' => 'required|numeric|min:0',
            ]);

            return DB::transaction(function () use ($validated) {
                $sale = Sale::create([
                    'customer_name' => $validated['client_name'] ?? "",
                    'date' => $validated['date'] ?? Carbon::now(),
                    'user_id' => Auth::id(),
                    'total_amount' => 0
                ]);
    
                $total = 0;
    
                foreach ($validated['items'] as $item) {
                    $sale->items()->create($item);
                    Product::find($item['product_id'])->decrement('stock', $item['quantity']);
                    $total += $item['quantity'] * $item['unit_price'];
    
                    StockMovement::create([
                        'product_id' => $item['product_id'],
                        'quantity' => -$item['quantity'],
                        'type' => 'sale',
                    ]);
                }
                $sale->total_amount = $total;
                $sale->save();

                $ticketInfos = Sale::with("items.product")->with("user")->where("id", $sale->id)->first();
    
                return response()->json([
                    "status"=>"success",
                    "result" => "Vente enregistrée.",
                    "ticket"=>$ticketInfos
                ]);
            });
        }catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return response()->json(['errors' => $errors ]);
        }
        catch (\Illuminate\Database\QueryException $e){
            return response()->json(['errors' => $e->getMessage() ]);
        }
        
    }


    /**
     * Retourner le produit acheter
     * 
    */

    public function returnProduct(Request $request)
    {
        $validated = $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($validated) {
            $sale = Sale::findOrFail($validated['sale_id']);
            // Vérifie que le produit fait partie de cette vente
            $saleItem = $sale->items()->where('product_id', $validated['product_id'])->first();

            if (!$saleItem) {
                return response()->json(['errors' => 'Produit non trouvé dans la vente.'], 404);
            }

            // Vérifie que la quantité retournée est possible
            if ($validated['quantity'] > $saleItem->quantity) {
                return response()->json(['errors' => 'Quantité retournée invalide.'], 400);
            }

            // Met à jour le stock
            Product::find($validated['product_id'])->increment('stock', $validated['quantity']);

            // Enregistre un mouvement de retour
            StockMovement::create([
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'type' => 'return',
            ]);

            // Optionnel : mettre à jour la quantité de l'article vendu (ou créer un système de retour à part)
            $saleItem->quantity -= $validated['quantity'];
            $saleItem->save();

            // Optionnel : recalculer le total de la vente
            $newTotal = $sale->items->sum(function ($item) {
                return $item->quantity * $item->unit_price;
            });
            $sale->total_amount = $newTotal;
            $sale->save();

            return response()->json([
                'status' => 'success',
                'result' => 'Retour enregistré.',
            ]);
        });
    }
    /**
     * Supprimer une vente entiere
     * 
    */

    public function deleteSale(Request $request)
    {
        $validated = $request->validate([
            'sale_id' => 'required|exists:sales,id',
        ]);

        return DB::transaction(function () use ($validated) {
            $sale = Sale::with('items')->findOrFail($validated['sale_id']);

            foreach ($sale->items as $item) {
                // Mise à jour du stock
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);
                }

                // Enregistrement du mouvement
                StockMovement::create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'type' => 'return',
                ]);
            }

            // Supprimer les éléments liés à la vente
            $sale->items()->delete(); // si pas en cascade
            $sale->delete();

            return response()->json([
                'status' => 'success',
                'result' => 'Suppression effectuée.',
            ]);
        });
    }


    /**
     * Affiche la somme des ventes journalière
     * @return JsonResponse
    */
    public function getDaySum(){
        $sum = Sale::whereDate("created_at", Carbon::now())->sum("total_amount");
        return response()->json(["day_sum"=>$sum]);
    }


    public function getReturnStories(Request $request)
    {
        $date = $request->query("date") ?? null;
        $req = StockMovement::with('product');

        if($date){
            $req->whereDate("created_at", $date);
        }

        $returns = $req->where('type', 'return') // ou 'retour'
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            "returns"=>$returns 
        ]);
    }

    /**
     * Enregistre une sortie d'argent (ex: frais de transport).
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeExpense(Request $request)
    {

        try{
            $validated = $request->validate([
                'label' => 'required|string',
                'amount' => 'required|numeric|min:0',
                'date' => 'nullable|date', 
            ]);
    
            $expense = Expense::create([
                'label' => $validated['label'],
                'amount' => $validated['amount'],
                'date' => $validated['date'] ?? Carbon::now(),
                'user_id' => Auth::id(),
            ]);
    
            return response()->json(['message' => 'Dépense enregistrée.', 'expense' => $expense]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return response()->json(['errors' => $errors ]);
        }
        catch (\Illuminate\Database\QueryException $e){
            return response()->json(['errors' => $e->getMessage() ]);
        }
       
    }


    public function getCurrentInventory()
    {
        $inventory = Inventory::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->latest()
            ->first();

        if ($inventory) {
            return response()->json([
                'status' => 'success',
                'inventory' => $inventory
            ]);
        }

        return response()->json([
            'status' => 'none',
            'inventory' => null
        ]);
    }


    /**
     * Commence un inventaire physique.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function startInventory()
    {
        try{
            $inventory = Inventory::create([
                'date' => Carbon::now(),
                'status' => 'pending',
                'user_id' => Auth::id()
            ]);
            return response()->json([
                'status'=>'success',
                'result' => 'Inventaire démarré.',
                'inventory'=>$inventory
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return response()->json(['errors' => $errors ]);
        }
        catch (\Illuminate\Database\QueryException $e){
            return response()->json(['errors' => $e->getMessage() ]);
        }
        
    }

    /**
     * Supprimer un inventaire en cours
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteInventory(Request $request)
    {
        try{
            $data = $request->validate([
                'inventory_id'=>'required|int|exists:inventories,id'
            ]);
            $inventory = Inventory::where("id", $data["inventory_id"])->delete();
            return response()->json([
                'status'=>'success',
                'result' => 'Inventaire annulé avec succès.',
                'inventory'=>$inventory
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return response()->json(['errors' => $errors ]);
        }
        catch (\Illuminate\Database\QueryException $e){
            return response()->json(['errors' => $e->getMessage() ]);
        }
        
    }

    /**
     * Valide un inventaire avec les quantités physiques.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateInventory(Request $request)
    {
        try{
            $validated = $request->validate([
                'inventory_id' => 'required|exists:inventories,id',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.real_quantity' => 'required|integer',
                'items.*.theoretical_quantity' => 'required|integer'
            ]);
    
            return DB::transaction(function () use ($validated) {
                $inventory = Inventory::findOrFail($validated['inventory_id']);
                $inventory->update(['status' => 'validated']);

                foreach ($validated['items'] as $item) {

                    $product = Product::find($item['product_id']);
                    $difference = $item['real_quantity'] - $item["theoretical_quantity"];

                    InventoryLine::create([
                        "inventory_id"=>$inventory->id,
                        "product_id"=>$item["product_id"],
                        "theoretical_qty"=>$item["theoretical_quantity"],
                        "real_qty"=>$item["real_quantity"],
                        "difference"=>$difference
                    ]);

                    if ($difference != 0) {
                        $product->update(['stock' => $item['real_quantity']]);
                        StockMovement::create([
                            'product_id' => $item['product_id'],
                            'quantity' => $difference,
                            'type' => 'adjustment',
                        ]);
                    }
                }
    
                return response()->json([
                    'status'=>'success',
                    'result' => 'Inventaire validé.',
                    'inventory'=>$inventory
                ]);
            });
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return response()->json(['errors' => $errors ]);
        }
        catch (\Illuminate\Database\QueryException $e){
            return response()->json(['errors' => $e->getMessage() ]);
        }
        
    }

    public function getInventories(){
        $inventories = Inventory::with("lines.product")->with("user")->orderByDesc("created_at")->get();
        return response()->json([
            "inventories"=>$inventories
        ]);
    }

    /**
     * Retourne l'état actuel du stock pour tous les produits.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reportStock()
    {
        $products = Product::select('id', 'name', 'stock')->get();
        return response()->json(['stock_report' => $products]);
    }

    /**
     * Retourne le rapport des ventes.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reportSales(Request $request)
    {
        $date = $request->query('date');
        $sales = Sale::with(['items.product', 'user'])
            ->when($date, function ($query, $date) {
                $query->whereDate('created_at', $date);
            })
            ->orderByDesc('created_at')
            ->get();
        return response()->json(['sales_report' => $sales]);
    }


    /**
     * Retourne le rapport des dépenses.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reportExpenses()
    {
        $expenses = Expense::with('type')->orderByDesc('date')->get();
        return response()->json(['expenses_report' => $expenses]);
    }

    /**
     * Retourne tous les mouvements de stock (entrées/sorties/ajustements).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reportStockMovements(Request $request)
    {
        $start = $request->query("start") ?? null;
        $end = $request->query("end") ?? null;

        $req = StockMovement::with('product');
        $req->when($start && $end, function ($query) use ($start, $end) {
            return $query->whereBetween('created_at', [$start, $end]);
        })
        ->when($start && !$end, function ($query) use ($start) {
            return $query->whereDate('created_at', $start);
        })
        ->when(!$start && $end, function ($query) use ($end) {
            return $query->whereDate('created_at', $end);
        });
        $movements = $req->orderByDesc('created_at')->get();
        return response()->json(['stock_movements' => $movements]);
    }
    /**
     * Retourne tous les mouvements de stock (ajustements).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reportStockAdjustments(Request $request)
    {
        $start = $request->query("start") ?? null;
        $end = $request->query("end") ?? null;
        $req = StockMovement::with('product');

        $req->when($start && $end, function ($query) use ($start, $end) {
            return $query->whereBetween('created_at', [$start, $end]);
        })
        ->when($start && !$end, function ($query) use ($start) {
            return $query->whereDate('created_at', $start);
        })
        ->when(!$start && $end, function ($query) use ($end) {
            return $query->whereDate('created_at', $end);
        });
        
        $movements = $req->where("type", "adjustment")->orderByDesc('created_at')->get();
        return response()->json(['adjustments' => $movements]);
    }

    public function reportStockGlobal()
    {
        $reports = Product::query()
            ->leftJoin('stock_movements', 'products.id', '=', 'stock_movements.product_id')
            ->select([
                'products.id',
                'products.name',
                'products.stock',

                // Mouvements de stock
                DB::raw("SUM(CASE WHEN stock_movements.type = 'sale' THEN -stock_movements.quantity ELSE 0 END) as total_sale"),
                DB::raw("SUM(CASE WHEN stock_movements.type = 'adjustment' THEN stock_movements.quantity ELSE 0 END) as total_adjustment"),
                DB::raw("SUM(CASE WHEN stock_movements.type = 'return' THEN stock_movements.quantity ELSE 0 END) as total_return"),
                DB::raw("SUM(CASE WHEN stock_movements.type = 'output' THEN -stock_movements.quantity ELSE 0 END) as total_output"),
                DB::raw("SUM(CASE WHEN stock_movements.type = 'purchase' THEN stock_movements.quantity ELSE 0 END) as total_purchase"),

                // Stock réel calculé dynamiquement
                DB::raw("SUM(
                    CASE
                        WHEN stock_movements.type IN ('purchase', 'return', 'adjustment') THEN stock_movements.quantity
                        WHEN stock_movements.type IN ('sale', 'output') THEN -stock_movements.quantity
                        ELSE 0
                    END
                ) as stock_actuel"),
            ])
            // Sous-requête pour total_quantity_vendue
            ->addSelect([
                'total_quantity_vendue' => SaleItem::selectRaw('COALESCE(SUM(quantity), 0)')
                    ->whereColumn('product_id', 'products.id')
            ])
            // Sous-requête pour total_revenu
            ->addSelect([
                'total_revenu' => SaleItem::selectRaw('COALESCE(SUM(quantity * unit_price), 0)')
                    ->whereColumn('product_id', 'products.id')
            ])
            // Sous-requête pour prix_moyen_achat
            ->addSelect([
                'prix_moyen_achat' => PurchaseItem::selectRaw('COALESCE(AVG(unit_price), 0)')
                    ->whereColumn('product_id', 'products.id')
            ])
            ->groupBy('products.id', 'products.name', 'products.stock')
            ->get()
            ->map(function ($item) {
                $revenu = $item->total_revenu ?? 0;
                $cout = ($item->prix_moyen_achat ?? 0) * ($item->total_quantity_vendue ?? 0);
                $item->benefice = round($revenu - $cout, 2);
                return $item;
            });

        return response()->json([
            "status" => "success",
            "reports" => $reports
        ]);
    }

}
