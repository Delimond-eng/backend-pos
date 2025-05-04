<?php

namespace App\Http\Controllers;

use App\Models\Currencie;
use App\Models\Expense;
use App\Models\ExpenseType;
use App\Models\Facture;
use App\Models\ProductCategory;
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
        if(Auth::user()->role == 'admin'){
            return view('dashboard', [
                "title"=>"Dashboard"
            ]);
        }
        else if(Auth::user()->role== 'vendor'){
            return view('sales_home', [
                "title"=>"POS_Home"
            ]);
        }
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


}