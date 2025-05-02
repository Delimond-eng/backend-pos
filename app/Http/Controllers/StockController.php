<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Expense;
use App\Models\StockMovement;
use App\Models\Inventory;
use App\Models\PurchaseItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $products = Product::with('category')->orderByDesc('id')->get();
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
        $validated = $request->validate([
            'supplier_name' => 'nullable|string',
            'date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        return DB::transaction(function () use ($validated) {
            $purchase = Purchase::create([
                'supplier_name' => $validated['supplier_name'],
                'date' => $validated['date'],
                'user_id' => Auth::id(),
                'total_amount' => 0
            ]);

            $total = 0;

            foreach ($validated['items'] as $item) {
                $purchase->items()->create($item);
                Product::find($item['product_id'])->increment('stock', $item['quantity']);
                $total += $item['quantity'] * $item['unit_price'];

                StockMovement::create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'type' => 'purchase',
                ]);
            }

            $purchase->update(['total_amount' => $total]);

            return response()->json(['message' => 'Achat enregistré.', 'purchase' => $purchase->load('items.product')]);
        });
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
                'client_name' => $validated['client_name'],
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

            $sale->update(['total_amount' => $total]);

            return response()->json(['message' => 'Vente enregistrée.', 'sale' => $sale->load('items.product')]);
        });
    }

    /**
     * Enregistre une sortie d'argent (ex: frais de transport).
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeExpense(Request $request)
    {
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

    /**
     * Commence un inventaire physique.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function startInventory()
    {
        $inventory = Inventory::create([
            'started_at' => now(),
            'status' => 'pending',
            'user_id' => auth()->id()
        ]);

        return response()->json(['message' => 'Inventaire démarré.', 'inventory' => $inventory]);
    }

    /**
     * Valide un inventaire avec les quantités physiques.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateInventory(Request $request)
    {
        $validated = $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.real_quantity' => 'required|integer'
        ]);

        return DB::transaction(function () use ($validated) {
            $inventory = Inventory::findOrFail($validated['inventory_id']);
            $inventory->update(['status' => 'validated', 'validated_at' => now()]);

            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                $difference = $item['real_quantity'] - $product->stock;

                if ($difference !== 0) {
                    $product->update(['stock' => $item['real_quantity']]);

                    StockMovement::create([
                        'product_id' => $item['product_id'],
                        'quantity' => $difference,
                        'type' => 'inventory_adjustment',
                        'reference_type' => 'Inventory',
                        'reference_id' => $inventory->id,
                    ]);
                }
            }

            return response()->json(['message' => 'Inventaire validé.', 'inventory' => $inventory]);
        });
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
        $sales = Sale::with('items.product')->orderByDesc('date')->get();
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
}
