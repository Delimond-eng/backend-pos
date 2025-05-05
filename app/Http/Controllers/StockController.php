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
                'name' => 'required|string|unique:products,name',
                'category_id' => 'required|exists:product_categories,id',
                'unit_price' => 'required|numeric|min:0',
                'stock_supplier_name'=>'nullable|string',
                'stock_quantity'=>'nullable|integer',
                'stock_unit_price'=>'nullable|numeric',
                'stock_date'=> 'nullable|date'
            ]);
            $product = Product::create($validated);
            if(isset($validated["stock_quantity"])){
                $data = [
                    "supplier_name"=>$validated["stock_supplier_name"],
                    "date"=>$validated["stock_date"],
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
        try{
            $data = $request->validate([
                'supplier_name' => 'nullable|string',
                'date' => 'nullable|date',
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|int',
                'unit_price' => 'required|numeric',
            ]);
    
            $purchase = Purchase::create([
                'supplier_name' => $data['supplier_name'],
                'date' => $data['date'] ?? Carbon::now(),
                'user_id' => Auth::id(),
                'total_amount' => 0
            ]);
    
            $total = 0;
    
            $purchase->items()->create([
                'product_id'=>$data['product_id'], 
                'quantity'=>$data['quantity'], 
                'unit_price'=>$data['unit_price']
            ]);
            Product::find($data['product_id'])->increment('stock', $data['quantity']);
            $total += (int)$data['quantity'] * (float)$data['unit_price'];
    
            StockMovement::create([
                'product_id' =>$data['product_id'],
                'quantity' => $data['quantity'],
                'type' => 'purchase'
            ]);
            $purchase->total_amount = $total;
            $purchase->save();
            $p = Product::find($data['product_id']);
            $p->unit_price = $request->input("pu");
            $p->save();
    
            return response()->json([
                "status"=>"success",
                "result" => "Achat et approvisionnement enregistrés.",
            ]);
        }catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return response()->json(['errors' => $errors ]);
        }
        catch (\Illuminate\Database\QueryException $e){
            return response()->json(['errors' => $e->getMessage() ]);
        }
    }


    public function getApproStories(Request $request){
        $stories = PurchaseItem::with(["purchase", "product"])->orderByDesc("id")->get();
        return view("mvt_stories", [
            "stories"=>$stories
        ]);
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
    
                return response()->json([
                    "status"=>"success",
                    "result" => "Vente enregistrée.",
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
     * Affiche la somme des ventes journalière
     * @return JsonResponse
    */
    public function getDaySum(){
        $sum = Sale::whereDate("created_at", Carbon::now())->sum("total_amount");
        return response()->json(["day_sum"=>$sum]);
    }


    public function getReturnStories()
    {
        $retours = StockMovement::with('product')
            ->where('type', 'return') // ou 'retour'
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            "returns"=>$retours
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
    public function reportSales()
    {
        $sales = Sale::with('items.product')->with("user")->orderByDesc('date')->get();
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
    public function reportStockMovements()
    {
        $movements = StockMovement::with('product')->orderByDesc('created_at')->get();
        return response()->json(['stock_movements' => $movements]);
    }
    /**
     * Retourne tous les mouvements de stock (ajustements).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reportStockAdjustments()
    {
        $movements = StockMovement::with('product')->where("type", "adjustment")->orderByDesc('created_at')->get();
        return response()->json(['adjustments' => $movements]);
    }


    public function reportStockGlobal(){
        $reports = Product::select([
            'products.id',
            'products.name',
            'products.stock',
            // Totaux par type
            DB::raw("SUM(CASE WHEN stock_movements.type = 'sale' THEN -stock_movements.quantity ELSE 0 END) as total_sale"),
            DB::raw("SUM(CASE WHEN stock_movements.type = 'adjustment' THEN stock_movements.quantity ELSE 0 END) as total_adjustment"),
            DB::raw("SUM(CASE WHEN stock_movements.type = 'return' THEN stock_movements.quantity ELSE 0 END) as total_return"),
            DB::raw("SUM(CASE WHEN stock_movements.type = 'output' THEN -stock_movements.quantity ELSE 0 END) as total_output"),
            DB::raw("SUM(CASE WHEN stock_movements.type = 'purchase' THEN stock_movements.quantity ELSE 0 END) as total_purchase"),
            // Stock global (entrées - sorties)
            DB::raw("SUM(
                CASE
                    WHEN stock_movements.type IN ('purchase', 'return', 'adjustment') THEN stock_movements.quantity
                    WHEN stock_movements.type IN ('sale', 'output') THEN -stock_movements.quantity
                    ELSE 0
                END
            ) as stock_actuel"),
        ])
        ->leftJoin('stock_movements', 'products.id', '=', 'stock_movements.product_id')
        ->groupBy('products.id', 'products.name','products.stock')
        ->get();

        return response()->json([
            "status"=>"success",
            "reports"=>$reports
        ]);
    }
}
