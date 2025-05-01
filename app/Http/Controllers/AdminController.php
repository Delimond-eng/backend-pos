<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use App\Models\Currencie;
use App\Models\Entree;
use App\Models\Facture;
use App\Models\Item;
use App\Models\ItemNature;
use App\Models\Operation;
use App\Models\ProductCategory;
use App\Models\Produit;
use App\Models\Sortie;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Create new user
     * @param Request $request
     * @return JsonResponse
    */
    public function createUser(Request $request): JsonResponse
    {

        try {
            $data = $request->validate([
                'name' => 'required|string',
                'password' => 'required|string',
                'role'=>'required|string',
            ]);
            $user=User::updateOrCreate(
                ['name'=>$data['name']],
                [
                'name'=>$data['name'],
                'password'=>bcrypt($data['password']),
                'role'=>$data['role'],
                'state'=>'allowed',
            ]);
            return response()->json([
                'status' => 'success',
                'result' => $user,
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
     * Create new category
     * @param Request $request
     * @return JsonResponse
    */
    public function createCategory(Request $request): JsonResponse
    {

        try {
            $data = $request->validate([
                'name' => 'required|string',
            ]);
            $user=ProductCategory::updateOrCreate(
                ['name'=>$data['name']],
                [
                'name'=>$data['name'],
            ]);
            return response()->json([
                'status' => 'success',
                'result' => $user,
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
     * view all clients
     * @return JsonResponse
    */
    public function allUser():JsonResponse
    {
        $users = User::where('state', 'allowed')->get();
        return response()->json([
            "users"=>$users
        ]);
    }
}