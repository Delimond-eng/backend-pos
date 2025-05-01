<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use App\Models\Currencie;
use App\Models\Entree;
use App\Models\Facture;
use App\Models\Item;
use App\Models\ItemNature;
use App\Models\Operation;
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

    /**
     * Create new or update currencie
     * @param Request $request
     * @return JsonResponse
     */
    public function createCurrencie(Request $request):JsonResponse
    {
        try {
            $data = $request->validate([
                'currencie_value' => 'required|string'
            ]);
            $currencie = Currencie::all()->first();
            if(isset($currencie)){
                $old = Currencie::findOrFail($currencie['id']);
                $old->currencie_value = $data['currencie_value'];
                $isDone = $old->save();
                return response()->json([
                    'status' => 'success',
                    'result' => "Effectué avec succès",
                ]);
            }
            else{
                $new = Currencie::create($data);
                return response()->json([
                    'status' => 'success',
                    'result' => $new
                ]);
            }
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
     * Create new account
     * @param Request $request
     * @return JsonResponse
     */
    public function createAccount(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'compte_libelle' => 'required|string',
                'compte_devise' => 'required|string'
            ]);
            $account = Compte::updateOrCreate(["compte_libelle"=>$data["compte_libelle"]],$data);
            return response()->json([
                'status' => 'success',
                'compte' => $account,
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
     * Create new item nature
     * @param $data
     * @return mixed
     */
    private function createItemNature($data): mixed
    {
        try {
            $nature = ItemNature::updateOrCreate([
                'item_nature_libelle' => $data['item_nature_libelle'],
                'item_id' => $data['item_id']
            ],[
                'item_nature_libelle' => $data['item_nature_libelle'],
                'item_nature_prix' => $data['item_nature_prix'],
                'item_nature_prix_devise' => $data['item_nature_prix_devise'],
                'item_id' => $data['item_id']
            ]);
            return $nature;
        }
        catch (\Illuminate\Database\QueryException $e){
            return response()->json(['errors' => $e->getMessage() ]);
        }
    }


    /**
     * Create new item
     * @param Request $request
     * @return JsonResponse
     */
    public function createItem(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'item_libelle' => 'required|string',
                'natures'=>'required|array'
            ]);
            if(!isset($request->item_id)){
                $item = Item::create([
                    'item_libelle'=>$data['item_libelle'],
                ]);
                if(isset($item)){
                    $natures = $data['natures'];
                    foreach ($natures as $nature){
                        $nature['item_id'] = $item->id;
                        $this->createItemNature($nature);
                    }
                    return response()->json([
                        'status' => 'success',
                        'result' => $item,
                    ]);
                }
            }
            else{
                $natures = $data['natures'];
                foreach ($natures as $nature){
                    $nature['item_id'] = $request->item_id;
                    $this->createItemNature($nature);
                }
                return response()->json([
                    'status' => 'success',
                    'result' =>'natures créées avec succès !' ,
                ]);
            }

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
     * Create new stock product
     * @param Request $request
     * @return JsonResponse
     */
    public function createProduct(Request $request):JsonResponse{
        try {
            $data = $request->validate([
                'produit_libelle' => 'required|string',
                'entree'=>'nullable|array',
                'entree.entree_qte' => 'nullable|int',
                'entree.entree_prix_achat'=>'nullable|numeric',
                'entree.entree_prix_devise'=>'nullable|String',
            ]);
            $product = Produit::create($data);
            if(isset($data["entree"]["entree_qte"], $data["entree"]["entree_prix_achat"]) && isset($product)){
                $data["entree"]["produit_id"] = $product["id"];
                Entree::create($data["entree"]);
            }
            return response()->json([
                'status' => 'success',
                'result' => $product,
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
     * Create new stock entree
     * @param Request $request
     * @return JsonResponse
    */
    public function createEntree(Request $request):JsonResponse
    {
        try {
            $data = $request->validate([
                'entree_qte' => 'required|int',
                'entree_prix_achat'=>'required|numeric',
                'entree_prix_devise'=>'required|String',
                'produit_id'=>'required|int|exists:produits,id',
            ]);
            $result = Entree::create($data);
            return response()->json([
                'status' => 'success',
                'result' => $result,
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
     * Create new stock sortie
     * @param Request $request
     * @return JsonResponse
     */
    public function createSortie(Request $request):JsonResponse
    {
        try {
            $data = $request->validate([
                'sortie_motif' => 'required|string',
                'sortie_qte'=>'required|int',
                'produit_id'=>'required|int|exists:produits,id',
            ]);
            $count = $this->countStock((int)$data['produit_id']);
            if($count <= 0){
                return response()->json(['errors' => 'stock insuffisant. La quantité restante est : '.$count.'!' ]);
            }
            /** compute stock qte */
            $rest = $count - $data['sortie_qte'];
            if($rest < 0 ){
                return response()->json(['errors' => 'stock insuffisant. La quantité restante est : '.$count.'!' ]);
            }
            $result = Sortie::create($data);
            return response()->json([
                'status' => 'success',
                'result' => $result,
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
     * Count stocks of product
     * @param int $produitId
     * @return mixed
    */
    private function countStock(int $produitId)
    {
        $count = Entree::where('entree_state', 'allowed')
            ->where("produit_id", $produitId)
            ->sum('entree_qte');
        return $count ?? 0;
    }



    /**
     * View all stock data
     * @return JsonResponse
    */
    public function viewAllStock():JsonResponse
    {
        $results = Produit::with(['entrees' => function ($query) {
                $query->where('entree_state', 'allowed');
            }])
            ->with(['sorties' => function ($query) {
                $query->where('sortie_state', 'allowed');
            }])
            ->where('produit_state', 'allowed')
            ->orderByDesc('id')
            ->get();
        return response()->json([
            "status"=>"success",
            "results"=>$results
        ]);
    }

    /**
     * VOIR TOUTES LES DETAILS D'UNE OPERATION
     * @param $date
     * @return JsonResponse
     */
    public function viewInventoryDetails($date):JsonResponse
    {
        $results = null;
        if (!$this->isMonthFormat($date)){
            list($month, $year) = explode('-', $date);
            $results = Operation::with('compte')
                ->with('facture.paiements')
                ->with('facture.client')
                ->with('facture.user')
                ->whereMonth('operation_create_At', $month)
                ->whereYear('operation_create_At', $year)
                ->get();
        }
        else{
            $convertedDate = Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d');
            $results = Operation::with('compte')
                ->with('facture.paiements')
                ->with('facture.client')
                ->with('facture.user')
                ->whereDate('operation_create_At', $convertedDate)
                ->get();
        }
        return response()->json([
            "status"=>"success",
            "results"=>$results
        ]);
    }

    /**
     * Faire l'inventaire
     * @param null $keyValue
     * @param $key
     * @return JsonResponse
     */

    public function loadInventories ($key, string $keyValue=null): JsonResponse{
        $groupedData = null;
        switch ($key){
            case "all":
                $datas = \App\Models\Operation::all();
                $groupedData = $datas->groupBy(function ($item) {
                    return $item->operation_create_At->format('Y-m-d');
                })->map(function ($group) {
                    return [
                        'operation_create_At' => $group->first()->operation_create_At->format('d/m/Y'),
                        'total_amount' => round($group->sum('operation_montant'), 2),
                        'devise' => $group->first()->operation_devise,
                    ];
                })->values();
                break;
            case "mois":
                list($month, $year) = explode('-', "".$keyValue."");
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
                        'total_amount' => round($group->sum('operation_montant'), 2),
                        'devise' => $group->first()->operation_devise,
                    ];
                })->values();
                break;
            case 'date':
                $dateArray = explode(',', trim($keyValue));
                if(count($dateArray) === 2 && empty($dateArray[1])){
                    $datas = \App\Models\Operation::whereDate('operation_create_At', $dateArray[0])
                    ->get();
                }
                else{
                    $startDate = Carbon::parse($dateArray[0]);
                    $endDate = Carbon::parse($dateArray[1]);
                    $datas = \App\Models\Operation::whereBetween('operation_create_At', [$startDate, $endDate])
                    ->get();
                }
                $groupedData = $datas->groupBy(function ($item) {
                    return $item->operation_create_At->format('Y-m-d');
                })->map(function ($group){
                    return [
                        'operation_create_At' => $group->first()->operation_create_At->format('d/m/Y'),
                        'total_amount' => round($group->sum('operation_montant'),2),
                        'devise' => $group->first()->operation_devise,
                    ];
                })->values();
                break;
            case "compte":
                $datas = \App\Models\Operation::with('compte')->get();
                $groupedData = $datas->groupBy(function ($item) {
                    return $item->operation_create_At->format('Y-m-d');
                })->map(function ($group) use ($keyValue) {
                    return [
                        'operation_create_At' => $group->first()->operation_create_At->format('d/m/Y'),
                        'total_amount' => round($group->sum('operation_montant'),2),
                        'devise' => $group->first()->operation_devise,
                        'compte_id' => $group->first()->compte->id,
                        'compte_libelle' => $group->first()->compte->compte_libelle,
                    ];
                })->values()->unique('operation_create_At')->filter(function ($group) use ($keyValue) {
                    return $group['compte_id'] == (int)$keyValue;
                });
                break;
            default:
                $groupedData= null;
        }
        return response()->json([
            "status"=>"success",
            "results"=>$groupedData
        ]);
    }

    private function isMonthFormat($dateString): bool
    {
        try {
            $date = Carbon::createFromFormat('m-Y', $dateString);
            return !$date || $date->format('m-Y') !== $dateString;
        }
        catch (\Carbon\Exceptions\InvalidFormatException $e){
            return true;
        }
    }
}