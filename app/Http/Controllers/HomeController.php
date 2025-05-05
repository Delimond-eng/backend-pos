<?php

namespace App\Http\Controllers;

use App\Models\Currencie;
use App\Models\Expense;
use App\Models\ExpenseType;
use App\Models\Facture;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                $result = [
                    "daily_sales"=>$dailySales,
                    "monthly_sales"=>$monthlySales,
                    "monthly_expenses"=>$monthlyExpenses,
                    "stock_null"=>$stockNull
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