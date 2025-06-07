<?php

namespace App\Http\Controllers;

use App\Models\Currencie;
use App\Models\Expense;
use App\Models\ExpenseType;
use App\Models\Facture;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dashboard', [
            "title"=>"Dashboard"
        ]);
    }

    public function getReports($name){
        $result = null;
        switch($name){
            case "counts" :
                $dailySales = Sale::whereDate("created_at", Carbon::now())->sum("total_amount");
                $monthlySales = Sale::whereMonth("created_at", Carbon::now()->month)->sum("total_amount");
                $monthlyExpenses = Expense::whereMonth("date", Carbon::now()->month)->sum("amount");
                $stockNull = Product::where("stock", "<=", 0)->count();
                $products = Product::count();
                $sales = Sale::sum("total_amount");
                $revenue = $this->getGlobalProfit();
                
                $result = [
                    "daily_sales"=>$dailySales,
                    "monthly_sales"=>$monthlySales,
                    "monthly_expenses"=>$monthlyExpenses,
                    "stock_null"=>$stockNull,
                    "products"=>$products,
                    "sales"=>$sales,
                    "revenue"=>$revenue,
                ];
                break;

                default:
                    $result=[];
        }
        return response()->json([
            "status"=>"success",
            "result"=>$result
        ]);
    }


    private function getGlobalProfit()
    {
        $beneficeTotal = 0;

        // Regroupe par produit
        $saleGroups = SaleItem::select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('AVG(unit_price) as avg_price'))
            ->groupBy('product_id')
            ->get();

        foreach ($saleGroups as $group) {
            $product = Product::find($group->product_id);
            $prixAchat = $product->purchaseItems()->avg('unit_price') ?? 0;

            $revenu = $group->total_qty * $group->avg_price;
            $cout = $group->total_qty * $prixAchat;

            $beneficeTotal += ($revenu - $cout);
        }

        return $beneficeTotal;
    }


    public function getCategories(){
        $categories = ProductCategory::orderByDesc("id")->get();
        return response()->json([
            "categories"=>$categories
        ]);
    }
    public function getExpenseTypes(){
        $expenseTypes = ExpenseType::orderByDesc("id")->get();
        return response()->json([
            "expenseTypes"=>$expenseTypes
        ]);
    }
    public function getExpenses(){
        $expenses = Expense::with(["type","user"])->orderByDesc("id")->get();
        return response()->json([
            "expenses"=>$expenses
        ]);
    }


     /**
     * Show the application users manager
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function usersManage()
    {
        return view('users', [
            "title"=>"Utilisateurs"
        ]);
    }
}